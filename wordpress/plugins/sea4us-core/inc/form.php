<?php
/**
 * Contact form endpoint.
 *
 * Demo mode (default): full server-side validation, nothing is sent or stored.
 * Production mode: requires SEA4US_FORM_MODE = 'production' AND a recipient confirmed in
 * Settings → Sea4Us. Messages are emailed only; no personal data is stored in the database.
 */

defined( 'ABSPATH' ) || exit;

function s4u_form_mode() {
	$opts = get_option( 's4u_settings', array() );
	if ( 'production' === SEA4US_FORM_MODE && ! empty( $opts['form_recipient_ok'] ) && is_email( $opts['form_recipient'] ?? '' ) ) {
		return 'production';
	}
	return 'demo';
}

function s4u_form_interests() {
	return array( 'collaboration' => 'interest_collab', 'investment' => 'interest_invest', 'press' => 'interest_press', 'general' => 'interest_general' );
}

add_action( 'rest_api_init', function () {
	register_rest_route( 'sea4us/v1', '/contact', array(
		'methods'             => 'POST',
		'permission_callback' => '__return_true', // Public form; protected by nonce, honeypot, timing and rate limit.
		'callback'            => 's4u_handle_contact',
	) );
} );

/**
 * Validate a submission. Returns array of field => error key.
 */
function s4u_validate_contact( $data ) {
	$errors = array();
	if ( '' === trim( $data['name'] ) || mb_strlen( $data['name'] ) > 120 ) {
		$errors['name'] = 'err_required';
	}
	if ( '' === $data['email'] ) {
		$errors['email'] = 'err_required';
	} elseif ( ! is_email( $data['email'] ) ) {
		$errors['email'] = 'err_email';
	}
	if ( ! array_key_exists( $data['interest'], s4u_form_interests() ) ) {
		$errors['interest'] = 'err_required';
	}
	$len = mb_strlen( trim( $data['message'] ) );
	if ( 0 === $len ) {
		$errors['message'] = 'err_required';
	} elseif ( $len < 20 ) {
		$errors['message'] = 'err_short';
	} elseif ( $len > 3000 ) {
		$errors['message'] = 'err_long';
	}
	if ( empty( $data['consent'] ) ) {
		$errors['consent'] = 'err_consent';
	}
	if ( mb_strlen( $data['organization'] ) > 160 ) {
		$errors['organization'] = 'err_required';
	}
	return $errors;
}

function s4u_handle_contact( WP_REST_Request $req ) {
	$lang = s4u_is_lang( $req->get_param( 'lang' ) ) ? $req->get_param( 'lang' ) : s4u_default_lang();

	// Nonce.
	$nonce = (string) $req->get_param( '_s4u_nonce' );
	if ( ! wp_verify_nonce( $nonce, 's4u_contact' ) ) {
		return new WP_REST_Response( array( 'ok' => false, 'message' => s4u__( 'err_server', $lang ), 'code' => 'nonce' ), 403 );
	}

	// Honeypot and minimum fill time: silently accept bots without doing anything.
	$started = (int) $req->get_param( 'started' );
	if ( '' !== (string) $req->get_param( 'website' ) || ( $started && time() - $started < 3 ) ) {
		return new WP_REST_Response( array( 'ok' => true, 'mode' => s4u_form_mode() ), 200 );
	}

	// Rate limit: 5 submissions per 10 minutes per anonymised client.
	$ip      = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$key     = 's4u_rl_' . substr( hash_hmac( 'sha256', $ip, wp_salt( 'nonce' ) ), 0, 20 );
	$count   = (int) get_transient( $key );
	if ( $count >= 5 ) {
		return new WP_REST_Response( array( 'ok' => false, 'message' => s4u__( 'err_rate', $lang ), 'code' => 'rate' ), 429 );
	}
	set_transient( $key, $count + 1, 10 * MINUTE_IN_SECONDS );

	$data = array(
		'name'         => sanitize_text_field( (string) $req->get_param( 'name' ) ),
		'email'        => sanitize_email( (string) $req->get_param( 'email' ) ),
		'organization' => sanitize_text_field( (string) $req->get_param( 'organization' ) ),
		'interest'     => sanitize_key( (string) $req->get_param( 'interest' ) ),
		'message'      => sanitize_textarea_field( (string) $req->get_param( 'message' ) ),
		'consent'      => (bool) $req->get_param( 'consent' ),
	);
	$errors = s4u_validate_contact( $data );
	if ( $errors ) {
		$msgs = array();
		foreach ( $errors as $field => $err ) {
			$msgs[ $field ] = s4u__( $err, $lang );
		}
		return new WP_REST_Response( array( 'ok' => false, 'message' => s4u__( 'err_summary', $lang ), 'errors' => $msgs ), 422 );
	}

	if ( 'demo' === s4u_form_mode() ) {
		// Nothing is sent, logged or stored.
		return new WP_REST_Response( array(
			'ok'    => true,
			'mode'  => 'demo',
			'title' => s4u__( 'demo_success_title', $lang ),
			'text'  => s4u__( 'demo_success_text', $lang ),
		), 200 );
	}

	$opts     = get_option( 's4u_settings', array() );
	$interest = s4u__( s4u_form_interests()[ $data['interest'] ], 'pt' );
	$body     = "Nome: {$data['name']}\nEmail: {$data['email']}\nOrganização: {$data['organization']}\nAssunto: {$interest}\nIdioma: {$lang}\n\n{$data['message']}\n";
	$sent     = wp_mail(
		$opts['form_recipient'],
		'[sea4us.pt] ' . $interest . ' — ' . $data['name'],
		$body,
		array( 'Reply-To: ' . $data['name'] . ' <' . $data['email'] . '>' )
	);
	if ( ! $sent ) {
		return new WP_REST_Response( array( 'ok' => false, 'message' => s4u__( 'err_server', $lang ) ), 500 );
	}
	return new WP_REST_Response( array(
		'ok'    => true,
		'mode'  => 'production',
		'title' => s4u__( 'prod_success_title', $lang ),
		'text'  => s4u__( 'prod_success_text', $lang ),
	), 200 );
}
