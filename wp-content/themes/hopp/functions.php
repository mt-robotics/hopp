<?php
/**
 * Theme setup, demo data, and asset loading for HOPP.
 */

define( 'HOPP_DEMO_SEED_VERSION', '20260430.3' );
define( 'HOPP_LOCAL_PERMALINK_VERSION', '20260508.1' );
define( 'HOPP_LOCAL_WOOCOMMERCE_VISIBILITY_VERSION', '20260508.1' );
define( 'HOPP_THEME_MENU_VERSION', '20260519.1' );
define( 'HOPP_HERO_FEATURED_IMAGE_SYNC_VERSION', '20260519.1' );
define( 'HOPP_LEGACY_PAGE_EDITOR_CLEANUP_VERSION', '20260520.1' );

function hopp_setup(): void {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'site-icon' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'responsive-embeds' );

	register_nav_menus(
		array(
			'primary'       => __( 'Primary Menu', 'hopp' ),
			'footer'        => __( 'Footer Menu', 'hopp' ),
			'footer_social' => __( 'Footer Social Menu', 'hopp' ),
			'footer_legal'  => __( 'Footer Legal Menu', 'hopp' ),
		)
	);
}
add_action( 'after_setup_theme', 'hopp_setup' );

function hopp_register_page_hero_media_meta(): void {
	register_post_meta(
		'page',
		'hopp_hero_media_type',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => static function ( $value ): string {
				$value = is_string( $value ) ? $value : 'image';
				return in_array( $value, array( 'image', 'video' ), true ) ? $value : 'image';
			},
			'auth_callback'     => static function (): bool {
				return current_user_can( 'edit_pages' );
			},
		)
	);

	register_post_meta(
		'page',
		'hopp_hero_video_audio_mode',
		array(
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => static function ( $value ): string {
				$value = is_string( $value ) ? $value : 'start_muted';
				return in_array( $value, array( 'start_muted', 'always_muted' ), true ) ? $value : 'start_muted';
			},
			'auth_callback'     => static function (): bool {
				return current_user_can( 'edit_pages' );
			},
		)
	);

	foreach ( array( 'hopp_hero_image_id', 'hopp_hero_video_id', 'hopp_hero_video_poster_id' ) as $meta_key ) {
		register_post_meta(
			'page',
			$meta_key,
			array(
				'type'              => 'integer',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'absint',
				'auth_callback'     => static function (): bool {
					return current_user_can( 'edit_pages' );
				},
			)
		);
	}
}
add_action( 'init', 'hopp_register_page_hero_media_meta' );

function hopp_get_menu_items_by_location( string $location ): array {
	$locations = get_nav_menu_locations();

	if ( empty( $locations[ $location ] ) ) {
		return array();
	}

	$items = wp_get_nav_menu_items( (int) $locations[ $location ] );

	return is_array( $items ) ? $items : array();
}

function hopp_get_footer_social_defaults(): array {
	return array(
		array(
			'title' => __( 'Facebook', 'hopp' ),
			'url'   => 'https://www.facebook.com/humansofphnompenh/',
		),
		array(
			'title' => __( 'Instagram', 'hopp' ),
			'url'   => 'https://www.instagram.com/humansofphnompenhofficial/',
		),
		array(
			'title' => __( 'LinkedIn', 'hopp' ),
			'url'   => 'https://www.linkedin.com/company/humansofphnompenh/',
		),
		array(
			'title' => __( 'Telegram', 'hopp' ),
			'url'   => 'https://t.me/humansofpp',
		),
		array(
			'title' => __( 'X', 'hopp' ),
			'url'   => 'https://x.com/HoPP_Kh',
		),
	);
}

function hopp_detect_social_network( string $url, string $title = '' ): string {
	$haystack = strtolower( $title . ' ' . $url );

	if ( false !== strpos( $haystack, 'facebook' ) ) {
		return 'facebook';
	}

	if ( false !== strpos( $haystack, 'instagram' ) ) {
		return 'instagram';
	}

	if ( false !== strpos( $haystack, 'linkedin' ) ) {
		return 'linkedin';
	}

	if ( false !== strpos( $haystack, 'telegram' ) || false !== strpos( $haystack, 't.me/' ) ) {
		return 'telegram';
	}

	if ( false !== strpos( $haystack, 'twitter' ) || false !== strpos( $haystack, 'x.com/' ) || 'x' === trim( strtolower( $title ) ) ) {
		return 'x';
	}

	return '';
}

function hopp_get_social_icon_svg( string $network ): string {
	$icons = array(
		'facebook'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14 8.5V6.9c0-.8.6-1.4 1.4-1.4H17V2.7c-.8-.1-1.6-.2-2.4-.2-2.5 0-4.2 1.5-4.2 4.2v1.8H7.6v3.2h2.8v8.8H14v-8.8h2.8l.4-3.2H14z"></path></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M7.8 2.5h8.4c2.9 0 5.3 2.4 5.3 5.3v8.4c0 2.9-2.4 5.3-5.3 5.3H7.8c-2.9 0-5.3-2.4-5.3-5.3V7.8c0-2.9 2.4-5.3 5.3-5.3zm0 2A3.3 3.3 0 0 0 4.5 7.8v8.4a3.3 3.3 0 0 0 3.3 3.3h8.4a3.3 3.3 0 0 0 3.3-3.3V7.8a3.3 3.3 0 0 0-3.3-3.3H7.8zm4.2 3.3a4.2 4.2 0 1 1 0 8.4 4.2 4.2 0 0 1 0-8.4zm0 2a2.2 2.2 0 1 0 0 4.4 2.2 2.2 0 0 0 0-4.4zm4.8-2.7a1.1 1.1 0 1 1 0 2.2 1.1 1.1 0 0 1 0-2.2z"></path></svg>',
		'linkedin'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M5 8.8h3.5v10.7H5V8.8zm1.8-5.3a2 2 0 1 1 0 4 2 2 0 0 1 0-4zm4.1 5.3h3.3v1.5h.1c.5-.9 1.6-1.8 3.3-1.8 3.5 0 4.1 2.3 4.1 5.2v5.8h-3.5v-5.2c0-1.3 0-2.9-1.8-2.9s-2 1.4-2 2.8v5.3h-3.5V8.8z"></path></svg>',
		'telegram'  => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M21.7 4.1 18.4 20c-.2 1-1.1 1.2-1.9.7l-5.1-3.8-2.5 2.4c-.3.3-.5.5-1 .5l.4-5.2 9.4-8.5c.4-.4-.1-.6-.6-.3L5.5 13.1.5 11.5c-1.1-.3-1.1-1.1.2-1.6L20.3 2.4c.9-.3 1.7.2 1.4 1.7z"></path></svg>',
		'x'         => '<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="M14.4 10.6 21.5 2h-1.7l-6.2 7.4L8.7 2H3l7.5 11.1L3 22h1.7l6.5-7.7 5.1 7.7H22l-7.6-11.4zm-2.3 2.7-.8-1.1-6-8.8h2.6l4.8 7 .8 1.1 6.3 9.2h-2.6l-5.1-7.4z"></path></svg>',
	);

	return $icons[ $network ] ?? '';
}

function hopp_render_footer_menu( string $location, string $fallback = '' ): void {
	$theme_location = $location;

	if ( '' !== $fallback && empty( get_nav_menu_locations()[ $location ] ) && ! empty( get_nav_menu_locations()[ $fallback ] ) ) {
		$theme_location = $fallback;
	}

	wp_nav_menu(
		array(
			'theme_location' => $theme_location,
			'container'      => false,
			'fallback_cb'    => false,
			'menu_class'     => 'site-footer__menu',
			'depth'          => 1,
		)
	);
}

function hopp_render_footer_social_menu(): void {
	$items = hopp_get_menu_items_by_location( 'footer_social' );

	if ( empty( $items ) ) {
		$items = array_map(
			static function ( array $item ): object {
				return (object) $item;
			},
			hopp_get_footer_social_defaults()
		);
	}

	foreach ( $items as $item ) {
		$title   = isset( $item->title ) ? trim( wp_strip_all_tags( (string) $item->title ) ) : '';
		$url     = isset( $item->url ) ? (string) $item->url : '';
		$network = hopp_detect_social_network( $url, $title );
		$icon    = '' !== $network ? hopp_get_social_icon_svg( $network ) : '';

		if ( '' === $url || '' === $icon ) {
			continue;
		}
		?>
		<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( '' !== $title ? $title : ucfirst( $network ) ); ?>">
			<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<?php
	}
}

function hopp_render_footer_legal_menu(): void {
	$items = hopp_get_menu_items_by_location( 'footer_legal' );

	if ( empty( $items ) ) {
		echo '<p class="site-footer__legal">' . esc_html__( 'Privacy Policy / Terms and Conditions', 'hopp' ) . '</p>';
		return;
	}

	echo '<nav class="site-footer__legal" aria-label="' . esc_attr__( 'Footer legal menu', 'hopp' ) . '">';

	$total = count( $items );
	foreach ( $items as $index => $item ) {
		$title = trim( wp_strip_all_tags( (string) $item->title ) );
		$url   = (string) $item->url;

		if ( '' !== $url ) {
			echo '<a href="' . esc_url( $url ) . '">' . esc_html( $title ) . '</a>';
		} else {
			echo '<span>' . esc_html( $title ) . '</span>';
		}

		if ( $index < ( $total - 1 ) ) {
			echo '<span aria-hidden="true"> / </span>';
		}
	}

	echo '</nav>';
}

function hopp_upsert_menu_items( int $menu_id, array $items ): void {
	$existing_items = wp_get_nav_menu_items( $menu_id );
	if ( ! empty( $existing_items ) ) {
		return;
	}

	foreach ( $items as $item ) {
		wp_update_nav_menu_item(
			$menu_id,
			0,
			array(
				'menu-item-title'  => $item['title'],
				'menu-item-url'    => $item['url'],
				'menu-item-status' => 'publish',
				'menu-item-type'   => 'custom',
			)
		);
	}
}

