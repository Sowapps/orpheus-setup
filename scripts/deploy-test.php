#!/usr/bin/env php
<?php
/**
 * Deploy local Orpheus framework to a local website
 * Recreate the entire project each time, we must start from 0
 */

use FrontInterface\ConsoleInterface;
use OperatingSystem\AbstractOperatingSystem;
use ProcessContext\ConsoleContext;

chdir(__DIR__);

require_once '../orpheus/src/loader.php';

function setComposerConfig(string $config, string $orpheusProjectsPath): string {
	return str_replace('"url": "../*",', sprintf('"url": "%s/*",', $orpheusProjectsPath), $config);
}

$interface = new ConsoleInterface();
$os = AbstractOperatingSystem::getCurrent();
$os->setInterface($interface);

$commandOptions = ConsoleContext::mapOptions(
	getopt('hv', ['help', 'verbose', 'dry-run', 'replace', 'overwrite', 'permissions:', 'no-update', 'update']),
	['h' => 'help', 'v' => 'verbose']
);

$context = new ConsoleContext(__FILE__, $commandOptions);
$os->setContext($context);

try {
	$context->validateExclusiveParameters(['replace', 'overwrite']);
	$context->validateExclusiveParameters(['update', 'no-update']);
} catch( Throwable $exception ) {
	$interface->reportException($exception);
}

$help = $context->hasParameter('help');
$replace = $context->hasParameter('replace');
$overwrite = $context->hasParameter('overwrite');
$permissions = $context->getParameter('permissions', 'posix');
$update = !$context->hasParameter('no-update');// Update is the default so the option is not required

$webPath = realpath('../..');
$orpheusName = 'orpheus';
$orpheusProjectsPath = $webPath . '/orpheus';
$orpheusPath = $orpheusProjectsPath . '/' . $orpheusName;
$websitePath = $webPath . '/orpheus/orpheus-website';
$orpheusConfigPath = $webPath . '/orpheus/orpheus-website/composer.local.json';
//$orpheusScriptsPath = $webPath . '/orpheus/orpheus-website/scripts';
$targetName = 'orpheus-test';
$targetPath = $webPath . DIRECTORY_SEPARATOR . $targetName;

$webWritableFolders = ['/store'];

if( $help ) {
	$interface->write(sprintf(<<<OUT
./%s
Deploy local test Orpheus from local sources.
 - In replace mode, remove any existing folder
 - Copy Orpheus sources
 - Copy configurations from Orpheus Website
 - Configure local composer file to look for local Orpheus sources
 - Update composer
 - Set permissions of files and directories
Options:
 - -v | --verbose : Verbose mode
 - --dry-run : Dry run, do not apply any change
 - --replace : Remove any existing contents before starting
 - --overwrite : Force overwrite any existing contents
 - --no-update : Do not update composer
 - --update : Update composer. The default behavior, this option has no effect.
 - --permissions=[posix|acl|none] : Define how to set permissions. none to pass. The default is posix.
 - -h | --help : Show this help
OUT, $context->getScriptName()));
	exit;
}

try {
	$interface->writeTitle('Deploy local test instance of Orpheus');
	if( $context->isDryRun() ) {
		$interface->write('This is a dry run, no change will apply.');
	}
	$interface->write('Active website : ' . $websitePath);
	$interface->write('From : ' . $orpheusPath);
	$interface->write('To : ' . $targetPath);
	
	// Copy orpheus project
	if( is_dir($targetPath) ) {
		if( $replace ) {
			$interface->write('Remove previous install : ' . $targetPath);
			$os->removePath($targetPath);
		} else if( !$overwrite ) {
			throw new InvalidArgumentException("Target folder already exists, use --overwrite or --replace to force");
		}
	}
	
	$targetConfigPath = $targetPath . '/' . basename($orpheusConfigPath);
	$copyPath = $overwrite ? '/*' : '';
	$os->run("cp -r $orpheusPath$copyPath $targetPath");
	$os->run("cp $websitePath/config/database.ini $targetPath/config/database.ini");
	$os->run("cp $websitePath/.env.local $targetPath/");
	$os->run("cp $orpheusConfigPath $targetConfigPath");
	// Reconfigure config file
	if( $context->isVerbose() ) {
		$interface->write('Reconfigure target of composer configuration');
	}
	if( !$context->isDryRun() ) {
		file_put_contents($targetConfigPath, setComposerConfig(file_get_contents($targetConfigPath), $orpheusProjectsPath));
	}
	
	if( $update ) {
		$os->run("$targetPath/scripts/composer-update.php -lv");
	}
	
	if( $permissions === 'posix' ) {
		$os->setPathReadable($targetPath);
		foreach( $webWritableFolders as $folder ) {
			$os->setPathWritable($targetPath . $folder);
		}
	} // TODO Add ACL support
} catch( Throwable $exception ) {
	$interface->reportException($exception);
}
