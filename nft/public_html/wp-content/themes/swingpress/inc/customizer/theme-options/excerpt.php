<?php
/**
 * Excerpt options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

// Add excerpt section
$wp_customize->add_section( 'swingpress_excerpt_section', array(
	'title'             => esc_html__( 'Excerpt','swingpress' ),
	'description'       => esc_html__( 'Excerpt section options.', 'swingpress' ),
	'panel'             => 'swingpress_theme_options_panel',
) );


// long Excerpt length setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[long_excerpt_length]', array(
	'sanitize_callback' => 'swingpress_sanitize_number_range',
	'validate_callback' => 'swingpress_validate_long_excerpt',
	'default'			=> $options['long_excerpt_length'],
) );

$wp_customize->add_control( 'swingpress_theme_options[long_excerpt_length]', array(
	'label'       		=> esc_html__( 'Blog Page Excerpt Length', 'swingpress' ),
	'description' 		=> esc_html__( 'Total words to be displayed in archive page/search page.', 'swingpress' ),
	'section'     		=> 'swingpress_excerpt_section',
	'type'        		=> 'number',
	'input_attrs' 		=> array(
		'style'       => 'width: 80px;',
		'max'         => 100,
		'min'         => 5,
	),
) );