function hopp_ensure_theme_menus(): void {
	if ( ! current_theme_supports( 'menus' ) ) {
		return;
	}

	if ( HOPP_THEME_MENU_VERSION === get_option( 'hopp_theme_menu_version' ) ) {
		return;
	}

	$locations = get_nav_menu_locations();

	if ( empty( $locations['footer'] ) && ! empty( $locations['primary'] ) ) {
		$locations['footer'] = (int) $locations['primary'];
	}

	if ( empty( $locations['footer_social'] ) ) {
		$menu_name = 'HOPP Footer Social';
		$menu      = wp_get_nav_menu_object( $menu_name );
		$menu_id   = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu( $menu_name );
		hopp_upsert_menu_items( $menu_id, hopp_get_footer_social_defaults() );
		$locations['footer_social'] = $menu_id;
	}

	if ( ! empty( $locations ) ) {
		set_theme_mod( 'nav_menu_locations', $locations );
	}

	update_option( 'hopp_theme_menu_version', HOPP_THEME_MENU_VERSION, false );
}
add_action( 'after_switch_theme', 'hopp_ensure_theme_menus' );
add_action( 'admin_init', 'hopp_ensure_theme_menus' );

function hopp_output_theme_site_icons(): void {
	if ( function_exists( 'has_site_icon' ) && has_site_icon() ) {
		return;
	}

	$icon_base = get_template_directory_uri() . '/assets/images';
	?>
	<link rel="icon" href="<?php echo esc_url( $icon_base . '/favicon_hopp-32x32.png' ); ?>" sizes="32x32">
	<link rel="icon" href="<?php echo esc_url( $icon_base . '/favicon_hopp-192x192.png' ); ?>" sizes="192x192">
	<link rel="apple-touch-icon" href="<?php echo esc_url( $icon_base . '/favicon_hopp-180x180.png' ); ?>">
	<?php
}
add_action( 'wp_head', 'hopp_output_theme_site_icons' );

