<?php
/**
 * Counter Section options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add Counter section
$wp_customize->add_section( 'travel_life_counter_section', array(
	'title'             => esc_html__( 'Counter','travel-life' ),
	'description'       => esc_html__( 'Counter Section options.', 'travel-life' ),
	'panel'             => 'travel_life_front_page_panel',
) );

// Counter content enable control and setting
$wp_customize->add_setting( 'travel_life_theme_options[counter_section_enable]', array(
	'default'			=> 	$options['counter_section_enable'],
	'sanitize_callback' => 'travel_life_sanitize_switch_control',
) );

$wp_customize->add_control( new Travel_Life_Switch_Control( $wp_customize, 'travel_life_theme_options[counter_section_enable]', array(
	'label'             => esc_html__( 'Counter Section Enable', 'travel-life' ),
	'section'           => 'travel_life_counter_section',
	'on_off_label' 		=> travel_life_switch_options(),
) ) );


if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[counter_section_enable]', array(
		'selector'            => '#counter-section .tooltiptext',
		'settings'            => 'travel_life_theme_options[counter_section_enable]',
    ) );
}


for ( $i = 1; $i <= 5; $i++ ) :

	// counter custom date
	$wp_customize->add_setting( 'travel_life_theme_options[counter_text_' . $i . ']', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'travel_life_theme_options[counter_text_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Title %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_counter_section',
		'active_callback'	=> 'travel_life_is_counter_section_enable',
	) );

	// counter custom button
	$wp_customize->add_setting( 'travel_life_theme_options[counter_value_' . $i . ']', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );

	$wp_customize->add_control( 'travel_life_theme_options[counter_value_' . $i . ']', array(
		'label'             => sprintf( esc_html__( 'Value %d', 'travel-life' ), $i ),
		'section'           => 'travel_life_counter_section',
		'active_callback'	=> 'travel_life_is_counter_section_enable',
	) );

	// Separator setting
	$wp_customize->add_setting( 'travel_life_theme_options[counter_separator_' . $i . ']', array(
		'sanitize_callback' => 'wp_kses_post',
	) );

	$wp_customize->add_control( new Travel_Life_Note_Control( $wp_customize, 'travel_life_theme_options[counter_separator_' . $i . ']', array(
		'section'           => 'travel_life_counter_section',
		'active_callback'	=> 'travel_life_is_counter_section_enable',
		'type'				=> 'custom-html',
	) ) );
endfor;

