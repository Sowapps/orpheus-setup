<?php

abstract class OperatingSystem {

	protected static $current;
	
	
	public abstract function removePath($path);
	
	
	/**
	 * @return OperatingSystem
	 */
	public static function getCurrent() {
		if( !static::$current ) {
			static::$current = static::isWindowsNT() ? new WindowsNTOS() : new UnixOS();
		}
		return static::$current;
	}
	
	public static function isWindowsNT() {
		return isset($_SERVER['OS']) && $_SERVER['OS']==='Windows_NT';
	}
}
