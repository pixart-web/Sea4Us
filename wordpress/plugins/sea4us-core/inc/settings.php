<?php
/**
 * Single source of truth for contacts, locations and site-wide switches (Settings → Sea4Us).
 */

defined( 'ABSPATH' ) || exit;

function s4u_settings_schema() {
	return array(
		'Contactos gerais'          => array(
			'email'            => array( 'Email geral', 'email' ),
			'phone'            => array( 'Telefone (como apresentado)', 'text' ),
			'phone_e164'       => array( 'Telefone (formato +351…)', 'text' ),
			'hours_pt'         => array( 'Horário (PT)', 'text' ),
			'hours_en'         => array( 'Horário (EN)', 'text' ),
			'contacts_review'  => array( 'Contactos reconfirmados pela Sea4Us', 'checkbox' ),
		),
		'Localizações'              => array(
			'loc1_name_pt'     => array( 'Localização 1 · nome (PT)', 'text' ),
			'loc1_name_en'     => array( 'Localização 1 · nome (EN)', 'text' ),
			'loc1_role_pt'     => array( 'Localização 1 · função (PT)', 'textarea' ),
			'loc1_role_en'     => array( 'Localização 1 · função (EN)', 'textarea' ),
			'loc1_address'     => array( 'Localização 1 · morada', 'textarea' ),
			'loc1_map'         => array( 'Localização 1 · ligação de mapa (abre externamente)', 'url' ),
			'loc2_name_pt'     => array( 'Localização 2 · nome (PT)', 'text' ),
			'loc2_name_en'     => array( 'Localização 2 · nome (EN)', 'text' ),
			'loc2_role_pt'     => array( 'Localização 2 · função (PT)', 'textarea' ),
			'loc2_role_en'     => array( 'Localização 2 · função (EN)', 'textarea' ),
			'loc2_address'     => array( 'Localização 2 · morada', 'textarea' ),
			'loc2_map'         => array( 'Localização 2 · ligação de mapa (abre externamente)', 'url' ),
		),
		'Parcerias e formulário'    => array(
			'partnering_email_pt'  => array( 'Email de parcerias publicado (.pt)', 'email' ),
			'partnering_email_com' => array( 'Email de parcerias publicado (.com)', 'email' ),
			'partnering_confirmed' => array( 'Email de parcerias confirmado', 'select', array( '' => 'Por confirmar (mostrar email geral)', 'pt' => 'Usar .pt', 'com' => 'Usar .com' ) ),
			'form_recipient'       => array( 'Destinatário do formulário (só produção)', 'email' ),
			'form_recipient_ok'    => array( 'Destinatário confirmado pela Sea4Us', 'checkbox' ),
		),
		'Redes sociais'             => array(
			'linkedin'         => array( 'LinkedIn', 'url' ),
			'facebook'         => array( 'Facebook', 'url' ),
			'instagram'        => array( 'Instagram', 'url' ),
			'youtube'          => array( 'YouTube', 'url' ),
		),
		'Rodapé e créditos'         => array(
			'funding_strip'    => array( 'Bloco de cofinanciamento (IDs de imagens, pela ordem original)', 'text' ),
			'credits_pt'       => array( 'Créditos de imagem (PT)', 'textarea' ),
			'credits_en'       => array( 'Créditos de imagem (EN)', 'textarea' ),
		),
		'Lançamento'                => array(
			'allow_indexing'    => array( 'Permitir indexação (remover noindex) — apenas no lançamento autorizado', 'checkbox' ),
			'redirects_enabled' => array( 'Ativar redirecionamentos 301 do site antigo — apenas no lançamento', 'checkbox' ),
			'root_lang'         => array( 'Idioma da raiz / e x-default', 'select', array( 'en' => 'Inglês (como no site atual)', 'pt' => 'Português' ) ),
			'root_behavior'     => array( 'Comportamento da raiz /', 'select', array( 'redirect_302' => 'Redirecionamento temporário (302) para o idioma da raiz', 'redirect_301' => 'Redirecionamento permanente (301) para o idioma da raiz (após confirmar o comportamento atual)' ) ),
		),
	);
}

