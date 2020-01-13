<?php
class MM_Accessible_Slider {
	public static function init() {
		add_action( 'wp_enqueue_scripts', array( 'MM_Accessible_Slider', 'enqueue_assets' ) );
	}

	public static function enqueue_assets() {
		wp_enqueue_style( 'mm-accessible-slider', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/css/mm-accessible-slider.css', array(), '1.0.0' );
		wp_enqueue_script( 'mm-accessible-slider', MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/js/mm-accessible-slider.js', array( 'jquery' ), '1.0.0', true );
	}
}