function hopp_get_homepage_setting_definitions(): array {
	return array(
		'home_hero_eyebrow'           => array(
			'label' => __( 'Hero eyebrow', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Hero', 'hopp' ),
			'default' => __( 'Humans of Phnom Penh', 'hopp' ),
		),
		'home_hero_title'             => array(
			'label' => __( 'Hero title', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Hero', 'hopp' ),
			'default' => __( 'Every person has a story worth sharing.', 'hopp' ),
		),
		'home_hero_body'              => array(
			'label' => __( 'Hero body', 'hopp' ),
			'type'  => 'textarea',
			'group' => __( 'Hero', 'hopp' ),
			'default' => __( 'A cultural storytelling platform capturing the people, places, and creative pulse of Phnom Penh.', 'hopp' ),
		),
		'home_primary_cta_label'      => array(
			'label' => __( 'Primary CTA label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Hero', 'hopp' ),
			'default' => __( 'Read Stories', 'hopp' ),
		),
		'home_primary_cta_url'        => array(
			'label' => __( 'Primary CTA URL', 'hopp' ),
			'type'  => 'url',
			'group' => __( 'Hero', 'hopp' ),
			'default' => home_url( '/stories/' ),
		),
		'home_secondary_cta_label'    => array(
			'label' => __( 'Secondary CTA label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Hero', 'hopp' ),
			'default' => __( 'About HOPP', 'hopp' ),
		),
		'home_secondary_cta_url'      => array(
			'label' => __( 'Secondary CTA URL', 'hopp' ),
			'type'  => 'url',
			'group' => __( 'Hero', 'hopp' ),
			'default' => home_url( '/about-us/' ),
		),
		'home_intro_band'             => array(
			'label' => __( 'Intro band text', 'hopp' ),
			'type'  => 'textarea',
			'group' => __( 'Intro Band', 'hopp' ),
			'default' => __( 'We document daily life, creative work, and community memory through warm editorial storytelling.', 'hopp' ),
		),
		'home_stories_label'          => array(
			'label' => __( 'Latest Stories label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Stories Section', 'hopp' ),
			'default' => __( 'Latest Stories', 'hopp' ),
		),
		'home_stories_title'          => array(
			'label' => __( 'Latest Stories title', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Stories Section', 'hopp' ),
			'default' => __( 'People, craft, neighborhoods, and quiet city details.', 'hopp' ),
		),
		'home_products_label'         => array(
			'label' => __( 'Products label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Products Section', 'hopp' ),
			'default' => __( 'Products', 'hopp' ),
		),
		'home_products_title'         => array(
			'label' => __( 'Products title', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Products Section', 'hopp' ),
			'default' => __( 'Cultural objects shaped by stories.', 'hopp' ),
		),
		'home_products_body'          => array(
			'label' => __( 'Products body', 'hopp' ),
			'type'  => 'textarea',
			'group' => __( 'Products Section', 'hopp' ),
			'default' => __( 'The V1 demo frames products as editorial artifacts: books, prints, and artist-made objects connected to local narratives.', 'hopp' ),
		),
		'home_products_link_label'    => array(
			'label' => __( 'Products link label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Products Section', 'hopp' ),
			'default' => __( 'Explore products', 'hopp' ),
		),
		'home_artists_label'          => array(
			'label' => __( 'Artists label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Artists Section', 'hopp' ),
			'default' => __( 'Artists', 'hopp' ),
		),
		'home_artists_title'          => array(
			'label' => __( 'Artists title', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Artists Section', 'hopp' ),
			'default' => __( 'A place for contributors, portraits, and creative work.', 'hopp' ),
		),
		'home_artists_body'           => array(
			'label' => __( 'Artists body', 'hopp' ),
			'type'  => 'textarea',
			'group' => __( 'Artists Section', 'hopp' ),
			'default' => __( 'Artists can review the contribution direction, form layout, and exhibition-style presentation before the final submission workflow is wired.', 'hopp' ),
		),
		'home_artists_link_label'     => array(
			'label' => __( 'Artists link label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Artists Section', 'hopp' ),
			'default' => __( 'Contribute work', 'hopp' ),
		),
		'home_contact_eyebrow'        => array(
			'label' => __( 'Contact CTA eyebrow', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Contact CTA', 'hopp' ),
			'default' => __( 'Contact', 'hopp' ),
		),
		'home_contact_title'          => array(
			'label' => __( 'Contact CTA title', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Contact CTA', 'hopp' ),
			'default' => __( 'Pitch a story, collaborate, or ask about the project.', 'hopp' ),
		),
		'home_contact_body'           => array(
			'label' => __( 'Contact CTA body', 'hopp' ),
			'type'  => 'textarea',
			'group' => __( 'Contact CTA', 'hopp' ),
			'default' => __( 'Reach the team for story ideas, collaborations, products, and community partnerships.', 'hopp' ),
		),
		'home_contact_button_label'   => array(
			'label' => __( 'Contact CTA button label', 'hopp' ),
			'type'  => 'text',
			'group' => __( 'Contact CTA', 'hopp' ),
			'default' => __( 'Get in Touch', 'hopp' ),
		),
		'home_contact_button_url'     => array(
			'label' => __( 'Contact CTA button URL', 'hopp' ),
			'type'  => 'url',
			'group' => __( 'Contact CTA', 'hopp' ),
			'default' => home_url( '/contact-us/' ),
		),
	);
}

function hopp_get_homepage_setting( string $key ): string {
	$definitions = hopp_get_homepage_setting_definitions();

	if ( ! isset( $definitions[ $key ] ) ) {
		return '';
	}

	$default = isset( $definitions[ $key ]['default'] ) ? (string) $definitions[ $key ]['default'] : '';

	$front_page_id = (int) get_option( 'page_on_front' );
	if ( $front_page_id > 0 ) {
		$legacy_value = get_post_meta( $front_page_id, 'hopp_' . $key, true );
		if ( is_string( $legacy_value ) ) {
			$legacy_value = trim( $legacy_value );
			if ( '' !== $legacy_value ) {
				return $legacy_value;
			}
		}
	}

	return $default;
}

function hopp_get_front_page_id(): int {
	return (int) get_option( 'page_on_front' );
}

function hopp_is_front_page_post( int $post_id ): bool {
	return $post_id > 0 && $post_id === hopp_get_front_page_id();
}

function hopp_get_structured_hero_page_configs(): array {
	return array(
		'about-us' => array(
			'body_box'   => true,
			'body_label' => __( 'Page Content', 'hopp' ),
			'body_help'  => __( 'Update the main About Us body content here.', 'hopp' ),
		),
		'products' => array(
			'body_box'    => false,
			'note_title'  => __( 'Template-managed page', 'hopp' ),
			'note_body'   => __( 'This page uses a curated product grid and call-to-action layout from the theme. Edit the title, hero intro, and Hero Media here. Product cards are managed in WooCommerce.', 'hopp' ),
		),
		'stories' => array(
			'body_box'    => false,
			'note_title'  => __( 'Template-managed page', 'hopp' ),
			'note_body'   => __( 'This page uses the published story cards and series teaser from the theme. Edit the title, hero intro, and Hero Media here.', 'hopp' ),
		),
		'series' => array(
			'body_box'    => false,
			'note_title'  => __( 'Template-managed page', 'hopp' ),
			'note_body'   => __( 'This page uses the curated YouTube playlist layout from the theme. Edit the title, hero intro, and Hero Media here.', 'hopp' ),
		),
		'artist' => array(
			'body_box'   => true,
			'body_label' => __( 'Page Content', 'hopp' ),
			'body_help'  => __( 'Update the editorial copy shown above the artist submission form here.', 'hopp' ),
		),
		'career' => array(
			'body_box'   => true,
			'body_label' => __( 'Page Content', 'hopp' ),
			'body_help'  => __( 'Update the editorial copy shown above the application form here.', 'hopp' ),
		),
		'contact-us' => array(
			'body_box'   => true,
			'body_label' => __( 'Page Content', 'hopp' ),
			'body_help'  => __( 'Update the main contact page copy shown above the contact form here.', 'hopp' ),
		),
		'pitch-your-pal-phnom-penh' => array(
			'body_box'   => true,
			'body_label' => __( 'Page Content', 'hopp' ),
			'body_help'  => __( 'Update the nomination guidance shown above the submission form here.', 'hopp' ),
		),
	);
}

function hopp_get_system_page_configs(): array {
	return array(
		'cart' => array(
			'title' => __( 'WooCommerce system page', 'hopp' ),
			'body'  => __( 'This page powers the storefront cart at /cart/. The public content is rendered by WooCommerce and the theme, not by the page body editor.', 'hopp' ),
		),
		'checkout' => array(
			'title' => __( 'WooCommerce system page', 'hopp' ),
			'body'  => __( 'This page powers the storefront checkout at /checkout/. The public content is rendered by WooCommerce and the theme, not by the page body editor.', 'hopp' ),
		),
		'my-account' => array(
			'title' => __( 'WooCommerce account page', 'hopp' ),
			'body'  => __( 'This page powers the customer account area at /my-account/. The public content is rendered by WooCommerce.', 'hopp' ),
		),
	);
}

function hopp_get_document_page_configs(): array {
	return array(
		'contest-guidelines' => array(
			'label' => __( 'Document Content', 'hopp' ),
			'help'  => __( 'Update the contest guidelines content here. This page uses the document-style layout on the site.', 'hopp' ),
		),
		'termsandconditions-artist' => array(
			'label' => __( 'Document Content', 'hopp' ),
			'help'  => __( 'Update the artist terms and conditions content here. This page uses the document-style layout on the site.', 'hopp' ),
		),
		'refund_returns' => array(
			'label' => __( 'Document Content', 'hopp' ),
			'help'  => __( 'Update the refund and returns policy content here. This page uses the document-style layout on the site.', 'hopp' ),
		),
	);
}

function hopp_get_structured_hero_page_config( int $post_id ): ?array {
	if ( $post_id <= 0 ) {
		return null;
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return null;
	}

	$slug = (string) $post->post_name;

	if ( '' === $slug ) {
		return null;
	}

	$configs = hopp_get_structured_hero_page_configs();

	return $configs[ $slug ] ?? null;
}

function hopp_get_system_page_config( int $post_id ): ?array {
	if ( $post_id <= 0 ) {
		return null;
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return null;
	}

	$slug = (string) $post->post_name;

	if ( '' === $slug ) {
		return null;
	}

	$configs = hopp_get_system_page_configs();

	return $configs[ $slug ] ?? null;
}

function hopp_get_document_page_config( int $post_id ): ?array {
	if ( $post_id <= 0 ) {
		return null;
	}

	$post = get_post( $post_id );
	if ( ! $post instanceof WP_Post || 'page' !== $post->post_type ) {
		return null;
	}

	$slug = (string) $post->post_name;

	if ( '' === $slug ) {
		return null;
	}

	$configs = hopp_get_document_page_configs();

	return $configs[ $slug ] ?? null;
}

function hopp_is_structured_hero_page_post( int $post_id ): bool {
	return null !== hopp_get_structured_hero_page_config( $post_id );
}

function hopp_is_system_page_post( int $post_id ): bool {
	return null !== hopp_get_system_page_config( $post_id );
}

function hopp_is_document_page_post( int $post_id ): bool {
	return null !== hopp_get_document_page_config( $post_id );
}

function hopp_page_supports_hero_media( int $post_id ): bool {
	return hopp_is_front_page_post( $post_id ) || hopp_is_structured_hero_page_post( $post_id );
}

function hopp_uses_structured_page_editor( int $post_id ): bool {
	return hopp_page_supports_hero_media( $post_id ) || hopp_is_system_page_post( $post_id ) || hopp_is_document_page_post( $post_id );
}

function hopp_disable_block_editor_for_structured_pages( bool $use_block_editor, WP_Post $post ): bool {
	if ( 'page' !== $post->post_type ) {
		return $use_block_editor;
	}

	return hopp_uses_structured_page_editor( (int) $post->ID ) ? false : $use_block_editor;
}
add_filter( 'use_block_editor_for_post', 'hopp_disable_block_editor_for_structured_pages', 10, 2 );

function hopp_add_front_page_settings_meta_box( WP_Post $post ): void {
	if ( ! hopp_is_front_page_post( (int) $post->ID ) ) {
		return;
	}

	add_meta_box(
		'hopp-front-page-settings',
		__( 'Homepage Content', 'hopp' ),
		'hopp_render_front_page_settings_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_page', 'hopp_add_front_page_settings_meta_box' );

function hopp_render_front_page_settings_meta_box( WP_Post $post ): void {
	if ( ! hopp_is_front_page_post( (int) $post->ID ) ) {
		echo '<p>' . esc_html__( 'These fields only apply to the page currently assigned as the static homepage.', 'hopp' ) . '</p>';
		return;
	}

	wp_nonce_field( 'hopp_front_page_settings', 'hopp_front_page_settings_nonce' );

	$grouped_fields = array();
	foreach ( hopp_get_homepage_setting_definitions() as $key => $field ) {
		$group = (string) $field['group'];
		if ( ! isset( $grouped_fields[ $group ] ) ) {
			$grouped_fields[ $group ] = array();
		}
		$grouped_fields[ $group ][ $key ] = $field;
	}
	?>
	<p><?php esc_html_e( 'Update the homepage hero, buttons, and section text here. Use the Hero Media panel for the hero image or video.', 'hopp' ); ?></p>
	<div class="hopp-home-hero-video-note" style="display: none; margin: 0 0 16px; padding: 12px 14px; border-left: 4px solid #996633; background: #fff8e5;">
		<p style="margin: 0; font-weight: 600;"><?php esc_html_e( 'Hero video active', 'hopp' ); ?></p>
		<p style="margin: 6px 0 0;"><?php esc_html_e( 'Homepage hero text and buttons are hidden while Hero media type is set to Video. These fields will be used again if you switch back to Image.', 'hopp' ); ?></p>
	</div>
	<?php foreach ( $grouped_fields as $group => $fields ) : ?>
		<h2><?php echo esc_html( $group ); ?></h2>
		<table class="form-table" role="presentation">
			<tbody>
				<?php foreach ( $fields as $key => $field ) : ?>
					<tr>
						<th scope="row"><label for="<?php echo esc_attr( 'hopp-' . $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label></th>
						<td>
							<?php $value = hopp_get_homepage_setting( $key ); ?>
							<?php if ( 'textarea' === $field['type'] ) : ?>
								<textarea class="large-text" rows="4" id="<?php echo esc_attr( 'hopp-' . $key ); ?>" name="<?php echo esc_attr( 'hopp_' . $key ); ?>"><?php echo esc_textarea( $value ); ?></textarea>
							<?php else : ?>
								<input class="regular-text" type="<?php echo esc_attr( 'url' === $field['type'] ? 'url' : 'text' ); ?>" id="<?php echo esc_attr( 'hopp-' . $key ); ?>" name="<?php echo esc_attr( 'hopp_' . $key ); ?>" value="<?php echo esc_attr( $value ); ?>">
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endforeach; ?>
	<?php
}

function hopp_save_front_page_settings( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['hopp_front_page_settings_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hopp_front_page_settings_nonce'] ) ), 'hopp_front_page_settings' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( ! hopp_is_front_page_post( $post_id ) ) {
		return;
	}

	foreach ( hopp_get_homepage_setting_definitions() as $key => $field ) {
		$input_name = 'hopp_' . $key;
		$value      = isset( $_POST[ $input_name ] ) ? wp_unslash( $_POST[ $input_name ] ) : '';
		$value      = is_string( $value ) ? trim( $value ) : '';

		if ( 'url' === $field['type'] ) {
			$value = '' !== $value ? esc_url_raw( $value ) : '';
		} else {
			$value = sanitize_textarea_field( $value );
		}

		if ( '' === $value ) {
			delete_post_meta( $post_id, 'hopp_' . $key );
			continue;
		}

		update_post_meta( $post_id, 'hopp_' . $key, $value );
	}
}
add_action( 'save_post_page', 'hopp_save_front_page_settings' );

function hopp_get_homepage_featured_image_edit_url(): string {
	$front_page_id = (int) get_option( 'page_on_front' );

	if ( $front_page_id <= 0 ) {
		return admin_url( 'options-reading.php' );
	}

	return admin_url( 'post.php?post=' . $front_page_id . '&action=edit' );
}

function hopp_disable_front_page_content_editor(): void {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'page' !== $screen->post_type || 'post' !== $screen->base ) {
		return;
	}

	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
	if ( ! hopp_uses_structured_page_editor( $post_id ) ) {
		return;
	}

	remove_post_type_support( 'page', 'editor' );
}
add_action( 'current_screen', 'hopp_disable_front_page_content_editor' );

function hopp_add_structured_page_content_meta_boxes( WP_Post $post ): void {
	$config = hopp_get_structured_hero_page_config( (int) $post->ID );

	if ( null === $config ) {
		return;
	}

	add_meta_box(
		'hopp-structured-hero-content',
		__( 'Hero Content', 'hopp' ),
		'hopp_render_structured_hero_content_meta_box',
		'page',
		'normal',
		'high'
	);

	if ( ! empty( $config['body_box'] ) ) {
		add_meta_box(
			'hopp-structured-page-content',
			(string) $config['body_label'],
			'hopp_render_structured_page_content_meta_box',
			'page',
			'normal',
			'default'
		);
		return;
	}

	add_meta_box(
		'hopp-structured-page-template-note',
		__( 'Page Layout Note', 'hopp' ),
		'hopp_render_structured_page_template_note_meta_box',
		'page',
		'normal',
		'default'
	);
}
add_action( 'add_meta_boxes_page', 'hopp_add_structured_page_content_meta_boxes', 15 );

function hopp_add_system_page_meta_box( WP_Post $post ): void {
	if ( ! hopp_is_system_page_post( (int) $post->ID ) ) {
		return;
	}

	add_meta_box(
		'hopp-system-page-note',
		__( 'System Page', 'hopp' ),
		'hopp_render_system_page_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_page', 'hopp_add_system_page_meta_box', 15 );

function hopp_add_document_page_meta_box( WP_Post $post ): void {
	$config = hopp_get_document_page_config( (int) $post->ID );
	if ( null === $config ) {
		return;
	}

	add_meta_box(
		'hopp-document-page-content',
		__( 'Document Page', 'hopp' ),
		'hopp_render_document_page_meta_box',
		'page',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes_page', 'hopp_add_document_page_meta_box', 15 );

function hopp_render_structured_hero_content_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'hopp_structured_page_content', 'hopp_structured_page_content_nonce' );
	?>
	<p><?php esc_html_e( 'Update the hero intro text here. This copy appears in the page hero when Hero media type is set to Image.', 'hopp' ); ?></p>
	<div class="hopp-structured-hero-copy-note" style="display: none; margin: 0 0 16px; padding: 12px 14px; border-left: 4px solid #996633; background: #fff8e5;">
		<p style="margin: 0; font-weight: 600;"><?php esc_html_e( 'Hero video active', 'hopp' ); ?></p>
		<p style="margin: 6px 0 0;"><?php esc_html_e( 'Hero intro text is hidden while Hero media type is set to Video. This field becomes active again if you switch back to Image.', 'hopp' ); ?></p>
	</div>
	<table class="form-table" role="presentation">
		<tbody>
			<tr>
				<th scope="row"><label for="hopp-page-hero-intro"><?php esc_html_e( 'Hero intro text', 'hopp' ); ?></label></th>
				<td>
					<textarea class="large-text" rows="4" id="hopp-page-hero-intro" name="hopp_page_hero_intro"><?php echo esc_textarea( (string) $post->post_excerpt ); ?></textarea>
				</td>
			</tr>
		</tbody>
	</table>
	<?php
}

function hopp_render_structured_page_content_meta_box( WP_Post $post ): void {
	$config = hopp_get_structured_hero_page_config( (int) $post->ID );
	if ( null === $config ) {
		return;
	}
	?>
	<p><?php echo esc_html( (string) $config['body_help'] ); ?></p>
	<?php
	wp_editor(
		(string) $post->post_content,
		'hopp_page_content_editor',
		array(
			'textarea_name' => 'hopp_page_content',
			'textarea_rows' => 14,
			'media_buttons' => false,
			'teeny'         => false,
			'quicktags'     => true,
		)
	);
}

function hopp_render_structured_page_template_note_meta_box( WP_Post $post ): void {
	$config = hopp_get_structured_hero_page_config( (int) $post->ID );
	if ( null === $config ) {
		return;
	}
	?>
	<p><strong><?php echo esc_html( (string) $config['note_title'] ); ?></strong></p>
	<p><?php echo esc_html( (string) $config['note_body'] ); ?></p>
	<?php
}

function hopp_render_system_page_meta_box( WP_Post $post ): void {
	$config = hopp_get_system_page_config( (int) $post->ID );
	if ( null === $config ) {
		return;
	}
	?>
	<p><strong><?php echo esc_html( (string) $config['title'] ); ?></strong></p>
	<p><?php echo esc_html( (string) $config['body'] ); ?></p>
	<ul style="margin: 0 0 16px 18px; list-style: disc;">
		<li><?php esc_html_e( 'Do not edit page body content here.', 'hopp' ); ?></li>
		<li><?php esc_html_e( 'Keep this page published at its current public URL.', 'hopp' ); ?></li>
		<li><?php esc_html_e( 'Use WooCommerce settings, products, and checkout/cart configuration to change storefront behavior.', 'hopp' ); ?></li>
		<li><?php esc_html_e( 'Only change the title or slug if there is a deliberate technical reason and the storefront route is updated accordingly.', 'hopp' ); ?></li>
	</ul>
	<p><strong><?php esc_html_e( 'Public URL:', 'hopp' ); ?></strong> <a href="<?php echo esc_url( get_permalink( $post ) ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( (string) wp_parse_url( get_permalink( $post ), PHP_URL_PATH ) ); ?></a></p>
	<?php
}

function hopp_render_document_page_meta_box( WP_Post $post ): void {
	$config = hopp_get_document_page_config( (int) $post->ID );
	if ( null === $config ) {
		return;
	}

	wp_nonce_field( 'hopp_document_page_content', 'hopp_document_page_content_nonce' );
	?>
	<p><?php echo esc_html( (string) $config['help'] ); ?></p>
	<?php
	wp_editor(
		(string) $post->post_content,
		'hopp_document_page_content_editor',
		array(
			'textarea_name' => 'hopp_document_page_content',
			'textarea_rows' => 16,
			'media_buttons' => false,
			'teeny'         => false,
			'quicktags'     => true,
		)
	);
}

function hopp_save_structured_page_content( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['hopp_structured_page_content_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hopp_structured_page_content_nonce'] ) ), 'hopp_structured_page_content' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || ! hopp_is_structured_hero_page_post( $post_id ) ) {
		return;
	}

	$update = array(
		'ID'           => $post_id,
		'post_excerpt' => isset( $_POST['hopp_page_hero_intro'] ) ? sanitize_textarea_field( wp_unslash( $_POST['hopp_page_hero_intro'] ) ) : '',
	);

	$config = hopp_get_structured_hero_page_config( $post_id );
	if ( null !== $config && ! empty( $config['body_box'] ) ) {
		$update['post_content'] = isset( $_POST['hopp_page_content'] ) ? wp_kses_post( wp_unslash( $_POST['hopp_page_content'] ) ) : '';
	}

	remove_action( 'save_post_page', 'hopp_save_structured_page_content' );
	wp_update_post( $update );
	add_action( 'save_post_page', 'hopp_save_structured_page_content' );
}
add_action( 'save_post_page', 'hopp_save_structured_page_content' );

function hopp_save_document_page_content( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['hopp_document_page_content_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hopp_document_page_content_nonce'] ) ), 'hopp_document_page_content' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) || ! hopp_is_document_page_post( $post_id ) ) {
		return;
	}

	remove_action( 'save_post_page', 'hopp_save_document_page_content' );
	wp_update_post(
		array(
			'ID'           => $post_id,
			'post_content' => isset( $_POST['hopp_document_page_content'] ) ? wp_kses_post( wp_unslash( $_POST['hopp_document_page_content'] ) ) : '',
		)
	);
	add_action( 'save_post_page', 'hopp_save_document_page_content' );
}
add_action( 'save_post_page', 'hopp_save_document_page_content' );

