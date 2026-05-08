<?php
/**
 * Single post template for the HOPP theme.
 */

get_header();
?>

<main class="single-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'single-content__article' ); ?>>
			<header class="single-content__header">
				<p class="single-content__meta"><?php echo esc_html( get_the_date() ); ?></p>
				<h1><?php the_title(); ?></h1>
			</header>

			<?php if ( has_post_thumbnail() ) : ?>
				<figure class="single-content__media">
					<?php the_post_thumbnail( 'large' ); ?>
				</figure>
			<?php endif; ?>

			<div class="single-content__body">
				<?php hopp_render_imported_content( get_the_content( null, false, get_the_ID() ) ); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
