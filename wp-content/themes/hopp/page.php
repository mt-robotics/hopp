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
				</section>
			<?php elseif ( 'stories' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Stories', 'hopp' ), get_the_title(), __( 'Browse the demo editorial archive before real content is imported.', 'hopp' ), 'brown' ); ?>
				<section class="section section--paper">
					<div class="card-grid">
						<?php
						$story_query = new WP_Query( array( 'posts_per_page' => 9 ) );
						while ( $story_query->have_posts() ) :
							$story_query->the_post();
							?>
							<article class="story-card">
								<a href="<?php the_permalink(); ?>">
									<div class="story-card__media" style="background: <?php echo esc_attr( hopp_demo_asset_gradient( 'teal' ) ); ?>"></div>
									<div class="story-card__body"><p class="card-kicker"><?php echo esc_html( get_the_date() ); ?></p><h3><?php the_title(); ?></h3><?php the_excerpt(); ?></div>
								</a>
							</article>
							<?php
						endwhile;
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
					<form class="demo-form" action="#" method="post">
						<label><?php esc_html_e( 'Name', 'hopp' ); ?><input type="text" name="name"></label>
						<label><?php esc_html_e( 'Email', 'hopp' ); ?><input type="email" name="email"></label>
						<label><?php echo esc_html( $is_career ? __( 'Role Interest', 'hopp' ) : __( 'Message', 'hopp' ) ); ?><textarea name="message" rows="5"></textarea></label>
						<button type="button" class="button-primary"><?php esc_html_e( 'Demo Only', 'hopp' ); ?></button>
					</form>
				</section>
			<?php elseif ( 'cart' === $slug ) : ?>
				<?php hopp_render_page_hero( __( 'Cart', 'hopp' ), get_the_title(), __( 'A visual empty cart state. Production cart behavior waits for plugin validation.', 'hopp' ), 'brown' ); ?>
				<section class="section section--paper empty-state">
					<h2><?php esc_html_e( 'Your cart is empty in this V1 demo.', 'hopp' ); ?></h2>
					<p><?php esc_html_e( 'WooCommerce or the live e-commerce plugin must be confirmed before cart and checkout behavior can be styled safely.', 'hopp' ); ?></p>
					<a class="button-primary" href="<?php echo esc_url( home_url( '/products/' ) ); ?>"><?php esc_html_e( 'Return to Products', 'hopp' ); ?></a>
				</section>
			<?php else : ?>
				<section class="page-content">
					<div class="page-content__article">
						<header class="page-content__header"><h1><?php the_title(); ?></h1></header>
						<div class="page-content__body"><?php the_content(); ?></div>
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
