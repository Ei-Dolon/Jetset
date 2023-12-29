<?php

add_action( 'wp_footer', 'youzify_add_bp_uploader_js' );

function youzify_add_bp_uploader_js() {

    if ( ! bp_is_my_profile() ) {
        return;
    }

    ?>

    <script type="text/javascript">

        ( function( $ ) {

		    $( document ).ready( function() {

		        $( 'body' ).on( 'click', '.youzify-bp-close-uploader', function( e ) {

		            e.preventDefault();

		            $( 'body' ).removeClass( 'youzify-modal-overlay-active' );

		            let parent = $( this ).closest( '.youzify-bp-uploader-popup' );

		            parent.fadeOut(function() {
		                $( this ).remove();
		            });

		            $( 'script.youzify-uploader-element' ).each( function( x, y ) {
		                eval( 'delete ' + $( this ).attr( 'object' ) );
		                $( this ).remove();
		            });

		        });

		        $( document ).on( 'click', '.youzify-open-bp-uploader', function( e ) {

		            e.preventDefault();
		            e.stopImmediatePropagation();

		            let button = $( this ),
		                type = button.attr( 'data-type' );

	                if ( typeof bp !== 'undefined' ) {
	                    if ( type == 'avatar' ) {
	                        if ( typeof YZ_Avatar_Uploader !== 'undefined' ) {
	                            BP_Uploader = YZ_Avatar_Uploader;
	                        }
	                    } else if ( type == 'cover' ) {
	                        if ( typeof YZ_Cover_Uploader !== 'undefined' ) {
	                            BP_Uploader = YZ_Cover_Uploader;
	                        }
	                    }
	                }


		            if ( button.hasClass( 'youzify-is-loading' ) )  {
		                return;
		            }

		            button.addClass( 'youzify-is-loading' );

		            let icon_class = button.find( 'i' ).attr( 'class' );

		            button.find( 'i' ).attr( 'class', 'fas fa-spinner fa-spin' );

		            var data = {
		                action: 'youzify_get_buddypress_uploader',
		                type: button.attr( 'data-type' )
		            };


		            $.post( ajaxurl, data, function( response ) {

		                button.removeClass( 'youzify-is-loading' );

		                button.find( 'i' ).attr( 'class', icon_class );

		                if ( response.success ) {

		                    $( 'body' ).addClass( 'youzify-modal-overlay-active' );

		                    let form = $( response.data.form );

		                    form.find( 'h2, p:first' ).remove();

		                    $.each( response.data.styles, function( file_id, url ) {
		                        $( '<link/>', { rel: 'stylesheet', href: url, id: file_id + '-css' } ).appendTo( 'head' );

		                    });

		                    $( 'body' ).append( form );

		                    $.each( response.data.scripts, function(file_id,data) {

		                        if( ! youzify_check_if_library_exists( data.function ) === true ) {

		                            if ( file_id == 'wp-util' ) {
		                                $( 'body').append( response.data.util_js );
		                            }

		                            $( '<script/>', { rel: 'text/javascript', src: data.url, id: file_id + '-js', object: data.function, class: 'youzify-uploader-element' } ).appendTo( 'body' );
		                        }
		                    });
		                }

		            });

		          });

		            function youzify_check_if_library_exists( name ) {
		                var result,source;
		                source = name.split('.');
		                if ( source[0] == 'bp' ) {

		                    if ( typeof bp === 'undefined' ) {
		                        return false;
		                    }

		                    result = bp;
		                } else if ( source[0] == 'window') {
		                    result = window;
		                }

		                name = name.replace( 'bp.', '' );
		                name = name.replace( 'window.', '' );

		                var index = 0,
		                parts = name.split('.');
		                try {
		                    while (typeof result !== "undefined" && result !== null && index < parts.length + 1 ) {
		                        result = result[ parts[ index++ ] ];
		                    }
		                } catch ( e ) {

		                }

		                if (index < parts.length + 1 ) {
		                    return false
		                }

		                return true;

		            }
		    });

		$( document ).keyup( function( e ) {
		    if ( e.keyCode === 27 ) {
			    $( '.youzify-bp-close-uploader' ).trigger( 'click' );
		    }
		});

		})( jQuery );

    </script>
    <?php
}

add_action( 'wp_ajax_youzify_get_buddypress_uploader', 'youzify_get_buddypress_uploader');

