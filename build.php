<?php

/*
 * php build.php && copy /Y orpheus.phar "..\..\Workspace-Web\OrpheusInstallerTest\"
 */

try {
// 	ini_set('phar.readonly', 0);
	$archFile = 'orpheus.phar';
	
	$phar = new Phar($archFile);
	$phar->buildFromDirectory(dirname(__FILE__).'/orpheus');
// 	echo $phar->createDefaultStub('console/index.php', 'web/index.php')."\n";
// 	$phar->setStub($phar->createDefaultStub('console/index.php', 'web/index.php'));
	$phar->setDefaultStub('console/index.php', 'web/index.php');
	echo 'Compiled files into '.$archFile."\n";
	
} catch (Exception $e) {
	// handle errors
	echo $e;
}