function hopp_get_legacy_editor_backup_meta_key( WP_Post $post ): string {
	$slug = sanitize_key( (string) $post->post_name );

	if ( '' === $slug ) {
		$slug = 'post_' . (int) $post->ID;
	}

	return 'hopp_legacy_editor_backup_' . $slug;
}

function hopp_is_legacy_builder_content( string $content ): bool {
	return false !== strpos( $content, '[et_pb_' )
		|| false !== strpos( $content, 'react-scroll-to-bottom--css-' )
		|| false !== strpos( $content, 'data-testid="conversation-turn-' )
		|| false !== strpos( $content, '[smartslider3' )
		|| false !== strpos( $content, '[dsm_embed_google_map' );
}

function hopp_cleanup_legacy_page_editor_content(): void {
	if ( HOPP_LEGACY_PAGE_EDITOR_CLEANUP_VERSION === get_option( 'hopp_legacy_page_editor_cleanup_version' ) ) {
		return;
	}

	$pages = get_posts(
		array(
			'post_type'      => 'page',
			'post_status'    => array( 'publish', 'draft', 'private' ),
			'posts_per_page' => -1,
			'orderby'        => 'ID',
			'order'          => 'ASC',
		)
	);

	foreach ( $pages as $page ) {
		if ( ! $page instanceof WP_Post ) {
			continue;
		}

		if ( hopp_is_front_page_post( (int) $page->ID ) ) {
			continue;
		}

		$raw_content = (string) $page->post_content;
		if ( '' === trim( $raw_content ) || ! hopp_is_legacy_builder_content( $raw_content ) ) {
			continue;
		}

		$backup_meta_key = hopp_get_legacy_editor_backup_meta_key( $page );
		if ( '' === (string) get_post_meta( $page->ID, $backup_meta_key, true ) ) {
			update_post_meta( $page->ID, $backup_meta_key, $raw_content );
		}

		$clean_html = trim( hopp_prepare_page_body_html( $raw_content ) );
		if ( '' === $clean_html || $clean_html === trim( $raw_content ) ) {
			continue;
		}

		wp_update_post(
			array(
				'ID'           => (int) $page->ID,
				'post_content' => $clean_html,
			)
		);
	}

	update_option( 'hopp_legacy_page_editor_cleanup_version', HOPP_LEGACY_PAGE_EDITOR_CLEANUP_VERSION, false );
}
add_action( 'admin_init', 'hopp_cleanup_legacy_page_editor_content' );

function hopp_get_hero_fallback_image_map(): array {
	return array(
		'about-us'                  => '2023/10/phnom-penh-cover-image.jpg',
		'artist'                    => '2025/03/Untitled-2-01-1536x1536.png',
		'career'                    => '2023/10/Untitled-design-15.jpg',
		'home'                      => '2023/09/combodians.jpg',
		'pitch-your-pal-phnom-penh' => '2026/01/happy-new-year-2025-fireworks-festive-fun-joyous-midnight-countdown-new-beginnings-scaled-1.jpg',
		'products'                  => '2023/10/Artist.jpg',
		'stories'                   => '2023/09/combodians.jpg',
	);
}

function hopp_get_hero_media_type( int $post_id ): string {
	$media_type = get_post_meta( $post_id, 'hopp_hero_media_type', true );

	return is_string( $media_type ) && in_array( $media_type, array( 'image', 'video' ), true ) ? $media_type : 'image';
}

function hopp_get_hero_video_audio_mode( int $post_id ): string {
	$audio_mode = get_post_meta( $post_id, 'hopp_hero_video_audio_mode', true );

	return is_string( $audio_mode ) && in_array( $audio_mode, array( 'start_muted', 'always_muted' ), true ) ? $audio_mode : 'start_muted';
}

function hopp_get_hero_media_attachment_id( int $post_id, string $meta_key ): int {
	$attachment_id = (int) get_post_meta( $post_id, $meta_key, true );

	return $attachment_id > 0 ? $attachment_id : 0;
}

