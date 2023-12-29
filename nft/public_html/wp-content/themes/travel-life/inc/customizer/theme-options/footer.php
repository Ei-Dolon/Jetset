<?php
/**
 * Footer options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

// Footer Section
$wp_customize->add_section( 'travel_life_section_footer',
	array(
		'title'      			=> esc_html__( 'Footer Options', 'travel-life' ),
		'priority'   			=> 900,
		'panel'      			=> 'travel_life_theme_options_panel',
	)
);

// footer text
$wp_customize->add_setting( 'travel_life_theme_options[copyright_text]',
	array(
		'default'       		=> $options['copyright_text'],
		'sanitize_callback'		=> 'travel_life_santize_allow_tag',
		'transport'				=> 'postMessage',
	)
);
$wp_customize->add_control( 'travel_life_theme_options[copyright_text]',
    array(
		'label'      			=> esc_html__( 'Copyright Text', 'travel-life' ),
		'section'    			=> 'travel_life_section_footer',
		'type'		 			=> 'textarea',
    )
);

// Abort if selective refresh is not available.
if ( isset( $wp_customize->selective_refresh ) ) {
    $wp_customize->selective_refresh->add_partial( 'travel_life_theme_options[copyright_text]', array(
		'selector'            => '.site-info .copyright p',
		'settings'            => 'travel_life_theme_options[copyright_text]',
		'container_inclusive' => false,
		'fallback_refresh'    => true,
		'render_callback'     => 'travel_life_copyright_text_partial',
    ) );
}