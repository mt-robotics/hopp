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
