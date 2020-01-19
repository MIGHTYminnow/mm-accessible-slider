<?php
/**
 * Plugin Name: MM Accessible Slider
 * Description: Accessible Slider by MIGHTYminnow.
 * Version: 1.2.0-beta
 * Author: MIGHTYminnow
 * Author URI: https://mightyminnow.com
 */

define( 'MM_ACCESSIBLE_SLIDER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'MM_ACCESSIBLE_SLIDER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

require_once( MM_ACCESSIBLE_SLIDER_PLUGIN_DIR . 'includes/class-mm-accessible-slider.php' );

MM_Accessible_Slider::init();
