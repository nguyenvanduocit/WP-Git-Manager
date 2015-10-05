<?php
/**
 * Created by PhpStorm.
 * User: nguyenvanduocit
 * Date: 10/4/2015
 * Time: 3:28 PM
 */

namespace PHPGit;


class Util {
	public static function parseOutputRaw($raw){
		return explode("\n", trim($raw));
	}
}