<?php

class WindowsNTOS extends OperatingSystem {

	public function removePath($path) {
		if( is_dir($path) ) {
			system('rd /s /q "'.$path.'"');
		} else {
			system('del /f /q "'.$path.'"');
		}
	}
	
}
