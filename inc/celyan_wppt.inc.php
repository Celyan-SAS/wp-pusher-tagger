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
		
		
		$response1 = wp_remote_get( $url1 );
		
		/*
		if( isset( $response1['tag_name'] ) && preg_match( '/^(.*)\.(\d+)$/', $response1['tag_name'], $matches ) ) {
			$tag_name = $matches[1] . '.' . $matches[2]+1;
			
		} else {
			$tag_name = 'v0.1.0';
			
		}
		*/
		
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
		
		/* Debug: */
		echo '<div class="updated">';
		echo '<p>URL1: ' . $url1 . '</p>';
		echo '<p>URL: ' . $url . '</p>';
		echo '<p><pre>';
		var_dump( $response1 );
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