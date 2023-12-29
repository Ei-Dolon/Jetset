<?php

    /**
    * Compatibility for Plugin Name: Oxygen
    * Since:            3.1
    * Last checked:     4.1.1
    */
    
    if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    class WPH_conflict_handle_oxygen
        {
            var $wph;
                                        
            function __construct()
                {
                    if( !   $this->is_plugin_active() )
                        return FALSE;
                        
                    global $wph;
                    
                    $this->wph  =   $wph;
                    
                    add_filter( 'plugins_loaded',                        array( $this,   'plugins_loaded'), 999 );
                    
                    if ( isset ( $_GET['ct_builder'] ) )
                        {
                            $WPH_module_general_html    =   $this->wph->functions->return_component_instance( 'WPH_module_general_html' );
                            remove_filter('wp-hide/ob_start_callback', array( $WPH_module_general_html, 'remove_html_new_lines'));
                        }
                }                        
            
            function is_plugin_active()
                {
                    
                    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
                    
                    if(is_plugin_active( 'oxygen/functions.php' ))
                        return TRUE;
                        else
                        return FALSE;
                }
                
                
            
            function plugins_loaded()
                {
                    
                    include ('oxygen-class.php');    
                    
                    global $oxygen_signature;
                    
                    $oxygen_signature = new WPH_OXYGEN_VSB_Signature();
                }
                
                            
        }


        new WPH_conflict_handle_oxygen();
        
        
        

?>