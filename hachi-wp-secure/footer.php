<?php
/**
 * HACHI Theme — footer.php
 */
?>

<!-- ===== SITE FOOTER ===== -->
<footer class="site-footer" role="contentinfo">
	<div class="container">

		<!-- Footer Navigation -->
		<nav class="footer-nav" aria-label="<?php esc_attr_e( 'Footer Navigation', 'hachi' ); ?>">

			<div class="footer-nav__col">
				<p class="footer-nav__heading">Navigation</p>
				<?php
				$nav_links = [
					home_url( '/' )          => __( 'Home', 'hachi' ),
					home_url( '/about/' )    => __( 'About', 'hachi' ),
					home_url( '/service/' )  => __( 'Service', 'hachi' ),
					home_url( '/company/' )  => __( 'Company', 'hachi' ),
					home_url( '/news/' )     => __( 'News', 'hachi' ),
					home_url( '/contact/' )  => __( 'Contact', 'hachi' ),
				];
				foreach ( $nav_links as $url => $label ) :
					printf(
						'<a href="%s" class="footer-nav__link">%s</a>',
						esc_url( $url ),
						esc_html( $label )
					);
				endforeach;
				?>
			</div>

			<div class="footer-nav__col">
				<p class="footer-nav__heading">Service</p>
				<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="footer-nav__link">コンディション・インサイト</a>
			</div>

			<div class="footer-nav__col">
				<p class="footer-nav__heading">Company</p>
				<a href="<?php echo esc_url( home_url( '/company/' ) ); ?>" class="footer-nav__link"><?php _e( '会社概要', 'hachi' ); ?></a>
			</div>

			<div class="footer-nav__col">
				<p class="footer-nav__heading">Legal</p>
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>" class="footer-nav__link"><?php _e( 'プライバシーポリシー', 'hachi' ); ?></a>
			</div>

		</nav>

		<!-- Footer bottom bar -->
		<div class="footer-bottom">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-logo" aria-label="<?php esc_attr_e( 'HACHI ホームへ', 'hachi' ); ?>">
				HACHI
			</a>

			<small class="footer-copyright">
				&copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. All Rights Reserved.
			</small>

			<div class="footer-social">
				<?php
				$social_links = [
					'X (Twitter)' => 'https://x.com/PACEathlete',
				];
				foreach ( $social_links as $label => $url ) :
					?>
					<a
						href="<?php echo esc_url( $url ); ?>"
						class="footer-social__link"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $label ); ?>"
					>
						<!-- X icon -->
						<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
							<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
						</svg>
					</a>
				<?php endforeach; ?>
			</div>
		</div>

		<p class="footer-note"><?php echo esc_html( hachi_cd_footer_note() ); ?></p>

	</div>
</footer>
<!-- /SITE FOOTER -->

<?php wp_footer(); ?>
</body>
</html>
