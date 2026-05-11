<footer class="site-footer">
	<div class="site-footer__main">
		<div>
			<a class="site-footer__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<?php esc_html_e( 'Humans of Phnom Penh', 'hopp' ); ?>
			</a>
			<p><?php esc_html_e( 'Stories, portraits, art, and community moments from Phnom Penh.', 'hopp' ); ?></p>
		</div>

		<nav aria-label="<?php esc_attr_e( 'Footer menu', 'hopp' ); ?>">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'hopp' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/about-us/' ) ); ?>"><?php esc_html_e( 'About Us', 'hopp' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/stories/' ) ); ?>"><?php esc_html_e( 'Stories', 'hopp' ); ?></a>
			<a href="<?php echo esc_url( home_url( '/contact-us/' ) ); ?>"><?php esc_html_e( 'Get in Touch', 'hopp' ); ?></a>
		</nav>

		<div class="site-footer__social">
			<a href="https://www.facebook.com/humansofphnompenh/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'hopp' ); ?>">Fb</a>
			<a href="https://www.instagram.com/humansofphnompenhofficial/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'hopp' ); ?>">Ig</a>
			<a href="https://www.linkedin.com/company/humansofphnompenh/" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'hopp' ); ?>">In</a>
			<a href="https://t.me/humansofpp" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Telegram', 'hopp' ); ?>">Tg</a>
			<a href="https://x.com/HoPP_Kh" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'X (Twitter)', 'hopp' ); ?>">𝕏</a>
		</div>
	</div>

	<div class="site-footer__bar">
		<p>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php esc_html_e( 'Humans of Phnom Penh', 'hopp' ); ?> | <a href="https://macrosolutions.asia/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Designed by Macro Solutions', 'hopp' ); ?></a></p>
		<p><?php esc_html_e( 'Privacy Policy / Terms and Conditions', 'hopp' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
