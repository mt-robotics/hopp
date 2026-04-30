<?php
/**
 * 404 template for the HOPP theme.
 */

get_header();
?>

<main class="error-content">
	<section class="error-content__inner">
		<p class="error-content__eyebrow"><?php esc_html_e( '404', 'hopp' ); ?></p>
		<h1><?php esc_html_e( 'Page not found', 'hopp' ); ?></h1>
		<p><?php esc_html_e( 'The page you are looking for does not exist or has been moved.', 'hopp' ); ?></p>
		<a class="button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>">
			<?php esc_html_e( 'Return Home', 'hopp' ); ?>
		</a>
	</section>
</main>

<?php
get_footer();
