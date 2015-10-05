<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/4/2015
 * Time: 1:09 AM
 */


namespace WPPM;
class PullManager {
	/** @var  \WPPM\PullManager */
	private static $instance;
	/**
	 * @return \WPPM\PullManager
	 */
	public static function getInstance(){
		if(is_null(static::$instance)){
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Run the plugin
	 */
	public function run(){
		$this->API()->init();
		// Load and register team
		$this->Term()->init();
		// Load and register posttype
		$this->PostType()->init();
		// Run all active module
		$this->Module()->runActivedModules();
		// Load and register shortcode
		$this->Shortcode()->init();
		// register widget
		$this->Widget()->init();
		if(is_admin())
		{
			$this->Admin()->init();
		}
		else {
			// Load and init front
			$this->Front()->init();
		}
	}
	public function API(){
		return API::getInstance();
	}
	public function Widget(){
		return Widget::getInstance();
	}
	public function Admin(){
		return Admin::getInstance();
	}

	public function Term(){
		return Term::getInstance();
	}
	public function Shortcode(){
		return Shortcode::getInstance();
	}

	public function PostType(){
		return PostType::getInstance();
	}
	/**
	 * @return Front
	 */
	public function Front(){
		return Front::getInstance();
	}
	/**
	 * @return \WPPM\Module
	 */
	public function Module() {
		return Module::getInstance();
	}

	/**
	 * @return \WPPM\Template
	 */
	public function Template(){
		return Template::getInstace();
	}
}