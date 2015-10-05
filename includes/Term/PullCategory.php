<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 9/27/2015
 * Time: 9:58 AM
 */

namespace WPPM\Term;


class PullCategory extends Base{
	public function __construct(){
		$this->name = 'pull-category';
		$this->singularName ='Pull category';
		$this->pluralName = 'Pull categories';
		$this->menuName = 'Pull';
		$this->slug = 'pull-cateogry';
	}

	function init() {
		add_action( 'init', array( $this, 'registerTerm' ));
	}
}