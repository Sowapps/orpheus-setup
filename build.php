#!/usr/bin/env php
<?php

/*
 * php build.php && copy /Y orpheus.phar "..\..\Workspace-Web\OrpheusInstallerTest\"
 * php -d phar.readonly=off build.php && cp orpheus.phar "..\..\Workspace-Web\OrpheusInstallerTest\"
 */

try {
	$archFile = 'orpheus.phar';
	
	$phar = new Phar($archFile);
	try {
		$phar->buildFromDirectory(dirname(__FILE__) . '/orpheus');
	} catch( UnexpectedValueException $e ) {
		throw new Exception('Unable to create phar archive, phar are readonly, edit the php.ini configuration to set phar.readonly to Off', 0, $e);
	}
	$phar->setDefaultStub('console/index.php', 'web/index.php');
	echo 'Compiled files into ' . $archFile . "\n";
	
} catch( Exception $e ) {
	// handle errors
	echo $e;
}
