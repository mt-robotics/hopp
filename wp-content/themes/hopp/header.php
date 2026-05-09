<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
	<div class="site-header__inner">
		<a class="site-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<span><?php esc_html_e( 'Humans of', 'hopp' ); ?></span>
			<span><?php esc_html_e( 'Phnom Penh', 'hopp' ); ?></span>
		</a>

		<button class="site-nav-toggle" type="button" aria-controls="site-navigation" aria-expanded="false">
			<span class="site-nav-toggle__bar"></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Open menu', 'hopp' ); ?></span>
		</button>

		<?php
		$hopp_cart_url   = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
		$hopp_cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? (int) WC()->cart->get_cart_contents_count() : 0;
		$hopp_cart_label = $hopp_cart_count > 0
			? esc_attr( sprintf( _n( 'View cart (%d item)', 'View cart (%d items)', $hopp_cart_count, 'hopp' ), $hopp_cart_count ) )
			: esc_attr__( 'View cart', 'hopp' );
		?>
		<a class="site-cart" href="<?php echo esc_url( $hopp_cart_url ); ?>" aria-label="<?php echo $hopp_cart_label; ?>">
			<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<circle cx="9" cy="21" r="1"></circle>
				<circle cx="20" cy="21" r="1"></circle>
				<path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
			</svg>
			<span class="site-cart__count<?php echo $hopp_cart_count > 0 ? ' site-cart__count--visible' : ''; ?>" aria-hidden="true"><?php echo esc_html( $hopp_cart_count ); ?></span>
		</a>

		<nav id="site-navigation" class="site-nav" aria-label="<?php esc_attr_e( 'Primary menu', 'hopp' ); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => 'hopp_fallback_menu',
					'menu_class'     => 'site-nav__list',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	</div>
</header>
