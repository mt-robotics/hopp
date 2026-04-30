<?php
/**
 * Main fallback template for the HOPP theme.
 *
 * This file keeps the theme valid while the real templates are built.
 */

get_header();
?>

<main>
	<h1><?php bloginfo( 'name' ); ?></h1>
	<p>HOPP theme is active.</p>
</main>

<?php
get_footer();
