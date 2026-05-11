<?php
/**
 * Search results template for the HOPP theme.
 */

get_header();
?>

<main class="archive-content">
	<header class="archive-content__header">
		<h1>
			<?php
			printf(
				/* translators: %s: search query. */
				esc_html__( 'Search results for "%s"', 'hopp' ),
				esc_html( get_search_query() )
			);
			?>
		</h1>
	</header>

	<?php if ( have_posts() ) : ?>
		<div class="archive-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'archive-card' ); ?>>
					<a class="archive-card__link" href="<?php the_permalink(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="archive-card__media">
								<?php the_post_thumbnail( 'medium_large' ); ?>
							</figure>
						<?php endif; ?>

						<div class="archive-card__body">
							<p class="archive-card__meta"><?php echo esc_html( get_post_type() ); ?></p>
							<h2><?php the_title(); ?></h2>
							<?php if ( $summary = hopp_get_post_card_summary( get_the_ID() ) ) : ?>
								<p class="card-summary"><?php echo esc_html( $summary ); ?></p>
							<?php endif; ?>
						</div>
					</a>
				</article>
				<?php
			endwhile;
			?>
		</div>

		<nav class="pagination" aria-label="<?php esc_attr_e( 'Search results pagination', 'hopp' ); ?>">
			<?php the_posts_pagination(); ?>
		</nav>
	<?php else : ?>
		<p><?php esc_html_e( 'No results found.', 'hopp' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