function hopp_get_hero_image_attachment_id( int $post_id ): int {
	$attachment_id = hopp_get_hero_media_attachment_id( $post_id, 'hopp_hero_image_id' );

	if ( $attachment_id > 0 ) {
		return $attachment_id;
	}

	$thumbnail_id = get_post_thumbnail_id( $post_id );

	return $thumbnail_id > 0 ? (int) $thumbnail_id : 0;
}

function hopp_has_explicit_hero_media_override( int $post_id ): bool {
	foreach ( array( 'hopp_hero_media_type', 'hopp_hero_image_id', 'hopp_hero_video_id', 'hopp_hero_video_poster_id', 'hopp_hero_video_audio_mode' ) as $meta_key ) {
		if ( metadata_exists( 'post', $post_id, $meta_key ) ) {
			return true;
		}
	}

	return false;
}

function hopp_get_media_attachment_preview_data( int $attachment_id, string $media_kind ): array {
	if ( $attachment_id <= 0 ) {
		return array(
			'id'       => 0,
			'title'    => '',
			'filename' => '',
			'url'      => '',
			'thumb'    => '',
		);
	}

	$attachment = get_post( $attachment_id );
	if ( ! $attachment instanceof WP_Post || 'attachment' !== $attachment->post_type ) {
		return array(
			'id'       => 0,
			'title'    => '',
			'filename' => '',
			'url'      => '',
			'thumb'    => '',
		);
	}

	$url      = (string) wp_get_attachment_url( $attachment_id );
	$filename = basename( get_attached_file( $attachment_id ) ?: '' );
	$title    = get_the_title( $attachment_id );
	$thumb    = '';

	if ( 'image' === $media_kind || 'poster' === $media_kind ) {
		$thumb = (string) wp_get_attachment_image_url( $attachment_id, 'medium' );
	} elseif ( 'video' === $media_kind ) {
		$thumb = (string) wp_get_attachment_image_url( $attachment_id, 'medium' );
	}

	return array(
		'id'       => $attachment_id,
		'title'    => is_string( $title ) ? $title : '',
		'filename' => $filename,
		'url'      => $url,
		'thumb'    => $thumb,
	);
}

function hopp_render_hero_media_picker( string $field_key, string $label, string $media_kind, array $preview ): void {
	$field_id     = 'hopp-' . $field_key;
	$description  = '';
	$button_label = __( 'Choose media', 'hopp' );

	if ( 'image' === $media_kind ) {
		$description  = __( 'Used when Hero media type is set to Image.', 'hopp' );
		$button_label = $preview['id'] > 0 ? __( 'Replace hero image', 'hopp' ) : __( 'Choose hero image', 'hopp' );
	} elseif ( 'video' === $media_kind ) {
		$description  = __( 'Used when Hero media type is set to Video.', 'hopp' );
		$button_label = $preview['id'] > 0 ? __( 'Replace hero video', 'hopp' ) : __( 'Choose hero video', 'hopp' );
	} elseif ( 'poster' === $media_kind ) {
		$description  = __( 'Optional fallback image shown before the video loads.', 'hopp' );
		$button_label = $preview['id'] > 0 ? __( 'Replace poster image', 'hopp' ) : __( 'Choose poster image', 'hopp' );
	}
	?>
	<div class="hopp-hero-media-field" data-media-kind="<?php echo esc_attr( $media_kind ); ?>" style="margin-top: 16px;">
		<p style="margin: 0 0 8px;"><strong><?php echo esc_html( $label ); ?></strong></p>
		<input type="hidden" id="<?php echo esc_attr( $field_id ); ?>" name="<?php echo esc_attr( 'hopp_' . $field_key ); ?>" value="<?php echo esc_attr( (string) $preview['id'] ); ?>">
		<div class="hopp-hero-media-preview" data-empty-label="<?php esc_attr_e( 'No media selected yet.', 'hopp' ); ?>" style="margin-bottom: 10px;">
			<?php if ( $preview['id'] > 0 ) : ?>
				<?php if ( 'video' === $media_kind && '' !== $preview['url'] ) : ?>
					<video controls muted playsinline preload="metadata" style="display: block; width: 100%; border-radius: 8px; margin-bottom: 8px;">
						<source src="<?php echo esc_url( $preview['url'] ); ?>" type="video/mp4">
					</video>
				<?php elseif ( '' !== $preview['thumb'] ) : ?>
					<img src="<?php echo esc_url( $preview['thumb'] ); ?>" alt="" style="display: block; width: 100%; border-radius: 8px; margin-bottom: 8px;">
				<?php endif; ?>
				<p style="margin: 0; font-weight: 600;"><?php echo esc_html( '' !== $preview['title'] ? $preview['title'] : $preview['filename'] ); ?></p>
				<?php if ( '' !== $preview['filename'] && $preview['filename'] !== $preview['title'] ) : ?>
					<p style="margin: 4px 0 0; color: #50575e;"><?php echo esc_html( $preview['filename'] ); ?></p>
				<?php endif; ?>
			<?php else : ?>
				<p style="margin: 0; color: #50575e;"><?php esc_html_e( 'No media selected yet.', 'hopp' ); ?></p>
			<?php endif; ?>
		</div>
		<p style="display: flex; gap: 8px; flex-wrap: wrap; margin: 0;">
			<button type="button" class="button button-secondary hopp-hero-media-select" data-target="<?php echo esc_attr( $field_id ); ?>" data-media-kind="<?php echo esc_attr( $media_kind ); ?>"><?php echo esc_html( $button_label ); ?></button>
			<button type="button" class="button-link-delete hopp-hero-media-remove<?php echo $preview['id'] > 0 ? '' : ' hidden'; ?>" data-target="<?php echo esc_attr( $field_id ); ?>" data-media-kind="<?php echo esc_attr( $media_kind ); ?>"><?php esc_html_e( 'Remove', 'hopp' ); ?></button>
		</p>
		<p class="description" style="margin-top: 8px;"><?php echo esc_html( $description ); ?></p>
	</div>
	<?php
}

function hopp_render_hero_media_settings_meta_box( WP_Post $post ): void {
	wp_nonce_field( 'hopp_hero_media_settings', 'hopp_hero_media_settings_nonce' );

	$media_type = hopp_get_hero_media_type( (int) $post->ID );
	$audio_mode = hopp_get_hero_video_audio_mode( (int) $post->ID );
	$image_data = hopp_get_media_attachment_preview_data( hopp_get_hero_image_attachment_id( (int) $post->ID ), 'image' );
	$video_data = hopp_get_media_attachment_preview_data( hopp_get_hero_media_attachment_id( (int) $post->ID, 'hopp_hero_video_id' ), 'video' );
	$poster_data = hopp_get_media_attachment_preview_data( hopp_get_hero_media_attachment_id( (int) $post->ID, 'hopp_hero_video_poster_id' ), 'poster' );
	?>
	<div class="hopp-hero-media-panel">
		<p><?php esc_html_e( 'Choose the hero media for this page here. Use one image or one video, with an optional poster image for video mode.', 'hopp' ); ?></p>
		<div class="hopp-hero-video-copy-note" style="display: none; margin: 0 0 16px; padding: 12px 14px; border-left: 4px solid #996633; background: #fff8e5;">
			<p style="margin: 0; font-weight: 600;"><?php esc_html_e( 'Hero video active', 'hopp' ); ?></p>
			<p style="margin: 6px 0 0;"><?php esc_html_e( 'Page hero text overlays are hidden while Hero media type is set to Video. The existing hero text becomes active again if you switch back to Image.', 'hopp' ); ?></p>
		</div>
		<p style="margin-bottom: 8px;"><strong><?php esc_html_e( 'Hero media type', 'hopp' ); ?></strong></p>
		<select id="hopp-hero-media-type" name="hopp_hero_media_type" class="widefat">
			<option value="image"<?php selected( $media_type, 'image' ); ?>><?php esc_html_e( 'Image', 'hopp' ); ?></option>
			<option value="video"<?php selected( $media_type, 'video' ); ?>><?php esc_html_e( 'Video', 'hopp' ); ?></option>
		</select>

		<div class="hopp-hero-media-section" data-mode="image">
			<?php hopp_render_hero_media_picker( 'hero_image_id', __( 'Hero image', 'hopp' ), 'image', $image_data ); ?>
		</div>

		<div class="hopp-hero-media-section" data-mode="video">
			<?php hopp_render_hero_media_picker( 'hero_video_id', __( 'Hero video', 'hopp' ), 'video', $video_data ); ?>
			<?php hopp_render_hero_media_picker( 'hero_video_poster_id', __( 'Poster image', 'hopp' ), 'poster', $poster_data ); ?>
			<div class="hopp-hero-media-field" style="margin-top: 16px;">
				<p style="margin: 0 0 8px;"><strong><?php esc_html_e( 'Video audio mode', 'hopp' ); ?></strong></p>
				<select id="hopp-hero-video-audio-mode" name="hopp_hero_video_audio_mode" class="widefat">
					<option value="start_muted"<?php selected( $audio_mode, 'start_muted' ); ?>><?php esc_html_e( 'Start muted', 'hopp' ); ?></option>
					<option value="always_muted"<?php selected( $audio_mode, 'always_muted' ); ?>><?php esc_html_e( 'No sound (always muted)', 'hopp' ); ?></option>
				</select>
				<p class="description" style="margin-top: 8px;"><?php esc_html_e( 'Start muted keeps a visible sound toggle on the hero. No sound keeps the hero permanently silent.', 'hopp' ); ?></p>
			</div>
		</div>
	</div>
	<?php
}

