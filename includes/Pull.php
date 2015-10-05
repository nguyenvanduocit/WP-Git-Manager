<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/4/2015
 * Time: 12:23 PM
 */

namespace WPPM;
use PHPGit\PHPGit;
use Symfony\Component\Process\Exception\ProcessFailedException;


/**
 * @property string repo_local_path
 * @property mixed  remote
 * @property mixed  repo_branch
 */
class Pull {
	public $id = 0;
	public $post = null;
	protected $phpGit;
	public function __construct($repo){
		if ( is_numeric( $repo ) ) {
			$this->id   = absint( $repo );
			$this->post = get_post( $this->id );
		}
		elseif(is_string($repo)){
			$post = static::getRepoIdByUrl($repo);
			if($post != null){

				/** @var \WP_Post $repo */
				$this->id   = absint( $post->ID );
				$this->post = $post;
			}
		}
		elseif ( $repo instanceof Repository ) {
			$this->id   = absint( $repo->id );
			$this->post = $repo->post;
		} elseif ( isset( $repo->ID ) ) {
			$this->id   = absint( $repo->ID );
			$this->post = $repo;
		}
	}
	/**
	 * __isset function.
	 *
	 * @param mixed $key
	 * @return bool
	 */
	public function __isset( $key ) {
		return metadata_exists( 'post', $this->id,$key );
	}
	/**
	 * __get function.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get( $key ) {
		if($key == 'remote'){
			$value = $this->get_repo_remote();
		}
		else{
			$value = get_post_meta( $this->id, $key, true );
		}

		if ( ! empty( $value ) ) {
			$this->$key = $value;
		}

		return $value;
	}
	public function get_repo_remote(){
		return get_post_meta( $this->repo_id, 'repo_remote', true );
	}
	/**
	 * Get the product's post data.
	 *
	 * @return object
	 */
	public function get_post_data() {
		return $this->post;
	}
	/**
	 * Wrapper for get_permalink
	 *
	 * @return string
	 */
	public function get_permalink() {
		return get_permalink( $this->id );
	}
	/**
	 * Returns whether or not the product post exists.
	 *
	 * @return bool
	 */
	public function exists() {
		return empty( $this->post ) ? false : true;
	}

	public function getPhpGit(){
		if(is_null($this->phpGit)){
			$this->phpGit = new PHPGit($this->repo_local_path, 'git');
		}
		return $this->phpGit;
	}

	/**
	 * Pull the branch
	 * @return \WP_Error
	 */
	public function pull(){
		if(!$this->exists()){
			return new \WP_Error('NOT_FOUND','This pull is not exist in database');
		}
		try {
			$commander = $this->getPhpGit();
			if ( ! $commander->exist() ) {

				$commander->exec( 'clone','-b',$this->repo_branch, $this->remote, '.' );
			} else {
				$remotes = $commander->remotes( 'origin' );
				if ( ! in_array( $this->remote, $remotes ) ) {
					$commander->exec( 'remote', 'add', 'origin', $this->remote );
				}
				$commander->exec('fetch', '--all');
				$commander->exec( 'reset', '--hard', 'origin/'.$this->repo_branch );
			}
			return true;
		}
		catch( ProcessFailedException $e){
			return new \WP_Error($e->getCode(), $e->getProcess()->getErrorOutput());
		}
	}

	public static function getByRepoId($id){
		$posts = get_posts(array(
			'posts_per_page'	=> 1,
			'post_type'		=> 'pull',
			'post_status'      => 'publish',
			'meta_key'		=> 'repo_id',
			'meta_value'	=> $id
		));
		if(count($posts) > 0){
			return $posts[0];
		}
		return null;
	}

	/**
	 * Adds a note (comment) to the order
	 *
	 * @param string $note Note to add
	 * @param string $comment_author
	 * @param string $comment_author_email
	 *
	 * @return bool|int
	 */
	public function add_note( $note, $comment_author ='Github', $comment_author_email = 'noreply@github.com', $extras = null) {

		$comment_post_ID        = $this->id;
		$comment_author_url     = '';
		$comment_content        = $note;
		$comment_agent          = 'WooCommerce';
		$comment_type           = 'pull_note';
		$comment_parent         = 0;
		$comment_approved       = 1;
		$commentData            = apply_filters( 'wppm_new_repo_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ), array( 'repo_id' => $this->id) );
		$comment_id = wp_insert_comment( $commentData );
		if(!is_null($extras)) {
			add_comment_meta( $comment_id, 'extras', $extras );
		}
		return $comment_id;
	}
}