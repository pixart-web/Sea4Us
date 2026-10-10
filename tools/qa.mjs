// Sea4Us QA: screenshots, overflow checks at 360/390/768/1024/1440, keyboard focus order,
// form error/empty/demo-success states. Usage: node qa.mjs <baseUrl> <outDir>
import { chromium } from '/opt/node22/lib/node_modules/playwright/index.mjs';
import fs from 'node:fs';
import path from 'node:path';

const base = process.argv[2] || 'http://localhost:8080';
const out = process.argv[3] || './qa';
fs.mkdirSync(out, { recursive: true });

const widths = [360, 390, 768, 1024, 1440];
const pages = JSON.parse(fs.readFileSync(new URL('./qa-pages.json', import.meta.url)));
const report = { overflow: [], overlap: [], consoleErrors: [], http: [], keyboard: [], form: [], shots: [] };

const browser = await chromium.launch();

async function checkLayout(page, url, w) {
	return page.evaluate(() => {
		const doc = document.documentElement;
		const issues = { overflowX: doc.scrollWidth > doc.clientWidth + 1 ? doc.scrollWidth - doc.clientWidth : 0, wide: [] };
		if (issues.overflowX) {
			for (const el of document.querySelectorAll('body *')) {
				const r = el.getBoundingClientRect();
				if (r.right > doc.clientWidth + 1 && r.width > 0 && getComputedStyle(el).position !== 'fixed') {
					issues.wide.push((el.className && el.className.toString().slice(0, 60)) || el.tagName);
					if (issues.wide.length > 6) break;
				}
			}
		}
		// Text clipped inside its box (simple heuristic for overlapping/clipped headings and buttons).
		issues.clipped = [];
		for (const el of document.querySelectorAll('h1,h2,h3,.s4u-btn,.s4u-card__title,.s4u-nav__list a')) {
			if (el.scrollWidth > el.clientWidth + 2 && getComputedStyle(el).overflow !== 'visible') {
				issues.clipped.push(el.textContent.trim().slice(0, 40));
			}
		}
		return issues;
	});
}

for (const w of (process.env.QA_SKIP_PAGES ? [] : widths)) {
	const ctx = await browser.newContext({ viewport: { width: w, height: w < 768 ? 800 : 900 }, deviceScaleFactor: 1, reducedMotion: 'reduce' });
	const page = await ctx.newPage();
	page.on('console', (m) => { if (m.type() === 'error') report.consoleErrors.push(`${w}px ${page.url()} ${m.text()}`); });
	for (const p of pages) {
		const url = base + p.path;
		const res = await page.goto(url, { waitUntil: 'networkidle' });
		if (!res || res.status() >= 400 && !p.expect404) report.http.push(`${res && res.status()} ${p.path}`);
		await page.waitForTimeout(150);
		if (p.shot) {
			// Load lazy images before full-page screenshots.
			await page.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 600) { window.scrollTo(0, y); await new Promise((r) => setTimeout(r, 40)); } window.scrollTo(0, 0); });
			await page.waitForLoadState('networkidle');
		}
		const lay = await checkLayout(page, url, w);
		if (lay.overflowX) report.overflow.push(`${w}px ${p.path} +${lay.overflowX}px: ${lay.wide.join(' | ')}`);
		if (lay.clipped.length) report.overlap.push(`${w}px ${p.path}: ${lay.clipped.join(' | ')}`);
		if (p.shot && (p.shotWidths || [390, 1440]).includes(w)) {
			const file = path.join(out, `${p.shot}-${w}.png`);
			await page.screenshot({ path: file, fullPage: true });
			report.shots.push(file);
		}
	}
	await ctx.close();
}

// Keyboard: tab through the PT homepage header and check a visible focus indicator on each stop.
{
	const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 } });
	const page = await ctx.newPage();
	await page.goto(base + '/pt/', { waitUntil: 'networkidle' });
	const stops = [];
	for (let i = 0; i < 18; i++) {
		await page.keyboard.press('Tab');
		stops.push(await page.evaluate(() => {
			const el = document.activeElement;
			const cs = getComputedStyle(el);
			const visible = cs.outlineStyle !== 'none' && parseFloat(cs.outlineWidth) > 0 || cs.boxShadow !== 'none';
			return `${el.tagName.toLowerCase()} "${(el.textContent || el.getAttribute('aria-label') || '').trim().slice(0, 32)}" focus:${visible ? 'visible' : 'NONE'}`;
		}));
	}
	report.keyboard.push(...stops);
	// Submenu by keyboard.
	await page.goto(base + '/pt/', { waitUntil: 'networkidle' });
	const toggle = page.locator('.s4u-subtoggle').first();
	if (await toggle.count()) {
		await toggle.focus();
		await page.keyboard.press('Enter');
		report.keyboard.push('submenu Enter → expanded=' + (await toggle.getAttribute('aria-expanded')));
		await page.keyboard.press('Escape');
		report.keyboard.push('submenu Escape → expanded=' + (await toggle.getAttribute('aria-expanded')));
	}
	// Mobile menu by keyboard.
	await page.setViewportSize({ width: 390, height: 800 });
	await page.goto(base + '/pt/', { waitUntil: 'networkidle' });
	const burger = page.locator('[data-s4u-burger]');
	await burger.focus();
	await page.keyboard.press('Enter');
	report.keyboard.push('mobile menu Enter → expanded=' + (await burger.getAttribute('aria-expanded')));
	await page.screenshot({ path: path.join(out, 'menu-mobile-390.png') });
	await page.keyboard.press('Escape');
	report.keyboard.push('mobile menu Escape → expanded=' + (await burger.getAttribute('aria-expanded')) + ', focus on burger=' + (await page.evaluate(() => document.activeElement.hasAttribute('data-s4u-burger'))));
	await ctx.close();
}

