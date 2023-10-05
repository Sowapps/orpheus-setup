<?php

define('IS_CONSOLE', isset($_SERVER['argv']));
const IS_WEB = !IS_CONSOLE;

function rmove(string $src, string $dst): void {
	$dir = opendir($src);
	@mkdir($dst);
	while( ($file = readdir($dir)) !== false ) {
		if( ($file !== '.') && ($file !== '..' ) ) {
			$srcPath = $src.'/'.$file;
			$destPath = $dst.'/'.$file;
			rename($srcPath, $destPath);
		}
	}
	closedir($dir);
	rmdir($src);
}

function force_rmdir(string $path, bool $recursive = false): void {
	$dir = opendir($path);
	while( ($file = readdir($dir)) !== false ) {
		if( ($file !== '.') && ($file !== '..' ) ) {
			$filePath = $path.'/'.$file;
			if( is_dir($filePath) ) {
				if( $recursive ) {
					force_rmdir($filePath);
				}
			} else {
				unlink($filePath);
			}
		}
	}
	closedir($dir);
	rmdir($path);
}

function createComposerFile(): void {
	file_put_contents('composer.json', json_encode(array(
		'minimum-stability' => 'dev',
		'require' => array(
			'orpheus/orpheus-framework' => '>=3.2.0'
		)
	)));
}

class ConsoleProcessing {
	protected string $composerInstall = 'composer-setup.php';
	
	public function run(): void {
		try {
			$command = $_SERVER['argv'][1] ?? null;
			if( !$command ) {
				throw new Exception('Require at least one parameter (php '.$_SERVER['PHP_SELF'].' install|update)');
			}
			switch( $command ) {
				case 'install': {
					$this->install();
					break;
				}
				case 'update': {
					$this->update();
					break;
				}
			}
		} catch( Exception $e ) {
			echo $e;
		}
	}
	
	/**
	 * @throws Exception
	 */
	protected function install(): void {
		if( !is_writable('.') ) {
			throw new Exception('Install folder is not writable');
		}
		// Install composer.phar
		copy('https://getcomposer.org/installer', $this->composerInstall);
		//if (hash_file('SHA384', 'composer-setup.php') === '92102166af5abdb03f49ce52a40591073a7b859a86e8ff13338cf7db58a19f7844fbc0bb79b2773bf30791e935dbd938') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;
		// require_once $this->composerInstall;
		// This script ends the current one, so we use exec
		echo "Installing composer\n";
		system('php '.$this->composerInstall);
		unlink($this->composerInstall);
		// Create json if not existing
		createComposerFile();
		// Start install
		exec('php composer.phar install');
		unlink('composer.lock');
		rmove('vendor/orpheus/orpheus-framework', '.');
		@force_rmdir('.settings');
		@unlink('.buildpath');
		@unlink('.project');
		$composerConfig = json_decode(file_get_contents('composer.json'), true);
		$composerConfig = array_intersect_key($composerConfig, array_flip(array('type', 'require')));
		file_put_contents('composer.json', json_encode($composerConfig));
		exec('php composer.phar install');
	}
	
	protected function update(): void {
		echo "Update feature is not implemented yet.\n";
	}
	
}

class WebProcessing {
	public function run(): void {
		try {
			echo "Web processing in progress";
		} catch( Exception $e ) {
			echo "An exception occurred\n".$e;
		}
	}
}

if( IS_CONSOLE ) {
	$process = new ConsoleProcessing();
} else {
	$process = new WebProcessing();
}
$process->run();
