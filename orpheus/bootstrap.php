<?php

require_once 'src/loader.php';

$interface = null;

if( defined('IS_CONSOLE') && IS_CONSOLE ) {
	$interface = new ConsoleInterface();
}

if( !$interface ) {
	throw new Exception('No front interface found, please define IS_CONSOLE or IS_WEB');
}

if( $interface->hasInputTask() ) {
	$interface->exec($interface->getInputTask());
} else {
	$interface->printHelp();
}
