<?php
class MM_Accessible_Slider {
	public static function init() {
		require_once( MM_ACCESSIBLE_SLIDER_PLUGIN_DIR . 'includes/admin/class-mmas-customizer.php' );

		MMAS_Customizer::init();

		add_action( 'wp_enqueue_scripts', array( 'MM_Accessible_Slider', 'enqueue_assets' ) );
		add_action( 'wp_get_custom_css', array( 'MM_Accessible_Slider', 'print_custom_styles' ), 10, 2 );
	}

	public static function enqueue_assets() {
		if ( ! wp_style_is( 'fontawesome', 'registered' ) ) {
			wp_register_style( 'fontawesome', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'libraries/fontawesome/css/all.min.css', array(), '5.12.0' );
		}
		wp_enqueue_style( 'mm-accessible-slider', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/css/mm-accessible-slider.css', array( 'fontawesome' ), '1.0.0' );
		wp_enqueue_script( 'mm-accessible-slider', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/js/mm-accessible-slider.js', array( 'jquery' ), '1.0.0', true );
		$autoplay = (boolean) get_option( 'mmas_autoplay', 0 );
		wp_localize_script( 'mm-accessible-slider', 'MMAS', array( 'settings' => array(
			'id' => 'c',
			'slidenav' => (boolean) get_option( 'mmas_thumb_nav', 1 ),
			'animate' => $autoplay,
			'startAnimated' => $autoplay,
			'delay' => absint( get_option( 'mmas_delay', 8000 ) ),
		) ) );
	}

	public static function print_custom_styles( $css, $stylesheet ) {
		ob_start();
		?>
		.mm-accessible-slider .mm-slide {
			background: <?php echo get_option( 'mmas_bg', '#f5f5f5' ); ?>; 
		}
		.mm-accessible-slider .mm-slide .mm-slide-content a{
			color: <?php echo get_option( 'mmas_button_color', '#FFFFFF' ); ?>;
			background: <?php echo get_option( 'mmas_button_bg', '#058588' ); ?>;
		}
		.mm-accessible-slider .mm-slide .mm-slide-content a:hover,
		.mm-accessible-slider .mm-slide .mm-slide-content a:focus{
			color: <?php echo get_option( 'mmas_button_hover_color', '#FFFFFF' ); ?>;
			background: <?php echo get_option( 'mmas_button_hover_bg', '#262626' ); ?>;
		}
		<?php
		$css .= ob_get_clean();

		return $css;
	}
}
