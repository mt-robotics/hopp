<?php
/**
 * WooCommerce product archive template for the HOPP theme.
 */

get_header();
?>

<main>
	<?php hopp_render_page_hero( __( 'Products', 'hopp' ), post_type_archive_title( '', false ) ?: __( 'Products', 'hopp' ), __( 'Browse the imported product catalog and open each item for details.', 'hopp' ), 'terracotta' ); ?>

	<section class="section section--paper product-grid">
		<?php if ( function_exists( 'wc_get_products' ) ) : ?>
			<?php foreach ( hopp_get_product_cards( 12 ) as $product ) : ?>
				<?php
				$product_id = $product->get_id();
				$thumbnail  = get_the_post_thumbnail_url( $product_id, 'medium_large' );
				?>
				<article class="product-card">
					<a class="product-card__link" href="<?php echo esc_url( $product->get_permalink() ); ?>">
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
		<?php endif; ?>
	</section>
</main>

<?php
get_footer();
