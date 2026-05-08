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
				<?php hopp_render_page_hero( __( 'Stories', 'hopp' ), get_the_title(), __( 'Browse the demo editorial archive before real content is imported.', 'hopp' ), 'brown' ); ?>
				<section class="section section--paper">
					<div class="card-grid">
						<?php
						$story_posts = hopp_get_story_cards( 9 );
						foreach ( $story_posts as $story_post ) :
							setup_postdata( $story_post );
							$clean_story = hopp_clean_imported_content( get_post_field( 'post_content', $story_post->ID ) );
							$summary     = wp_trim_words( wp_strip_all_tags( $clean_story ), 26, '...' );
							?>
							<article class="story-card">
								<a href="<?php echo esc_url( get_permalink( $story_post ) ); ?>">
									<div class="story-card__media" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'teal' ) ); ?>"></div>
									<div class="story-card__body"><p class="card-kicker"><?php echo esc_html( get_the_date( '', $story_post ) ); ?></p><h2><?php echo esc_html( get_the_title( $story_post ) ); ?></h2><p><?php echo esc_html( $summary ); ?></p></div>
								</a>
							</article>
							<?php
						endforeach;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php elseif ( 'artist' === $slug || 'career' === $slug || 'contact-us' === $slug ) : ?>
				<?php
				$is_artist  = 'artist' === $slug;
				$is_career  = 'career' === $slug;
				$page_intro = $is_artist ? __( 'A demo contribution page for artists, photographers, illustrators, and storytellers.', 'hopp' ) : ( $is_career ? __( 'A demo recruitment page for volunteers, interns, and creative collaborators.', 'hopp' ) : __( 'A demo contact page with direct details and a polished message form layout.', 'hopp' ) );
				hopp_render_page_hero( get_the_title(), get_the_title(), $page_intro, $is_career ? 'sand' : 'terracotta' );
				?>
				<section class="section section--paper form-layout">
					<div>
						<p class="section-label"><?php esc_html_e( 'Details', 'hopp' ); ?></p>
						<h2><?php echo esc_html( $is_artist ? __( 'Share work with the platform.', 'hopp' ) : ( $is_career ? __( 'Join the storytelling workflow.', 'hopp' ) : __( 'Reach the team directly.', 'hopp' ) ) ); ?></h2>
						<p><?php esc_html_e( 'This form is a visual demo only. Final submission behavior depends on the live WordPress form plugin and email setup.', 'hopp' ); ?></p>
					</div>
					<form class="demo-form" action="#" method="post" novalidate data-validate>
						<div class="demo-form__field">
							<label for="demo-name"><?php esc_html_e( 'Name', 'hopp' ); ?></label>
							<input type="text" id="demo-name" name="name" required data-error-empty="<?php esc_attr_e( 'Please enter your name.', 'hopp' ); ?>">
							<span class="demo-form__error" aria-live="polite"></span>
						</div>
						<div class="demo-form__field">
							<label for="demo-email"><?php esc_html_e( 'Email', 'hopp' ); ?></label>
							<input type="email" id="demo-email" name="email" required data-error-empty="<?php esc_attr_e( 'Please enter your email address.', 'hopp' ); ?>">
							<span class="demo-form__error" aria-live="polite"></span>
						</div>
						<div class="demo-form__field">
							<label for="demo-message"><?php echo esc_html( $is_career ? __( 'Role Interest', 'hopp' ) : __( 'Message', 'hopp' ) ); ?></label>
							<textarea id="demo-message" name="message" rows="5" required data-error-empty="<?php esc_attr_e( 'Please enter a message.', 'hopp' ); ?>"></textarea>
							<span class="demo-form__error" aria-live="polite"></span>
						</div>
						<button type="submit" class="button-primary"><?php esc_html_e( 'Send', 'hopp' ); ?></button>
					</form>
					<script>
					(function () {
						var form = document.querySelector('.demo-form[data-validate]');
						if (!form) return;
						function validate(input) {
							var field = input.closest('.demo-form__field');
							var error = field.querySelector('.demo-form__error');
							var msg = '';
							if (!input.value.trim()) {
								msg = input.dataset.errorEmpty || 'This field is required.';
							} else if (input.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value)) {
								msg = 'Enter a valid email address.';
							}
							error.textContent = msg;
							input.setAttribute('aria-invalid', msg ? 'true' : 'false');
							field.classList.toggle('demo-form__field--error', !!msg);
							return !msg;
						}
						form.querySelectorAll('input, textarea').forEach(function (input) {
							input.addEventListener('blur', function () { validate(input); });
							input.addEventListener('input', function () {
								if (input.closest('.demo-form__field').classList.contains('demo-form__field--error')) {
									validate(input);
								}
							});
						});
						form.addEventListener('submit', function (e) {
							e.preventDefault();
							var inputs = Array.from(form.querySelectorAll('input[required], textarea[required]'));
							var allValid = inputs.map(validate).every(Boolean);
							if (allValid) {
								var btn = form.querySelector('button[type="submit"]');
								btn.textContent = 'Demo only — no submission';
								btn.disabled = true;
							} else {
								form.querySelector('.demo-form__field--error input, .demo-form__field--error textarea').focus();
							}
						});
					})();
					</script>
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
