<?php

if( isset($_SERVER['REQUEST_URI']) ) {
	require_once '../web/index.php';
	return;
}

// echo "CONSOLE STUB\n";

define('IS_CONSOLE', 1);

require_once '../bootstrap.php';
