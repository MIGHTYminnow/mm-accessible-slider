<?php
class MM_Accessible_Slider {
	public static function init() {
		require_once( MM_ACCESSIBLE_SLIDER_PLUGIN_DIR . 'includes/admin/class-mmas-customizer.php' );

		MMAS_Customizer::init();

		add_action( 'wp_enqueue_scripts', array( 'MM_Accessible_Slider', 'enqueue_assets' ) );
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
}
