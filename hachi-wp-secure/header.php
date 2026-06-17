<?php
/**
 * HACHI Theme — header.php
 * The header template for this theme.
 * @package HACHI
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a href="#main-content" class="skip-link"><?php _e( '本文へスキップ', 'hachi' ); ?></a>

<!-- ===== SCROLL PROGRESS ===== -->
<div id="scroll-progress" aria-hidden="true"></div>

<!-- ===== PAGE LOADER ===== -->
<div id="hachi-loader" role="status" aria-label="読み込み中">
	<div class="loader__logo" aria-hidden="true">
		<?php
		$chars = str_split( 'HACHI' );
		foreach ( $chars as $char ) {
			echo '<span class="loader__logo-char">' . esc_html( $char ) . '</span>';
		}
		?>
	</div>
	<p class="loader__tagline">Condition Insight.</p>
	<div class="loader__bar" aria-hidden="true"></div>
</div>

<!-- ===== PAGE TRANSITION CURTAIN ===== -->
<div id="page-curtain" aria-hidden="true"></div>

<!-- ===== MOBILE DRAWER ===== -->
<nav class="nav-drawer" id="nav-drawer" role="dialog" aria-modal="true" aria-label="<?php esc_attr_e( 'ナビゲーションメニュー', 'hachi' ); ?>">
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="nav-drawer__item"><?php _e( 'Home', 'hachi' ); ?></a>
	<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="nav-drawer__item"><?php _e( 'About Us', 'hachi' ); ?></a>
	<a href="<?php echo esc_url( home_url( '/service/' ) ); ?>" class="nav-drawer__item"><?php _e( 'Service', 'hachi' ); ?></a>
	<a href="<?php echo esc_url( home_url( '/company/' ) ); ?>" class="nav-drawer__item"><?php _e( 'Company', 'hachi' ); ?></a>
	<a href="<?php echo esc_url( home_url( '/news/' ) ); ?>" class="nav-drawer__item"><?php _e( 'News', 'hachi' ); ?></a>
	<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-drawer__item"><?php _e( 'Contact', 'hachi' ); ?></a>
</nav>

<!-- ===== SITE HEADER ===== -->
<header class="site-header" id="site-header" role="banner">
	<div class="site-header__inner">

		<!-- Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo esc_html( get_bloginfo( 'name' ) ?: 'HACHI' );
			}
			?>
		</a>

		<!-- Primary navigation -->
		<nav class="primary-nav" aria-label="<?php esc_attr_e( 'Primary Navigation', 'hachi' ); ?>">
			<?php
			$pages = [
				''          => __( 'Home', 'hachi' ),
				'about'     => __( 'About Us', 'hachi' ),
				'service'   => __( 'Service', 'hachi' ),
				'company'   => __( 'Company', 'hachi' ),
				'news'      => __( 'News', 'hachi' ),
			];

			$current_slug = trim( parse_url( $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH ), '/' );
			$path_parts   = explode( '/', $current_slug );
			$current_top  = $path_parts[0] ?? '';

			foreach ( $pages as $slug => $label ) :
				$href       = home_url( $slug ? "/{$slug}/" : '/' );
				$is_current = ( $slug === '' && is_front_page() ) || ( $slug !== '' && $current_top === $slug );
				$aria       = $is_current ? ' aria-current="page"' : '';
				$class      = 'primary-nav__item' . ( $is_current ? ' is-current' : '' );
				printf(
					'<a href="%s" class="%s"%s>%s</a>',
					esc_url( $href ),
					esc_attr( $class ),
					$aria,
					esc_html( $label )
				);
			endforeach;
			?>

			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="nav-cta">
				<?php _e( 'Contact', 'hachi' ); ?>
			</a>
		</nav>

		<!-- Hamburger toggle -->
		<button
			class="hamburger"
			id="hamburger-btn"
			aria-expanded="false"
			aria-controls="nav-drawer"
			aria-label="<?php esc_attr_e( 'メニューを開く', 'hachi' ); ?>"
		>
			<span class="hamburger__line"></span>
			<span class="hamburger__line"></span>
			<span class="hamburger__line"></span>
		</button>

	</div>
</header>
<!-- /SITE HEADER -->
