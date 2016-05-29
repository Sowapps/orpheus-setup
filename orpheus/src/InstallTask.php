<?php

class InstallTask extends Task {

	protected $composerInstallURL = 'https://getcomposer.org/installer';
	protected $composerInstallFile = 'composer-setup.php';
	protected $composerJSONFile = 'composer.json';

	/**
	 * @see Task::run()
	 */
	public function run(FrontInterface $out) {
		/*
		 * Some Filesystem PHP function are not caught
		 * Take care of is_writable() and rename()
		 */
		// Phar requires realpath() or is_writable() always returns false
// 		chdir(realpath('.'));
		$wd = str_replace('\\', '/', realpath('.'));
		if( !is_writable($wd) ) {
// 		if( !is_writable('.') ) {
// 			echo 'RealPath => '.realpath('.')."\n";
			throw new Exception('Install folder is not writable');
		}
// 		file_put_contents('test-OK.txt', 'OK');
// 		return;
		$out->writeMasterTitle('Orpheus Install');
		
		$out->write("Running on ".date('r')."\n");
		
		// Install composer.phar
		$out->writeTitle('Get Composer');
		copy($this->composerInstallURL, $this->composerInstallFile);
		system('php '.$this->composerInstallFile);
		unlink($this->composerInstallFile);
		
		//if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;
		// require_once $this->composerInstall;
		// This script ends the current one, so we use exec
// 		echo "Installing composer\n";

		$out->writeTitle('Get Orpheus');
		// Create json if not existing
		$this->createComposerFile();
		// Start install of Orpheus
		system('php composer.phar install --prefer-dist');
// 		if( OperatingSystem::isWindowsNT() ) {
// 			system('attrib -R "vendor/orpheus/orpheus-framework" /S /D');
// 		}
		rcopy($wd.'/vendor/orpheus/orpheus-framework', $wd);
		OperatingSystem::getCurrent()->removePath($wd.'/vendor/orpheus/orpheus-framework');

// 		$out->writeTitle('Clean Orpheus');
		
		unlink($wd.'/composer.lock');
		@force_rmdir($wd.'/.settings');
		@unlink($wd.'/.buildpath');
		@unlink($wd.'/.project');

		$out->writeTitle('Get Orpheus dependencies');
		$composerConfig = json_decode(file_get_contents($this->composerJSONFile), true);
		$composerConfig = array_intersect_key($composerConfig, array_flip(array('type', 'require')));
		file_put_contents($this->composerJSONFile, json_encode($composerConfig));
		// Retrieve Orpheus dependencies
		system('php composer.phar install');

// 		die("Composer install terminated\n");
// 		echo "Composer install terminated\n";
	}

	function createComposerFile() {
		// if( file_exists('composer.json') ) {
			// return;
		// }
		file_put_contents($this->composerJSONFile, json_encode(array(
			'minimum-stability' => 'dev',
			'require' => array(
				'orpheus/orpheus-framework' => '>=3.2.0'
			)
		)));
	}
	
}
