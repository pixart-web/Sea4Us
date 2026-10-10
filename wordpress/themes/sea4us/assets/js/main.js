/* Sea4Us — progressive enhancements. Everything works without this file. */
( function () {
	'use strict';

	var doc = document;
	var root = doc.documentElement;
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	function $( sel, ctx ) { return ( ctx || doc ).querySelector( sel ); }
	function $$( sel, ctx ) { return Array.prototype.slice.call( ( ctx || doc ).querySelectorAll( sel ) ); }
	function norm( s ) { return ( s || '' ).toString().toLowerCase().normalize( 'NFD' ).replace( /[̀-ͯ]/g, '' ); }

	/* ---------- Header: overlay → solid, panel offset ---------- */
	var header = $( '[data-s4u-header]' );
	if ( header ) {
		var setTop = function () {
			var r = header.getBoundingClientRect();
			root.style.setProperty( '--panel-top', Math.max( 0, r.bottom ) + 'px' );
		};
		var onScroll = function () {
			if ( header.classList.contains( 'is-overlay' ) ) {
				header.classList.toggle( 'is-solid', window.scrollY > 40 || doc.body.classList.contains( 's4u-menu-open' ) );
			} else {
				header.classList.toggle( 'is-solid', window.scrollY > 8 );
			}
			setTop();
		};
		var ticking = false;
		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				window.requestAnimationFrame( function () { onScroll(); ticking = false; } );
				ticking = true;
			}
		}, { passive: true } );
		window.addEventListener( 'resize', setTop );
		onScroll();
	}

	/* ---------- Mobile panel ---------- */
	var burger = $( '[data-s4u-burger]' );
	var panel = $( '[data-s4u-panel]' );
	if ( burger && panel ) {
		var close = function ( focus ) {
			burger.setAttribute( 'aria-expanded', 'false' );
			panel.classList.remove( 'is-open' );
			doc.body.classList.remove( 's4u-menu-open' );
			if ( header ) { header.classList.toggle( 'is-solid', window.scrollY > 40 || ! header.classList.contains( 'is-overlay' ) && window.scrollY > 8 ); }
			if ( focus ) { burger.focus(); }
		};
		burger.addEventListener( 'click', function () {
			var open = burger.getAttribute( 'aria-expanded' ) !== 'true';
			if ( ! open ) { close(); return; }
			burger.setAttribute( 'aria-expanded', 'true' );
			panel.classList.add( 'is-open' );
			doc.body.classList.add( 's4u-menu-open' );
			if ( header ) { header.classList.add( 'is-solid' ); }
			var first = $( 'a, button', panel );
			if ( first ) { first.focus(); }
		} );
		doc.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && panel.classList.contains( 'is-open' ) ) { close( true ); }
		} );
		window.matchMedia( '(min-width: 1100px)' ).addEventListener( 'change', function ( m ) { if ( m.matches ) { close(); } } );
	}

	/* ---------- Sub-menus: real buttons, keyboard and touch friendly ---------- */
	$$( '.s4u-nav__list .menu-item-has-children' ).forEach( function ( li, i ) {
		var link = $( 'a', li );
		var sub = $( '.sub-menu', li );
		if ( ! link || ! sub ) { return; }
		sub.id = sub.id || 's4u-sub-' + i;
		var btn = doc.createElement( 'button' );
		btn.type = 'button';
		btn.className = 's4u-subtoggle';
		btn.setAttribute( 'aria-expanded', 'false' );
		btn.setAttribute( 'aria-controls', sub.id );
		btn.innerHTML = '<span class="screen-reader-text">' + ( root.lang.indexOf( 'en' ) === 0 ? 'Show submenu: ' : 'Mostrar submenu: ' ) + link.textContent + '</span>';
		link.insertAdjacentElement( 'afterend', btn );
		var set = function ( open ) {
			btn.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			li.classList.toggle( 'is-open', open );
		};
		btn.addEventListener( 'click', function () { set( btn.getAttribute( 'aria-expanded' ) !== 'true' ); } );
		li.addEventListener( 'keydown', function ( e ) {
			if ( e.key === 'Escape' && li.classList.contains( 'is-open' ) ) { set( false ); btn.focus(); e.stopPropagation(); }
		} );
		li.addEventListener( 'focusout', function ( e ) {
			if ( ! li.contains( e.relatedTarget ) && window.innerWidth >= 1100 ) { set( false ); }
		} );
	} );

	/* ---------- Reveal on scroll (decorative only) ---------- */
	if ( 'IntersectionObserver' in window && ! reduced ) {
		var targets = $$( '.s4u-head, .s4u-statement, .s4u-split-media, .s4u-pillar, .s4u-cards > *, .s4u-team > *, .s4u-milestones__item, .s4u-location, .s4u-sheet, .s4u-partner' );
		targets.forEach( function ( el, i ) {
			el.classList.add( 's4u-reveal' );
			el.style.transitionDelay = Math.min( ( i % 4 ) * 70, 210 ) + 'ms';
		} );
		var io = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( en ) {
				if ( en.isIntersecting ) { en.target.classList.add( 'is-visible' ); io.unobserve( en.target ); }
			} );
		}, { rootMargin: '0px 0px -8% 0px', threshold: 0.08 } );
		targets.concat( $$( '.s4u-pipeline' ) ).forEach( function ( el ) { io.observe( el ); } );
	} else {
		$$( '.s4u-pipeline' ).forEach( function ( el ) { el.classList.add( 'is-visible' ); } );
	}

	/* ---------- Listings: filters + "show more" ---------- */
	$$( '[data-s4u-listing]' ).forEach( function ( list ) {
		var form = $( '[data-s4u-filters]', list );
		var items = $$( '[data-s4u-items] > *', list );
		var count = $( '[data-s4u-count]', list );
		var empty = $( '[data-s4u-empty]', list );
		var more = $( '[data-s4u-more]', list );
		var size = parseInt( list.getAttribute( 'data-page-size' ), 10 ) || 0;
		var limit = size;
		var isEn = root.lang.indexOf( 'en' ) === 0;
		if ( form ) { form.hidden = false; }

		function state() {
			var s = {};
			$$( '[data-filter]', form ).forEach( function ( f ) {
				if ( ( f.type === 'radio' && ! f.checked ) ) { return; }
				s[ f.getAttribute( 'data-filter' ) ] = f.value;
			} );
			return s;
		}
		function apply( resetLimit ) {
			if ( resetLimit ) { limit = size; }
			var s = state();
			var q = norm( s.text ).trim();
			var matches = items.filter( function ( it ) {
				for ( var k in s ) {
					if ( k === 'text' || ! s[ k ] ) { continue; }
					if ( it.getAttribute( 'data-' + k ) !== s[ k ] ) { return false; }
				}
				return ! q || norm( it.getAttribute( 'data-text' ) ).indexOf( q ) !== -1;
			} );
			items.forEach( function ( it ) { it.hidden = true; } );
			matches.forEach( function ( it, i ) { it.hidden = !! ( limit && i >= limit ); } );
			var shown = limit ? Math.min( limit, matches.length ) : matches.length;
			if ( count ) {
				count.textContent = ( matches.length === 0 || ( matches.length === items.length && shown === matches.length ) )
					? matches.length + ( isEn ? ' results' : ' resultados' )
					: ( isEn ? 'Showing ' : 'A mostrar ' ) + shown + ( isEn ? ' of ' : ' de ' ) + matches.length + ( isEn ? ' results' : ' resultados' );
			}
			if ( empty ) { empty.hidden = matches.length !== 0; }
			if ( more ) { more.hidden = ! limit || matches.length <= limit; }
		}
		if ( form ) {
			form.addEventListener( 'input', function () { apply( true ); } );
			form.addEventListener( 'change', function () { apply( true ); } );
			form.addEventListener( 'submit', function ( e ) { e.preventDefault(); } );
			form.addEventListener( 'reset', function () { setTimeout( function () { apply( true ); }, 0 ); } );
		}
		if ( more ) {
			more.addEventListener( 'click', function () {
				var firstHidden = items.filter( function ( it ) { return it.hidden; } )[ 0 ];
				limit += size;
				apply( false );
				var link = firstHidden && ! firstHidden.hidden ? $( 'a', firstHidden ) : null;
				if ( link ) { link.focus(); }
			} );
		}
		apply( true );
	} );

	/* ---------- Contact form: client + server validation, demo mode ---------- */
	$$( '[data-s4u-form]' ).forEach( function ( form ) {
		var msgs = {};
		try { msgs = JSON.parse( $( '[data-s4u-messages]', form ).textContent ); } catch ( e ) {}
		var summary = $( '[data-s4u-summary]', form );
		var result = $( '[data-s4u-result]', form );
		var started = $( '[data-s4u-started]', form );
		var labelOf = function ( field ) {
			var l = $( 'label', field );
			return l ? l.textContent.replace( /\*|\(.*\)/g, '' ).trim() : '';
		};

		function setError( name, text ) {
			var field = $( '[data-field="' + name + '"]', form );
			if ( ! field ) { return; }
			var input = $( 'input, select, textarea', field );
			var err = $( '[data-error]', field );
			field.classList.toggle( 'has-error', !! text );
			if ( input ) { input.setAttribute( 'aria-invalid', text ? 'true' : 'false' ); }
			if ( err ) { err.textContent = text || ''; }
		}
		function validate() {
			var data = new FormData( form );
			var errors = {};
			var name = ( data.get( 'name' ) || '' ).trim();
			var email = ( data.get( 'email' ) || '' ).trim();
			var message = ( data.get( 'message' ) || '' ).trim();
			if ( ! name ) { errors.name = msgs.err_required; }
			if ( ! email ) { errors.email = msgs.err_required; } else if ( ! /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test( email ) ) { errors.email = msgs.err_email; }
			if ( ! data.get( 'interest' ) ) { errors.interest = msgs.err_required; }
			if ( ! message ) { errors.message = msgs.err_required; } else if ( message.length < 20 ) { errors.message = msgs.err_short; } else if ( message.length > 3000 ) { errors.message = msgs.err_long; }
			if ( ! data.get( 'consent' ) ) { errors.consent = msgs.err_consent; }
			return errors;
		}
		function showErrors( errors ) {
			[ 'name', 'email', 'interest', 'message', 'consent', 'organization' ].forEach( function ( f ) { setError( f, errors[ f ] ); } );
			var keys = Object.keys( errors );
			if ( ! keys.length ) { summary.hidden = true; return; }
			var html = '<p>' + msgs.err_summary + '</p><ul>';
			keys.forEach( function ( k ) {
				var field = $( '[data-field="' + k + '"]', form );
				var input = field && $( 'input, select, textarea', field );
				html += '<li><a href="#' + ( input ? input.id : '' ) + '">' + labelOf( field ) + ': ' + errors[ k ] + '</a></li>';
			} );
			summary.innerHTML = html + '</ul>';
			summary.hidden = false;
			summary.focus();
		}
		function success( title, text ) {
			result.innerHTML = '<h3></h3><p></p>';
			result.querySelector( 'h3' ).textContent = title;
			result.querySelector( 'p' ).textContent = text;
			result.hidden = false;
			form.reset();
			if ( started ) { started.value = Math.floor( Date.now() / 1000 ); }
			result.focus();
		}
		// Re-validate a field as it is corrected after an error was shown (while typing, so the
		// error clears before the user moves on and the layout does not shift under the pointer).
		function revalidate( e ) {
			var field = e.target.closest( '[data-field]' );
			if ( field && field.classList.contains( 'has-error' ) ) {
				var errs = validate();
				setError( field.getAttribute( 'data-field' ), errs[ field.getAttribute( 'data-field' ) ] );
			}
		}
		form.addEventListener( 'input', revalidate );
		form.addEventListener( 'change', revalidate );
		form.addEventListener( 'submit', function ( e ) {
			e.preventDefault();
			result.hidden = true;
			var errors = validate();
			showErrors( errors );
			if ( Object.keys( errors ).length ) { return; }
			form.classList.add( 'is-busy' );
			var mode = form.getAttribute( 'data-mode' );
			fetch( form.action, { method: 'POST', body: new FormData( form ), credentials: 'same-origin', headers: { Accept: 'application/json' } } )
				.then( function ( r ) {
					return r.json().then( function ( j ) { return { status: r.status, json: j }; } );
				} )
				.then( function ( res ) {
					form.classList.remove( 'is-busy' );
					if ( res.json && res.json.ok ) { success( res.json.title || msgs.demo_title, res.json.text || msgs.demo_text ); return; }
					if ( res.json && res.json.errors ) { showErrors( res.json.errors ); return; }
					summary.innerHTML = '<p>' + ( ( res.json && res.json.message ) || msgs.err_server ) + '</p>';
					summary.hidden = false;
					summary.focus();
				} )
				.catch( function () {
					form.classList.remove( 'is-busy' );
					// Static preview (no server): in demo mode the client-side validation is the whole demo.
					if ( mode === 'demo' ) { success( msgs.demo_title, msgs.demo_text ); return; }
					summary.innerHTML = '<p>' + msgs.err_server + '</p>';
					summary.hidden = false;
					summary.focus();
				} );
		} );
	} );

	/* ---------- Instant search from the language index ---------- */
	$$( '[data-s4u-search]' ).forEach( function ( box ) {
		var input = $( '[data-s4u-search-input]', box );
		var list = $( '[data-s4u-search-results]', box );
		var status = $( '[data-s4u-search-status]', box );
		var empty = $( '[data-s4u-search-empty]', box );
		var m = {};
		try { m = JSON.parse( $( '[data-s4u-search-messages]', box ).textContent ); } catch ( e ) {}
		var index = null;
		var base = box.getAttribute( 'data-base' ) || '';
		var timer;
		function esc( s ) { var d = doc.createElement( 'div' ); d.textContent = s; return d.innerHTML; }
		function load() {
			if ( index ) { return Promise.resolve( index ); }
			return fetch( box.getAttribute( 'data-index' ), { credentials: 'same-origin' } ).then( function ( r ) { return r.json(); } ).then( function ( j ) { index = j; return j; } );
		}
		function run() {
			var q = input.value.trim();
			if ( q.length < 2 ) {
				if ( ! q ) { list.innerHTML = ''; status.textContent = ''; empty.hidden = true; } else { status.textContent = m.hint; }
				return;
			}
			load().then( function ( rows ) {
				var terms = norm( q ).split( /\s+/ ).filter( Boolean );
				var hits = rows.map( function ( r ) {
					var t = norm( r.t ), b = norm( r.b ), score = 0;
					for ( var i = 0; i < terms.length; i++ ) {
						if ( t.indexOf( terms[ i ] ) !== -1 ) { score += 5; } else if ( b.indexOf( terms[ i ] ) !== -1 ) { score += 1; } else { return null; }
					}
					return { r: r, s: score };
				} ).filter( Boolean ).sort( function ( a, b ) { return b.s - a.s; } );
				list.innerHTML = hits.slice( 0, 60 ).map( function ( h ) {
					var r = h.r;
					return '<li class="s4u-result"><p class="s4u-meta"><span>' + esc( r.k ) + '</span>' + ( r.d ? '<span class="s4u-meta__sep" aria-hidden="true">·</span><span>' + esc( r.d ) + '</span>' : '' ) + '</p><h2 class="s4u-result__title"><a href="' + esc( base ? base + r.u.replace( /^\//, '' ) : r.u ) + '">' + esc( r.t ) + '</a></h2><p class="s4u-result__text">' + esc( r.x ) + '</p></li>';
				} ).join( '' );
				status.textContent = hits.length + ' ' + ( hits.length === 1 ? m.result : m.results ) + ' — ' + m.for + ' “' + q + '”';
				empty.hidden = hits.length !== 0;
				if ( window.history && window.history.replaceState ) {
					var u = new URL( window.location.href );
					u.searchParams.set( 'q', q );
					window.history.replaceState( null, '', u.toString() );
				}
			} ).catch( function () {} );
		}
		if ( input ) {
			input.addEventListener( 'input', function () { clearTimeout( timer ); timer = setTimeout( run, 180 ); } );
			// Static preview: the server could not read ?q=, so run it here.
			var qs = new URLSearchParams( window.location.search ).get( 'q' );
			if ( qs && ! list.children.length ) { input.value = qs; run(); }
		}
	} );
} )();
