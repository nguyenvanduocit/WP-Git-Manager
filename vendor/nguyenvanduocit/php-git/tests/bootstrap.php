<?php

date_default_timezone_set('Europe/Paris');

require_once __DIR__ . '/../vendor/autoload.php';
use Symfony\Component\Process\Process;
define('TMP_DIR', __DIR__.'\\tmp');
// Delete the temp test user after all tests have fired
register_shutdown_function(function () {
	/**
	 * Delete tmp folder
	 */
	$process = new Process(sprintf('rmdir /s /q %1$s', TMP_DIR));
	$process->setTimeout(10);
	$process->run();
	if (!$process->isSuccessful()) {
		echo $process->getErrorOutput();
	}
	echo $process->getOutput();
	/**
	 * Create tmp folder
	 */
	$process = new Process(sprintf('mkdir %1$s', TMP_DIR));
	$process->setTimeout(10);
	$process->run();
	if (!$process->isSuccessful()) {
		echo $process->getErrorOutput();
	}
	echo $process->getOutput();
});