function hopp_add_hero_media_meta_box(): void {
	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
	if ( $post_id > 0 && ! hopp_page_supports_hero_media( $post_id ) ) {
		return;
	}

	add_meta_box(
		'hopp-hero-media-settings',
		__( 'Hero Media', 'hopp' ),
		'hopp_render_hero_media_settings_meta_box',
		'page',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes_page', 'hopp_add_hero_media_meta_box' );

function hopp_remove_page_featured_image_meta_box(): void {
	$post_id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0;
	if ( $post_id > 0 && ! hopp_page_supports_hero_media( $post_id ) ) {
		remove_meta_box( 'postimagediv', 'page', 'side' );
		return;
	}

	remove_meta_box( 'postimagediv', 'page', 'side' );
}
add_action( 'add_meta_boxes_page', 'hopp_remove_page_featured_image_meta_box', 20 );

function hopp_save_hero_media_settings( int $post_id ): void {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$is_rest_save = defined( 'REST_REQUEST' ) && REST_REQUEST;
	$has_nonce    = isset( $_POST['hopp_hero_media_settings_nonce'] )
		&& wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['hopp_hero_media_settings_nonce'] ) ), 'hopp_hero_media_settings' );

	if ( ! $has_nonce && ! $is_rest_save ) {
		return;
	}

	if ( $has_nonce ) {
		$media_type = isset( $_POST['hopp_hero_media_type'] ) ? sanitize_text_field( wp_unslash( $_POST['hopp_hero_media_type'] ) ) : 'image';
		$media_type = in_array( $media_type, array( 'image', 'video' ), true ) ? $media_type : 'image';
		$audio_mode = isset( $_POST['hopp_hero_video_audio_mode'] ) ? sanitize_text_field( wp_unslash( $_POST['hopp_hero_video_audio_mode'] ) ) : 'start_muted';
		$audio_mode = in_array( $audio_mode, array( 'start_muted', 'always_muted' ), true ) ? $audio_mode : 'start_muted';

		$image_id  = isset( $_POST['hopp_hero_image_id'] ) ? absint( wp_unslash( $_POST['hopp_hero_image_id'] ) ) : 0;
		$video_id  = isset( $_POST['hopp_hero_video_id'] ) ? absint( wp_unslash( $_POST['hopp_hero_video_id'] ) ) : 0;
		$poster_id = isset( $_POST['hopp_hero_video_poster_id'] ) ? absint( wp_unslash( $_POST['hopp_hero_video_poster_id'] ) ) : 0;

		if ( 'image' === $media_type && $image_id <= 0 ) {
			delete_post_meta( $post_id, 'hopp_hero_media_type' );
		} else {
			update_post_meta( $post_id, 'hopp_hero_media_type', $media_type );
		}

		if ( 'video' === $media_type || 'start_muted' !== $audio_mode ) {
			update_post_meta( $post_id, 'hopp_hero_video_audio_mode', $audio_mode );
		} else {
			delete_post_meta( $post_id, 'hopp_hero_video_audio_mode' );
		}

		if ( $image_id > 0 ) {
			update_post_meta( $post_id, 'hopp_hero_image_id', $image_id );
		} else {
			delete_post_meta( $post_id, 'hopp_hero_image_id' );
		}

		if ( $video_id > 0 ) {
			update_post_meta( $post_id, 'hopp_hero_video_id', $video_id );
		} else {
			delete_post_meta( $post_id, 'hopp_hero_video_id' );
		}

		if ( $poster_id > 0 ) {
			update_post_meta( $post_id, 'hopp_hero_video_poster_id', $poster_id );
		} else {
			delete_post_meta( $post_id, 'hopp_hero_video_poster_id' );
		}

		delete_post_meta( $post_id, 'hopp_hero_video_url' );
		delete_post_meta( $post_id, 'hopp_hero_video_poster_url' );
	}

	$image_id = hopp_get_hero_media_attachment_id( $post_id, 'hopp_hero_image_id' );

	if ( $image_id > 0 ) {
		set_post_thumbnail( $post_id, $image_id );
	} else {
		delete_post_thumbnail( $post_id );
	}
}
add_action( 'save_post_page', 'hopp_save_hero_media_settings' );

function hopp_get_page_hero_media( int $post_id, string $slug = '' ): array {
	$media_type = hopp_get_hero_media_type( $post_id );
	$image_id   = hopp_get_hero_image_attachment_id( $post_id );
	$video_id   = hopp_get_hero_media_attachment_id( $post_id, 'hopp_hero_video_id' );
	$poster_id  = hopp_get_hero_media_attachment_id( $post_id, 'hopp_hero_video_poster_id' );
	$has_override = hopp_has_explicit_hero_media_override( $post_id );
	$audio_mode = hopp_get_hero_video_audio_mode( $post_id );
	$image_url  = $image_id > 0 ? (string) wp_get_attachment_image_url( $image_id, 'large' ) : '';
	$video_url  = $video_id > 0 ? (string) wp_get_attachment_url( $video_id ) : trim( (string) get_post_meta( $post_id, 'hopp_hero_video_url', true ) );
	$poster_url = $poster_id > 0 ? (string) wp_get_attachment_image_url( $poster_id, 'large' ) : trim( (string) get_post_meta( $post_id, 'hopp_hero_video_poster_url', true ) );

	if ( 'video' === $media_type ) {
		if ( '' !== $video_url ) {
			return array(
				'type'       => 'video',
				'image_url'  => $image_url,
				'video_url'  => $video_url,
				'poster_url' => $poster_url,
				'audio_mode' => $audio_mode,
			);
		}

		if ( '' !== $poster_url ) {
			return array(
				'type'       => 'image',
				'image_url'  => $poster_url,
				'video_url'  => '',
				'poster_url' => $poster_url,
				'audio_mode' => $audio_mode,
			);
		}

		if ( $has_override ) {
			return array(
				'type'       => 'image',
				'image_url'  => '',
				'video_url'  => '',
				'poster_url' => '',
				'audio_mode' => $audio_mode,
			);
		}
	}

	if ( '' !== $image_url ) {
		return array(
			'type'       => 'image',
			'image_url'  => $image_url,
			'video_url'  => '',
			'poster_url' => '',
			'audio_mode' => $audio_mode,
		);
	}

	if ( $has_override ) {
		return array(
			'type'       => 'image',
			'image_url'  => '',
			'video_url'  => '',
			'poster_url' => '',
			'audio_mode' => $audio_mode,
		);
	}

	if ( '' !== $slug ) {
		$fallback_map = hopp_get_hero_fallback_image_map();
		if ( ! empty( $fallback_map[ $slug ] ) ) {
			return array(
				'type'       => 'image',
				'image_url'  => hopp_get_upload_image_url( $fallback_map[ $slug ] ),
				'video_url'  => '',
				'poster_url' => '',
				'audio_mode' => $audio_mode,
			);
		}
	}

	return array(
		'type'       => 'image',
		'image_url'  => '',
		'video_url'  => '',
		'poster_url' => '',
		'audio_mode' => $audio_mode,
	);
}

function hopp_get_attachment_id_by_upload_relative_path( string $relative_path ): int {
	$relative_path = trim( $relative_path );

	if ( '' === $relative_path ) {
		return 0;
	}

	$url = hopp_get_upload_image_url( $relative_path );
	$id  = attachment_url_to_postid( $url );

	return $id > 0 ? (int) $id : 0;
}

function hopp_sync_page_featured_images_from_hero_fallbacks(): void {
	if ( HOPP_HERO_FEATURED_IMAGE_SYNC_VERSION === get_option( 'hopp_hero_featured_image_sync_version' ) ) {
		return;
	}

	foreach ( hopp_get_hero_fallback_image_map() as $slug => $relative_path ) {
		$page = get_page_by_path( $slug );

		if ( ! $page instanceof WP_Post || has_post_thumbnail( $page->ID ) ) {
			continue;
		}

		$attachment_id = hopp_get_attachment_id_by_upload_relative_path( $relative_path );
		if ( $attachment_id > 0 ) {
			set_post_thumbnail( $page->ID, $attachment_id );
		}
	}

	update_option( 'hopp_hero_featured_image_sync_version', HOPP_HERO_FEATURED_IMAGE_SYNC_VERSION, false );
}
add_action( 'admin_init', 'hopp_sync_page_featured_images_from_hero_fallbacks' );

