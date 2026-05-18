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
				hopp_render_page_hero( __( 'About Us', 'hopp' ), get_the_title(), __( 'A cultural storytelling platform showcasing the people, diversity, and heritage of Phnom Penh.', 'hopp' ), 'sand' );
				?>
				<section class="section section--paper about-intro">
					<div class="about-intro__copy">
						<p class="section-label"><?php esc_html_e( 'About Us', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Stories that make the city easier to understand, remember, and care about.', 'hopp' ); ?></h2>
						<p><?php esc_html_e( 'Humans of Phnom Penh is dedicated to showcasing the rich diversity and culture of Phnom Penh, Cambodia. Through interviews, photographs, and videos, the platform brings together the unique stories of the city’s people and creates a space for empathy, understanding, and dialogue.', 'hopp' ); ?></p>
					</div>
				</section>
				<section class="section section--cream about-principles">
					<div class="about-principle about-principle--mission">
						<p class="section-label"><?php esc_html_e( 'Mission', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Highlight the diverse and captivating stories of the people who call Phnom Penh home.', 'hopp' ); ?></h2>
						<p><?php esc_html_e( 'Through the platform, Humans of Phnom Penh aims to foster greater empathy and understanding, promote dialogue, and celebrate the cultural richness of the city.', 'hopp' ); ?></p>
					</div>
					<div class="about-principle">
						<p class="section-label"><?php esc_html_e( 'Vision', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'Become a leading platform for Phnom Penh’s diversity and cultural heritage.', 'hopp' ); ?></h2>
						<p><?php esc_html_e( 'The long-term vision is to promote social cohesion, inclusivity, and understanding among the people of Phnom Penh through human-centered cultural storytelling.', 'hopp' ); ?></p>
					</div>
				</section>
				<section class="section section--paper about-objectives">
					<div class="section__header">
						<p class="section-label"><?php esc_html_e( 'Objectives', 'hopp' ); ?></p>
						<h2><?php esc_html_e( 'What the platform is built to do.', 'hopp' ); ?></h2>
					</div>
					<div class="about-objectives__grid">
						<div class="about-objective">
							<span><?php esc_html_e( '01', 'hopp' ); ?></span>
							<p><?php esc_html_e( 'Showcase the unique and captivating stories of the people of Phnom Penh through interviews, photographs, and videos.', 'hopp' ); ?></p>
						</div>
						<div class="about-objective">
							<span><?php esc_html_e( '02', 'hopp' ); ?></span>
							<p><?php esc_html_e( 'Foster empathy and understanding among the audience, promoting greater social cohesion and inclusivity within the city.', 'hopp' ); ?></p>
						</div>
						<div class="about-objective">
							<span><?php esc_html_e( '03', 'hopp' ); ?></span>
							<p><?php esc_html_e( 'Celebrate the cultural richness and diversity of Phnom Penh, highlighting the traditions, beliefs, and practices that make it special.', 'hopp' ); ?></p>
						</div>
						<div class="about-objective">
							<span><?php esc_html_e( '04', 'hopp' ); ?></span>
							<p><?php esc_html_e( 'Create a space for dialogue and exchange of ideas, encouraging audiences to engage with the stories and perspectives shared on the platform.', 'hopp' ); ?></p>
						</div>
						<div class="about-objective">
							<span><?php esc_html_e( '05', 'hopp' ); ?></span>
							<p><?php esc_html_e( 'Promote tourism and cultural exchange by showcasing the hidden gems and local experiences that Phnom Penh has to offer.', 'hopp' ); ?></p>
						</div>
					</div>
				</section>
				<?php
				hopp_render_context_cta(
					__( 'Join the Dialogue', 'hopp' ),
					__( 'Read a story, contribute a perspective, or help document the city.', 'hopp' ),
					__( 'Explore the latest stories and see how the platform turns local memory into public storytelling.', 'hopp' ),
					__( 'Explore Stories', 'hopp' ),
					home_url( '/stories/' )
				);
				?>
			<?php elseif ( 'products' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Products', 'hopp' ), get_the_title(), __( 'A visual store direction for cultural objects, books, prints, and artist collaborations.', 'hopp' ), 'terracotta' ); ?>
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
				<?php hopp_render_page_hero( __( 'Stories', 'hopp' ), get_the_title(), __( 'Portraits, interviews, and neighborhood dispatches from Phnom Penh.', 'hopp' ), 'brown' ); ?>
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
				<?php hopp_render_page_hero( __( 'Series', 'hopp' ), get_the_title(), __( 'Curated video story collections from Humans of Phnom Penh.', 'hopp' ), 'brown' ); ?>
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
					<div class="hopp-form-wrap">
						<?php hopp_render_contact_form( 'HOPP Artist Submission' ); ?>
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
				<?php
				hopp_render_context_cta(
					__( 'Pitch a Creative Voice', 'hopp' ),
					__( 'Know an artist or contributor we should feature?', 'hopp' ),
					__( 'Nominate a creative person whose work helps document Phnom Penh through art, portraits, or visual storytelling.', 'hopp' ),
					__( 'Open Pitch Your Pal', 'hopp' ),
					home_url( '/pitch-your-pal-phnom-penh/' )
				);
				?>
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
							<div class="volunteer-card__media volunteer-card__media--writers">
								<img src="<?php echo esc_url( hopp_get_career_role_image_url( 'writers' ) ); ?>" alt="<?php esc_attr_e( 'Humans of Phnom Penh writing and storytelling work', 'hopp' ); ?>" loading="lazy">
							</div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'Writers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for writers to help us write inspiring and positive stories about the people of Phnom Penh.', 'hopp' ); ?></p>
							</div>
						</article>
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--photographers">
								<img src="<?php echo esc_url( hopp_get_career_role_image_url( 'photographers' ) ); ?>" alt="<?php esc_attr_e( 'Humans of Phnom Penh photography work', 'hopp' ); ?>" loading="lazy">
							</div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'Photographers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for photographers to help us capture the beauty and diversity of Phnom Penh.', 'hopp' ); ?></p>
							</div>
						</article>
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--videographers">
								<img src="<?php echo esc_url( hopp_get_career_role_image_url( 'videographers' ) ); ?>" alt="<?php esc_attr_e( 'Humans of Phnom Penh video storytelling work', 'hopp' ); ?>" loading="lazy">
							</div>
							<div class="volunteer-card__body">
								<h3><?php esc_html_e( 'Videographers', 'hopp' ); ?></h3>
								<p><?php esc_html_e( 'We are looking for videographers to help us create short films and documentaries for our website and social media pages.', 'hopp' ); ?></p>
							</div>
						</article>
						<article class="volunteer-card">
							<div class="volunteer-card__media volunteer-card__media--social">
								<img src="<?php echo esc_url( hopp_get_career_role_image_url( 'social' ) ); ?>" alt="<?php esc_attr_e( 'Humans of Phnom Penh social media storytelling work', 'hopp' ); ?>" loading="lazy">
							</div>
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
					<div class="hopp-form-wrap">
						<?php hopp_render_contact_form( 'HOPP Career Application' ); ?>
					</div>
				</section>
				<?php
				hopp_render_context_cta(
					__( 'Questions Before Applying?', 'hopp' ),
					__( 'Reach out before you submit your application.', 'hopp' ),
					__( 'Ask about volunteer roles, internships, portfolio expectations, or how the team reviews applications.', 'hopp' ),
					__( 'Contact Us', 'hopp' ),
					home_url( '/contact-us/' )
				);
				?>
			<?php elseif ( 'contact-us' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Contact Us', 'hopp' ), get_the_title(), __( 'Reach the Humans of Phnom Penh team directly.', 'hopp' ), 'brown' ); ?>
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
						<div class="hopp-form-wrap">
							<?php hopp_render_contact_form( 'HOPP Contact Message' ); ?>
						</div>
					</div>
				</section>
				<?php
				hopp_render_context_cta(
					__( 'Stories Start With People', 'hopp' ),
					__( 'Know someone whose story deserves to be told?', 'hopp' ),
					__( 'Send a nomination through Pitch Your Pal so the editorial team can review it.', 'hopp' ),
					__( 'Open Pitch Your Pal', 'hopp' ),
					home_url( '/pitch-your-pal-phnom-penh/' )
				);
				?>
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
					<div class="hopp-form-wrap">
						<?php hopp_render_contact_form( 'HOPP Pitch Your Pal Nomination' ); ?>
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
