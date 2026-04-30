<?php
/**
 * Theme setup and asset loading for HOPP.
 */

function hopp_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );

	register_nav_menus(
		array(
			'primary' => __( 'Primary Menu', 'hopp' ),
		)
	);
}
add_action( 'after_setup_theme', 'hopp_setup' );

function hopp_enqueue_assets(): void {
	wp_enqueue_style(
		'hopp-fonts',
		'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap',
		array(),
		null
	);

	wp_enqueue_style(
		'hopp-style',
		get_stylesheet_uri(),
		array( 'hopp-fonts' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'hopp_enqueue_assets' );