function s4u_setting( $key, $lang = null ) {
	$opts = get_option( 's4u_settings', array() );
	if ( isset( $opts[ $key ] ) && '' !== $opts[ $key ] ) {
		return $opts[ $key ];
	}
	$lang = $lang ?: s4u_current_lang();
	$k    = $key . '_' . $lang;
	return $opts[ $k ] ?? '';
}

/**
 * Partnering contact: only a confirmed address is shown; otherwise the general email.
 */
function s4u_partnering_email() {
	$which = s4u_setting( 'partnering_confirmed' );
	if ( 'pt' === $which || 'com' === $which ) {
		return s4u_setting( 'partnering_email_' . $which );
	}
	return s4u_setting( 'email' );
}

add_action( 'admin_menu', function () {
	add_options_page( 'Sea4Us', 'Sea4Us', 'manage_options', 's4u-settings', 's4u_settings_page' );
} );

add_action( 'admin_init', function () {
	register_setting( 's4u_settings', 's4u_settings', array(
		'type'              => 'array',
		'sanitize_callback' => 's4u_sanitize_settings',
	) );
} );

function s4u_sanitize_settings( $input ) {
	$out = array();
	foreach ( s4u_settings_schema() as $fields ) {
		foreach ( $fields as $key => $def ) {
			$v = $input[ $key ] ?? '';
			switch ( $def[1] ) {
				case 'email':
					$out[ $key ] = sanitize_email( $v );
					break;
				case 'url':
					$out[ $key ] = esc_url_raw( $v );
					break;
				case 'textarea':
					$out[ $key ] = sanitize_textarea_field( $v );
					break;
				case 'checkbox':
					$out[ $key ] = $v ? '1' : '';
					break;
				case 'select':
					$out[ $key ] = array_key_exists( $v, $def[2] ) ? $v : array_key_first( $def[2] );
					break;
				default:
					$out[ $key ] = sanitize_text_field( $v );
			}
		}
	}
	return $out;
}

function s4u_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = get_option( 's4u_settings', array() );
	echo '<div class="wrap"><h1>Sea4Us</h1>';
	echo '<p>Fonte única para contactos, localizações e interruptores de lançamento, partilhada por PT e EN.</p>';
	printf( '<p><strong>Modo do formulário:</strong> %s</p>', 'production' === SEA4US_FORM_MODE ? 'produção' : 'demonstração (nada é enviado nem guardado)' );
	echo '<form method="post" action="options.php">';
	settings_fields( 's4u_settings' );
	foreach ( s4u_settings_schema() as $section => $fields ) {
		echo '<h2>' . esc_html( $section ) . '</h2><table class="form-table" role="presentation"><tbody>';
		foreach ( $fields as $key => $def ) {
			$id  = 's4u_s_' . $key;
			$val = $opts[ $key ] ?? '';
			echo '<tr><th scope="row"><label for="' . esc_attr( $id ) . '">' . esc_html( $def[0] ) . '</label></th><td>';
			$name = 's4u_settings[' . $key . ']';
			if ( 'textarea' === $def[1] ) {
				printf( '<textarea class="large-text" rows="3" id="%s" name="%s">%s</textarea>', esc_attr( $id ), esc_attr( $name ), esc_textarea( $val ) );
			} elseif ( 'checkbox' === $def[1] ) {
				printf( '<input type="checkbox" id="%s" name="%s" value="1"%s>', esc_attr( $id ), esc_attr( $name ), checked( $val, '1', false ) );
			} elseif ( 'select' === $def[1] ) {
				printf( '<select id="%s" name="%s">', esc_attr( $id ), esc_attr( $name ) );
				foreach ( $def[2] as $k => $label ) {
					printf( '<option value="%s"%s>%s</option>', esc_attr( $k ), selected( $val, $k, false ), esc_html( $label ) );
				}
				echo '</select>';
			} else {
				printf( '<input type="%s" class="regular-text" id="%s" name="%s" value="%s">', 'email' === $def[1] ? 'email' : ( 'url' === $def[1] ? 'url' : 'text' ), esc_attr( $id ), esc_attr( $name ), esc_attr( $val ) );
			}
			echo '</td></tr>';
		}
		echo '</tbody></table>';
	}
	submit_button();
	echo '</form></div>';
}
