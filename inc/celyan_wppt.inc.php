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
			//var_dump( $row );	//Debug
			
			$this->github_tag( $row );
			
		} else {
			/** Failed **/
			return;
		}
	}
	
	private function github_tag( $plugin_data ) {
		
		$repository = $plugin_data->repository;
		
		$token = get_option('gh_token');
		
		$payload = json_encode(array(
			'tag_name'	=> 'v.0.1.0',
			'name'		=> 'test',
			'body'		=> 'body test'
		));
		
		$url = "https://api.github.com/repos/{$repository}/releases?access_token={$token}";
		
		$response = wp_remote_post($url, array(
			'body' => $payload,
			'headers' => array(
					'Content-Type' => 'application/json',
			),
		));
		
		var_dump( $response );	//Debug
		
		if ($response instanceof \WP_Error) {
			throw new \Exception('Release tag was not created on GitHub. Make sure a valid GitHub token is stored.');
		}
	}
}
?>