<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 9/27/2015
 * Time: 8:01 AM
 */

namespace WPPM;


class PostType {
	private static $instance = null;

	/**
	 * @return \WPPM\PostType
	 */
	public static function getInstance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new static();
		}

		return static::$instance;
	}

	/** @var  \WPPM\PostType\Base[] */
	protected $postTypes;

	public function __construct() {
		$load_posttypes = array(
			'\WPPM\PostType\Repository',
			'\WPPM\PostType\Pull'
		);
		$load_posttypes = apply_filters( 'diress_posttypes', $load_posttypes );
		// Get sort order option
		// Load gateways in order
		foreach ( $load_posttypes as $postType ) {
			/** @var \WPPM\PostType\Base $load_postType */
			$load_postType = is_string( $postType ) ? new $postType() : $postType;

			$this->postTypes[ $load_postType->getPostType() ] = $load_postType;
		}
	}

	/**
	 * Get gateways.
	 *
	 * @access public
	 * @return PostType\Base[]
	 */
	public function getPostTypes() {
		return $this->postTypes;
	}

	/**
	 * @param $id
	 *
	 * @return PostType\Base|null
	 */
	public function getPostType( $postTypeName ) {
		if ( array_key_exists( $postTypeName, $this->postTypes ) ) {
			return $this->postTypes[ $postTypeName ];
		}

		return null;
	}

	/**
	 * Register posttype
	 */
	public function init() {
		foreach ( $this->postTypes as $postTypeName => $postType ) {
			$postType->init();
		}
	}
}