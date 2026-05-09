<?php
/**
 * Theme setup, demo data, and asset loading for HOPP.
 */

define( 'HOPP_DEMO_SEED_VERSION', '20260430.3' );
define( 'HOPP_LOCAL_PERMALINK_VERSION', '20260508.1' );
define( 'HOPP_LOCAL_WOOCOMMERCE_VISIBILITY_VERSION', '20260508.1' );

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

function hopp_enqueue_cart_assets(): void {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	wp_enqueue_script(
		'hopp-cart',
		get_template_directory_uri() . '/assets/js/hopp-cart.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'hopp_enqueue_cart_assets' );

function hopp_filter_nav_menu_item_titles( array $items ): array {
	foreach ( $items as $item ) {
		if ( 'post_type' === $item->type ) {
			$linked = get_post( $item->object_id );
			if ( $linked && 'pitch-your-pal-phnom-penh' === $linked->post_name ) {
				$item->title = __( 'Pitch Your Pal', 'hopp' );
				$item->classes[] = 'menu-item-pitch';
			}
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'hopp_filter_nav_menu_item_titles' );

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

function hopp_sync_local_permalink_structure(): void {
	if ( 'local' !== wp_get_environment_type() ) {
		return;
	}

	$target_structure = '/%postname%/';
	$current_structure = (string) get_option( 'permalink_structure' );

	if ( $current_structure !== $target_structure ) {
		update_option( 'permalink_structure', $target_structure, false );
		flush_rewrite_rules( false );
		update_option( 'hopp_permalink_structure_version', HOPP_LOCAL_PERMALINK_VERSION, false );

		return;
	}

	if ( HOPP_LOCAL_PERMALINK_VERSION !== get_option( 'hopp_permalink_structure_version' ) ) {
		flush_rewrite_rules( false );
		update_option( 'hopp_permalink_structure_version', HOPP_LOCAL_PERMALINK_VERSION, false );
	}
}
add_action( 'init', 'hopp_sync_local_permalink_structure', 1 );

function hopp_sync_local_woocommerce_visibility(): void {
	if ( 'local' !== wp_get_environment_type() ) {
		return;
	}

	if ( 'no' !== (string) get_option( 'woocommerce_coming_soon' ) ) {
		update_option( 'woocommerce_coming_soon', 'no', false );
	}

	if ( 'no' !== (string) get_option( 'woocommerce_feature_site_visibility_badge_enabled' ) ) {
		update_option( 'woocommerce_feature_site_visibility_badge_enabled', 'no', false );
	}

	if ( HOPP_LOCAL_WOOCOMMERCE_VISIBILITY_VERSION !== get_option( 'hopp_local_woocommerce_visibility_version' ) ) {
		update_option( 'hopp_local_woocommerce_visibility_version', HOPP_LOCAL_WOOCOMMERCE_VISIBILITY_VERSION, false );
	}
}
add_action( 'init', 'hopp_sync_local_woocommerce_visibility', 2 );

function hopp_default_checkout_country(): string {
	return 'KH';
}
add_filter( 'default_checkout_billing_country', 'hopp_default_checkout_country' );
add_filter( 'default_checkout_shipping_country', 'hopp_default_checkout_country' );

function hopp_default_checkout_state(): string {
	return '';
}
add_filter( 'default_checkout_billing_state', 'hopp_default_checkout_state' );
add_filter( 'default_checkout_shipping_state', 'hopp_default_checkout_state' );

function hopp_customize_checkout_fields( array $fields ): array {
	if ( isset( $fields['billing']['billing_first_name'] ) ) {
		$fields['billing']['billing_first_name']['label'] = __( 'First name', 'hopp' );
		$fields['billing']['billing_first_name']['priority'] = 10;
	}

	if ( isset( $fields['billing']['billing_last_name'] ) ) {
		$fields['billing']['billing_last_name']['label'] = __( 'Last name', 'hopp' );
		$fields['billing']['billing_last_name']['priority'] = 20;
	}

	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['label'] = __( 'Phone', 'hopp' );
		$fields['billing']['billing_phone']['required'] = true;
		$fields['billing']['billing_phone']['priority'] = 30;
	}

	$fields['billing']['billing_alt_phone'] = array(
		'type'        => 'tel',
		'label'       => __( 'Alternate Number', 'hopp' ),
		'placeholder' => __( 'Alternate Phone Number', 'hopp' ),
		'required'    => false,
		'class'       => array( 'form-row-wide' ),
		'priority'    => 40,
		'autocomplete' => 'tel',
	);

	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['label'] = __( 'Email address', 'hopp' );
		$fields['billing']['billing_email']['required'] = true;
		$fields['billing']['billing_email']['priority'] = 50;
	}

	if ( isset( $fields['billing']['billing_country'] ) ) {
		$fields['billing']['billing_country']['label'] = __( 'Country / Region', 'hopp' );
		$fields['billing']['billing_country']['default'] = 'KH';
		$fields['billing']['billing_country']['priority'] = 60;
	}

	if ( isset( $fields['billing']['billing_address_1'] ) ) {
		$fields['billing']['billing_address_1']['label'] = __( 'Address', 'hopp' );
		$fields['billing']['billing_address_1']['placeholder'] = __( 'House number, street name, apartment, suite, unit, etc.', 'hopp' );
		$fields['billing']['billing_address_1']['priority'] = 70;
	}

	$fields['billing']['billing_landmark'] = array(
		'type'        => 'text',
		'label'       => __( 'Landmark', 'hopp' ),
		'placeholder' => __( 'Landmark', 'hopp' ),
		'required'    => true,
		'class'       => array( 'form-row-wide' ),
		'priority'    => 80,
	);

	if ( isset( $fields['billing']['billing_city'] ) ) {
		$fields['billing']['billing_city']['label'] = __( 'Town / City', 'hopp' );
		$fields['billing']['billing_city']['required'] = true;
		$fields['billing']['billing_city']['priority'] = 90;
	}

	$fields['billing']['billing_state'] = array(
		'type'        => 'text',
		'label'       => __( 'State / County', 'hopp' ),
		'placeholder' => __( 'State / County', 'hopp' ),
		'required'    => true,
		'class'       => array( 'form-row-wide' ),
		'priority'    => 100,
	);

	if ( isset( $fields['billing']['billing_postcode'] ) ) {
		$fields['billing']['billing_postcode']['label'] = __( 'Postcode / ZIP', 'hopp' );
		$fields['billing']['billing_postcode']['required'] = true;
		$fields['billing']['billing_postcode']['priority'] = 110;
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'hopp_customize_checkout_fields', 20 );

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

function hopp_clean_imported_content( string $content ): string {
	$patterns = array(
		'/<!--\s*wp:divi\/placeholder\s*-->/i',
		'/<!--\s*\/wp:divi\/placeholder\s*-->/i',
		'/\[(?:\/)?et_pb_[^\]]*\]/i',
	);

	return trim( preg_replace( $patterns, '', $content ) );
}

function hopp_render_imported_content( string $content ): void {
	echo apply_filters( 'the_content', hopp_clean_imported_content( $content ) );
}

function hopp_clean_product_content_for_display( string $content ): string {
	if ( is_admin() || ! function_exists( 'get_post_type' ) || 'product' !== get_post_type() ) {
		return $content;
	}

	return hopp_clean_imported_content( $content );
}
add_filter( 'the_content', 'hopp_clean_product_content_for_display', 9 );

function hopp_clean_product_structured_data( array $markup, $product ): array {
	if ( 'local' !== wp_get_environment_type() || empty( $markup['description'] ) ) {
		return $markup;
	}

	$clean_description = trim( wp_strip_all_tags( hopp_clean_imported_content( (string) $markup['description'] ) ) );
	$markup['description'] = $clean_description;

	return $markup;
}
add_filter( 'woocommerce_structured_data_product', 'hopp_clean_product_structured_data', 50, 2 );

function hopp_get_story_cards( int $limit = 9 ): array {
	if ( ! function_exists( 'get_posts' ) ) {
		return array();
	}

	return get_posts(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'numberposts'    => $limit,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'suppress_filters' => false,
		)
	);
}

function hopp_get_product_cards( int $limit = 9 ): array {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return array();
	}

	return wc_get_products(
		array(
			'limit'   => $limit,
			'status'  => 'publish',
			'orderby' => 'date',
			'order'   => 'DESC',
			'return'  => 'objects',
		)
	);
}

function hopp_get_imported_product_profiles(): array {
	static $profiles = null;

	if ( null !== $profiles ) {
		return $profiles;
	}

	$profiles = array();
	$file     = get_stylesheet_directory() . '/data/imported-product-profiles.php';

	if ( is_readable( $file ) ) {
		$loaded = require $file;
		if ( is_array( $loaded ) ) {
			$profiles = $loaded;
		}
	}

	return $profiles;
}

function hopp_get_imported_product_profile( $product ): array {
	$product_id = 0;

	if ( is_numeric( $product ) ) {
		$product_id = (int) $product;
	} elseif ( is_object( $product ) && method_exists( $product, 'get_id' ) ) {
		$product_id = (int) $product->get_id();
	} elseif ( is_object( $product ) && isset( $product->ID ) ) {
		$product_id = (int) $product->ID;
	}

	if ( ! $product_id ) {
		return array();
	}

	$slug     = (string) get_post_field( 'post_name', $product_id );
	$profiles = hopp_get_imported_product_profiles();

	return $profiles[ $slug ] ?? array();
}

function hopp_format_imported_product_excerpt( string $excerpt ): string {
	$excerpt = trim( preg_replace( '/\s+/u', ' ', $excerpt ) );

	if ( '' === $excerpt ) {
		return '';
	}

	$matches = array();
	if ( preg_match( '/^(.*?)(?:\s+Artist:\s*(.*?)\s+Medium:\s*(.*?)\s+Dimensions:\s*(.*))$/su', $excerpt, $matches ) ) {
		$intro     = trim( $matches[1] );
		$artist    = trim( $matches[2] );
		$medium    = trim( $matches[3] );
		$dimension = trim( $matches[4] );

		$html = '';
		if ( '' !== $intro ) {
			$html .= '<p>' . esc_html( $intro ) . '</p>';
		}

		foreach (
			array(
				'Artist'     => $artist,
				'Medium'     => $medium,
				'Dimensions' => $dimension,
			) as $label => $value
		) {
			if ( '' === $value ) {
				continue;
			}

			$html .= '<p><strong>' . esc_html( $label ) . ':</strong> ' . esc_html( $value ) . '</p>';
		}

		return $html;
	}

	return '<p>' . esc_html( $excerpt ) . '</p>';
}

function hopp_get_product_detail_html( $product ): string {
	$profile = hopp_get_imported_product_profile( $product );
	$excerpt  = isset( $profile['excerpt'] ) ? trim( (string) $profile['excerpt'] ) : '';

	if ( '' !== $excerpt ) {
		return hopp_format_imported_product_excerpt( $excerpt );
	}

	if ( ! is_object( $product ) ) {
		return '';
	}

	$content = '';
	if ( method_exists( $product, 'get_short_description' ) ) {
		$content = $product->get_short_description();
	}

	if ( '' === trim( (string) $content ) && method_exists( $product, 'get_description' ) ) {
		$content = $product->get_description();
	}

	$clean = trim( wp_strip_all_tags( hopp_clean_imported_content( (string) $content ) ) );

	if ( '' === $clean ) {
		return '';
	}

	return '<p>' . esc_html( $clean ) . '</p>';
}

function hopp_get_product_category_label( int $product_id ): string {
	if ( ! function_exists( 'wc_get_product_category_list' ) ) {
		return __( 'Product', 'hopp' );
	}

	$label = trim( wp_strip_all_tags( wc_get_product_category_list( $product_id, ', ' ) ) );

	return '' !== $label ? $label : __( 'Product', 'hopp' );
}

function hopp_get_product_summary( $product, int $word_count = 22 ): string {
	if ( ! is_object( $product ) || ! method_exists( $product, 'get_short_description' ) || ! method_exists( $product, 'get_description' ) ) {
		return '';
	}

	$profile = hopp_get_imported_product_profile( $product );
	if ( ! empty( $profile['excerpt'] ) ) {
		$raw = $profile['excerpt'];
	} else {
		$raw = $product->get_short_description() ?: $product->get_description();
	}
	$clean = trim( wp_strip_all_tags( hopp_clean_imported_content( (string) $raw ) ) );

	if ( '' === $clean ) {
		return '';
	}

	return wp_trim_words( $clean, $word_count, '...' );
}

function hopp_get_related_product_objects( int $product_id, int $limit = 3 ): array {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return array();
	}

	$related_products = array();

	if ( function_exists( 'wc_get_related_products' ) ) {
		$related_ids = wc_get_related_products( $product_id, $limit, array( $product_id ) );
		foreach ( $related_ids as $related_id ) {
			$product = wc_get_product( $related_id );
			if ( $product ) {
				$related_products[] = $product;
			}
		}
	}

	if ( count( $related_products ) >= $limit || ! function_exists( 'wc_get_products' ) ) {
		return array_slice( $related_products, 0, $limit );
	}

	$latest_products = wc_get_products(
		array(
			'limit'   => $limit + 6,
			'status'  => 'publish',
			'orderby' => 'date',
			'order'   => 'DESC',
			'return'  => 'objects',
		)
	);

	foreach ( $latest_products as $candidate ) {
		if ( ! $candidate || $candidate->get_id() === $product_id ) {
			continue;
		}

		$already_added = false;
		foreach ( $related_products as $existing ) {
			if ( $existing->get_id() === $candidate->get_id() ) {
				$already_added = true;
				break;
			}
		}

		if ( $already_added ) {
			continue;
		}

		$related_products[] = $candidate;

		if ( count( $related_products ) >= $limit ) {
			break;
		}
	}

	return array_slice( $related_products, 0, $limit );
}

add_filter( 'woocommerce_currency', fn() => 'USD' );
add_filter( 'woocommerce_price_thousand_sep', fn() => ',' );
add_filter( 'woocommerce_price_decimal_sep', fn() => '.' );
