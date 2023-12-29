<?php
/**
 * Footer options
 *
 * @package Theme Palace
 * @subpackage Next Travel
 * @since Next Travel 1.0.0
 */

// Footer Section
$wp_customize->add_section( 'next_travel_section_footer',
	array(
		'title'      			=> esc_html__( 'Footer Options', 'next-travel' ),
		'priority'   			=> 900,
		'panel'      			=> 'next_travel_theme_options_panel',
	)
);

// footer image setting and control.
$wp_customize->add_setting( 'next_travel_theme_options[footer_logo]', array(
	'sanitize_callback' => 'next_travel_sanitize_image'
	) );

$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'next_travel_theme_options[footer_logo]',
	array(
		'label'       		=> esc_html__( 'Site Info Logo', 'next-travel' ),
		'section'     		=> 'next_travel_section_footer',
		) ) );

// footer text
$wp_customize->add_setting( 'next_travel_theme_options[copyright_text]',
	array(
		'default'       		=> $options['copyright_text'],
		'sanitize_callback'		=> 'next_travel_santize_allow_tag',
		'transport'				=> 'postMessage',
	)
);
$wp_customize->add_control( 'next_travel_theme_options[copyright_text]',
    array(
		'label'      			=> esc_html__( 'Copyright Text', 'next-travel' ),
		'section'    			=> 'next_travel_section_footer',
		'type'		 			=> 'textarea',
    )
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'next_travel_theme_options[copyright_text]', array(
		'selector'            => '.site-info span.copyright',
		'settings'            => 'next_travel_theme_options[copyright_text]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'next_travel_copyright_text_partial',
    ) );
}