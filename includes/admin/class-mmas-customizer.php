<?php
class MMAS_Customizer {
	public static function register( $wp_customize ) {
		$wp_customize->add_section( 'mmas_section', array(
			'title' => __( 'MM Accessible Slider', 'mmas' ),
		) );

		$wp_customize->add_setting( 'mmas_thumb_nav', array(
			'default' => false,
			'type' => 'option',
			'sanitize_callback' => array( 'MMAS_Customizer', 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'mmas_thumb_nav_control', array(
			'label' => __( 'Hide Thumbnails Navigation', 'mmas' ),
			'section' => 'mmas_section',
			'type' => 'checkbox',
			'settings' => 'mmas_thumb_nav',
		) );
	}

	public static function sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true == $checked ) ? true : false );
	}
}
