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
// 		$out->write("Phar::running() => ".dirname(Phar::running(false)));
// 		$out->write("APPLICATION_PATH => ".APPLICATION_PATH);
// 		$out->write("__DIR__ => ".__DIR__);
// 		$out->write("getcwd() => ".getcwd());
// 		$wd = str_replace('\\', '/', realpath('.'));
		$wd = APPLICATION_PATH;
		
		// Config
		chdir($wd);
		ignore_user_abort(true);
		set_time_limit(0);// No limit to execution
		
// 		$out->write("Working directory => $wd");
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
// 		$out->write("copy({$this->composerInstallURL}, {$wd}/{$this->composerInstallFile});");
		copy($this->composerInstallURL, $wd.'/'.$this->composerInstallFile);
// 		$out->write('pwd');
// 		system('pwd');
// 		$out->write('');
		putenv('COMPOSER_HOME='.$wd.'/.composer');
// 		$out->write('php '.$wd.'/'.$this->composerInstallFile.' 2>&1');
		$command = 'php '.$wd.'/'.$this->composerInstallFile.' 2>&1';
		system($command, $returnVal);
// 		$out->write('');
		if( $returnVal ) {
			throw new Exception('Something went wrong with '.$this->composerInstallFile.', command "'.$command.'" returned value '.$returnVal);
		}
// 		$out->write('Command returned => '.$return);
		unlink($wd.'/'.$this->composerInstallFile);
		
		//if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;
		// require_once $this->composerInstall;
		// This script ends the current one, so we use exec
// 		echo "Installing composer\n";

		$out->writeTitle('Get Orpheus');
		// Create json if not existing
		$this->createComposerFile();
		// Start install of Orpheus
		system('php '.$wd.'/composer.phar install --prefer-dist 2>&1');
		$out->write('');
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

		$out->writeTitle("Get Orpheus dependencies");
		$composerConfig = json_decode(file_get_contents($wd.'/'.$this->composerJSONFile), true);
		$composerConfig = array_intersect_key($composerConfig, array_flip(array('type', 'require')));
		file_put_contents($wd.'/'.$this->composerJSONFile, json_encode($composerConfig));
		// Retrieve Orpheus dependencies
		system('php '.$wd.'/composer.phar install 2>&1');
		echo "\n";

		$out->writeTitle("Installed Orpheus successfully !");

// 		die("Composer install terminated\n");
// 		echo "Composer install terminated\n";
	}

	function createComposerFile() {
		// if( file_exists('composer.json') ) {
			// return;
		// }
		file_put_contents(APPLICATION_PATH.'/'.$this->composerJSONFile, json_encode(array(
			'minimum-stability' => 'dev',
			'require' => array(
				'orpheus/orpheus-framework' => '>=3.2.0'
			)
		)));
	}
	
}
