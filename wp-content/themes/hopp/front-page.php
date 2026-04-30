<?php
/**
 * Front page template for the HOPP theme.
 */

get_header();
?>

<main>
	<section class="hero hero--home">
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
		<div class="card-grid">
			<?php
			$stories = new WP_Query(
				array(
					'posts_per_page' => 3,
					'post_status'    => 'publish',
				)
			);
			if ( $stories->have_posts() ) :
				while ( $stories->have_posts() ) :
					$stories->the_post();
					?>
					<article class="story-card">
						<a href="<?php the_permalink(); ?>">
							<div class="story-card__media" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'teal' ) ); ?>"></div>
							<div class="story-card__body">
								<p class="card-kicker"><?php echo esc_html( get_the_date() ); ?></p>
								<h3><?php the_title(); ?></h3>
								<?php the_excerpt(); ?>
							</div>
						</a>
					</article>
					<?php
				endwhile;
				wp_reset_postdata();
			endif;
			?>
		</div>
	</section>

	<section class="section section--cream split-section">
		<div>
			<p class="section-label"><?php esc_html_e( 'Products', 'hopp' ); ?></p>
			<h2><?php esc_html_e( 'Cultural objects shaped by stories.', 'hopp' ); ?></h2>
			<p><?php esc_html_e( 'The V1 demo frames products as editorial artifacts: books, prints, and artist-made objects connected to local narratives.', 'hopp' ); ?></p>
			<a class="text-link" href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Explore products', 'hopp' ); ?></a>
		</div>
		<div class="editorial-image" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'sand' ) ); ?>"></div>
	</section>

	<section class="section section--paper split-section split-section--reverse">
		<div class="editorial-image" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'terracotta' ) ); ?>"></div>
		<div>
			<p class="section-label"><?php esc_html_e( 'Artists', 'hopp' ); ?></p>
			<h2><?php esc_html_e( 'A place for contributors, portraits, and creative work.', 'hopp' ); ?></h2>
			<p><?php esc_html_e( 'Artists can review the contribution direction, form layout, and exhibition-style presentation before the final submission workflow is wired.', 'hopp' ); ?></p>
			<a class="text-link" href="<?php echo esc_url( home_url( '/artist/' ) ); ?>"><?php esc_html_e( 'Contribute work', 'hopp' ); ?></a>
		</div>
	</section>

	<section class="cta-band">
		<div>
			<p class="section-label"><?php esc_html_e( 'Contact', 'hopp' ); ?></p>
			<h2><?php esc_html_e( 'Pitch a story, collaborate, or ask about the project.', 'hopp' ); ?></h2>
		</div>
		<a class="button-primary button-primary--light" href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Get in Touch', 'hopp' ); ?></a>
	</section>
</main>

<?php
get_footer();
