<?php
namespace PHPGit\Tests;
use PHPGit\ProcessBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/3/2015
 * Time: 8:04 PM
 */
class ProcessBuilderTest extends PHPUnit_Framework_TestCase {
	/** @var  ProcessBuilder */
	protected $commandBuilder;
	public function setUp(){
		parent::setUp();
		$this->commandBuilder = new ProcessBuilder('git', TMP_DIR);
	}

	public function testInit(){
		$actual = $this->commandBuilder->getProcess(array('init','-q','--bare'))->getCommandLine();
		$this->assertEquals($this->buildCommentLine(array('init', '-q', '--bare')), $actual);
	}

	public function testCommit(){
		$actual = $this->commandBuilder->getProcess(array('commit', 'test message'))->getCommandLine();
		$this->assertEquals($this->buildCommentLine(array('commit','test message')), $actual);
	}
	protected function buildCommentLine($args, $pathNeed = true){
		if(!is_array($args)){
			$args = (array)$args;
		}
		$prefixArgs = array('git');
		if($pathNeed){
			$prefixArgs[] = '-C';
			$prefixArgs[] = TMP_DIR;
		}
		$args = array_merge($prefixArgs, $args);

		return '"'.implode('" "', $args).'"';
	}
}
