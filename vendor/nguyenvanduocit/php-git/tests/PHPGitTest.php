<?php

/**
 * Test for PHPGist.
 * User: nguyenvanduocit
 * Date: 10/3/2015
 * Time: 11:49 PM
 */
class PHPGitTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var \PHPGit\PHPGit
	 */
	protected $phpGit;
	public function setUp(){
		$this->phpGit = new \PHPGit\PHPGit(TMP_DIR,'git');
	}
	public function testInit(){
		$this->phpGit->exec('init');
		$this->assertFileExists(TMP_DIR.'/.git');
	}
}
