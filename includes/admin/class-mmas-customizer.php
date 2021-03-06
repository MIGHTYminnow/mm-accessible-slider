<?php
class MMAS_Customizer {
	public static function init() {
		add_action( 'customize_register', array( 'MMAS_Customizer', 'register' ) );
		add_action( 'customize_controls_enqueue_scripts', array( 'MMAS_Customizer', 'enqueue_script' ) );
	}

	public static function register( $wp_customize ) {
		$wp_customize->add_section( 'mmas_section', array(
			'title' => __( 'MM Accessible Slider', 'mmas' ),
		) );

		$wp_customize->add_setting( 'mmas_thumb_nav', array(
			'default' => 1,
			'type' => 'option',
			'sanitize_callback' => array( 'MMAS_Customizer', 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'mmas_thumb_nav_control', array(
			'label' => __( 'Thumbnails Navigation', 'mmas' ),
			'section' => 'mmas_section',
			'type' => 'checkbox',
			'settings' => 'mmas_thumb_nav',
		) );

		$wp_customize->add_setting( 'mmas_autoplay', array(
			'default' => 0,
			'type' => 'option',
			'sanitize_callback' => array( 'MMAS_Customizer', 'sanitize_checkbox' ),
		) );

		$wp_customize->add_control( 'mmas_autoplay_control', array(
			'label' => __( 'Autoplay', 'mmas' ),
			'section' => 'mmas_section',
			'type' => 'checkbox',
			'settings' => 'mmas_autoplay',
		) );

		$wp_customize->add_setting( 'mmas_delay', array(
			'default' => 8000,
			'type' => 'option',
			'sanitize_callback' => 'absint',
		) );

		$wp_customize->add_control( 'mmas_delay_control', array(
			'label' => __( 'Delay', 'mmas' ),
			'description' => __( 'Delay between transitions (in ms).', 'mmas' ),
			'section' => 'mmas_section',
			'type' => 'number',
			'settings' => 'mmas_delay',
		) );

		$wp_customize->add_setting( 'mmas_bg', array(
			'default' => '#f5f5f5',
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'mmas_bg_control',
			array(
				'label' => __( 'Background Color', 'mmas' ),
				'section' => 'mmas_section',
				'settings' => 'mmas_bg',
			)
		) );

		$wp_customize->add_setting( 'mmas_button_bg', array(
			'default' => '#058588',
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'mmas_button_bg_control',
			array(
				'label' => __( 'Button Background Color', 'mmas' ),
				'section' => 'mmas_section',
				'settings' => 'mmas_button_bg',
			)
		) );

		$wp_customize->add_setting( 'mmas_button_color', array(
			'default' => '#FFFFFF',
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'mmas_button_color_control',
			array(
				'label' => __( 'Button Text Color', 'mmas' ),
				'section' => 'mmas_section',
				'settings' => 'mmas_button_color',
			)
		) );

		$wp_customize->add_setting( 'mmas_button_hover_bg', array(
			'default' => '#262626',
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'mmas_button_hover_bg_control',
			array(
				'label' => __( 'Button Hover Background Color', 'mmas' ),
				'section' => 'mmas_section',
				'settings' => 'mmas_button_hover_bg',
			)
		) );

		$wp_customize->add_setting( 'mmas_button_hover_color', array(
			'default' => '#FFFFFF',
			'type' => 'option',
			'sanitize_callback' => 'sanitize_hex_color',
		) );

		$wp_customize->add_control( new WP_Customize_Color_Control(
			$wp_customize,
			'mmas_button_hover_color_control',
			array(
				'label' => __( 'Button Hover Text Color', 'mmas' ),
				'section' => 'mmas_section',
				'settings' => 'mmas_button_hover_color',
			)
		) );
	}

	public static function sanitize_checkbox( $checked ) {
		return ( ( isset( $checked ) && true == $checked ) ? 1 : 0 );
	}

	public static function enqueue_script() {
		wp_enqueue_script(
			'maas-customizer',
			MM_ACCESSIBLE_SLIDER_PLUGIN_URL . 'assets/js/customizer.js',
			array( 'jquery' ),
			null,
			true
		);
	}
}
