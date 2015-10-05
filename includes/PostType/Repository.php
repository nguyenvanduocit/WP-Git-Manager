<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 9/27/2015
 * Time: 11:34 AM
 */

namespace WPPM\PostType;


class Repository extends Base{

	public function __construct(){

		$this->postType = 'repository';
		$this->singularName ='Repository';
		$this->pluralName = 'Repositories';
		$this->menuName = 'Repository';
		$this->slug = 'repository';
		$this->args = array(
			'supports'=>array( 'title', 'editor', 'comments')
		);
		$this->meta_fields = array(
			'repo_remote'=>array(
				'type' => 'text',
				'name' => 'repo_remote',
				'title'=>'Remote',
				'desc'=>'If using enterprice : https://username:password@github.com/username/repository.git',
				'value' => 'http://github.com'
			)
		);
	}

	public function init() {
		add_action( 'init', array( $this, 'registerPostType' ));
	}
}