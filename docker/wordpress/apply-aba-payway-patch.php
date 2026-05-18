<?php

declare(strict_types=1);

$target = getenv('HOPP_ABA_PAYWAY_TARGET');
if ($target === false || $target === '') {
    $target = '/var/www/html/wp-content/plugins/aba-payway-woocommerce-payment-gateway/PayWayApiCheckout.php';
}

if (!file_exists($target)) {
    fwrite(STDERR, "HOPP ABA patch: plugin file not present, skipping\n");
    exit(0);
}

$contents = file_get_contents($target);
if ($contents === false) {
    fwrite(STDERR, "HOPP ABA patch: unable to read plugin file\n");
    exit(1);
}

$eol = strpos($contents, "\r\n") !== false ? "\r\n" : "\n";
$normalizeEol = static function (string $text) use ($eol): string {
    return str_replace("\n", $eol, str_replace("\r\n", "\n", $text));
};

if (strpos($contents, 'normalize_payment_options_value') !== false) {
    fwrite(STDERR, "HOPP ABA patch: plugin already patched\n");
    exit(0);
}

$patchedGetIcon = <<<'PHP'
	public function get_icon() {
		$get_size = $this->get_option('image_size');
		$payment_option = self::normalize_payment_options_value( $this->get_option('payment_options') );
		$bg_color = $this->get_option('bg_color');
		$icon = "";

		if( $get_size == 'small' ){
			if( in_array( 'credit_debit', $payment_option, true ) )
			{
			$icon  .= sprintf(
				'<img src="%s" alt="Cards" class="img-1x"  style="margin-left: 2px;"/>',
				WC_HTTPS::force_https_url( plugins_url('/images/'.$bg_color.'/1x/credit_debit.png', __FILE__ ) )
			);
			}

			if( in_array( 'abapay_khqr', $payment_option, true ) )
			{
			$icon  .= sprintf(
				'<img src="%s" alt="ABA Pay" class="img-1x"/>',
				WC_HTTPS::force_https_url( plugins_url( '/images/'.$bg_color.'/1x/aba.png', __FILE__ ) )
			);
			}
		}elseif($get_size == 'medium'){
			if( in_array( 'credit_debit', $payment_option, true ) )
			{
			$icon  .= sprintf(
				'<img src="%s" alt="Cards" class="img-2x"  style="margin-left: 3px;"/>',
				WC_HTTPS::force_https_url( plugins_url('/images/'.$bg_color.'/2x/credit_debit.png', __FILE__ ) )
			);
			 }
			if( in_array( 'abapay_khqr', $payment_option, true ) )
			{
			$icon  .= sprintf(
				'<img src="%s" alt="ABA Pay" class="img-2x"/>',
				WC_HTTPS::force_https_url( plugins_url( '/images/'.$bg_color.'/2x/aba.png', __FILE__ ) )
			);
			}

		}elseif($get_size == 'large'){
			if( in_array( 'credit_debit', $payment_option, true ) )
			{
			$icon  .= sprintf(
				'<img src="%s" alt="Cards" class="img-3x" style="margin-left: 3px;"/>',
				WC_HTTPS::force_https_url( plugins_url('/images/'.$bg_color.'/3x/credit_debit.png', __FILE__ ) )
			);
			}

			if( in_array( 'abapay_khqr', $payment_option, true ) )
			{
			$icon  .= sprintf(
				'<img src="%s" alt="ABA Pay" class="img-3x"/>',
				WC_HTTPS::force_https_url( plugins_url( '/images/'.$bg_color.'/3x/aba.png', __FILE__ ) )
			);
			}

		}
		if ( $icon !== '' ) {
			$icon = '<span class="abay_payway_icon">'.$icon.'</span>';
		}
		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
    }

	private static function normalize_payment_options_value( $payment_option ) {
		if ( is_array( $payment_option ) ) {
			return array_values( array_filter( array_map( 'strval', $payment_option ), 'strlen' ) );
		}

		if ( is_string( $payment_option ) ) {
			$payment_option = trim( $payment_option );
			if ( $payment_option === '' ) {
				return array();
			}

			if ( strpos( $payment_option, ',' ) !== false ) {
				$parts = array_map( 'trim', explode( ',', $payment_option ) );
				return array_values( array_filter( $parts, 'strlen' ) );
			}

			return array( $payment_option );
		}

		return array();
	}
PHP;

$patchedGetPaymentOption = <<<'PHP'
	public static function getPaymentOption() {
		$PluginGateway = new aba_PAYWAY_AIM();
		$payment_option = self::normalize_payment_options_value( $PluginGateway->get_option('payment_options') );
		return implode(",", $payment_option);
	}
PHP;

if (strpos($contents, '$this->description = "";') === false) {
    $descriptionCount = 0;
    $contents = str_replace(
        '$this->icon = null;',
        '$this->icon = null;' . $eol . '			$this->description = "";',
        $contents,
        $descriptionCount
    );
    if ($descriptionCount !== 1) {
        fwrite(STDERR, "HOPP ABA patch: could not apply description hotfix; plugin version drifted\n");
        exit(1);
    }
}

$replacements = array(
    'get_icon() and normalize helper' => array(
        '~\R\tpublic function get_icon\(\) \{.*?\R\t// administration fields for specific Gateway~s',
        $eol . $normalizeEol($patchedGetIcon) . $eol . $eol . "\t// administration fields for specific Gateway",
    ),
    'getPaymentOption()' => array(
        '~\R\s+public static function getPaymentOption\(\) \{.*?\R\s+\}~s',
        $eol . $normalizeEol($patchedGetPaymentOption),
    ),
);

foreach ($replacements as $label => $pair) {
    [$pattern, $replace] = $pair;

    if (strpos($contents, $replace) !== false) {
        continue;
    }

    $count = 0;
    $contents = preg_replace($pattern, $replace, $contents, 1, $count);
    if ($count !== 1) {
        fwrite(STDERR, "HOPP ABA patch: could not apply {$label}; plugin version drifted\n");
        exit(1);
    }
}

$backupPath = $target . '.bak.hopp-runtime';
if (!file_exists($backupPath) && !copy($target, $backupPath)) {
    fwrite(STDERR, "HOPP ABA patch: unable to create runtime backup\n");
    exit(1);
}

if (file_put_contents($target, $contents) === false) {
    fwrite(STDERR, "HOPP ABA patch: unable to write patched plugin file\n");
    exit(1);
}

fwrite(STDERR, "HOPP ABA patch: plugin patched successfully\n");
