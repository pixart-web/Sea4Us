<?php
/**
 * Qualified first-contact form. Demo mode by default: validates, sends nothing, stores nothing.
 */
$a       = $args['a'] ?? array();
$lang    = s4u_current_lang();
$mode    = s4u_form_mode();
$uid     = 's4u-form-' . wp_unique_id();
$pre     = array_key_exists( $a['interest'] ?? '', s4u_form_interests() ) ? $a['interest'] : '';
$privacy = s4u_page_url( 'privacy', $lang );
?>
<form class="s4u-form" id="<?php echo esc_attr( $uid ); ?>" method="post" action="<?php echo esc_url( rest_url( 'sea4us/v1/contact' ) ); ?>" data-s4u-form data-mode="<?php echo esc_attr( $mode ); ?>" novalidate>
	<?php if ( 'demo' === $mode ) : ?>
		<p class="s4u-form__demo"><?php echo s4u_icon( 'info' ); // phpcs:ignore ?><span><?php echo esc_html( s4u_pick( 'Modo de demonstração: o formulário valida os dados mas não envia nem guarda nada.', 'Demonstration mode: the form validates input but sends and stores nothing.' ) ); ?></span></p>
	<?php endif; ?>
	<div class="s4u-form__summary" role="alert" tabindex="-1" data-s4u-summary hidden></div>
	<input type="hidden" name="_s4u_nonce" value="<?php echo esc_attr( wp_create_nonce( 's4u_contact' ) ); ?>">
	<input type="hidden" name="lang" value="<?php echo esc_attr( $lang ); ?>">
	<input type="hidden" name="started" value="<?php echo esc_attr( time() ); ?>" data-s4u-started>
	<div class="s4u-hp" aria-hidden="true"><label for="<?php echo esc_attr( $uid ); ?>-website">Website</label><input type="text" id="<?php echo esc_attr( $uid ); ?>-website" name="website" tabindex="-1" autocomplete="off"></div>

	<div class="s4u-form__grid">
		<div class="s4u-field" data-field="name">
			<label for="<?php echo esc_attr( $uid ); ?>-name"><?php s4u_e( 'form_name' ); ?> <span class="s4u-req" aria-hidden="true">*</span></label>
			<input type="text" id="<?php echo esc_attr( $uid ); ?>-name" name="name" autocomplete="name" required maxlength="120" aria-describedby="<?php echo esc_attr( $uid ); ?>-name-err">
			<p class="s4u-field__error" id="<?php echo esc_attr( $uid ); ?>-name-err" data-error></p>
		</div>
		<div class="s4u-field" data-field="email">
			<label for="<?php echo esc_attr( $uid ); ?>-email"><?php s4u_e( 'form_email' ); ?> <span class="s4u-req" aria-hidden="true">*</span></label>
			<input type="email" id="<?php echo esc_attr( $uid ); ?>-email" name="email" autocomplete="email" required maxlength="160" aria-describedby="<?php echo esc_attr( $uid ); ?>-email-err">
			<p class="s4u-field__error" id="<?php echo esc_attr( $uid ); ?>-email-err" data-error></p>
		</div>
		<div class="s4u-field" data-field="organization">
			<label for="<?php echo esc_attr( $uid ); ?>-org"><?php s4u_e( 'form_org' ); ?> <span class="s4u-opt">(<?php s4u_e( 'optional' ); ?>)</span></label>
			<input type="text" id="<?php echo esc_attr( $uid ); ?>-org" name="organization" autocomplete="organization" maxlength="160">
		</div>
		<div class="s4u-field" data-field="interest">
			<label for="<?php echo esc_attr( $uid ); ?>-interest"><?php s4u_e( 'form_interest' ); ?> <span class="s4u-req" aria-hidden="true">*</span></label>
			<select id="<?php echo esc_attr( $uid ); ?>-interest" name="interest" required aria-describedby="<?php echo esc_attr( $uid ); ?>-interest-err">
				<option value=""><?php echo esc_html( s4u_pick( 'Selecione…', 'Select…' ) ); ?></option>
				<?php foreach ( s4u_form_interests() as $k => $label ) : ?>
					<option value="<?php echo esc_attr( $k ); ?>"<?php selected( $pre, $k ); ?>><?php s4u_e( $label ); ?></option>
				<?php endforeach; ?>
			</select>
			<p class="s4u-field__error" id="<?php echo esc_attr( $uid ); ?>-interest-err" data-error></p>
		</div>
		<div class="s4u-field s4u-field--full" data-field="message">
			<label for="<?php echo esc_attr( $uid ); ?>-message"><?php s4u_e( 'form_message' ); ?> <span class="s4u-req" aria-hidden="true">*</span></label>
			<textarea id="<?php echo esc_attr( $uid ); ?>-message" name="message" rows="6" required minlength="20" maxlength="3000" aria-describedby="<?php echo esc_attr( $uid ); ?>-message-hint <?php echo esc_attr( $uid ); ?>-message-err"></textarea>
			<p class="s4u-field__hint" id="<?php echo esc_attr( $uid ); ?>-message-hint"><?php s4u_e( 'form_notice' ); ?></p>
			<p class="s4u-field__error" id="<?php echo esc_attr( $uid ); ?>-message-err" data-error></p>
		</div>
		<div class="s4u-field s4u-field--full s4u-field--check" data-field="consent">
			<input type="checkbox" id="<?php echo esc_attr( $uid ); ?>-consent" name="consent" value="1" required aria-describedby="<?php echo esc_attr( $uid ); ?>-consent-err">
			<label for="<?php echo esc_attr( $uid ); ?>-consent"><?php echo wp_kses( str_replace( s4u_pick( 'política de privacidade', 'privacy policy' ), '<a href="' . esc_url( $privacy ) . '">' . s4u_pick( 'política de privacidade', 'privacy policy' ) . '</a>', s4u__( 'form_privacy' ) ), array( 'a' => array( 'href' => array() ) ) ); ?></label>
			<p class="s4u-field__error" id="<?php echo esc_attr( $uid ); ?>-consent-err" data-error></p>
		</div>
	</div>
	<div class="s4u-form__actions">
		<button type="submit" class="s4u-btn s4u-btn--primary" data-s4u-submit><?php s4u_e( 'form_submit' ); ?> <?php echo s4u_icon( 'arrow' ); // phpcs:ignore ?></button>
		<p class="s4u-form__req"><span aria-hidden="true">*</span> <?php s4u_e( 'form_required' ); ?></p>
	</div>
	<div class="s4u-form__result" role="status" tabindex="-1" data-s4u-result hidden></div>
	<script type="application/json" data-s4u-messages><?php echo wp_json_encode( array(
		'err_required' => s4u__( 'err_required' ),
		'err_email'    => s4u__( 'err_email' ),
		'err_short'    => s4u__( 'err_short' ),
		'err_long'     => s4u__( 'err_long' ),
		'err_consent'  => s4u__( 'err_consent' ),
		'err_summary'  => s4u__( 'err_summary' ),
		'err_server'   => s4u__( 'err_server' ),
		'demo_title'   => s4u__( 'demo_success_title' ),
		'demo_text'    => s4u__( 'demo_success_text' ),
	), JSON_UNESCAPED_UNICODE | JSON_HEX_TAG ); ?></script>
</form>
