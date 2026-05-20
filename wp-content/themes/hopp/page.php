<?php
/**
 * Default page template for the HOPP theme.
 */

get_header();
?>

<main>
	<?php
	while ( have_posts() ) :
		the_post();
		$slug = get_post_field( 'post_name', get_the_ID() );
		$page = get_post( get_the_ID() );
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
			if ( ! $page instanceof WP_Post ) :
				continue;
			elseif ( 'about-us' === $slug ) :
				hopp_render_page_hero( __( 'About Us', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'sand' );
				?>
				<section class="section section--paper prose-section">
					<div>
						<?php hopp_render_page_body_content( get_the_content( null, false, get_the_ID() ) ); ?>
					</div>
				</section>
			<?php elseif ( 'products' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Products', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'terracotta' ); ?>
				<section class="section section--paper product-grid">
					<?php if ( function_exists( 'wc_get_products' ) ) : ?>
						<?php foreach ( hopp_get_product_cards( 12 ) as $product ) : ?>
							<?php
							$product_id = $product->get_id();
							$permalink  = $product->get_permalink();
							$thumbnail  = hopp_get_product_card_thumbnail_url( $product, 'medium_large' );
							?>
							<article class="product-card">
								<a class="product-card__link" href="<?php echo esc_url( $permalink ); ?>">
									<div class="product-card__media"<?php echo $thumbnail ? '' : ' style="background: ' . esc_attr( hopp_demo_asset_gradient( 'terracotta' ) ) . '"' ; ?>>
										<?php if ( $thumbnail ) : ?>
											<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
										<?php endif; ?>
									</div>
									<div class="product-card__body">
										<p class="card-kicker"><?php echo esc_html( hopp_get_product_category_label( $product_id ) ); ?></p>
										<h2><?php echo esc_html( $product->get_name() ); ?></h2>
										<?php if ( $product_summary = hopp_get_product_summary( $product ) ) : ?>
											<p class="product-card__summary"><?php echo esc_html( $product_summary ); ?></p>
										<?php else : ?>
											<p class="product-card__summary product-card__summary--empty" aria-hidden="true">&nbsp;</p>
										<?php endif; ?>
										<div class="product-card__footer"><strong><?php echo wp_kses_post( $product->get_price_html() ); ?></strong><span><?php esc_html_e( 'View Product', 'hopp' ); ?></span></div>
									</div>
								</a>
							</article>
						<?php endforeach; ?>
					<?php else : ?>
						<?php foreach ( hopp_get_demo_products() as $product ) : ?>
							<article class="product-card">
								<div class="product-card__media" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( $product['variant'] ) ); ?>"></div>
								<div class="product-card__body">
									<p class="card-kicker"><?php echo esc_html( $product['category'] ); ?></p>
									<h2><?php echo esc_html( $product['title'] ); ?></h2>
									<p><?php echo esc_html( $product['description'] ); ?></p>
									<div class="product-card__footer"><strong><?php echo esc_html( $product['price'] ); ?></strong><span><?php esc_html_e( 'View Product', 'hopp' ); ?></span></div>
								</div>
							</article>
						<?php endforeach; ?>
					<?php endif; ?>
				</section>
				<?php
				hopp_render_context_cta(
					__( 'Pitch Your Pal', 'hopp' ),
					__( 'Nominate someone whose story should become part of the project.', 'hopp' ),
					__( 'Use the Pitch Your Pal form to recommend a person, place, or community story for Humans of Phnom Penh to consider.', 'hopp' ),
					__( 'Open Pitch Your Pal', 'hopp' ),
					home_url( '/pitch-your-pal-phnom-penh/' ),
					'pitch'
				);
				?>
			<?php elseif ( 'stories' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Stories', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'brown' ); ?>
				<section class="section section--cream series-teaser">
					<div class="series-teaser__inner">
						<div>
							<p class="section-label"><?php esc_html_e( 'Browse by Series', 'hopp' ); ?></p>
							<h2><?php esc_html_e( 'Explore curated story collections.', 'hopp' ); ?></h2>
							<p><?php esc_html_e( 'Watch connected Humans of Phnom Penh video stories through curated YouTube playlists.', 'hopp' ); ?></p>
						</div>
						<a class="button-primary" href="<?php echo esc_url( home_url( '/series/' ) ); ?>"><?php esc_html_e( 'Browse All Series', 'hopp' ); ?></a>
					</div>
				</section>
				<section class="section section--paper">
					<?php
					$story_posts = hopp_get_story_cards( 9 );
					if ( ! empty( $story_posts ) ) :
						?>
						<div class="card-grid">
							<?php
							foreach ( $story_posts as $story_post ) :
								setup_postdata( $story_post );
								$summary     = hopp_get_post_card_summary( $story_post->ID );
								$story_thumb = get_the_post_thumbnail_url( $story_post->ID, 'medium_large' );
								?>
								<article class="story-card">
									<a href="<?php echo esc_url( get_permalink( $story_post ) ); ?>">
										<div class="story-card__media"<?php echo $story_thumb ? '' : ' style="background: ' . esc_attr( hopp_demo_asset_gradient( 'teal' ) ) . '"'; ?>>
											<?php if ( $story_thumb ) : ?>
												<img src="<?php echo esc_url( $story_thumb ); ?>" alt="<?php echo esc_attr( get_the_title( $story_post ) ); ?>" loading="lazy">
											<?php endif; ?>
										</div>
										<div class="story-card__body">
											<p class="card-kicker"><?php echo esc_html( get_the_date( '', $story_post ) ); ?></p>
											<h2><?php echo esc_html( get_the_title( $story_post ) ); ?></h2>
											<?php if ( '' !== $summary ) : ?>
												<p class="card-summary"><?php echo esc_html( $summary ); ?></p>
											<?php endif; ?>
										</div>
									</a>
								</article>
							<?php
							endforeach;
							wp_reset_postdata();
							?>
						</div>
					<?php else : ?>
						<div class="empty-state">
							<p><?php esc_html_e( 'No stories published yet. Check back soon.', 'hopp' ); ?></p>
						</div>
					<?php endif; ?>
				</section>
				<?php
				hopp_render_context_cta(
					__( 'Browse by Series', 'hopp' ),
					__( 'Follow connected story collections on YouTube.', 'hopp' ),
					__( 'Move from single articles into curated playlists built around people, food, places, and neighborhood stories.', 'hopp' ),
					__( 'Browse All Series', 'hopp' ),
					home_url( '/series/' )
				);
				?>
			<?php elseif ( 'series' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Series', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'brown' ); ?>
				<section class="section section--paper youtube-series">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'YouTube Playlists', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Follow each story collection on YouTube.', 'hopp' ); ?></h2>
						<p><?php esc_html_e( 'Each card opens a curated Humans of Phnom Penh playlist in a new tab.', 'hopp' ); ?></p>
					</div>
					<div class="youtube-series__grid">
						<?php foreach ( hopp_get_youtube_series_playlists() as $playlist ) : ?>
							<a class="youtube-series-card" href="<?php echo esc_url( $playlist['url'] ); ?>" target="_blank" rel="noopener noreferrer">
								<span class="youtube-series-card__thumb">
									<img src="<?php echo esc_url( hopp_get_youtube_thumbnail_url( $playlist['video'] ?? '' ) ); ?>" alt="<?php echo esc_attr( $playlist['title'] ); ?>" loading="lazy">
									<span class="youtube-series-card__play" aria-hidden="true">
										<svg viewBox="0 0 24 24" focusable="false">
											<path d="M21.6 7.2s-.2-1.5-.8-2.1c-.8-.8-1.7-.8-2.1-.9C15.8 4 12 4 12 4s-3.8 0-6.7.2c-.4.1-1.3.1-2.1.9-.6.6-.8 2.1-.8 2.1S2.2 9 2.2 10.8v1.7c0 1.8.2 3.6.2 3.6s.2 1.5.8 2.1c.8.8 1.9.8 2.4.9 1.7.2 6.4.2 6.4.2s3.8 0 6.7-.2c.4-.1 1.3-.1 2.1-.9.6-.6.8-2.1.8-2.1s.2-1.8.2-3.6v-1.7c0-1.8-.2-3.6-.2-3.6zM10.1 14.8V8.6l5.8 3.1-5.8 3.1z"></path>
										</svg>
									</span>
								</span>
								<span class="youtube-series-card__body">
									<span class="youtube-series-card__label"><?php echo esc_html( $playlist['label'] ); ?></span>
									<span class="youtube-series-card__title"><?php echo esc_html( $playlist['title'] ); ?></span>
									<span class="youtube-series-card__meta"><?php echo esc_html( $playlist['count'] ); ?> <span aria-hidden="true">&middot;</span> <?php esc_html_e( 'Open playlist on YouTube', 'hopp' ); ?></span>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php elseif ( 'artist' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Artist', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'terracotta' ); ?>
				<section class="section section--paper prose-section">
					<div><?php hopp_render_page_body_content( get_the_content( null, false, get_the_ID() ) ); ?></div>
				</section>
				<section class="section section--cream">
					<div class="section__header"><h2><?php esc_html_e( 'Submit Your Artwork', 'hopp' ); ?></h2></div>
					<div class="hopp-form-wrap">
						<?php hopp_render_contact_form( 'HOPP Artist Submission' ); ?>
					</div>
				</section>
			<?php elseif ( 'career' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Career', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'sand' ); ?>
				<section class="section section--paper prose-section">
					<div><?php hopp_render_page_body_content( get_the_content( null, false, get_the_ID() ) ); ?></div>
				</section>
				<section class="section section--paper apply-section">
					<div class="section__header"><h2><?php esc_html_e( 'Apply Now', 'hopp' ); ?></h2></div>
					<div class="hopp-form-wrap">
						<?php hopp_render_contact_form( 'HOPP Career Application' ); ?>
					</div>
				</section>
			<?php elseif ( 'contact-us' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Contact Us', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'brown' ); ?>
				<section class="section section--paper prose-section">
					<div>
						<?php hopp_render_page_body_content( get_the_content( null, false, get_the_ID() ) ); ?>
					</div>
				</section>
				<section class="section section--cream">
					<div class="section__header"><h2><?php esc_html_e( 'Send a Message', 'hopp' ); ?></h2></div>
					<div class="hopp-form-wrap"><?php hopp_render_contact_form( 'HOPP Contact Message' ); ?></div>
				</section>
			<?php elseif ( 'pitch-your-pal-phnom-penh' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Pitch Your Pal', 'hopp' ), get_the_title(), hopp_get_page_intro_text( $page ), 'sand' ); ?>
				<section class="section section--paper prose-section">
					<div><?php hopp_render_page_body_content( get_the_content( null, false, get_the_ID() ) ); ?></div>
				</section>
				<section class="section section--cream">
					<div class="section__header"><h2><?php esc_html_e( 'Submit a Nomination', 'hopp' ); ?></h2></div>
					<div class="hopp-form-wrap">
						<?php hopp_render_contact_form( 'HOPP Pitch Your Pal Nomination' ); ?>
					</div>
				</section>
			<?php elseif ( 'cart' === $slug || 'checkout' === $slug ) : ?>
				<section class="page-content page-content--commerce">
					<div class="page-content__article">
						<header class="page-content__header"><h1><?php the_title(); ?></h1></header>
						<div class="page-content__body">
							<?php
							if ( function_exists( 'woocommerce_output_all_notices' ) ) {
								woocommerce_output_all_notices();
							}

							echo do_shortcode( 'checkout' === $slug ? '[woocommerce_checkout]' : '[woocommerce_cart]' );
							?>
						</div>
					</div>
				</section>
			<?php elseif ( in_array( $slug, array( 'contest-guidelines', 'termsandconditions-artist', 'refund_returns' ), true ) ) : ?>
				<section class="document-page">
					<div class="document-page__article">
						<header class="document-page__header">
							<p class="document-page__eyebrow"><?php esc_html_e( 'Document', 'hopp' ); ?></p>
							<h1><?php the_title(); ?></h1>
						</header>
						<div class="document-page__body prose-section"><?php hopp_render_imported_content( get_the_content( null, false, get_the_ID() ) ); ?></div>
					</div>
				</section>
			<?php else : ?>
				<section class="page-content">
					<div class="page-content__article">
						<header class="page-content__header"><h1><?php the_title(); ?></h1></header>
						<div class="page-content__body"><?php hopp_render_imported_content( get_the_content( null, false, get_the_ID() ) ); ?></div>
					</div>
				</section>
			<?php endif; ?>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
