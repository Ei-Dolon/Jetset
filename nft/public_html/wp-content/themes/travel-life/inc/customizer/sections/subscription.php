<?php
/**
 * Subscription Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add Subscription section
$wp_customize->add_section( 'travel_life_subscription_section', array(
	'title'             => esc_html__( 'Subscription','travel-life' ),
	'description'       => esc_html__( 'Note: To activate this section you need to install Jetpack Plugin and activate subscription module.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Subscription content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[subscription_section_enable]', array(
	'default'			=> 	$options['subscription_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[subscription_section_enable]', array(
	'label'             => esc_html__( 'Subscription Section Enable', 'travel-life' ),
	'section'           => 'travel_life_subscription_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );

if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[subscription_section_enable]', array(
		'selector'            => '#subscribe-now .tooltiptext',
		'settings'            => 'travel_life_theme_options[subscription_section_enable]',
    ) );
}


// subscription title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[subscription_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['subscription_title'],
	'transport'			=> 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[subscription_title]', array(
	'label'           	=> esc_html__( 'Title', 'travel-life' ),
	'section'        	=> 'travel_life_subscription_section',
	'active_callback' 	=> 'travel_life_is_subscription_section_enable',
	'type'				=> 'text',
) );

// subscription image setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[subscription_image]', array(
	'sanitize_callback' => 'travel_life_sanitize_image'
) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'travel_life_theme_options[subscription_image]',
		array(
		'label'       		=> esc_html__( 'Background Image', 'travel-life' ),
		'description' 		=> sprintf( esc_html__( 'Recommended size: %1$dpx x %2$dpx ', 'travel-life' ), 1920, 520 ),
		'section'     		=> 'travel_life_subscription_section',
		'active_callback'	=> 'travel_life_is_subscription_section_enable',
) ) );

// subscription btn title setting and control
$wp_customize->add_setting( 'travel_life_theme_options[subscription_btn_title]', array(
	'sanitize_callback' => 'sanitize_text_field',
	'default'			=> $options['subscription_btn_title'],
) );

$wp_customize->add_control( 'travel_life_theme_options[subscription_btn_title]', array(
	'label'           	=> esc_html__( 'Button Label', 'travel-life' ),
	'section'        	=> 'travel_life_subscription_section',
	'active_callback' 	=> 'travel_life_is_subscription_section_enable',
	'type'				=> 'text',
) );

$wp_customize->add_setting(	
	'travel_life_theme_options[subscription_btn_color]',
	array(
		'sanitize_callback' => 'sanitize_hex_color',
	)
);


$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'travel_life_theme_options[subscription_btn_color]',
		array(
			'section'		=> 'travel_life_subscription_section',
			'label'			=> esc_html__( 'Btn Color', 'travel-life' ),

		)
	)
);

$wp_customize->add_setting(	
	'travel_life_theme_options[subscription_btn_hover_color]',
	array(
		'sanitize_callback' => 'sanitize_hex_color',
	)
);


$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'travel_life_theme_options[subscription_btn_hover_color]',
		array(
			'section'		=> 'travel_life_subscription_section',
			'label'			=> esc_html__( 'Btn Hover Color', 'travel-life' ),

		)
	)
); 

$wp_customize->add_setting(	
	'travel_life_theme_options[subscription_btn_text_color]',
	array(
		'sanitize_callback' => 'sanitize_hex_color',
	)
);


$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'travel_life_theme_options[subscription_btn_text_color]',
		array(
			'section'		=> 'travel_life_subscription_section',
			'label'			=> esc_html__( 'Btn Text Color', 'travel-life' ),

		)
	)
);

$wp_customize->add_setting(	
	'travel_life_theme_options[subscription_btn_text_hover_color]',
	array(
		'sanitize_callback' => 'sanitize_hex_color',
	)
);


$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'travel_life_theme_options[subscription_btn_text_hover_color]',
		array(
			'section'		=> 'travel_life_subscription_section',
			'label'			=> esc_html__( 'Btn Text Hover Color', 'travel-life' ),

		)
	)
); 

$wp_customize->add_setting(	
	'travel_life_theme_options[subscription_btn_border_color]',
	array(
		'sanitize_callback' => 'sanitize_hex_color',
	)
);


$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'travel_life_theme_options[subscription_btn_border_color]',
		array(
			'section'		=> 'travel_life_subscription_section',
			'label'			=> esc_html__( 'Btn Border Color', 'travel-life' ),

		)
	)
);

$wp_customize->add_setting(	
	'travel_life_theme_options[subscription_btn_border_hover_color]',
	array(
		'sanitize_callback' => 'sanitize_hex_color',
	)
);


$wp_customize->add_control(
	new WP_Customize_Color_Control( 
	$wp_customize,
		'travel_life_theme_options[subscription_btn_border_hover_color]',
		array(
			'section'		=> 'travel_life_subscription_section',
			'label'			=> esc_html__( 'Btn Border Hover Color', 'travel-life' ),

		)
	)
); 