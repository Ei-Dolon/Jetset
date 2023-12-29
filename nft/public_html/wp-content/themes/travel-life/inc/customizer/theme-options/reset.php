<?php
/**
 * Reset options
 *
 * @package Theme Palace
 * @subpackage Travel Life
 * @since Travel Life 1.0.0
 */

/**
* Reset section
*/
// Add reset enable section
$wp_customize->add_section( 'travel_life_reset_section', array(
	'title'             => esc_html__('Reset all settings','travel-life'),
	'description'       => esc_html__( 'Caution: All settings will be reset to default. Refresh the page after clicking Save & Publish.', 'travel-life' ),
) );

// Add reset enable setting and control.
$wp_customize->add_setting( 'travel_life_theme_options[reset_options]', array(
	'default'           => $options['reset_options'],
	'sanitize_callback' => 'travel_life_sanitize_checkbox',
	'transport'			  => 'postMessage',
) );

$wp_customize->add_control( 'travel_life_theme_options[reset_options]', array(
	'label'             => esc_html__( 'Check to reset all settings', 'travel-life' ),
	'section'           => 'travel_life_reset_section',
	'type'              => 'checkbox',
) );
