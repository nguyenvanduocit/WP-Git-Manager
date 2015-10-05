<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/4/2015
 * Time: 12:23 PM
 */

namespace WPPM;


class Repository {
	public $id = 0;
	public $post = null;
	public $pulls;
	public function __construct($repo){
		if ( is_numeric( $repo ) ) {
			$this->id   = absint( $repo );
			$this->post = get_post( $this->id );
		}
		elseif(is_string($repo)){
			$post = static::getByUrl($repo);
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
		return metadata_exists( 'post', $this->id, $key );
	}
	/**
	 * __get function.
	 *
	 * @param string $key
	 * @return mixed
	 */
	public function __get( $key ) {
		if($key === 'pulls'){
			return $this->get_pulls();
		}
		$value = get_post_meta( $this->id, $key, true );

		if ( ! empty( $value ) ) {
			$this->$key = $value;
		}

		return $value;
	}

	/**
	 * @param bool|false $forceUpdate
	 *
	 * @return Pull[]
	 */
	public function get_pulls($forceUpdate = false){
		if(!is_array($this->pulls) || $forceUpdate) {
			$posts = get_posts( array(
				'posts_per_page' => -1,
				'post_type'      => 'pull',
				'post_status'    => 'publish',
				'meta_key'       => 'repo_id',
				'meta_value'     => $this->id
			) );
			foreach ( $posts as $post ) {
				$this->pulls[] = new Pull($post);
			}
		}
		return $this->pulls;
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

	public function pull(){

	}
	public static function getByUrl($url){
		$posts = get_posts(array(
			'posts_per_page'	=> 1,
			'post_type'		=> 'repository',
			'post_status'      => 'publish',
			'meta_key'		=> 'repo_upstream',
			'meta_value'	=> $url
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
	public function add_note( $note, $comment_author ='Github', $comment_author_email = 'noreply@github.com') {

		$comment_post_ID        = $this->id;
		$comment_author_url     = '';
		$comment_content        = $note;
		$comment_agent          = 'WooCommerce';
		$comment_type           = 'repository_note';
		$comment_parent         = 0;
		$comment_approved       = 1;
		$commentData            = apply_filters( 'wppm_new_repo_note_data', compact( 'comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_agent', 'comment_type', 'comment_parent', 'comment_approved' ), array( 'repo_id' => $this->id) );
		$comment_id = wp_insert_comment( $commentData );
		return $comment_id;
	}
}