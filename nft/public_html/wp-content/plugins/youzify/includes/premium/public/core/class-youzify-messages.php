<?php
/**
 * Messages Class
 */
class Youzify_Pro_Messages {

	function __construct() {

		// Actions.
		add_action( 'bp_after_messages_compose_content', array( $this, 'compose_message_attachments' ) );
		add_action( 'bp_after_message_reply_box', array( $this, 'conversation_attachments' ) );

	}

	/**
	 * Add Compose Message Attachments.
	 */
	function compose_message_attachments() {

		if ( apply_filters( 'youzify_enable_messages_attachments', true ) && 'on' == youzify_option( 'youzify_messages_attachments', 'on' ) ) : ?><div class="youzify-upload-btn"><i class="fas fa-paperclip"></i><span class="youzify-upload-btn-title"><?php _e( 'Upload Attachment', 'youzify' ); ?></span></div><?php

		endif;
	}


	/**
	 * Add Conversation Message Attachments.
	 */
	function conversation_attachments() {

		if ( apply_filters( 'youzify_enable_messages_attachments', true ) && 'on' == youzify_option( 'youzify_messages_attachments', 'on' ) ) : ?><div class="youzify-upload-btn"><i class="fas fa-paperclip"></i><span class="youzify-upload-btn-title"><?php _e( 'Upload Attachment', 'youzify' ); ?></span></div><?php
		endif;

	}


}

new Youzify_Pro_Messages();