function hopp_enqueue_page_admin_assets( string $hook_suffix ): void {
	if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'page' !== $screen->post_type ) {
		return;
	}

	wp_enqueue_media();

	wp_enqueue_script(
		'hopp-admin-hero-media',
		get_template_directory_uri() . '/assets/js/hopp-admin-hero-media.js',
		array( 'jquery', 'media-editor', 'wp-data', 'wp-edit-post', 'wp-api-fetch' ),
		wp_get_theme()->get( 'Version' ),
		true
	);

	wp_localize_script(
		'hopp-admin-hero-media',
		'hoppHeroMediaAdmin',
		array(
			'chooseImage'       => __( 'Choose hero image', 'hopp' ),
			'chooseVideo'       => __( 'Choose hero video', 'hopp' ),
			'choosePosterImage' => __( 'Choose poster image', 'hopp' ),
			'useImage'          => __( 'Use this image', 'hopp' ),
			'useVideo'          => __( 'Use this video', 'hopp' ),
			'usePosterImage'    => __( 'Use this image', 'hopp' ),
			'noMediaSelected'   => __( 'No media selected yet.', 'hopp' ),
			'replaceHeroImage'  => __( 'Replace hero image', 'hopp' ),
			'chooseHeroImage'   => __( 'Choose hero image', 'hopp' ),
			'replaceHeroVideo'  => __( 'Replace hero video', 'hopp' ),
			'chooseHeroVideo'   => __( 'Choose hero video', 'hopp' ),
			'replacePoster'     => __( 'Replace poster image', 'hopp' ),
			'choosePoster'      => __( 'Choose poster image', 'hopp' ),
			'postId'            => isset( $_GET['post'] ) ? (int) $_GET['post'] : 0,
			'restPath'          => '/wp/v2/pages/',
			'saving'            => __( 'Saving hero media…', 'hopp' ),
			'saved'             => __( 'Hero media saved.', 'hopp' ),
			'saveError'         => __( 'Hero media could not be saved. Please refresh and try again.', 'hopp' ),
			'unmuteLabel'       => __( 'Unmute', 'hopp' ),
			'muteLabel'         => __( 'Mute', 'hopp' ),
			'homePageId'        => hopp_get_front_page_id(),
		)
	);
}
add_action( 'admin_enqueue_scripts', 'hopp_enqueue_page_admin_assets' );

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
	$stories_item    = null;
	$has_series_item = false;
	$filtered_items  = array();

	foreach ( $items as $item ) {
		if ( 'post_type' === $item->type ) {
			$linked = get_post( $item->object_id );
			if ( $linked && 'pitch-your-pal-phnom-penh' === $linked->post_name ) {
				continue;
			}
			if ( $linked && 'stories' === $linked->post_name ) {
				$stories_item = $item;
				$item->classes[] = 'menu-item-has-children';
				if ( is_page( 'series' ) ) {
					$item->classes[] = 'current-menu-ancestor';
					$item->classes[] = 'current-menu-parent';
				}
			}
			if ( $linked && 'series' === $linked->post_name ) {
				$has_series_item = true;
			}
		}

		$filtered_items[] = $item;
	}

	if ( $stories_item && ! $has_series_item ) {
		$series_item = clone $stories_item;
		$series_item->ID = -9001;
		$series_item->db_id = -9001;
		$series_item->menu_item_parent = (string) $stories_item->ID;
		$series_item->object_id = 0;
		$series_item->object = 'custom';
		$series_item->type = 'custom';
		$series_item->type_label = __( 'Custom Link', 'hopp' );
		$series_item->title = __( 'Browse by Series', 'hopp' );
		$series_item->url = home_url( '/series/' );
		$series_item->classes = array( 'menu-item', 'menu-item-series-child' );
		$series_item->current = is_page( 'series' );
		$series_item->current_item_ancestor = false;
		$series_item->current_item_parent = false;
		$series_item->xfn = '';
		$series_item->target = '';
		$series_item->attr_title = '';

		$filtered_items[] = $series_item;
	}

	return $filtered_items;
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
		'series'     => array(
			'title'   => __( 'Series', 'hopp' ),
			'excerpt' => __( 'Curated YouTube story collections from Humans of Phnom Penh.', 'hopp' ),
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

function hopp_get_youtube_series_playlists(): array {
	return array(
		array(
			'label' => __( 'Series 01', 'hopp' ),
			'title' => __( 'Dakshin Restaurant Stories', 'hopp' ),
			'count' => __( '4 videos', 'hopp' ),
			'url'   => 'https://www.youtube.com/playlist?list=PLN-8MWE9jViIiIJDyfsEC5dAvjN756yUA',
			'video' => '8lMh0XuFVcU',
		),
		array(
			'label' => __( 'Series 02', 'hopp' ),
			'title' => __( 'Uy Ratha Stories', 'hopp' ),
			'count' => __( '5 videos', 'hopp' ),
			'url'   => 'https://www.youtube.com/playlist?list=PLN-8MWE9jViKLYjkmY22MiExSZSsjCNdU',
			'video' => '2l7wBAY8fLQ',
		),
		array(
			'label' => __( 'Series 03', 'hopp' ),
			'title' => __( 'Ley Oudom Stories', 'hopp' ),
			'count' => __( '5 videos', 'hopp' ),
			'url'   => 'https://www.youtube.com/playlist?list=PLN-8MWE9jViLdVqkjTOF2fPvaBJRksha0',
			'video' => '8X3iHSOQl0I',
		),
		array(
			'label' => __( 'Series 04', 'hopp' ),
			'title' => __( 'E Chen Stories', 'hopp' ),
			'count' => __( '5 videos', 'hopp' ),
			'url'   => 'https://www.youtube.com/playlist?list=PLN-8MWE9jViK2M2YhP0vCEXcSHPiB6HB3',
			'video' => '4gs8PwfVL2k',
		),
		array(
			'label' => __( 'Series 05', 'hopp' ),
			'title' => __( 'Duck Roasted House Stories', 'hopp' ),
			'count' => __( '5 videos', 'hopp' ),
			'url'   => 'https://www.youtube.com/playlist?list=PLN-8MWE9jViL4ZooFRbnVtEtO51DY9--P',
			'video' => 'OLJEvYm4qtc',
		),
	);
}

function hopp_get_youtube_thumbnail_url( string $video_id ): string {
	$video_id = preg_replace( '/[^A-Za-z0-9_-]/', '', $video_id );

	if ( '' === $video_id ) {
		return '';
	}

	return 'https://i.ytimg.com/vi/' . $video_id . '/hqdefault.jpg';
}

function hopp_get_career_role_image_url( string $role ): string {
	$images = array(
		'writers'       => '2023/10/2.jpg',
		'photographers' => '2023/10/3.jpg',
		'videographers' => '2023/10/4.jpg',
		'social'        => '2023/10/5.jpg',
	);

	if ( empty( $images[ $role ] ) ) {
		return '';
	}

	return hopp_get_upload_image_url( $images[ $role ] );
}

function hopp_render_context_cta( string $eyebrow, string $title, string $body, string $button_label, string $button_url, string $modifier = '' ): void {
	$classes = array( 'section', 'context-cta' );
	if ( '' !== $modifier ) {
		$classes[] = 'context-cta--' . sanitize_html_class( $modifier );
	}
	?>
	<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<div class="context-cta__inner">
			<div>
				<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
				<h2><?php echo esc_html( $title ); ?></h2>
				<?php if ( '' !== $body ) : ?>
					<p><?php echo esc_html( $body ); ?></p>
				<?php endif; ?>
			</div>
			<a class="button-primary" href="<?php echo esc_url( $button_url ); ?>"><?php echo esc_html( $button_label ); ?></a>
		</div>
	</section>
	<?php
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

function hopp_get_upload_image_url( string $relative_path ): string {
	return content_url( 'uploads/' . ltrim( $relative_path, '/' ) );
}

function hopp_get_page_intro_text( WP_Post $post, string $default = '' ): string {
	$excerpt = trim( (string) $post->post_excerpt );

	return '' !== $excerpt ? $excerpt : $default;
}

function hopp_prepare_page_body_html( string $content ): string {
	$clean = hopp_clean_imported_content( $content );
	$clean = trim( $clean );

	if ( '' === $clean ) {
		return '';
	}

	$is_legacy_builder_content = hopp_is_legacy_builder_content( $clean );

	if ( ! $is_legacy_builder_content ) {
		return (string) apply_filters( 'the_content', $clean );
	}

	$normalized = preg_replace( '/<script\b[^>]*>.*?<\/script>/is', '', $clean );
	$normalized = preg_replace( '/<style\b[^>]*>.*?<\/style>/is', '', (string) $normalized );
	$normalized = preg_replace( '/<br\s*\/?>/i', "\n", (string) $normalized );
	$normalized = preg_replace( '/<\/(p|div|section|article|li|h[1-6]|ul|ol)>/i', "\n\n", (string) $normalized );
	$normalized = preg_replace( '/\[(?:\/)?[A-Za-z0-9_\-]+[^\]]*\]/', ' ', (string) $normalized );
	$normalized = html_entity_decode( wp_strip_all_tags( (string) $normalized ), ENT_QUOTES | ENT_HTML5, 'UTF-8' );
	$normalized = str_replace( "\xc2\xa0", ' ', $normalized );
	$normalized = preg_replace( "/\r\n?/", "\n", $normalized );
	$normalized = preg_replace( "/[ \t]+\n/", "\n", (string) $normalized );
	$normalized = preg_replace( "/\n{3,}/", "\n\n", (string) $normalized );

	$blocks = preg_split( "/\n{2,}/", (string) $normalized );
	$blocks = is_array( $blocks ) ? $blocks : array();
	$html   = '';

	foreach ( $blocks as $block ) {
		$block = trim( preg_replace( '/\s+/u', ' ', $block ) );

		if ( '' === $block ) {
			continue;
		}

		$tokenized = strtolower( preg_replace( '/[^a-z]/i', '', $block ) );
		if ( in_array( $tokenized, array( 'facebooktwitterinstagramlinkedintelegramx', 'facebooktwitterinstagram', 'followus' ), true ) ) {
			continue;
		}

		$html .= '<p>' . esc_html( $block ) . '</p>';
	}

	return $html;
}

function hopp_render_page_body_content( string $content ): void {
	echo wp_kses_post( hopp_prepare_page_body_html( $content ) );
}

function hopp_render_hero_media_markup( array $media, string $class_prefix ): void {
	if ( 'video' !== $media['type'] || '' === $media['video_url'] ) {
		return;
	}
	$audio_mode = isset( $media['audio_mode'] ) && in_array( $media['audio_mode'], array( 'start_muted', 'always_muted' ), true ) ? $media['audio_mode'] : 'start_muted';
	?>
	<div class="<?php echo esc_attr( $class_prefix ); ?>__media-wrap">
		<video class="<?php echo esc_attr( $class_prefix ); ?>__media" autoplay muted loop playsinline preload="metadata" data-hopp-audio-mode="<?php echo esc_attr( $audio_mode ); ?>"<?php echo '' !== $media['poster_url'] ? ' poster="' . esc_url( $media['poster_url'] ) . '"' : ''; ?>>
			<source src="<?php echo esc_url( $media['video_url'] ); ?>" type="video/mp4">
		</video>
		<?php if ( 'start_muted' === $audio_mode ) : ?>
			<button type="button" class="<?php echo esc_attr( $class_prefix ); ?>__audio-toggle hopp-hero-audio-toggle" aria-pressed="false" aria-label="<?php esc_attr_e( 'Unmute hero video', 'hopp' ); ?>" data-muted-label="<?php esc_attr_e( 'Unmute', 'hopp' ); ?>" data-unmuted-label="<?php esc_attr_e( 'Mute', 'hopp' ); ?>">
				<?php esc_html_e( 'Unmute', 'hopp' ); ?>
			</button>
		<?php endif; ?>
	</div>
	<?php
}

function hopp_render_page_hero( string $eyebrow, string $title, string $intro, string $variant = 'brown' ): void {
	$post_id = get_the_ID();
	$slug    = get_post_field( 'post_name', $post_id );
	$media   = hopp_get_page_hero_media( (int) $post_id, (string) $slug );
	$classes = array( 'page-hero', 'page-hero--' . $variant );
	$style   = '';
	$hide_copy = 'video' === $media['type'] && '' !== $media['video_url'];

	if ( 'video' === $media['type'] && '' !== $media['video_url'] ) {
		$classes[] = 'page-hero--has-video';
	} elseif ( '' !== $media['image_url'] ) {
		$classes[] = 'page-hero--has-image';
		$style     = ' style="--hopp-hero-image: url(' . esc_url( $media['image_url'] ) . ');"';
	}
	?>
	<section class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $style; ?>>
		<?php hopp_render_hero_media_markup( $media, 'page-hero' ); ?>
		<?php if ( ! $hide_copy ) : ?>
			<div class="page-hero__inner">
				<p class="section-label"><?php echo esc_html( $eyebrow ); ?></p>
				<h1><?php echo esc_html( $title ); ?></h1>
				<?php if ( '' !== trim( $intro ) ) : ?>
					<p><?php echo esc_html( $intro ); ?></p>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</section>
	<?php
}

function hopp_get_contact_form_id_by_title( string $title ): int {
	if ( ! post_type_exists( 'wpcf7_contact_form' ) ) {
		return 0;
	}

	$forms = get_posts(
		array(
			'post_type'              => 'wpcf7_contact_form',
			'post_status'            => 'publish',
			'title'                  => $title,
			'posts_per_page'         => 1,
			'fields'                 => 'ids',
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		)
	);

	return empty( $forms ) ? 0 : (int) $forms[0];
}

function hopp_render_contact_form( string $title ): void {
	if ( ! shortcode_exists( 'contact-form-7' ) ) {
		echo '<p class="hopp-form-message">' . esc_html__( 'This form is temporarily unavailable.', 'hopp' ) . '</p>';
		return;
	}

	$form_id = hopp_get_contact_form_id_by_title( $title );
	if ( ! $form_id ) {
		if ( current_user_can( 'manage_options' ) ) {
			printf(
				'<p class="hopp-form-message hopp-form-message--admin">%s</p>',
				esc_html( sprintf( __( 'Missing Contact Form 7 form: %s', 'hopp' ), $title ) )
			);
			return;
		}

		echo '<p class="hopp-form-message">' . esc_html__( 'This form is temporarily unavailable.', 'hopp' ) . '</p>';
		return;
	}

	echo do_shortcode(
		sprintf(
			'[contact-form-7 id="%d" title="%s"]',
			$form_id,
			esc_attr( $title )
		)
	);
}

function hopp_clean_imported_content( string $content ): string {
	$patterns = array(
		'/<!--\s*wp:divi\/placeholder\s*-->/i',
		'/<!--\s*\/wp:divi\/placeholder\s*-->/i',
		'/\[(?:\/)?et_pb_[^\]]*\]/i',
		'/\[forminator_form[^\]]*\]/i',
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

function hopp_normalize_content_image_url( string $url ): string {
	$url = trim( html_entity_decode( $url ) );

	if ( '' === $url ) {
		return '';
	}

	$uploads_path = '/wp-content/uploads/';
	$uploads_pos  = strpos( $url, $uploads_path );

	if ( false !== $uploads_pos ) {
		return content_url( 'uploads/' . ltrim( substr( $url, $uploads_pos + strlen( $uploads_path ) ), '/' ) );
	}

	return esc_url_raw( $url );
}

function hopp_extract_first_image_url_from_content( string $content ): string {
	$matches = array();

	if ( preg_match( '/\bbackground_image="([^"]+)"/i', $content, $matches ) ) {
		return hopp_normalize_content_image_url( $matches[1] );
	}

	if ( preg_match( '/\bbackground_image_phone="([^"]+)"/i', $content, $matches ) ) {
		return hopp_normalize_content_image_url( $matches[1] );
	}

	if ( preg_match( '/<img[^>]+src=["\']([^"\']+)["\']/i', $content, $matches ) ) {
		return hopp_normalize_content_image_url( $matches[1] );
	}

	return '';
}

function hopp_get_page_banner_image_url( string $slug, string $size = 'large' ): string {
	$page = get_page_by_path( $slug );

	if ( ! $page instanceof WP_Post ) {
		return '';
	}

	$thumbnail = get_the_post_thumbnail_url( $page->ID, $size );
	if ( $thumbnail ) {
		return $thumbnail;
	}

	return hopp_extract_first_image_url_from_content( (string) $page->post_content );
}

function hopp_get_latest_product_image_url( string $size = 'large' ): string {
	if ( ! function_exists( 'wc_get_products' ) ) {
		return hopp_get_page_banner_image_url( 'products', $size );
	}

	$products = wc_get_products(
		array(
			'limit'   => 12,
			'status'  => 'publish',
			'orderby' => 'date',
			'order'   => 'DESC',
			'return'  => 'objects',
		)
	);

	foreach ( $products as $product ) {
		if ( ! $product || ! method_exists( $product, 'get_image_id' ) ) {
			continue;
		}

		$image_id = (int) $product->get_image_id();
		if ( ! $image_id && method_exists( $product, 'get_gallery_image_ids' ) ) {
			$gallery_ids = $product->get_gallery_image_ids();
			$image_id    = ! empty( $gallery_ids[0] ) ? (int) $gallery_ids[0] : 0;
		}

		if ( $image_id ) {
			$image_url = wp_get_attachment_image_url( $image_id, $size );
			if ( $image_url ) {
				return $image_url;
			}
		}
	}

	return hopp_get_page_banner_image_url( 'products', $size );
}

function hopp_get_image_url_from_post( WP_Post $post, string $size = 'large' ): string {
	$thumbnail = get_the_post_thumbnail_url( $post->ID, $size );
	if ( $thumbnail ) {
		return $thumbnail;
	}

	return hopp_extract_first_image_url_from_content( (string) $post->post_content );
}

function hopp_get_latest_post_image_url( string $size = 'large', array $term_slugs = array() ): string {
	$query_args = array(
		'post_type'        => 'post',
		'post_status'      => 'publish',
		'numberposts'      => 12,
		'orderby'          => 'date',
		'order'            => 'DESC',
		'suppress_filters' => false,
	);

	if ( ! empty( $term_slugs ) ) {
		$tax_query = array( 'relation' => 'OR' );

		foreach ( array( 'category', 'post_tag' ) as $taxonomy ) {
			$matched_terms = array();
			foreach ( $term_slugs as $slug ) {
				$term = get_term_by( 'slug', $slug, $taxonomy );
				if ( $term && ! is_wp_error( $term ) ) {
					$matched_terms[] = $slug;
				}
			}

			if ( ! empty( $matched_terms ) ) {
				$tax_query[] = array(
					'taxonomy' => $taxonomy,
					'field'    => 'slug',
					'terms'    => $matched_terms,
				);
			}
		}

		if ( count( $tax_query ) > 1 ) {
			$query_args['tax_query'] = $tax_query;
		} else {
			return '';
		}
	}

	$posts = get_posts( $query_args );

	foreach ( $posts as $post ) {
		if ( ! $post instanceof WP_Post ) {
			continue;
		}

		$image_url = hopp_get_image_url_from_post( $post, $size );
		if ( $image_url ) {
			return $image_url;
		}
	}

	return '';
}

function hopp_get_home_artist_image_url( string $size = 'large' ): string {
	$artist_image = hopp_get_latest_post_image_url( $size, array( 'artist', 'artists', 'artwork', 'contributor', 'contributors' ) );

	if ( $artist_image ) {
		return $artist_image;
	}

	return hopp_get_page_banner_image_url( 'artist', $size );
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

function hopp_trim_text_by_chars( string $text, int $max_chars = 140 ): string {
	$clean = trim( preg_replace( '/\s+/', ' ', $text ) );

	if ( '' === $clean ) {
		return '';
	}

	$length = function_exists( 'mb_strlen' ) ? mb_strlen( $clean ) : strlen( $clean );
	if ( $length <= $max_chars ) {
		return $clean;
	}

	$slice = function_exists( 'mb_substr' ) ? mb_substr( $clean, 0, $max_chars ) : substr( $clean, 0, $max_chars );
	$slice = preg_replace( '/\s+\S*$/', '', trim( $slice ) );

	return rtrim( $slice, " \t\n\r\0\x0B.,;:!?" ) . '...';
}

function hopp_get_post_card_summary( int $post_id, int $max_chars = 140 ): string {
	$excerpt = get_post_field( 'post_excerpt', $post_id );
	$raw     = '' !== trim( (string) $excerpt ) ? $excerpt : get_post_field( 'post_content', $post_id );
	$clean   = trim( wp_strip_all_tags( hopp_clean_imported_content( (string) $raw ) ) );

	return hopp_trim_text_by_chars( $clean, $max_chars );
}

function hopp_get_product_card_thumbnail_url( $product, string $size = 'medium_large' ): string {
	if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) {
		return '';
	}

	$image_url = get_the_post_thumbnail_url( $product->get_id(), $size );
	if ( $image_url ) {
		return $image_url;
	}

	if ( method_exists( $product, 'get_slug' ) && 'registration-fee' === $product->get_slug() ) {
		return get_stylesheet_directory_uri() . '/assets/images/registration-fee-thumbnail.svg';
	}

	return '';
}

function hopp_cart_item_thumbnail( string $thumbnail, array $cart_item ): string {
	if ( empty( $cart_item['data'] ) || ! is_object( $cart_item['data'] ) ) {
		return $thumbnail;
	}

	$product = $cart_item['data'];
	if ( method_exists( $product, 'get_image_id' ) && (int) $product->get_image_id() ) {
		return $thumbnail;
	}

	$image_url = hopp_get_product_card_thumbnail_url( $product, 'woocommerce_thumbnail' );
	if ( '' === $image_url ) {
		return $thumbnail;
	}

	return sprintf(
		'<img src="%1$s" alt="%2$s" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail hopp-cart-item-thumbnail">',
		esc_url( $image_url ),
		esc_attr( method_exists( $product, 'get_name' ) ? $product->get_name() : __( 'Product image', 'hopp' ) )
	);
}
add_filter( 'woocommerce_cart_item_thumbnail', 'hopp_cart_item_thumbnail', 20, 2 );

function hopp_get_product_summary( $product, int $max_chars = 140 ): string {
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

	return hopp_trim_text_by_chars( $clean, $max_chars );
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
