<?php
/**
 * Front page template for the HOPP theme.
 */

get_header();

$home_hero_image    = hopp_get_hero_image_url_for_slug( 'home' );
$home_product_image = hopp_get_latest_product_image_url();
$home_artist_image  = hopp_get_home_artist_image_url();
?>

<main>
	<section class="hero hero--home"<?php echo $home_hero_image ? ' style="--hopp-hero-image: url(' . esc_url( $home_hero_image ) . ');"' : ''; ?>>
		<div class="hero__content">
			<p class="hero__eyebrow"><?php esc_html_e( 'Humans of Phnom Penh', 'hopp' ); ?></p>
			<h1><?php esc_html_e( 'Every person has a story worth sharing.', 'hopp' ); ?></h1>
			<p><?php esc_html_e( 'A cultural storytelling platform capturing the people, places, and creative pulse of Phnom Penh.', 'hopp' ); ?></p>
			<div class="hero__actions">
				<a class="button-primary" href="<?php echo esc_url( home_url( '/stories/' ) ); ?>"><?php esc_html_e( 'Read Stories', 'hopp' ); ?></a>
				<a class="button-outline" href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About HOPP', 'hopp' ); ?></a>
			</div>
		</div>
	</section>

	<section class="intro-band">
		<div class="intro-band__inner">
			<p><?php esc_html_e( 'We document daily life, creative work, and community memory through warm editorial storytelling.', 'hopp' ); ?></p>
		</div>
	</section>

	<section class="section section--paper">
		<div class="section__header">
			<p class="section-label"><?php esc_html_e( 'Latest Stories', 'hopp' ); ?></p>
			<h2><?php esc_html_e( 'People, craft, neighborhoods, and quiet city details.', 'hopp' ); ?></h2>
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
			<p class="section-label"><?php esc_html_e( 'Products', 'hopp' ); ?></p>
			<h2><?php esc_html_e( 'Cultural objects shaped by stories.', 'hopp' ); ?></h2>
			<p><?php esc_html_e( 'The V1 demo frames products as editorial artifacts: books, prints, and artist-made objects connected to local narratives.', 'hopp' ); ?></p>
			<a class="text-link" href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Explore products', 'hopp' ); ?></a>
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
			<p class="section-label"><?php esc_html_e( 'Artists', 'hopp' ); ?></p>
			<h2><?php esc_html_e( 'A place for contributors, portraits, and creative work.', 'hopp' ); ?></h2>
			<p><?php esc_html_e( 'Artists can review the contribution direction, form layout, and exhibition-style presentation before the final submission workflow is wired.', 'hopp' ); ?></p>
			<a class="text-link" href="<?php echo esc_url( home_url( '/artist/' ) ); ?>"><?php esc_html_e( 'Contribute work', 'hopp' ); ?></a>
		</div>
	</section>

	<?php
	hopp_render_context_cta(
		__( 'Contact', 'hopp' ),
		__( 'Pitch a story, collaborate, or ask about the project.', 'hopp' ),
		__( 'Reach the team for story ideas, collaborations, products, and community partnerships.', 'hopp' ),
		__( 'Get in Touch', 'hopp' ),
		home_url( '/contact-us/' )
	);
	?>
</main>

<?php
get_footer();
