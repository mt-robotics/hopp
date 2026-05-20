<?php
/**
 * Front page template for the HOPP theme.
 */

get_header();

$front_page_id      = get_queried_object_id();
$home_hero_media    = hopp_get_page_hero_media( (int) $front_page_id, 'home' );
$home_product_image = hopp_get_latest_product_image_url();
$home_artist_image  = hopp_get_home_artist_image_url();

$hero_eyebrow         = hopp_get_homepage_setting( 'home_hero_eyebrow' );
$hero_title           = hopp_get_homepage_setting( 'home_hero_title' );
$hero_body            = hopp_get_homepage_setting( 'home_hero_body' );
$primary_cta_label    = hopp_get_homepage_setting( 'home_primary_cta_label' );
$primary_cta_url      = hopp_get_homepage_setting( 'home_primary_cta_url' );
$secondary_cta_label  = hopp_get_homepage_setting( 'home_secondary_cta_label' );
$secondary_cta_url    = hopp_get_homepage_setting( 'home_secondary_cta_url' );
$intro_band           = hopp_get_homepage_setting( 'home_intro_band' );
$stories_label        = hopp_get_homepage_setting( 'home_stories_label' );
$stories_title        = hopp_get_homepage_setting( 'home_stories_title' );
$products_label       = hopp_get_homepage_setting( 'home_products_label' );
$products_title       = hopp_get_homepage_setting( 'home_products_title' );
$products_body        = hopp_get_homepage_setting( 'home_products_body' );
$products_link_label  = hopp_get_homepage_setting( 'home_products_link_label' );
$artists_label        = hopp_get_homepage_setting( 'home_artists_label' );
$artists_title        = hopp_get_homepage_setting( 'home_artists_title' );
$artists_body         = hopp_get_homepage_setting( 'home_artists_body' );
$artists_link_label   = hopp_get_homepage_setting( 'home_artists_link_label' );
$contact_eyebrow      = hopp_get_homepage_setting( 'home_contact_eyebrow' );
$contact_title        = hopp_get_homepage_setting( 'home_contact_title' );
$contact_body         = hopp_get_homepage_setting( 'home_contact_body' );
$contact_button_label = hopp_get_homepage_setting( 'home_contact_button_label' );
$contact_button_url   = hopp_get_homepage_setting( 'home_contact_button_url' );
$hide_home_hero_copy  = 'video' === $home_hero_media['type'] && '' !== $home_hero_media['video_url'];
?>

<main>
	<section class="hero hero--home<?php echo 'video' === $home_hero_media['type'] && '' !== $home_hero_media['video_url'] ? ' hero--home-video' : ''; ?>"<?php echo 'image' === $home_hero_media['type'] && '' !== $home_hero_media['image_url'] ? ' style="--hopp-hero-image: url(' . esc_url( $home_hero_media['image_url'] ) . ');"' : ''; ?>>
		<?php hopp_render_hero_media_markup( $home_hero_media, 'hero' ); ?>
		<?php if ( ! $hide_home_hero_copy ) : ?>
			<div class="hero__content">
				<p class="hero__eyebrow"><?php echo esc_html( $hero_eyebrow ); ?></p>
				<h1><?php echo esc_html( $hero_title ); ?></h1>
				<p><?php echo esc_html( $hero_body ); ?></p>
				<div class="hero__actions">
					<a class="button-primary" href="<?php echo esc_url( $primary_cta_url ); ?>"><?php echo esc_html( $primary_cta_label ); ?></a>
					<a class="button-outline" href="<?php echo esc_url( $secondary_cta_url ); ?>"><?php echo esc_html( $secondary_cta_label ); ?></a>
				</div>
			</div>
		<?php endif; ?>
	</section>

	<section class="intro-band">
		<div class="intro-band__inner">
			<p><?php echo esc_html( $intro_band ); ?></p>
		</div>
	</section>

	<section class="section section--paper">
		<div class="section__header">
			<p class="section-label"><?php echo esc_html( $stories_label ); ?></p>
			<h2><?php echo esc_html( $stories_title ); ?></h2>
		</div>
		<?php
		$stories = new WP_Query(
			array(
				'posts_per_page' => 3,
				'post_status'    => 'publish',
			)
		);
		if ( $stories->have_posts() ) :
			?>
			<div class="card-grid">
				<?php
				while ( $stories->have_posts() ) :
					$stories->the_post();
					$story_thumb = get_the_post_thumbnail_url( get_the_ID(), 'medium_large' );
					$summary     = hopp_get_post_card_summary( get_the_ID() );
					?>
					<article class="story-card">
						<a href="<?php the_permalink(); ?>">
							<div class="story-card__media"<?php echo $story_thumb ? '' : ' style="background: ' . esc_attr( hopp_demo_asset_gradient( 'teal' ) ) . '"'; ?>>
								<?php if ( $story_thumb ) : ?>
									<img src="<?php echo esc_url( $story_thumb ); ?>" alt="<?php the_title_attribute(); ?>" loading="lazy">
								<?php endif; ?>
							</div>
							<div class="story-card__body">
								<p class="card-kicker"><?php echo esc_html( get_the_date() ); ?></p>
								<h3><?php the_title(); ?></h3>
								<?php if ( '' !== $summary ) : ?>
									<p class="card-summary"><?php echo esc_html( $summary ); ?></p>
								<?php endif; ?>
							</div>
						</a>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<div class="empty-state">
				<p><?php esc_html_e( 'No stories published yet.', 'hopp' ); ?></p>
			</div>
		<?php endif; ?>
	</section>

	<section class="section section--cream split-section">
		<div>
			<p class="section-label"><?php echo esc_html( $products_label ); ?></p>
			<h2><?php echo esc_html( $products_title ); ?></h2>
			<p><?php echo esc_html( $products_body ); ?></p>
			<a class="text-link" href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php echo esc_html( $products_link_label ); ?></a>
		</div>
		<div class="editorial-image" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'sand' ) ); ?>">
			<?php if ( $home_product_image ) : ?>
				<img src="<?php echo esc_url( $home_product_image ); ?>" alt="<?php esc_attr_e( 'Latest product from Humans of Phnom Penh', 'hopp' ); ?>" loading="lazy">
			<?php endif; ?>
		</div>
	</section>

	<section class="section section--paper split-section split-section--reverse">
		<div class="editorial-image" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'terracotta' ) ); ?>">
			<?php if ( $home_artist_image ) : ?>
				<img src="<?php echo esc_url( $home_artist_image ); ?>" alt="<?php esc_attr_e( 'Artist and contributor story from Humans of Phnom Penh', 'hopp' ); ?>" loading="lazy">
			<?php endif; ?>
		</div>
		<div>
			<p class="section-label"><?php echo esc_html( $artists_label ); ?></p>
			<h2><?php echo esc_html( $artists_title ); ?></h2>
			<p><?php echo esc_html( $artists_body ); ?></p>
			<a class="text-link" href="<?php echo esc_url( home_url( '/artist/' ) ); ?>"><?php echo esc_html( $artists_link_label ); ?></a>
		</div>
	</section>

	<?php
	hopp_render_context_cta(
		$contact_eyebrow,
		$contact_title,
		$contact_body,
		$contact_button_label,
		$contact_button_url
	);
	?>
</main>

<?php
get_footer();
