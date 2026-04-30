<?php
/**
 * Default page template for the HOPP theme.
 */

get_header();
?>

<main class="page-content">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'page-content__article' ); ?>>
			<header class="page-content__header">
				<h1><?php the_title(); ?></h1>
			</header>

			<div class="page-content__body">
				<?php the_content(); ?>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
