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
			
			$this->github_tag( $row, $wppusherPluginObj );
			
		} else {
			/** Failed **/
			return;
		}
	}
	
	private function github_tag( $plugin_data, $wppusherPluginObj ) {
		
		$repository = $plugin_data->repository;
		
		$token = get_option('gh_token');
		
		/** Get the latest release **/
		
		$url1 = "https://api.github.com/repos/{$repository}/releases/latest?access_token={$token}";
		//TODO: /latest endpoint does not return the latest, use /releases instead and grab the last one of the list
		
		$response1 = wp_remote_get( $url1, array( 'timeout' => 5 ) );
		
		/* */
		$tag_name = 'v0.1.0';
		if( 
			is_array( $response1 ) &&
			isset( $response1['body'] )
		) { 
			$payload1 = json_decode( $response1['body'] );
			if( 
				isset( $payload1->tag_name ) &&
				preg_match( '/^(.*)\.(\d+)$/', $payload1->tag_name, $matches ) 
			) {
				$tag_name = $matches[1] . '.' . ($matches[2]+1);
				
			} 
		}
		/* */
		
		/** Create a new release **/
		
		$payload = json_encode(array(
			'tag_name'	=> $tag_name,
			'name'		=> 'Déploiement en production ' . $tag_name,
			'body'		=> 'Déploiement en production',
			'token'		=> $token
		));
		
		$url = "https://api.github.com/repos/{$repository}/releases?access_token={$token}";
		
		$response = wp_remote_post($url, array(
			'body' => $payload,
			'headers' => array(
					'Content-Type' => 'application/json',
			),
		));
		
		/* Debug: *
		echo '<div class="updated">';
		echo '<p>URL1: ' . $url1 . '</p>';
		echo '<p>pl tag_name: ' . $payload1->tag_name . '</p>';
		echo '<p>matches[1]: ' . $matches[1] . '</p>';
		echo '<p>matches[2]: ' . $matches[2] . '</p>';
		echo '<p>tag_name: ' . $tag_name . '</p>';
		echo '<p>URL: ' . $url . '</p>';
		echo '<p><pre>';
		var_dump( $payload1 );
		var_dump( $response );
		var_dump( $wppusherPluginObj );
		echo '</pre></p></div>';
		/* */
		
		if ($response instanceof \WP_Error) {
			throw new \Exception('Release tag was not created on GitHub. Make sure a valid GitHub token is stored.');
		}
	}
}
?>