<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'travel_life_excerpt_section', array(
	'title'             => esc_html__( 'Excerpt','travel-life' ),
	'description'       => esc_html__( 'Excerpt section options.', 'travel-life' ),
	'panel'             => 'travel_life_theme_options_panel',
) );


// long Excerpt length setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[long_excerpt_length]', array(
	'sanitize_callback' => 'travel_life_sanitize_number_range',
	'validate_callback' => 'travel_life_validate_long_excerpt',
	'default'			=> $options['long_excerpt_length'],
) );

$wp_customize->add_control( 'travel_life_theme_options[long_excerpt_length]', array(
	'label'       		=> esc_html__( 'Blog Page Excerpt Length', 'travel-life' ),
	'description' 		=> esc_html__( 'Total words to be displayed in archive page/search page.', 'travel-life' ),
	'section'     		=> 'travel_life_excerpt_section',
	'type'        		=> 'number',
	'input_attrs' 		=> array(
		'style'       => 'width: 80px;',
		'max'         => 100,
		'min'         => 5,
	),
) );
