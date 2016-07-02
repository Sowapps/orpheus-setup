<?php

class InstallTask extends Task {

	protected $composerInstallerURL = 'https://getcomposer.org/installer';
	protected $composerInstallerFile = 'composer-setup.php';
	protected $composerJSONFile = 'composer.json';

	protected $projectName;
	
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
		
		// TODO: Use official way
		// https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
		
		// Get composer setup
// 		$out->write("copy({$this->composerInstallerURL}, {$wd}/{$this->composerInstallerFile});");
		copy($this->composerInstallerURL, $wd.'/'.$this->composerInstallerFile);
		
// 		$out->write('pwd');
// 		system('pwd');
// 		$out->write('');

		// Run composer setup to get composer.phar
		putenv('COMPOSER_HOME='.$wd.'/.composer');
// 		$out->write('php '.$wd.'/'.$this->composerInstallerFile.' 2>&1');
		$command = 'php '.$wd.'/'.$this->composerInstallerFile.' 2>&1';
		system($command, $returnVal);
// 		$out->write('');
		if( $returnVal ) {
			throw new Exception('Something went wrong with '.$this->composerInstallerFile.', command "'.$command.'" returned value '.$returnVal);
		}
// 		$out->write('Command returned => '.$return);
		unlink($wd.'/'.$this->composerInstallerFile);

		// Allow relative and absolute paths
		$projectFolder = basename($this->getProjectName());
		$projectPath = file_exists($this->getProjectName()) ? $this->getProjectName() : $wd.'/'.$this->getProjectName();
// 		$out->write("Is project folder \"{$projectFolder}\" existing in \"".$wd."\" ? ".(file_exists($projectPath) ? 'YES' : 'no'));
		
		$out->write('');
		
		if( file_exists($projectPath) ) {
			$out->writeTitle('Update existing Orpheus project');
			if( !is_dir($projectPath) || !is_writable($projectPath) ) {
				throw new Exception('Project '.$projectFolder.' already exists and is not a folder.');
			}
			if( !isComposerProject($projectPath) ) {
				throw new Exception('Project '.$projectFolder.' already exists and is not a valid composer project.');
			}
			if( !isOrpheusProject($projectPath) ) {
				throw new Exception('Project '.$projectFolder.' already exists and is not a valid composer project.');
			}
			// Install composer dependencies in Orpheus
			$this->exec('php composer.phar install --working-dir '.$projectFolder.' --prefer-dist 2>&1', $out);
// 			system($command, $returnVal);
// 			$out->write('');
// 			if( $returnVal ) {
// 				throw new Exception('Something went wrong with composer.phar, command "'.$command.'" returned value '.$returnVal);
// 			}
			$out->writeTitle("Installed Orpheus dependencies in existing project successfully !");
			
		} else {
			$out->writeTitle('Get Orpheus');
			// Start install of Orpheus
			$command = 'php composer.phar create-project "orpheus/orpheus-framework" '.$projectFolder.' --prefer-dist 2>&1';
// 			$command = 'php '.$wd.'/composer.phar create-project "orpheus/orpheus-framework" '.$projectFolder.' --prefer-dist 2>&1';
			$this->exec($command, $out);
// 			system($command, $returnVal);
// 			$out->write('');
// 			if( $returnVal ) {
// 				throw new Exception('Something went wrong with composer.phar, command "'.$command.'" returned value '.$returnVal);
// 			}
			
			@force_rmdir($wd.'/'.$this->getProjectName().'/.settings');
			@unlink($wd.'/'.$this->getProjectName().'/.buildpath');
			@unlink($wd.'/'.$this->getProjectName().'/.project');
			
			$out->writeTitle("Installed Orpheus successfully !");
		}
		
	}

	function exec($command, FrontInterface $out=null) {
		$returnVal = null;
		system($command, $returnVal);
		if( $out ) {
			$out->write('');
		}
		if( $returnVal ) {
			throw new Exception('Something went wrong running command "'.$command.'", returned value '.$returnVal);
		}
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
	
	public function getProjectName() {
		return $this->projectName;
	}
	public function setProjectName($projectName) {
		$this->projectName = $projectName;
		return $this;
	}
}
