<?php
/**
 * Repo-owned production mail bridge for HOPP.
 *
 * Routes SMTP and operational recipients from environment variables so
 * production mail behavior does not depend on hidden WP-admin plugin state.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function hopp_mail_env( string $key ): string {
	$value = getenv( $key );
	return false === $value ? '' : trim( $value );
}

function hopp_mail_admin_recipient(): string {
	return hopp_mail_env( 'HOPP_ADMIN_NOTIFICATION_EMAIL' );
}

function hopp_mail_from_address(): string {
	return hopp_mail_env( 'HOPP_MAIL_FROM_ADDRESS' );
}

function hopp_mail_from_name(): string {
	$name = hopp_mail_env( 'HOPP_MAIL_FROM_NAME' );
	return '' !== $name ? $name : wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
}

add_filter(
	'pre_option_admin_email',
	static function ( $value ) {
		$recipient = hopp_mail_admin_recipient();
		return '' !== $recipient ? $recipient : $value;
	}
);

add_filter(
	'wp_mail_from',
	static function ( $from ) {
		$address = hopp_mail_from_address();
		return '' !== $address ? $address : $from;
	}
);

add_filter(
	'wp_mail_from_name',
	static function ( $name ) {
		$from_name = hopp_mail_from_name();
		return '' !== $from_name ? $from_name : $name;
	}
);

function hopp_filter_admin_email_recipient( $recipient ) {
	$override = hopp_mail_admin_recipient();
	return '' !== $override ? $override : $recipient;
}

add_filter( 'woocommerce_email_recipient_new_order', 'hopp_filter_admin_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_failed_order', 'hopp_filter_admin_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_cancelled_order', 'hopp_filter_admin_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_low_stock', 'hopp_filter_admin_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_no_stock', 'hopp_filter_admin_email_recipient', 10, 3 );
add_filter( 'woocommerce_email_recipient_backorder', 'hopp_filter_admin_email_recipient', 10, 3 );

add_filter(
	'wpcf7_mail_components',
	static function ( array $components ): array {
		$recipient = hopp_mail_admin_recipient();
		if ( '' !== $recipient ) {
			$components['recipient'] = $recipient;
		}

		$address = hopp_mail_from_address();
		if ( '' !== $address ) {
			$components['sender'] = sprintf(
				'%s <%s>',
				hopp_mail_from_name(),
				$address
			);
		}

		return $components;
	},
	10,
	3
);

add_action(
	'phpmailer_init',
	static function ( $phpmailer ): void {
		if ( 'smtp' !== strtolower( hopp_mail_env( 'HOPP_MAIL_TRANSPORT' ) ) ) {
			return;
		}

		$host = hopp_mail_env( 'HOPP_SMTP_HOST' );
		if ( '' === $host ) {
			return;
		}

		$phpmailer->isSMTP();
		$phpmailer->Host = $host;

		$port = (int) hopp_mail_env( 'HOPP_SMTP_PORT' );
		if ( $port > 0 ) {
			$phpmailer->Port = $port;
		}

		$secure = strtolower( hopp_mail_env( 'HOPP_SMTP_SECURE' ) );
		if ( in_array( $secure, array( 'ssl', 'tls' ), true ) ) {
			$phpmailer->SMTPSecure = $secure;
		} else {
			$phpmailer->SMTPSecure = '';
		}

		$auto_tls = strtolower( hopp_mail_env( 'HOPP_SMTP_AUTO_TLS' ) );
		if ( '' !== $auto_tls ) {
			$phpmailer->SMTPAutoTLS = in_array( $auto_tls, array( '1', 'true', 'yes', 'on' ), true );
		}

		$username = hopp_mail_env( 'HOPP_SMTP_USER' );
		$password = hopp_mail_env( 'HOPP_SMTP_PASSWORD' );

		$phpmailer->SMTPAuth = '' !== $username || '' !== $password;
		if ( $phpmailer->SMTPAuth ) {
			$phpmailer->Username = $username;
			$phpmailer->Password = $password;
		}

		$address = hopp_mail_from_address();
		if ( '' !== $address ) {
			$phpmailer->setFrom( $address, hopp_mail_from_name(), false );
		}
	},
	20
);
