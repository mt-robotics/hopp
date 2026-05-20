<footer class="site-footer">
	<?php $site_description = get_bloginfo( 'description' ); ?>
	<div class="site-footer__main">
		<div class="site-footer__intro">
			<a class="site-footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Humans of Phnom Penh', 'hopp' ); ?>
			</a>
			<p><?php echo esc_html( '' !== trim( $site_description ) ? $site_description : __( 'Stories, portraits, art, and community moments from Phnom Penh.', 'hopp' ) ); ?></p>
		</div>

		<div class="site-footer__links">
			<nav aria-label="<?php esc_attr_e( 'Footer menu', 'hopp' ); ?>">
				<?php hopp_render_footer_menu( 'footer', 'primary' ); ?>
			</nav>
		</div>

		<div class="site-footer__social">
			<?php hopp_render_footer_social_menu(); ?>
		</div>
	</div>

	<div class="site-footer__bar">
		<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php esc_html_e( 'Humans of Phnom Penh', 'hopp' ); ?> <span aria-hidden="true">|</span> <a href="https://macrosolutions.asia/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Designed by Macro Solutions', 'hopp' ); ?></a></p>
		<?php hopp_render_footer_legal_menu(); ?>
	</div>
</footer>

<a class="return-to-top" href="#top" aria-label="<?php esc_attr_e( 'Return to top', 'hopp' ); ?>">
	<svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
		<path d="M12 5 5.5 11.5l1.4 1.4 4.1-4.1V20h2V8.8l4.1 4.1 1.4-1.4L12 5z"></path>
	</svg>
</a>

<?php wp_footer(); ?>
</body>
</html>
