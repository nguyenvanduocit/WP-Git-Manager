<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 9/29/2015
 * Time: 12:59 AM
 */

namespace WPPM\Module\AutoPull;


use PHPGit\PHPGit;
use WPPM\Module\Base;
use WPPM\Repository;

class AutoPull extends Base{
	public function __construct() {
		$this->id = 'PushHandler';
	}

	public function run() {
		parent::run();
		add_action( 'wppm_webhook_recived_push', array($this, 'onPushed'), 10, 2);
	}

	/**
	 * @param $repo            Repository
	 * @param $request_payload \stdClass
	 */
	public function onPushed($repo, $payload){
		$pulls = $repo->get_pulls();
		if(!$pulls){
			return false;
		}
		$ref = explode('/', $payload->ref);
		$pushedBranch = $ref[2];
		foreach($pulls as $pull){
			if($pull->repo_branch == $pushedBranch)
			{
				$result = $pull->pull();
				if(is_wp_error($result)){
					echo $result->get_error_message();
				}
				else{
					foreach($payload->commits as $commit){
						$pull->add_note($commit->message, $commit->author->username, $commit->author->email );
					}
					echo "Pull success to {$pull->repo_local_path}. ";
				}
			}
		}
	}
}