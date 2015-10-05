<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/4/2015
 * Time: 1:34 PM
 */

namespace WPPM;


class API {

	protected $version = 1;
	private static $instance;

	/**
	 * @return \WPPM\API
	 */
	public static function getInstance(){
		if(is_null(static::$instance)){
			static::$instance = new static();
		}
		return static::$instance;
	}
	public function init(){
		add_action( 'rest_api_init', array($this, 'addEndpoint'));
	}
	public function addEndpoint(){
		register_rest_route( "wppm/v{$this->version}", '/all', array(
			'methods' => 'POST',
			'callback' => array($this, 'onGithubRequest'),
			'permission_callback' => function () {
				return true;
			}
		) );
	}

	/**
	 * This method use for handler request from Github's webhook.
	 *
	 * Because of github not required to response payload on response, so we can make a do_action instead of apply_filters
	 *
	 * @param \WP_REST_Request $request
	 *
	 * @return \WP_REST_Response
	 */
	public function onGithubRequest(\WP_REST_Request $request){
		// Create the response object
		$request_payload = json_decode($request->get_body());
		$response = new \WP_REST_Response();
		if($request_payload){
			ob_start();
			$repo = new Repository($request_payload->repository->git_url);
			if($repo->exists()){
				$event = $request->get_header('X-Github-Event');
				do_action('wppm_webhook_recived_'.$event, $repo, $request_payload);
			}else{
				$response->set_status(404);
				echo "Repo is not exist.";
			}
			$out_put = ob_get_clean();
			$response->set_data($out_put);
		}
		else{
			$response->set_status(500);
		}
		return $response;
	}
}