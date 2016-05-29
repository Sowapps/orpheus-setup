<?php

class WebInterface extends FrontInterface {

	public function exec(Task $task) {
		
	}

	public function hasInputTask() {
		return isset($_SERVER['argv'][1]);
	}

	public function getInputTask() {
		switch( $_SERVER['argv'][1] ) {
			case 'install': {
				return new InstallTask();
				break;
			}
// 			case 'update': {
// 				$this->update();
// 				break;
// 			}
		}
	}

	public function printHelp() {
		
	}
	
	public function write($text) {
	}
	
	public function writeMasterTitle($text) {
		
	}
	
	public function writeTitle($text) {
	}
	
	public function writeSmallTitle($text) {
	}
	
}