function youzify_get_buddypress_uploader() {

    $type = isset( $_POST['type'] ) ? $_POST['type'] : '';

    if ( empty( $type ) ) {
        wp_send_json_error();
    }

    $url = buddypress()->plugin_url . 'bp-core/js/';
    $css_url = buddypress()->plugin_url . 'bp-core/css/';

    $scripts = array();

    $bp_scripts = array(
        'bp-plupload' => array( 'url' => "{$url}bp-plupload.min.js",  'function' => 'bp.Uploader' )
    );

    if ( $type == 'avatar' ) {
        $bp_scripts['bp-avatar'] = array( 'url' =>"{$url}avatar.min.js", 'function' => 'bp.Avatar' );
        $bp_scripts['bp-webcam'] = array( 'url' =>"{$url}webcam.min.js", 'function' => 'bp.WebCam' );
    } elseif ( $type == 'cover' ) {
        $bp_scripts['bp-cover-image'] = array( 'url' =>"{$url}cover-image.min.js", 'function' => 'bp.CoverImage' );
    }

    $styles = array(
        'bp-avatar' => "{$css_url}avatar.min.css",
        'bp-plupload' => YOUZIFY_ASSETS . 'css/youzify-bp-uploader.min.css',
        'youzify-account' => YOUZIFY_ASSETS . 'css/youzify-account.min.css'
    );

    $wp_scripts = wp_scripts();
    $wp_styles = wp_styles();

    $wordpress_scripts = array( 'moxiejs' => array( 'level' => 'one', 'one' => 'mOxie' ) );
     // 'json2' => 'json2',
    $wordpress_scripts = array( 'moxiejs' => 'window.mOxie', 'plupload'=> 'window.plupload', 'underscore'=> 'window._', 'backbone'=> 'window.Backbone', 'wp-util'=> 'window._wpUtilSettings','wp-backbone'=> 'window.wp.Backbone', 'jcrop'=> 'window.jQuery.Jcrop', 'window.jQuery.ui.resizable'=> 'jquery', 'jquery-ui-draggable'=> 'window.jQuery.ui.draggable' );

    $wordpress_styles = array( 'jcrop' );

    foreach( $wordpress_scripts as $script => $function ) {
        if ( isset(  $wp_scripts->registered[ $script ] ) ) {
            $scripts[ $script ] = array( 'url' =>$wp_scripts->base_url . $wp_scripts->registered[ $script ]->src, 'function' => $function );
        }
    }

    foreach( $wordpress_styles as $style) {
        if ( isset(  $wp_styles->registered[ $style ] ) ) {
            $styles[ $style ] = $wp_styles->base_url . $wp_styles->registered[ $style ]->src;
        }
    }

    foreach( $bp_scripts as $bp_script => $data ) {
        $scripts[ $bp_script ] = array( 'url' => $data['url'], 'function' => $data['function'] );
    }

    ob_start();



    ?>

    <div class="youzify youzify-bp-uploader-popup" data-type="<?php echo $type; ?>">
        <div class="youzify-uploader-change-item youzify-change-<?php echo $type; ?>-item youzify-bp-uploader-popup-content">
            <?php if ( $type == 'avatar' ) : ?>
                <?php add_filter( 'bp_avatar_is_front_edit', '__return_true' ); ?>
                <?php bp_get_template_part( 'members/single/profile/change-avatar' ); ?>
            <?php elseif( $type == 'cover') : ?>
                <?php bp_get_template_part( 'members/single/profile/change-cover-image' ); ?>
            <?php endif; ?>
            <span class="youzify-bp-close-uploader"></span>
        </div>
    </div>
    <script id="bp-plupload-js-extra-<?php echo $type; ?>" class="youzify-uploader-element">

        if  ( typeof BP_Uploader === 'undefined' ) {
            var BP_Uploader;
        }
        <?php $class = $type == 'avatar' ? 'BP_Attachment_Avatar' : 'BP_Attachment_Cover_Image'; ?>

        // if ( typeof BP_Uploader === 'undefined' ) {
        //     var BP_Uploader = <?php echo json_encode( yozuify_get_attachments_localize( $class ) ); ?>;
        // }

        <?php if ( $type == 'avatar' ) : ?>
        if ( typeof YZ_Avatar_Uploader === 'undefined' ) {
            var YZ_Avatar_Uploader = <?php echo json_encode( yozuify_get_attachments_localize( 'BP_Attachment_Avatar' ) ); ?>;
            BP_Uploader = YZ_Avatar_Uploader;
        }
        <?php endif; ?>


        <?php if ( $type == 'cover' ) : ?>

            if ( typeof YZ_Cover_Uploader === 'undefined' ) {
                var YZ_Cover_Uploader = <?php echo json_encode( yozuify_get_attachments_localize( 'BP_Attachment_Cover_Image' ) ); ?>;
                BP_Uploader = YZ_Cover_Uploader;
            }
            if ( typeof youzify_default_cover === 'undefined' ) {
                youzify_default_cover = '<?php echo youzify_default_profile_cover() ?>';
            }

        ( function( $ ) {

            'use strict';

            $( document ).ready( function() {

                 bp.CoverImage.Attachment.on( 'change:url', function( data ) {

                    if ( data.attributes.url == '' ) {
                        if ( $( youzify_default_cover ).is( 'div' ) ) {
                            $( '.youzify-header-cover .youzify-user-profile-cover-img' ).replaceWith( youzify_default_cover );
                        } else {
                            $( '.youzify-header-cover .youzify-user-profile-cover-img' ).attr( 'src', youzify_default_cover );
                        }
                    } else {

                        if ( $( '.youzify-header-cover .youzify-user-profile-cover-img' )[0] ) {
                            $( '.youzify-header-cover .youzify-user-profile-cover-img' ).attr( 'src', data.attributes.url );
                        } else {
                            $( '.youzify-header-cover .youzify-cover-pattern' ).replaceWith( '<img src="' + data.attributes.url + '" alt="">' );
                        }
                    }

                } );
            });

        })( jQuery );
        <?php endif; ?>
    </script>
    <?php
    $attachments = ob_get_contents();

    ob_end_clean();

    ob_start();
     ?>

    <script id="youzify-util-js-extra" class="youzify-uploader-element">

        if ( typeof _wpUtilSettings === 'undefined' ) {
            var _wpUtilSettings = <?php echo json_encode(
                array(
                    'ajax' => array(
                        'url' => admin_url( 'admin-ajax.php', 'relative' ),
                    ),
                )
            );
         ?>;
        }

    </script>
    <?php

    $util_js = ob_get_contents();

    ob_end_clean();

    wp_send_json_success(
        array(
            'form' => $attachments,
            'scripts' => $scripts,
            'styles' => $styles,
            'util_js' => $util_js
        )
    );

}

