<?php
/**
 * Invitation to talk, with the three audiences named.
 */
$a    = $args['a'] ?? array();
$lang = s4u_current_lang();
$title = $a['title'] ?: s4u_pick( 'Vamos conversar sobre ciência, parcerias e investimento.', "Let's talk about science, partnerships and investment." );
$text  = $a['text'] ?: s4u_pick( 'A Sea4Us procura conversas qualificadas com quem pode acelerar a passagem da investigação para a clínica.', 'Sea4Us welcomes qualified conversations with those who can help move research towards the clinic.' );
$doors = array(
	array( s4u_pick( 'Indústria farmacêutica e biotecnológica', 'Pharma and biotech industry' ), s4u_pick( 'Licenciamento, codesenvolvimento e acesso à plataforma.', 'Licensing, co-development and platform access.' ), s4u_page_url( 'partnering' ) . '#industria' ),
	array( s4u_pick( 'Investidores', 'Investors' ), s4u_pick( 'Maturidade dos programas, equipa e marcos verificáveis.', 'Programme maturity, team and verifiable milestones.' ), s4u_page_url( 'partnering' ) . '#investidores' ),
	array( s4u_pick( 'Investigadores e instituições', 'Researchers and institutions' ), s4u_pick( 'Projetos colaborativos, competências e documentação.', 'Collaborative projects, expertise and documentation.' ), s4u_page_url( 'partnering' ) . '#investigacao' ),
);
?>
<section class="s4u-section s4u-section--petrol s4u-cta" aria-labelledby="s4u-cta-title">
	<div class="s4u-container s4u-cta__grid">
		<div class="s4u-cta__copy">
			<h2 id="s4u-cta-title" class="s4u-h2"><?php echo esc_html( $title ); ?></h2>
			<p class="s4u-lead"><?php echo esc_html( $text ); ?></p>
			<div class="s4u-actions">
				<a class="s4u-btn s4u-btn--light" href="<?php echo esc_url( $a['cta1Url'] ?: s4u_page_url( 'partnering' ) ); ?>"><?php echo esc_html( $a['cta1Label'] ?: s4u_pick( 'Parcerias e investimento', 'Partnering and investment' ) ); ?> <?php echo s4u_icon( 'arrow' ); // phpcs:ignore ?></a>
				<a class="s4u-btn s4u-btn--ghost" href="<?php echo esc_url( $a['cta2Url'] ?: s4u_page_url( 'contact' ) ); ?>"><?php echo esc_html( $a['cta2Label'] ?: s4u_pick( 'Contactos', 'Contact' ) ); ?></a>
			</div>
		</div>
		<ul class="s4u-doors">
			<?php foreach ( $doors as $i => $d ) : ?>
				<li class="s4u-door">
					<span class="s4u-door__n" aria-hidden="true">0<?php echo (int) $i + 1; ?></span>
					<h3 class="s4u-door__title"><a class="s4u-card__link" href="<?php echo esc_url( $d[2] ); ?>"><?php echo esc_html( $d[0] ); ?></a></h3>
					<p class="s4u-door__text"><?php echo esc_html( $d[1] ); ?></p>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
