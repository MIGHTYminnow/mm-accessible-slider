<?php
class MM_Accessible_Slider {
	public static function init() {
		require_once( MM_ACCESSIBLE_SLIDER_PLUGIN_DIR . 'includes/admin/class-mmas-customizer.php' );

		add_action( 'wp_enqueue_scripts', array( 'MM_Accessible_Slider', 'enqueue_assets' ) );
		add_action( 'customize_register', array( 'MMAS_Customizer', 'register' ) );
	}

	public static function enqueue_assets() {
		wp_enqueue_style( 'mm-accessible-slider', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/css/mm-accessible-slider.css', array(), '1.0.0' );
		wp_enqueue_script( 'mm-accessible-slider', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/js/mm-accessible-slider.js', array( 'jquery' ), '1.0.0', true );
		wp_localize_script( 'mm-accessible-slider', 'MMAS', array( 'settings' => array(
			'id' => 'c',
			'slidenav' => (boolean) ! get_option( 'mmas_thumb_nav', false ),
		) ) );
	}
}
