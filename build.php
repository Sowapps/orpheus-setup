<?php

/*
 * php build.php && copy /Y orpheus.phar "..\..\Workspace-Web\OrpheusInstallerTest\"
 */

try {
// 	ini_set('phar.readonly', 0);
	$archFile = 'orpheus.phar';
	
	$phar = new Phar($archFile);
	try {
		$phar->buildFromDirectory(dirname(__FILE__).'/orpheus');
	} catch( UnexpectedValueException $e ) {
		throw new Exception('Unable to create phar archive, phar are readonly, edit the php.ini configuration to set phar.readonly to Off', 0, $e);
	}
// 	echo $phar->createDefaultStub('console/index.php', 'web/index.php')."\n";
// 	$phar->setStub($phar->createDefaultStub('console/index.php', 'web/index.php'));
	$phar->setDefaultStub('console/index.php', 'web/index.php');
	echo 'Compiled files into '.$archFile."\n";
	
} catch (Exception $e) {
	// handle errors
	echo $e;
}
