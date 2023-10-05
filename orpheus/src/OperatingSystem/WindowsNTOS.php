<?php

namespace OperatingSystem;

use Exception;

class WindowsNTOS extends AbstractOperatingSystem {
	
	public function run(string $command): void {
		throw new Exception('Method "run" is not implemented yet for Windows OS');
	}
	
	public function setPathReadable(string $path): void {
		throw new Exception('Method "setPathReadable" not implemented yet for Windows OS');
	}
	
	public function setPathWritable(string $path): void {
		throw new Exception('Method "setPathWritable" not implemented yet for Windows OS');
	}
	
	public function removePath(string $path): void {
		if( is_dir($path) ) {
			system('rd /s /q "' . $path . '"');
		} else {
			system('del /f /q "' . $path . '"');
		}
	}
	
}
