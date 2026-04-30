<?php
/**
 * Theme setup, demo data, and asset loading for HOPP.
 */

define( 'HOPP_DEMO_SEED_VERSION', '20260430.3' );

function hopp_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

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

	wp_enqueue_script(
		'hopp-navigation',
		get_template_directory_uri() . '/assets/js/navigation.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hopp_enqueue_assets' );

function hopp_is_local_demo_environment(): bool {
	return defined( 'HOPP_ENABLE_DEMO_SEED' )
		&& true === HOPP_ENABLE_DEMO_SEED
		&& 'local' === wp_get_environment_type();
}

function hopp_get_demo_pages(): array {
	return array(
		'about-us'   => array(
			'title'   => __( 'About Us', 'hopp' ),
			'excerpt' => __( 'A cultural storytelling platform rooted in Phnom Penh.', 'hopp' ),
		),
		'products'   => array(
			'title'   => __( 'Products', 'hopp' ),
			'excerpt' => __( 'Books, prints, and cultural objects that support local stories.', 'hopp' ),
		),
		'stories'    => array(
			'title'   => __( 'Stories', 'hopp' ),
			'excerpt' => __( 'Portraits, interviews, and neighborhood dispatches.', 'hopp' ),
		),
		'artist'     => array(
			'title'   => __( 'Artist', 'hopp' ),
			'excerpt' => __( 'Contribute art, portraits, and creative work to the platform.', 'hopp' ),
		),
		'career'     => array(
			'title'   => __( 'Career', 'hopp' ),
			'excerpt' => __( 'Volunteer and internship pathways for storytellers and makers.', 'hopp' ),
		),
		'contact-us' => array(
			'title'   => __( 'Contact Us', 'hopp' ),
			'excerpt' => __( 'Reach the Humans of Phnom Penh team.', 'hopp' ),
		),
		'cart'       => array(
			'title'   => __( 'Cart', 'hopp' ),
			'excerpt' => __( 'A visual empty cart demo for V1 review.', 'hopp' ),
		),
	);
}

function hopp_get_demo_stories(): array {
	return array(
		array(
			'title'   => __( 'The Barber Who Keeps a Street in Conversation', 'hopp' ),
			'slug'    => 'barber-keeps-a-street-in-conversation',
			'excerpt' => __( 'A small shop, a steady chair, and the daily rhythm of neighbors passing through.', 'hopp' ),
			'content' => __( 'Morning begins early on the block. The metal shutter rises, the mirror is wiped clean, and the first conversations arrive before the first haircut. This demo story shows how a long-form HOPP article can frame one person as a doorway into a wider neighborhood.', 'hopp' ),
		),
		array(
			'title'   => __( 'A Young Painter Maps Memory in Color', 'hopp' ),
			'slug'    => 'young-painter-maps-memory-in-color',
			'excerpt' => __( 'Studio notes from an artist turning family photographs into contemporary Cambodian texture.', 'hopp' ),
			'content' => __( 'In the demo version, this article gives the team a sense of how artist profiles can move between personal history, process, and visual documentation. The final production story will use real interviews and photography after content access is available.', 'hopp' ),
		),
		array(
			'title'   => __( 'The Night Market After Rain', 'hopp' ),
			'slug'    => 'night-market-after-rain',
			'excerpt' => __( 'A visual dispatch on food stalls, reflected lights, and the people who keep the city awake.', 'hopp' ),
			'content' => __( 'This placeholder demonstrates a shorter photo-led field note. It is intentionally written as demo copy so the visual direction can be reviewed without pretending that final editorial content is already imported.', 'hopp' ),
		),
	);
}

function hopp_seed_demo_page( string $slug, array $page ): int {
	$existing = get_page_by_path( $slug );

	if ( $existing instanceof WP_Post ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_name'    => $slug,
			'post_title'   => $page['title'],
			'post_excerpt' => $page['excerpt'],
			'post_content' => '<p>' . esc_html( $page['excerpt'] ) . '</p>',
		)
	);
}

function hopp_seed_demo_post( array $story ): int {
	$existing = get_page_by_path( $story['slug'], OBJECT, 'post' );

	if ( $existing instanceof WP_Post ) {
		return (int) $existing->ID;
	}

	return (int) wp_insert_post(
		array(
			'post_type'    => 'post',
			'post_status'  => 'publish',
			'post_name'    => $story['slug'],
			'post_title'   => $story['title'],
			'post_excerpt' => $story['excerpt'],
			'post_content' => '<p>' . esc_html( $story['content'] ) . '</p><p>' . esc_html__( 'Production articles will be imported after WordPress admin access is available.', 'hopp' ) . '</p>',
		)
	);
}

