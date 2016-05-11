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
		
		$plugin_file = $wppusherPluginObj->plugin->file;
		
		global $wpdb;
		$table_name = pusherTableName();
		if(	$row = $wpdb->get_row("SELECT * FROM $table_name WHERE package='$plugin_file';") ) {
			var_dump( $row );	//Debug
			
		} else {
			/** Failed **/
			return;
		}
	}
}
?>