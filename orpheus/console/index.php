<?php

if( isset($_SERVER['REQUEST_URI']) ) {
	require_once '../web/index.php';
	return;
}

const IS_CONSOLE = 1;

require_once '../bootstrap.php';
