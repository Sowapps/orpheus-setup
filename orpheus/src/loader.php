<?php /** @noinspection ALL */

require_once 'OperatingSystem/AbstractOperatingSystem.php';
require_once 'OperatingSystem/WindowsNTOS.php';
require_once 'OperatingSystem/UnixOS.php';

require_once 'Task/Task.php';
require_once 'Task/InstallTask.php';

require_once 'FrontInterface/AbstractFrontInterface.php';
require_once 'FrontInterface/EmptyInterface.php';
require_once 'FrontInterface/ConsoleInterface.php';
require_once 'FrontInterface/WebInterface.php';

require_once 'ProcessContext/AbstractContext.php';
require_once 'ProcessContext/ConsoleContext.php';
require_once 'ProcessContext/EmptyContext.php';

function rcopy($src, $dst) {
	$dir = opendir($src);
	try {
		mkdir($dst);
	} catch( Exception $e ) {
	}
	while( ($file = readdir($dir)) !== false ) {
		if( ($file !== '.') && ($file !== '..') ) {
			$srcPath = $src . '/' . $file;
			$destPath = $dst . '/' . $file;
			if( is_dir($srcPath) ) {
				rcopy($srcPath, $destPath);
			} else {
				copy($srcPath, $destPath);
			}
		}
	}
	closedir($dir);
}

function rmove($src, $dst, $force = false) {
	$dir = opendir($src);
	try {
		mkdir($dst);
	} catch( Exception $e ) {
	}
	$allMoved = true;
	while( ($file = readdir($dir)) !== false ) {
		if( $file !== '.' && $file !== '..' ) {
			$srcPath = $src . '/' . $file;
			$destPath = $dst . '/' . $file;
			if( $force || !file_exists($destPath) ) {
				rename($srcPath, $destPath);
			} else {
				$allMoved = false;
			}
		}
	}
	closedir($dir);
	if( $allMoved ) {
		rmdir($src);
	}
}

function b($b) {
	return $b ? 'TRUE' : 'FALSE';
}

function force_rmdir($path, $recursive = false) {
	$dir = opendir($path);
	while( ($file = readdir($dir)) !== false && $file !== null ) {
		// Sometimes on a WAMP, a null occurs
		if( $file !== '.' && $file !== '..' ) {
			$filePath = $path . '/' . $file;
			if( is_dir($filePath) ) {
				force_rmdir($filePath);
			} else {
				unlink($filePath);
			}
		}
	}
	closedir($dir);
	rmdir($path);
}

function isComposerProject($path) {
	return file_exists($path . '/composer.json');
}

function isOrpheusProject($path) {
	return file_exists($path . '/ORPHEUS-LICENSE.txt');
}


/**
 * Error Handler
 *
 * System function to handle PHP errors and convert it into exceptions.
 */
set_error_handler(function ($errno, $errstr, $errfile, $errline) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});


function generateRandomString($length = 64, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
	if( $length < 1 ) {
		throw new RangeException('Length must be a positive integer');
	}
	$string = '';
	$max = mb_strlen($keyspace, '8bit') - 1;
	for( $i = 0; $i < $length; ++$i ) {
		$string .= $keyspace[mt_rand(0, $max)];
	}
	return $string;
}
