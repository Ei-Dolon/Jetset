<?php
/**
 * Reset options
 *
 * @package Theme Palace
 * @subpackage Swingpress
 * @since Swingpress 1.0.0
 */

/**
* Reset section
*/
// Add reset enable section
$wp_customize->add_section( 'swingpress_reset_section', array(
	'title'             => esc_html__('Reset all settings','swingpress'),
	'description'       => esc_html__( 'Caution: All settings will be reset to default. Refresh the page after clicking Save & Publish.', 'swingpress' ),
) );

// Add reset enable setting and control.
$wp_customize->add_setting( 'swingpress_theme_options[reset_options]', array(
	'default'           => $options['reset_options'],
	'sanitize_callback' => 'swingpress_sanitize_checkbox',
	'transport'			  => 'postMessage',
) );

$wp_customize->add_control( 'swingpress_theme_options[reset_options]', array(
	'label'             => esc_html__( 'Check to reset all settings', 'swingpress' ),
	'section'           => 'swingpress_reset_section',
	'type'              => 'checkbox',
) );
