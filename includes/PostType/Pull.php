<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 9/27/2015
 * Time: 11:34 AM
 */

namespace WPPM\PostType;


class Pull extends Base{

	public function __construct(){

		$this->postType = 'pull';
		$this->singularName ='Pull';
		$this->pluralName = 'Pulles';
		$this->menuName = 'Pull';
		$this->slug = 'pull';
		$this->args = array(
			'supports'=>array( 'title', 'editor', 'comments')
		);
		$this->hierarchical = true;
		$this->terms = array('pull-category');
		$this->meta_fields = array(
			'repo_id'=>array(
				'type' => 'number',
				'name' => 'repo_id',
				'title'=>'Repository Id',
				'value' => '-1'
			),
			'repo_secret'=>array(
				'type' => 'password',
				'name' => 'repo_secret',
				'title'=>'Secret',
				'value' => '-1'
			),
			'repo_branch'=>array(
				'type' => 'text',
				'name' => 'repo_branch',
				'title'=>'Local branch',
				'value' => ''
			),
			'repo_local_path'=>array(
				'type' => 'text',
				'name' => 'repo_local_path',
				'title'=>'Local path',
				'value'=>''
			),

		);
	}

	public function init() {
		add_action( 'init', array( $this, 'registerPostType' ));
	}
}