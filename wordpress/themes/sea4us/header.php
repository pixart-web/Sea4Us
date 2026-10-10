<?php
/**
 * Site header: logo, main navigation, search, language switcher, contacts and partnering CTA.
 */
$lang    = s4u_current_lang();
$overlay = function_exists( 's4u_is_lang_home' ) && s4u_is_lang_home();
$opts    = get_option( 's4u_settings', array() );
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="theme-color" content="#071F2B">
<script>document.documentElement.className=document.documentElement.className.replace('no-js','js');</script>
<?php wp_head(); ?>
</head>
<body <?php body_class( $overlay ? 's4u-has-overlay-header' : '' ); ?>>
<?php wp_body_open(); ?>
<a class="s4u-skip" href="#s4u-main"><?php s4u_e( 'skip' ); ?></a>
<?php if ( empty( $opts['allow_indexing'] ) ) : ?>
	<p class="s4u-preview-bar"><?php echo esc_html( s4u_pick( 'Proposta de reformulação · pré-visualização protegida, não indexada · formulário em modo de demonstração', 'Redesign proposal · protected preview, not indexed · form in demonstration mode' ) ); ?></p>
<?php endif; ?>
<header class="s4u-header<?php echo $overlay ? ' is-overlay' : ''; ?>" data-s4u-header>
	<div class="s4u-container s4u-header__inner">
		<a class="s4u-logo" href="<?php echo esc_url( s4u_home_url( $lang ) ); ?>" rel="home">
			<img class="s4u-logo__img s4u-logo__img--light" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-sea4us-white.png' ); ?>" width="48" height="52" alt="Sea4Us — <?php s4u_e( 'home' ); ?>">
			<img class="s4u-logo__img s4u-logo__img--dark" src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/logo-sea4us.png' ); ?>" width="48" height="52" alt="Sea4Us — <?php s4u_e( 'home' ); ?>">
		</a>
		<button class="s4u-burger" type="button" aria-expanded="false" aria-controls="s4u-panel" data-s4u-burger>
			<span class="s4u-burger__icon" aria-hidden="true"><span></span><span></span><span></span></span>
			<span class="s4u-burger__label"><?php s4u_e( 'menu' ); ?></span>
		</button>
		<div class="s4u-panel" id="s4u-panel" data-s4u-panel>
			<nav class="s4u-nav" aria-label="<?php s4u_e( 'main_nav' ); ?>">
				<?php s4u_theme_menu( 'primary', 's4u-nav__list' ); ?>
			</nav>
			<div class="s4u-header__tools">
				<a class="s4u-tool s4u-tool--search" href="<?php echo esc_url( s4u_page_url( 'search', $lang ) ); ?>"><?php echo s4u_icon( 'search' ); // phpcs:ignore ?><span class="s4u-tool__label"><?php s4u_e( 'search' ); ?></span></a>
				<a class="s4u-tool s4u-tool--contact" href="<?php echo esc_url( s4u_page_url( 'contact', $lang ) ); ?>"><span class="s4u-tool__label s4u-tool__label--always"><?php s4u_e( 'contacts' ); ?></span></a>
				<nav class="s4u-lang" aria-label="<?php s4u_e( 'language' ); ?>">
					<ul>
						<?php foreach ( s4u_languages() as $code => $l ) : ?>
							<li>
								<?php if ( $code === $lang ) : ?>
									<span class="s4u-lang__item is-current" aria-current="true"><abbr title="<?php echo esc_attr( $l['label'] ); ?>"><?php echo esc_html( $l['short'] ); ?></abbr></span>
								<?php else : ?>
									<a class="s4u-lang__item" href="<?php echo esc_url( s4u_switcher_url( $code ) ); ?>" hreflang="<?php echo esc_attr( $l['hreflang'] ); ?>" lang="<?php echo esc_attr( $l['hreflang'] ); ?>"><span class="screen-reader-text"><?php echo esc_html( $l['label'] ); ?> — </span><span aria-hidden="true"><?php echo esc_html( $l['short'] ); ?></span></a>
								<?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>
				</nav>
				<a class="s4u-btn s4u-btn--primary s4u-header__cta" href="<?php echo esc_url( s4u_page_url( 'partnering', $lang ) ); ?>"><?php echo esc_html( s4u_pick( 'Parcerias e investimento', 'Partnering and investment' ) ); ?></a>
			</div>
		</div>
	</div>
</header>
