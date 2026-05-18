<?php
/**
 * Custom single product template for the HOPP theme.
 */

get_header();
?>

<main>
	<?php
	while ( have_posts() ) :
		the_post();

		$product_id      = get_the_ID();
		$product         = function_exists( 'wc_get_product' ) ? wc_get_product( $product_id ) : null;
		$category_label  = hopp_get_product_category_label( $product_id );
		$detail_html     = $product ? hopp_get_product_detail_html( $product ) : '';
		$detail_image    = $product ? hopp_get_product_card_thumbnail_url( $product, 'large' ) : '';
		$has_image       = $product && method_exists( $product, 'get_image_id' ) && (int) $product->get_image_id();
		$related_products = $product ? hopp_get_related_product_objects( $product_id, 3 ) : array();
		?>

		<section class="page-hero page-hero--product">
			<div class="page-hero__inner">
				<h1><?php echo esc_html( get_the_title() ); ?></h1>
			</div>
		</section>

		<section class="product-detail">
			<?php
			if ( function_exists( 'woocommerce_output_all_notices' ) ) {
				woocommerce_output_all_notices();
			}

			if ( function_exists( 'woocommerce_breadcrumb' ) ) {
				woocommerce_breadcrumb();
			}
			?>

			<div class="product-detail__shell">
				<div class="product-detail__media">
					<?php
					if ( $has_image && function_exists( 'woocommerce_show_product_images' ) ) {
						woocommerce_show_product_images();
					} elseif ( $detail_image ) {
						?>
						<figure class="product-detail__fallback-image">
							<img src="<?php echo esc_url( $detail_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
						</figure>
						<?php
					}
					?>
				</div>

				<div class="product-detail__summary">
					<p class="product-detail__eyebrow"><?php echo esc_html( $category_label ); ?></p>
					<p class="product-detail__name"><?php echo esc_html( get_the_title() ); ?></p>

					<div class="product-detail__price">
						<?php
						if ( function_exists( 'woocommerce_template_single_price' ) ) {
							woocommerce_template_single_price();
						}
						?>
					</div>

					<?php if ( '' !== $detail_html ) : ?>
						<div class="product-detail__description">
							<?php echo wp_kses_post( $detail_html ); ?>
						</div>
					<?php endif; ?>

					<div class="product-detail__cart">
						<?php
						if ( function_exists( 'woocommerce_template_single_add_to_cart' ) ) {
							woocommerce_template_single_add_to_cart();
						}
						?>
					</div>

					<p class="product-detail__meta">
						<?php
						printf(
							/* translators: %s: product categories. */
							esc_html__( 'Category: %s', 'hopp' ),
							wp_kses_post( function_exists( 'wc_get_product_category_list' ) ? ( wc_get_product_category_list( $product_id, ', ' ) ?: __( 'Uncategorized', 'hopp' ) ) : __( 'Uncategorized', 'hopp' ) )
						);
						?>
					</p>
				</div>
			</div>
		</section>

		<?php if ( ! empty( $related_products ) ) : ?>
			<section class="product-related">
				<div class="product-related__inner">
					<p class="section-label"><?php esc_html_e( 'Related products', 'hopp' ); ?></p>
					<div class="product-related__grid">
						<?php foreach ( $related_products as $related_product ) : ?>
							<?php
							$related_id    = $related_product->get_id();
							$thumbnail     = hopp_get_product_card_thumbnail_url( $related_product, 'medium_large' );
							$summary       = hopp_get_product_summary( $related_product );
							$related_link  = $related_product->get_permalink();
							$related_label = hopp_get_product_category_label( $related_id );
							?>
							<article class="product-card">
								<a class="product-card__link" href="<?php echo esc_url( $related_link ); ?>">
									<div class="product-card__media"<?php echo $thumbnail ? '' : ' style="background: ' . esc_attr( hopp_demo_asset_gradient( 'terracotta' ) ) . '"' ; ?>>
										<?php if ( $thumbnail ) : ?>
											<img src="<?php echo esc_url( $thumbnail ); ?>" alt="<?php echo esc_attr( $related_product->get_name() ); ?>">
										<?php endif; ?>
									</div>
									<div class="product-card__body">
										<p class="card-kicker"><?php echo esc_html( $related_label ); ?></p>
										<h2><?php echo esc_html( $related_product->get_name() ); ?></h2>
										<?php if ( '' !== $summary ) : ?>
											<p class="product-card__summary"><?php echo esc_html( $summary ); ?></p>
										<?php else : ?>
											<p class="product-card__summary product-card__summary--empty" aria-hidden="true">&nbsp;</p>
										<?php endif; ?>
										<div class="product-card__footer">
											<strong><?php echo wp_kses_post( $related_product->get_price_html() ); ?></strong>
											<span><?php esc_html_e( 'View Product', 'hopp' ); ?></span>
										</div>
									</div>
								</a>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			</section>
		<?php endif; ?>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
