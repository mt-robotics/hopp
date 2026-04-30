<?php
/**
 * Posts index template for the HOPP theme.
 */

get_header();
?>

<main class="archive-content">
	<header class="archive-content__header">
		<h1><?php esc_html_e( 'Stories', 'hopp' ); ?></h1>
		<div class="archive-content__description">
			<p><?php esc_html_e( 'People, places, and perspectives from Phnom Penh.', 'hopp' ); ?></p>
		</div>
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
							<p class="archive-card__meta"><?php echo esc_html( get_the_date() ); ?></p>
							<h2><?php the_title(); ?></h2>
							<?php the_excerpt(); ?>
						</div>
					</a>
				</article>
				<?php
			endwhile;
			?>
		</div>

		<nav class="pagination" aria-label="<?php esc_attr_e( 'Stories pagination', 'hopp' ); ?>">
			<?php the_posts_pagination(); ?>
		</nav>
	<?php else : ?>
		<p><?php esc_html_e( 'No stories found.', 'hopp' ); ?></p>
	<?php endif; ?>
</main>

<?php
get_footer();