// Form states.
for (const lang of ['pt', 'en']) {
	const ctx = await browser.newContext({ viewport: { width: 1024, height: 900 } });
	const page = await ctx.newPage();
	const formPath = lang === 'pt' ? '/pt/contactos/' : '/en/contact/';
	await page.goto(base + formPath, { waitUntil: 'networkidle' });
	const form = page.locator('[data-s4u-form]').first();
	if (!(await form.count())) { report.form.push(`${lang}: form not found`); await ctx.close(); continue; }
	await form.locator('[data-s4u-submit]').click();
	const summaryVisible = await form.locator('[data-s4u-summary]').isVisible();
	const errs = await form.locator('[data-error]').allTextContents();
	report.form.push(`${lang} empty submit → summary=${summaryVisible}, field errors=${errs.filter(Boolean).length}, focus=${await page.evaluate(() => document.activeElement.className)}`);
	await form.screenshot({ path: path.join(out, `form-errors-${lang}.png`) });
	await form.locator('input[name=name]').fill('Teste Demonstração');
	await form.locator('input[name=email]').fill('nao-e-email');
	await form.locator('select[name=interest]').selectOption('collaboration');
	await form.locator('textarea[name=message]').fill('curta');
	await form.locator('[data-s4u-submit]').click();
	report.form.push(`${lang} invalid email/short message → ${(await form.locator('[data-error]').allTextContents()).filter(Boolean).join(' / ')}`);
	await form.locator('input[name=email]').fill('teste@example.org');
	await form.locator('textarea[name=message]').fill('Mensagem de teste da demonstração, sem dados reais.');
	await page.waitForTimeout(400); // let live validation settle (errors clear as fields are corrected)
	await form.locator('input[name=consent]').check();
	await page.waitForTimeout(3200); // minimum fill time (anti-spam)
	const reqs = [];
	page.on('request', (r) => reqs.push(r.url()));
	await form.locator('[data-s4u-submit]').click();
	await form.locator('[data-s4u-result]').waitFor({ state: 'visible', timeout: 8000 }).catch(() => {});
	const resultText = (await form.locator('[data-s4u-result]').textContent() || '').trim();
	report.form.push(`${lang} valid submit → "${resultText}" | requests: ${reqs.filter((u) => !u.includes('.png') && !u.includes('.woff')).join(', ') || 'none'}`);
	await form.screenshot({ path: path.join(out, `form-demo-success-${lang}.png`) });
	await ctx.close();
}

// Filters & search: empty states.
{
	const ctx = await browser.newContext({ viewport: { width: 1024, height: 900 } });
	const page = await ctx.newPage();
	await page.goto(base + '/pt/projetos/', { waitUntil: 'networkidle' });
	const input = page.locator('[data-s4u-listing] input[type=search]').first();
	if (await input.count()) {
		await input.fill('zzzz-sem-resultado');
		report.form.push('projects filter no-match → empty visible=' + (await page.locator('[data-s4u-empty]').first().isVisible()) + ', count="' + (await page.locator('[data-s4u-count]').first().textContent()) + '"');
		await page.locator('[data-s4u-listing]').first().screenshot({ path: path.join(out, 'projects-empty-state.png') });
	}
	await page.goto(base + '/pt/pesquisa/?q=zzzzsemresultado', { waitUntil: 'networkidle' });
	report.form.push('search no-match → empty visible=' + (await page.locator('[data-s4u-search-empty]').isVisible()));
	await page.goto(base + '/pt/pesquisa/?q=S072', { waitUntil: 'networkidle' });
	report.form.push('search "S072" → ' + (await page.locator('[data-s4u-search-status]').textContent()).trim());
	await ctx.close();
}

await browser.close();
fs.writeFileSync(path.join(out, 'qa-report.json'), JSON.stringify(report, null, 2));
console.log(JSON.stringify({ overflow: report.overflow.length, overlap: report.overlap.length, consoleErrors: report.consoleErrors.length, http: report.http.length, shots: report.shots.length }, null, 0));
