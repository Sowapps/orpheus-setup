<?php

require_once 'OperatingSystem.php';
require_once 'WindowsNTOS.php';
require_once 'UnixOS.php';

require_once 'Task.php';
require_once 'InstallTask.php';

require_once 'FrontInterface.php';
require_once 'ConsoleInterface.php';
require_once 'WebInterface.php';

function rcopy($src, $dst) {
// 	echo "rcopy($src, $dst)\n";
	$dir = opendir($src);
	try {
		mkdir($dst);
	} catch( Exception $e ) {}
	while( ($file = readdir($dir)) !== false ) {
		if( ($file !== '.') && ($file !== '..' ) ) {
			$srcPath = $src.'/'.$file;
			$destPath = $dst.'/'.$file;
			if( is_dir($srcPath) ) {
				rcopy($srcPath, $destPath);
			} else {
				copy($srcPath, $destPath);
			}
		}
	}
	closedir($dir);
}

function rmove($src, $dst) {
// 	echo "rmove($src, $dst)\n";
	$dir = opendir($src);
	try {
		mkdir($dst);
	} catch( Exception $e ) {}
	while( ($file = readdir($dir)) !== false ) {
		if( ($file !== '.') && ($file !== '..' ) ) {
			$srcPath = $src.'/'.$file;
			$destPath = $dst.'/'.$file;
// 			if( $file === '.git' ) {
// 				force_rmdir($srcPath);
// 				continue;
// 			}
// 			echo "$srcPath => $destPath\n";
			rename($srcPath, $destPath);
// 			die();
			/*
			 if( is_dir($srcPath) ) {
				recurse_move($srcPath, $destPath);
				} else {
				rename($srcPath, $destPath);
				}
				*/
		}
	}
	closedir($dir);
	rmdir($src);
}

function b($b) {
	return $b ? 'TRUE' : 'FALSE';
}

function force_rmdir($path, $recursive=false) {
// 	echo "force_rmdir($path, ".b($recursive).")\n";
	$dir = opendir($path);
	while( ($file = readdir($dir)) !== false && $file !== null ) {
		// Sometimes on a WAMP, a null occurs
// 		var_dump($file);echo "\n";
// 		echo "force_rmdir($path, ".b($recursive).") - file => $file \n";
		if( $file !== '.' && $file !== '..' ) {
			$filePath = $path.'/'.$file;
// 			echo "$filePath => ".b(is_dir(realpath($filePath)))."\n";
// 			echo "File exists ? => ".b(file_exists($filePath))."\n";
			if( is_dir($filePath) ) {
// 				if( $recursive ) {
				force_rmdir($filePath);
// 				}
			} else {
				unlink($filePath);
			}
			/*
			 if( is_dir($srcPath) ) {
				recurse_move($srcPath, $destPath);
				} else {
				rename($srcPath, $destPath);
				}
				*/
		}
	}
	closedir($dir);
	rmdir($path);
}


/**
 * Error Handler
 *
 * System function to handle PHP errors and convert it into exceptions.
 */
set_error_handler(function($errno, $errstr, $errfile, $errline) {
	throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

