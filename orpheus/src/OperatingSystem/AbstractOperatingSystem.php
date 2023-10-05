<?php

namespace OperatingSystem;

use FrontInterface\AbstractFrontInterface;
use FrontInterface\EmptyInterface;
use ProcessContext\AbstractContext;
use ProcessContext\EmptyContext;

abstract class AbstractOperatingSystem {
	
	protected AbstractContext $context;
	protected AbstractFrontInterface $interface;
	
	protected static AbstractOperatingSystem $current;
	
	public function __construct() {
		$this->context = new EmptyContext();
		$this->interface = new EmptyInterface();
	}
	
	abstract function run(string $command): void;
	
	abstract function setPathReadable(string $path): void;
	
	abstract function setPathWritable(string $path): void;
	
	abstract function removePath(string $path);
	
	//	abstract function setContext(AbstractContext $context): void;
	
	
	public function getContext(): AbstractContext {
		return $this->context;
	}
	
	public function setContext(AbstractContext $context): void {
		$this->context = $context;
	}
	
	public function getInterface(): AbstractFrontInterface {
		return $this->interface;
	}
	
	public function setInterface(AbstractFrontInterface $interface): void {
		$this->interface = $interface;
	}
	
	//	public abstract function getSystemInterface(): AbstractSystemInterface;
	
	public static function getCurrent(): static {
		return static::$current ??= (static::isWindowsNT() ? new WindowsNTOS() : new UnixOS());
	}
	
	public static function isWindowsNT(): bool {
		return isset($_SERVER['OS']) && $_SERVER['OS'] === 'Windows_NT';
	}
}
