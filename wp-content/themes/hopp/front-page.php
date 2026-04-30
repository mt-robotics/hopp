<?php
/**
 * Front page template for the HOPP theme.
 */

get_header();
?>

<main>
	<section class="hero">
		<div class="hero__content">
			<p class="hero__eyebrow"><?php esc_html_e( 'Humans of Phnom Penh', 'hopp' ); ?></p>
			<h1><?php esc_html_e( 'Every person has a story worth sharing.', 'hopp' ); ?></h1>
			<p>
				<?php esc_html_e( 'A cultural storytelling platform capturing the people, places, and creative pulse of Phnom Penh.', 'hopp' ); ?>
			</p>
		</div>
	</section>
</main>

<?php
get_footer();
