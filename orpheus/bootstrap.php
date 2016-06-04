<?php

require_once 'src/loader.php';

$interface = null;

if( defined('IS_CONSOLE') && IS_CONSOLE ) {
	$interface = new ConsoleInterface();
}
if( defined('IS_WEB') && IS_WEB ) {
	$interface = new WebInterface();
}

if( !$interface ) {
	throw new Exception('No front interface found, please define IS_CONSOLE or IS_WEB');
}

if( !defined('APPLICATION_PATH') ) {
	define('APPLICATION_PATH', dirname(Phar::running(false)));
}

if( $interface->hasInputTask() ) {
	try {
		$interface->exec($interface->getInputTask());
	} catch( Exception $e ) {
		$interface->reportException($e);
	}
} else {
	$interface->printHelp();
}
