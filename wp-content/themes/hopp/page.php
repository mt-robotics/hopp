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
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php
			if ( 'about-us' === $slug ) :
				hopp_render_page_hero( __( 'About Us', 'hopp' ), get_the_title(), __( 'A cultural storytelling platform documenting people, creative work, and community memory in Phnom Penh.', 'hopp' ), 'sand' );
				?>
				<section class="section section--paper prose-section">
					<p class="section-label"><?php esc_html_e( 'Mission', 'hopp' ); ?></p>
					<h2><?php esc_html_e( 'Make local stories visible, respectful, and memorable.', 'hopp' ); ?></h2>
					<p><?php esc_html_e( 'Humans of Phnom Penh exists at the intersection of cultural publishing, portrait storytelling, creative services, and community participation.', 'hopp' ); ?></p>
				</section>
				<section class="section section--cream feature-grid">
					<div><h3><?php esc_html_e( 'Vision', 'hopp' ); ?></h3><p><?php esc_html_e( 'A public archive where the city can recognize itself through people, places, craft, and memory.', 'hopp' ); ?></p></div>
					<div><h3><?php esc_html_e( 'Objective 01', 'hopp' ); ?></h3><p><?php esc_html_e( 'Publish human-centered stories that feel intimate, accurate, and visually refined.', 'hopp' ); ?></p></div>
					<div><h3><?php esc_html_e( 'Objective 02', 'hopp' ); ?></h3><p><?php esc_html_e( 'Connect artists, brands, volunteers, and readers through meaningful cultural work.', 'hopp' ); ?></p></div>
				</section>
			<?php elseif ( 'products' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Products', 'hopp' ), get_the_title(), __( 'A visual store direction for cultural objects, books, prints, and artist collaborations.', 'hopp' ), 'terracotta' ); ?>
				<section class="section section--paper product-grid">
					<?php if ( function_exists( 'wc_get_products' ) ) : ?>
						<?php foreach ( hopp_get_product_cards( 12 ) as $product ) : ?>
							<?php
							$product_id = $product->get_id();
							$permalink  = $product->get_permalink();
							$thumbnail  = get_the_post_thumbnail_url( $product_id, 'medium_large' );
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
										<?php if ( $product_summary = hopp_get_product_summary( $product, 22 ) ) : ?>
											<p><?php echo esc_html( $product_summary ); ?></p>
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
			<?php elseif ( 'stories' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Stories', 'hopp' ), get_the_title(), __( 'Portraits, interviews, and neighborhood dispatches from Phnom Penh.', 'hopp' ), 'brown' ); ?>
				<section class="section section--paper">
					<?php
					$story_posts = hopp_get_story_cards( 9 );
					if ( ! empty( $story_posts ) ) :
						?>
						<div class="card-grid">
							<?php
							foreach ( $story_posts as $story_post ) :
								setup_postdata( $story_post );
								$clean_story    = hopp_clean_imported_content( get_post_field( 'post_content', $story_post->ID ) );
								$summary        = wp_trim_words( wp_strip_all_tags( $clean_story ), 26, '...' );
								$story_thumb    = get_the_post_thumbnail_url( $story_post->ID, 'medium_large' );
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
											<p><?php echo esc_html( $summary ); ?></p>
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
				<section class="section section--cream series-teaser">
					<div class="series-teaser__inner">
						<div>
							<p class="section-label"><?php esc_html_e( 'Browse by Series', 'hopp' ); ?></p>
							<h2><?php esc_html_e( 'Explore curated story collections.', 'hopp' ); ?></h2>
							<p><?php esc_html_e( 'Some of our best storytelling runs across multiple entries. Browse series to follow a thread from start to finish.', 'hopp' ); ?></p>
						</div>
						<a class="button-primary" href="<?php echo esc_url( home_url( '/series/' ) ); ?>"><?php esc_html_e( 'Browse All Series', 'hopp' ); ?></a>
					</div>
				</section>
			<?php elseif ( 'artist' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Artist', 'hopp' ), get_the_title(), __( 'Contribute your creativity and stories to Humans of Phnom Penh.', 'hopp' ), 'terracotta' ); ?>
				<section class="section section--paper">
					<div class="artist-intro">
						<p class="section-label"><?php esc_html_e( 'Calling All Artists!', 'hopp' ); ?></p>
						<p><?php esc_html_e( 'Here are some ways you can contribute your art to Humans of Phnom Penh:', 'hopp' ); ?></p>
						<ul class="artist-intro__list">
							<li><?php esc_html_e( 'Submit a photo or video of your work.', 'hopp' ); ?></li>
							<li><?php esc_html_e( 'Write a blog post about your art and your inspiration.', 'hopp' ); ?></li>
							<li><?php esc_html_e( 'Share a story about your journey as an artist.', 'hopp' ); ?></li>
							<li><?php esc_html_e( 'Share a story behind the photo.', 'hopp' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'No matter how you choose to contribute, we are excited to share your art with the world.', 'hopp' ); ?></p>
						<p>
							<?php esc_html_e( 'Here is our ', 'hopp' ); ?>
							<a class="text-link" href="<?php echo esc_url( home_url( '/contest-guidelines/' ) ); ?>"><?php esc_html_e( 'Contest Guidelines &amp; Terms &amp; Conditions', 'hopp' ); ?></a>.
						</p>
					</div>
				</section>
				<section class="section section--cream">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'Showcase Your Artwork', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Share your work with the platform.', 'hopp' ); ?></h2>
					</div>
					<div class="forminator-wrap">
						<?php echo do_shortcode( '[forminator_form id="617"]' ); ?>
					</div>
				</section>
				<section class="section section--paper feature-grid">
					<div>
						<h3><?php esc_html_e( 'Why contribute to Humans of Phnom Penh?', 'hopp' ); ?></h3>
						<p><?php esc_html_e( 'HoPP gives artists a platform to share their work with a culturally engaged audience across Phnom Penh and beyond. Your contribution helps document the creative pulse of the city and becomes part of a lasting public archive.', 'hopp' ); ?></p>
					</div>
					<div>
						<h3><?php esc_html_e( 'How to contribute your art?', 'hopp' ); ?></h3>
						<p><?php esc_html_e( 'Submit your work through the form above. Our editorial team reviews all submissions and reaches out within two weeks with feedback or a publication plan.', 'hopp' ); ?></p>
					</div>
				</section>
			<?php elseif ( 'career' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Career', 'hopp' ), get_the_title(), __( 'We are a team of passionate individuals committed to using the power of storytelling to build a stronger community.', 'hopp' ), 'sand' ); ?>
				<section class="section section--paper">
					<div class="career-intro">
						<p><?php esc_html_e( 'Humans of Phnom Penh is a website dedicated to showcasing the inspiring and positive stories of our city. We are a team of passionate individuals who are committed to using the power of storytelling to build a stronger community.', 'hopp' ); ?></p>
						<p><?php esc_html_e( 'We are looking for volunteers and interns to join our team and help us achieve our mission. Whether you are interested in writing, photography, videography, or social media, we have a role for you.', 'hopp' ); ?></p>
						<p><?php esc_html_e( 'Here are some of the benefits of volunteering or interning with Humans of Phnom Penh:', 'hopp' ); ?></p>
					</div>
					<div class="career-benefits">
						<div class="career-benefit">
							<span class="career-benefit__icon" aria-hidden="true">★</span>
							<h3><?php esc_html_e( 'Gain Valuable Experience', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Build real-world skills in journalism, photography, videography, and social media.', 'hopp' ); ?></p>
						</div>
						<div class="career-benefit">
							<span class="career-benefit__icon" aria-hidden="true">◆</span>
							<h3><?php esc_html_e( 'Meet Passionate People', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Connect with individuals who are committed to making a difference in Phnom Penh.', 'hopp' ); ?></p>
						</div>
						<div class="career-benefit">
							<span class="career-benefit__icon" aria-hidden="true">●</span>
							<h3><?php esc_html_e( 'Publish Your Work', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Have the opportunity to publish your work on our website and social media.', 'hopp' ); ?></p>
						</div>
						<div class="career-benefit">
							<span class="career-benefit__icon" aria-hidden="true">▲</span>
							<h3><?php esc_html_e( 'Grow Your Network', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Network with professionals in the media and creative industries.', 'hopp' ); ?></p>
						</div>
					</div>
				</section>
				<section class="section section--cream">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'Volunteer Opportunities', 'hopp' ); ?></p>
					</div>
					<div class="volunteer-grid">
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--writers"></div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'Writers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for writers to help us write inspiring and positive stories about the people of Phnom Penh.', 'hopp' ); ?></p>
							</div>
						</article>
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--photographers"></div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'Photographers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for photographers to help us capture the beauty and diversity of Phnom Penh.', 'hopp' ); ?></p>
							</div>
						</article>
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--videographers"></div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'Videographers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for videographers to help us create short films and documentaries for our website and social media pages.', 'hopp' ); ?></p>
							</div>
						</article>
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--social"></div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'SM Managers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for social media managers to help us promote our content and engage with our audience on social media.', 'hopp' ); ?></p>
							</div>
						</article>
					</div>
				</section>
				<section class="section section--paper">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'Internship Opportunities', 'hopp' ); ?></p>
					</div>
					<div class="intern-grid">
						<div class="intern-card">
							<h3><?php esc_html_e( 'Journalism Interns', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Journalism interns will help us to research, write, and edit stories for our website.', 'hopp' ); ?></p>
						</div>
						<div class="intern-card">
							<h3><?php esc_html_e( 'Photography Interns', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'We are looking for photography interns to help us write inspiring and positive stories about the people of Phnom Penh.', 'hopp' ); ?></p>
						</div>
						<div class="intern-card">
							<h3><?php esc_html_e( 'Videography Interns', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Videography interns will help us create short films and documentaries for our website and social media pages.', 'hopp' ); ?></p>
						</div>
						<div class="intern-card">
							<h3><?php esc_html_e( 'Social Media Interns', 'hopp' ); ?></h3>
							<p><?php esc_html_e( 'Social media interns will help us promote our content and engage with our audience on social media.', 'hopp' ); ?></p>
						</div>
					</div>
				</section>
				<section class="section section--cream">
					<div class="career-qualifications">
						<h2><?php esc_html_e( 'Qualifications', 'hopp' ); ?></h2>
						<ul class="qualifications-list">
							<li><?php esc_html_e( 'Volunteers and interns must be passionate about storytelling and social change.', 'hopp' ); ?></li>
							<li><?php esc_html_e( 'Volunteers and interns must be able to work independently and as part of a team.', 'hopp' ); ?></li>
							<li><?php esc_html_e( 'Volunteers and interns must be able to meet deadlines and work under pressure.', 'hopp' ); ?></li>
						</ul>
						<p><?php esc_html_e( 'To apply for a volunteer or internship position at Humans of Phnom Penh, please visit our website and click on the "Careers" button. You will be able to submit your resume and a cover letter.', 'hopp' ); ?></p>
						<p><?php esc_html_e( 'We look forward to hearing from you!', 'hopp' ); ?></p>
					</div>
				</section>
				<section class="section section--paper apply-section">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'Apply Now', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Be A Part Of Winning Team', 'hopp' ); ?></h2>
					</div>
					<div class="forminator-wrap">
						<?php
						/* Career application form. If the form ID below shows an empty form,
						 * create the Career form in wp-admin → Forminator and update this ID. */
						echo do_shortcode( '[forminator_form id="1256"]' );
						?>
					</div>
				</section>
			<?php elseif ( 'contact-us' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Contact Us', 'hopp' ), get_the_title(), __( 'Reach the Humans of Phnom Penh team directly.', 'hopp' ), 'terracotta' ); ?>
				<section class="section section--paper contact-layout">
					<div class="contact-details">
						<p class="section-label"><?php esc_html_e( 'Get In Touch', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'We\'d love to hear from you.', 'hopp' ); ?></h2>
						<ul class="contact-list">
							<li class="contact-list__item">
								<span class="contact-list__label"><?php esc_html_e( 'Phone', 'hopp' ); ?></span>
								<a href="tel:+85581363753">+855 81 363 753</a>
							</li>
							<li class="contact-list__item">
								<span class="contact-list__label"><?php esc_html_e( 'Email', 'hopp' ); ?></span>
								<a href="mailto:info@humansofphnompenh.com">info@humansofphnompenh.com</a>
							</li>
							<li class="contact-list__item">
								<span class="contact-list__label"><?php esc_html_e( 'Address', 'hopp' ); ?></span>
								<address><?php esc_html_e( 'Morgan Tower, Street Sopheak Mongkol Rd, Koh Pich, Phnom Penh 120101', 'hopp' ); ?></address>
							</li>
						</ul>
						<div class="contact-map">
							<iframe
								title="<?php esc_attr_e( 'Humans of Phnom Penh location', 'hopp' ); ?>"
								src="https://maps.google.com/maps?q=Morgan+Tower+Koh+Pich+Phnom+Penh+Cambodia&output=embed"
								width="100%"
								height="300"
								style="border:0;"
								loading="lazy"
								referrerpolicy="no-referrer-when-downgrade"
							></iframe>
						</div>
					</div>
					<div class="contact-form">
						<p class="section-label"><?php esc_html_e( 'Send a Message', 'hopp' ); ?></p>
						<div class="forminator-wrap">
							<?php echo do_shortcode( '[forminator_form id="628"]' ); ?>
						</div>
					</div>
				</section>
			<?php elseif ( 'pitch-your-pal-phnom-penh' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Pitch Your Pal', 'hopp' ), __( 'Pitch Your Pal', 'hopp' ), __( 'Know someone whose story deserves to be told? Pitch them to us.', 'hopp' ), 'sand' ); ?>
				<section class="section section--paper">
					<div class="pitch-intro">
						<p class="section-label"><?php esc_html_e( 'Nominate a Story', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Every person has a story worth sharing.', 'hopp' ); ?></h2>
						<p><?php esc_html_e( 'Do you know someone in Phnom Penh whose work, life, or perspective deserves a wider audience? Tell us about them and we\'ll reach out to explore their story.', 'hopp' ); ?></p>
					</div>
				</section>
				<section class="section section--cream">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'Submit a Nomination', 'hopp' ); ?></p>
					</div>
					<div class="forminator-wrap">
						<?php echo do_shortcode( '[forminator_form id="1259"]' ); ?>
					</div>
				</section>
				<?php
				$pitch_content = get_the_content( null, false, get_the_ID() );
				if ( $pitch_content ) :
					?>
					<section class="section section--paper prose-section">
						<div><?php hopp_render_imported_content( $pitch_content ); ?></div>
					</section>
				<?php endif; ?>
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
