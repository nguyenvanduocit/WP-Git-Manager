<?php
namespace PHPGit;
/**
 * Class ProcessBuilder
 * @package PHPGit
 */
class ProcessBuilder {
	/** @var string path to git bin*/
	protected $binPath = null;
	/** @var string path to repo */
	protected $repoPath = null;
	protected $builder = null;

	public function __construct($binPath,$repoPath){
		if(is_null($binPath)){
			throw new \InvalidArgumentException('$binPath is missing');
		}
		if(is_null($repoPath))
		{
			throw new \InvalidArgumentException('$repoPath is missing');
		}
		$this->binPath = $binPath;
		$this->repoPath = $repoPath;

		$this->builder = new \Symfony\Component\Process\ProcessBuilder();
		$this->builder->setPrefix($this->binPath);
	}

	/**
	 * @param           $args
	 * @param bool|true $needPath
	 **/
	public function setArguments($args, $needPath = true){
		if($needPath){
			$args = array_merge(array('-C', $this->repoPath), $args);
		}

		$this->builder->setArguments($args);
	}
	/**
	 * @param            $args
	 * @param bool|false $needPath
	 *
	 * @return \Symfony\Component\Process\Process
	 */
	public function getProcess($args, $needPath = true){
		if(!is_null($args)){
			$this->setArguments($args, $needPath);
		}
		return $this->builder->getProcess();
	}
}