function hopp_seed_primary_menu( array $page_ids ): void {
	$menu_name = 'HOPP Primary Demo';
	$menu      = wp_get_nav_menu_object( $menu_name );
	$menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );

	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( ! empty( $existing_items ) ) {
		foreach ( $existing_items as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}

	wp_update_nav_menu_item(
		$menu_id,
		0,
		array(
			'menu-item-title'  => __( 'Home', 'hopp' ),
			'menu-item-url'    => home_url( '/' ),
			'menu-item-status' => 'publish',
		)
	);

	$nav_order = array( 'about-us', 'products', 'stories', 'artist', 'career', 'contact-us' );
	foreach ( $nav_order as $slug ) {
		if ( ! empty( $page_ids[ $slug ] ) ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-object-id' => $page_ids[ $slug ],
					'menu-item-object'    => 'page',
					'menu-item-type'      => 'post_type',
					'menu-item-status'    => 'publish',
				)
			);
		}
	}

	if ( ! empty( $page_ids['cart'] ) ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'     => __( 'Cart', 'hopp' ),
				'menu-item-object-id' => $page_ids['cart'],
				'menu-item-object'    => 'page',
				'menu-item-type'      => 'post_type',
				'menu-item-status'    => 'publish',
				'menu-item-classes'   => 'menu-item-cart',
			)
		);
	}

	set_theme_mod( 'nav_menu_locations', array( 'primary' => $menu_id ) );
}

function hopp_seed_local_demo_content(): void {
	if ( ! hopp_is_local_demo_environment() ) {
		return;
	}

	if ( HOPP_DEMO_SEED_VERSION === get_option( 'hopp_demo_seed_version' ) ) {
		return;
	}

	$page_ids = array();
	foreach ( hopp_get_demo_pages() as $slug => $page ) {
		$page_ids[ $slug ] = hopp_seed_demo_page( $slug, $page );
	}

	foreach ( hopp_get_demo_stories() as $story ) {
		hopp_seed_demo_post( $story );
	}

	hopp_seed_primary_menu( $page_ids );
	update_option( 'hopp_demo_seed_version', HOPP_DEMO_SEED_VERSION, false );
}
add_action( 'after_switch_theme', 'hopp_seed_local_demo_content' );
add_action( 'admin_init', 'hopp_seed_local_demo_content' );
add_action( 'init', 'hopp_seed_local_demo_content' );

function hopp_demo_asset_gradient( string $variant = 'brown' ): string {
	$gradients = array(
		'brown'      => 'linear-gradient(135deg, #33231a 0%, #7b4b34 52%, #c47254 100%)',
		'sand'       => 'linear-gradient(135deg, #a68a61 0%, #f4f1ea 100%)',
		'teal'       => 'linear-gradient(135deg, #1f607c 0%, #7ba07e 100%)',
		'terracotta' => 'linear-gradient(135deg, #8e4c32 0%, #c47254 100%)',
	);

	return $gradients[ $variant ] ?? $gradients['brown'];
}

function hopp_get_demo_products(): array {
	return array(
		array(
			'title'       => __( 'Portrait Print Series', 'hopp' ),
			'price'       => '$24',
			'category'    => __( 'Print', 'hopp' ),
			'description' => __( 'A gallery-style print direction for future HOPP photography and artist collaborations.', 'hopp' ),
			'variant'     => 'brown',
		),
		array(
			'title'       => __( 'Phnom Penh Field Notes', 'hopp' ),
			'price'       => '$18',
			'category'    => __( 'Book', 'hopp' ),
			'description' => __( 'A compact editorial object collecting short city dispatches and visual essays.', 'hopp' ),
			'variant'     => 'sand',
		),
		array(
			'title'       => __( 'Artist Postcard Set', 'hopp' ),
			'price'       => '$12',
			'category'    => __( 'Stationery', 'hopp' ),
			'description' => __( 'A refined product-card demo for small cultural goods and contributor artwork.', 'hopp' ),
			'variant'     => 'terracotta',
		),
	);
}

function hopp_fallback_menu(): void {
	$items = array(
		__( 'Home', 'hopp' )       => home_url( '/' ),
		__( 'About Us', 'hopp' )   => home_url( '/about-us/' ),
		__( 'Products', 'hopp' )   => home_url( '/products/' ),
		__( 'Stories', 'hopp' )    => home_url( '/stories/' ),
		__( 'Artist', 'hopp' )     => home_url( '/artist/' ),
		__( 'Career', 'hopp' )     => home_url( '/career/' ),
		__( 'Contact Us', 'hopp' ) => home_url( '/contact-us/' ),
		__( 'Cart', 'hopp' )       => home_url( '/cart/' ),
	);

	echo '<ul class="site-nav__list">';
	foreach ( $items as $label => $url ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

function hopp_render_page_hero( string $eyebrow, string $title, string $intro, string $variant = 'brown' ): void {
	?>
	<section class="page-hero page-hero--<?php echo esc_attr( $variant ); ?>">
		<div class="page-hero__inner">
			<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
			<h1><?php echo esc_html( $title ); ?></h1>
			<p><?php echo esc_html( $intro ); ?></p>
		</div>
	</section>
	<?php
}
