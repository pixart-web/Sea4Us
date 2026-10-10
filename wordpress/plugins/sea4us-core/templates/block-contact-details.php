<?php
$a     = $args['a'] ?? array();
$email = ! empty( $a['partnering'] ) ? s4u_partnering_email() : s4u_setting( 'email' );
$phone = s4u_setting( 'phone' );
$tel   = s4u_setting( 'phone_e164' );
$hours = s4u_setting( 'hours' );
$unconfirmed = ! empty( $a['partnering'] ) && ! s4u_setting( 'partnering_confirmed' );
?>
<ul class="s4u-contact-list">
	<?php if ( $email ) : ?>
		<li><?php echo s4u_icon( 'mail' ); // phpcs:ignore ?><span><span class="s4u-contact-list__k"><?php s4u_e( 'email' ); ?></span><a href="mailto:<?php echo esc_attr( antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></span></li>
	<?php endif; ?>
	<?php if ( $phone ) : ?>
		<li><?php echo s4u_icon( 'phone' ); // phpcs:ignore ?><span><span class="s4u-contact-list__k"><?php s4u_e( 'phone' ); ?></span><a href="tel:<?php echo esc_attr( preg_replace( '/[^+\d]/', '', $tel ?: $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></span></li>
	<?php endif; ?>
	<?php if ( $hours ) : ?>
		<li><?php echo s4u_icon( 'clock' ); // phpcs:ignore ?><span><span class="s4u-contact-list__k"><?php s4u_e( 'hours' ); ?></span><?php echo esc_html( $hours ); ?></span></li>
	<?php endif; ?>
</ul>
<?php if ( $unconfirmed ) : ?>
	<p class="s4u-review-flag"><?php echo esc_html( s4u_pick( 'Endereço dedicado a parcerias por confirmar: a fonte publica partnering@sea4us.pt e partnering@sea4us.com. Até confirmação, é apresentado o contacto geral.', 'Dedicated partnering address to be confirmed: the source publishes both partnering@sea4us.pt and partnering@sea4us.com. Until confirmed, the general contact is shown.' ) ); ?></p>
<?php endif; ?>
