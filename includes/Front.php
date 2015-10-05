<?php
/**
 * This class is used for manage front-end asset and some business on front-end.
 * User: nguyenvanduocit
 * Date: 9/25/2015
 * Time: 11:22 PM
 */

namespace WPPM;


class Front {
	/** @var  \WPPM\Front */
	private static $instance;

	/**
	 * @return Front
	 */
	public static function getInstance(){
		if(is_null(static::$instance)){
			static::$instance = new static();
		}
		return static::$instance;
	}
	public function init(){
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueAsset' ) );
	}
	/**
	 * Enqueue script and style
	 */
	public function enqueueAsset() {

	}
}