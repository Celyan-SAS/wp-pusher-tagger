<?php
/** WP Pusher tagger main class **/
class celyanWppt {
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		
		/** @see  wp-content/plugins/wppusher/Pusher/Handlers/UpdatePlugin.php:40 **/
		add_action( 'wppusher_plugin_was_updated', array( $this, 'on_plugin_updated' ), 10, 1 );
	}
	
	public function on_plugin_updated( $wppusherPluginObj ) {
		
		var_dump( $wppusherPluginObj );
	}
}
?>