function yozuify_get_attachments_localize( $class = '' ) {

    if ( ! $class || ! class_exists( $class ) ) {
        return new WP_Error( 'missing_parameter' );
    }

    // Get an instance of the class and get the script data.
    $attachment  = new $class;
    $script_data = $attachment->script_data();

    $args = bp_parse_args(
        $script_data,
        array(
            'action'            => '',
            'file_data_name'    => '',
            'max_file_size'     => 0,
            'browse_button'     => 'bp-browse-button',
            'container'         => 'bp-upload-ui',
            'drop_element'      => 'drag-drop-area',
            'bp_params'         => array(),
            'extra_css'         => array(),
            'extra_js'          => array(),
            'feedback_messages' => array(),
        ),
        'attachments_enqueue_scripts'
    );

    if ( empty( $args['action'] ) || empty( $args['file_data_name'] ) ) {
        return new WP_Error( 'missing_parameter' );
    }

    // Get the BuddyPress uploader strings.
    $strings = bp_attachments_get_plupload_l10n();

    // Get the BuddyPress uploader settings.
    $settings = bp_attachments_get_plupload_default_settings();

    // Set feedback messages.
    if ( ! empty( $args['feedback_messages'] ) ) {
        $strings['feedback_messages'] = $args['feedback_messages'];
    }

    // Use a temporary var to ease manipulation.
    $defaults = $settings['defaults'];

    // Set the upload action.
    $defaults['multipart_params']['action'] = $args['action'];

    // Set BuddyPress upload parameters if provided.
    if ( ! empty( $args['bp_params'] ) ) {
        $defaults['multipart_params']['bp_params'] = $args['bp_params'];
    }

    // Merge other arguments.
    $ui_args = array_intersect_key( $args, array(
        'file_data_name' => true,
        'browse_button'  => true,
        'container'      => true,
        'drop_element'   => true,
    ) );

    $defaults = array_merge( $defaults, $ui_args );

    if ( ! empty( $args['max_file_size'] ) ) {
        $defaults['filters']['max_file_size'] = $args['max_file_size'] . 'b';
    }

    if ( isset( $args['mime_types'] ) && $args['mime_types'] ) {
        $defaults['filters']['mime_types'] =  array( array( 'extensions' => $args['mime_types'] ) );
    }

    // Check if WebP images can be edited.
    if ( bp_is_running_wp( '5.8.0', '>=' ) && ! wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
        $defaults['webp_upload_error'] = true;
    }

    // Specific to BuddyPress Avatars.
    if ( 'bp_avatar_upload' === $defaults['multipart_params']['action'] ) {

        // Include the cropping informations for avatars.
        $settings['crop'] = array(
            'full_h'  => bp_core_avatar_full_height(),
            'full_w'  => bp_core_avatar_full_width(),
        );

        // Avatar only need 1 file and 1 only!
        $defaults['multi_selection'] = false;

        // Does the object already has an avatar set?
        $has_avatar = $defaults['multipart_params']['bp_params']['has_avatar'];

        // What is the object the avatar belongs to?
        $object = $defaults['multipart_params']['bp_params']['object'];

        // Get The item id.
        $item_id = $defaults['multipart_params']['bp_params']['item_id'];

        // Init the Avatar nav.
        $avatar_nav = array(
            'upload'  => array(
                'id'      => 'upload',
                'caption' => __( 'Upload', 'youzify' ),
                'order'   => 0
            ),
            'delete'  => array(
                'id'      => 'delete',
                'caption' => __( 'Delete', 'youzify' ),
                'order'   => 100,
                'hide'    => (int) ! $has_avatar
            ),
        );

        // Add the recycle view if avatar history is enabled.
        if ( 'user' === $object && ! bp_avatar_history_is_disabled() ) {
            // Look inside history to see if the user previously uploaded avatars.
            $avatars_history = bp_avatar_get_avatars_history( $item_id, $object );

            if ( $avatars_history ) {
                ksort( $avatars_history );
                $settings['history']       = array_values( $avatars_history );
                $settings['historyNonces'] = array(
                    'recylePrevious' => wp_create_nonce( 'bp_avatar_recycle_previous' ),
                    'deletePrevious' => wp_create_nonce( 'bp_avatar_delete_previous' ),
                );

                $avatar_nav['recycle'] = array(
                    'id'      => 'recycle',
                    'caption' => __( 'Recycle', 'youzify' ),
                    'order'   => 20,
                    'hide'    => (int) empty( $avatars_history ),
                );
            }
        }

        // Create the Camera Nav if the WebCam capture feature is enabled.
        if ( bp_avatar_use_webcam() && 'user' === $object ) {
            $avatar_nav['camera'] = array(
                'id'      => 'camera',
                'caption' => __( 'Take Photo', 'youzify' ),
                'order'   => 10
            );

            // Set warning messages.
            $strings['camera_warnings'] = array(
                'requesting'  => __( 'Please allow us to access to your camera.', 'youzify'),
                'loading'     => __( 'Please wait as we access your camera.', 'youzify' ),
                'loaded'      => __( 'Camera loaded. Click on the "Capture" button to take your photo.', 'youzify' ),
                'noaccess'    => __( 'It looks like you do not have a webcam or we were unable to get permission to use your webcam. Please upload a photo instead.', 'youzify' ),
                'errormsg'    => __( 'Your browser is not supported. Please upload a photo instead.', 'youzify' ),
                'videoerror'  => __( 'Video error. Please upload a photo instead.', 'youzify' ),
                'ready'       => __( 'Your profile photo is ready. Click on the "Save" button to use this photo.', 'youzify' ),
                'nocapture'   => __( 'No photo was captured. Click on the "Capture" button to take your photo.', 'youzify' ),
            );
        }

        /**
         * Use this filter to add a navigation to a custom tool to set the object's avatar.
         *
         * @since 2.3.0
         *
         * @param array  $avatar_nav {
         *     An associative array of available nav items where each item is an array organized this way:
         *     $avatar_nav[ $nav_item_id ].
         *     @type string $nav_item_id The nav item id in lower case without special characters or space.
         *     @type string $caption     The name of the item nav that will be displayed in the nav.
         *     @type int    $order       An integer to specify the priority of the item nav, choose one.
         *                               between 1 and 99 to be after the uploader nav item and before the delete nav item.
         *     @type int    $hide        If set to 1 the item nav will be hidden
         *                               (only used for the delete nav item).
         * }
         * @param string $object The object the avatar belongs to (eg: user or group).
         */
        $settings['nav'] = bp_sort_by_key( apply_filters( 'bp_attachments_avatar_nav', $avatar_nav, $object ), 'order', 'num' );

    // Specific to BuddyPress cover images.
    } elseif ( 'bp_cover_image_upload' === $defaults['multipart_params']['action'] ) {

        // Cover images only need 1 file and 1 only!
        $defaults['multi_selection'] = false;

        // Default cover component is members.
        $cover_component = 'members';

        // Get the object we're editing the cover image of.
        $object = $defaults['multipart_params']['bp_params']['object'];

        // Set the cover component according to the object.
        if ( 'group' === $object ) {
            $cover_component = 'groups';
        } elseif ( 'user' !== $object ) {
            $cover_component = apply_filters( 'bp_attachments_cover_image_ui_component', $cover_component );
        }
        // Get cover image advised dimensions.
        $cover_dimensions = bp_attachments_get_cover_image_dimensions( $cover_component );

        // Set warning messages.
        $strings['cover_image_warnings'] = apply_filters( 'bp_attachments_cover_image_ui_warnings', array(
            'dimensions'  => sprintf(
                /* translators: 1: the advised width size in pixels. 2: the advised height size in pixels. */
                __( 'For better results, make sure to upload an image that is larger than %1$spx wide, and %2$spx tall.', 'youzify' ),
                (int) $cover_dimensions['width'],
                (int) $cover_dimensions['height']
            ),
        ) );
    }

    // Set Plupload settings.
    $settings['defaults'] = $defaults;

    return array( 'strings' => $strings, 'settings' => $settings );
}