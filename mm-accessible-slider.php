<?php
/**
 * Plugin Name: MM Accessible Slider
 * Description: Accessible Slider by MIGHTYminnow.
 * Version: 1.0.0
 * Author: MIGHTYminnow
 * Author URI: https://mightyminnow.com
 */

add_action( 'wp_enqueue_scripts', function(){
	$dir_url = plugin_dir_url( __FILE__ );
	wp_enqueue_style( 'mm-accessible-slider', "{$dir_url}mm-accessible-slider.css", array(), '1.0-beta' );
	wp_enqueue_script( 'mm-accessible-slider', "{$dir_url}mm-accessible-slider.js", array( 'jquery' ), '1.0-beta', true );
});
