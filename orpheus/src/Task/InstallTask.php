<?php

namespace Task;

use Exception;
use FrontInterface\AbstractFrontInterface;

class InstallTask extends Task {
	
	protected string $composerInstallerURL = 'https://getcomposer.org/installer';
	protected string $composerInstallerFile = 'composer-setup.php';
	protected string $composerJSONFile = 'composer.json';
	
	protected ?string $projectName = null;
	
	/**
	 * @throws Exception
	 * @see Task::run()
	 */
	public function run(AbstractFrontInterface $out): void {
		/*
		 * Some Filesystem PHP function are not caught
		 * Take care of is_writable() and rename()
		 */
		// Phar requires realpath() or is_writable() always returns false
		
		$wd = APPLICATION_PATH;
		
		// Config
		chdir($wd);
		ignore_user_abort(true);
		set_time_limit(0);// No limit to execution
		
		if( !is_writable($wd) ) {
			throw new Exception('Install folder is not writable');
		}
		
		$out->writeMasterTitle('Orpheus Install');
		
		$out->write("Running on " . date('r') . "\n");
		
		// Install composer.phar
		$out->writeTitle('Get Composer');
		
		// TODO: Use official way
		// https://getcomposer.org/doc/faqs/how-to-install-composer-programmatically.md
		
		// Get composer setup
		copy($this->composerInstallerURL, $wd . '/' . $this->composerInstallerFile);
		
		// Run composer setup to get composer.phar
		putenv('COMPOSER_HOME=' . $wd . '/.composer');
		$command = 'php ' . $wd . '/' . $this->composerInstallerFile . ' 2>&1';
		system($command, $returnVal);
		if( $returnVal ) {
			throw new Exception('Something went wrong with ' . $this->composerInstallerFile . ', command "' . $command . '" returned value ' . $returnVal);
		}
		unlink($wd . '/' . $this->composerInstallerFile);
		
		// Allow relative and absolute paths
		$projectFolder = basename($this->getProjectName());
		$projectPath = file_exists($this->getProjectName()) ? $this->getProjectName() : $wd . '/' . $this->getProjectName();
		
		$out->write('');
		
		$folderExists = file_exists($projectPath);
		
		if( $folderExists && isComposerProject($projectPath) ) {
			$out->writeTitle('Update existing Orpheus project');
			if( !is_dir($projectPath) || !is_writable($projectPath) ) {
				throw new Exception('Project ' . $projectFolder . ' already exists and is not a writable folder.');
			}
			if( !isOrpheusProject($projectPath) ) {
				throw new Exception('Project ' . $projectFolder . ' already exists and is not a valid orpheus project.');
			}
			// Install composer dependencies in Orpheus
			$this->exec('php composer.phar install --working-dir ' . $projectFolder . ' --prefer-dist 2>&1', $out);
			$out->writeTitle("Installed Orpheus dependencies in existing project successfully !");
			
		} else {
			$out->writeTitle('Get Orpheus');
			// Start install of Orpheus
			$installPath = $projectFolder;
			if( $folderExists ) {
				$out->writeTitle(sprintf('Install Orpheus project in existing folder %s', $projectFolder));
				// If already exist, we first use another folder to then merge them
				do {
					$installPath = $projectFolder . '-' . generateRandomString(8);
				} while( file_exists($installPath) );
			}
			$command = 'php composer.phar create-project "orpheus/orpheus-framework" ' . $installPath . ' --prefer-dist 2>&1';
			$this->exec($command, $out);
			if( $folderExists ) {
				// Move temp install folder
				rmove($wd . '/' . $installPath, $wd . '/' . $projectFolder);
				force_rmdir($wd . '/' . $installPath, true);
			}
			
			try {
				foreach( $this->getUndesiredFileList() as $sourceName => $sourceFiles ) {
					$removed = false;
					foreach( $sourceFiles as $filePath ) {
						$path = $wd . '/' . $this->getProjectName() . '/' . $filePath;
						if( file_exists($path) ) {
							if( is_dir($path) ) {
								force_rmdir($path);
							} else {
								unlink($path);
							}
							$removed = true;
						}
					}
					if( $removed ) {
						$out->write(sprintf('%s files removed.', $sourceName));
					}
				}
			} catch( Exception $e ) {
				$out->write($e->getMessage());
			}
			
			$out->writeTitle("Installed Orpheus successfully !");
		}
		
	}
	
	function getUndesiredFileList(): array {
		return [
			'Eclipse'  => ['.settings', '.project', '.buildpath'],
			'IntelliJ' => ['.idea'],
		];
	}
	
	/**
	 * @param AbstractFrontInterface|null $out
	 * @throws Exception
	 */
	function exec(string $command, AbstractFrontInterface $out = null): void {
		$returnVal = null;
		system($command, $returnVal);
		$out?->write('');
		if( $returnVal ) {
			throw new Exception('Something went wrong running command "' . $command . '", returned value ' . $returnVal);
		}
	}
	
	function createComposerFile(): void {
		file_put_contents(APPLICATION_PATH . '/' . $this->composerJSONFile, json_encode([
			'minimum-stability' => 'dev',
			'require'           => [
				'orpheus/orpheus-framework' => '>=3.2.0',
			],
		]));
	}
	
	public function getProjectName(): ?string {
		return $this->projectName;
	}
	
	public function setProjectName(string $projectName): static {
		$this->projectName = $projectName;
		return $this;
	}
}
