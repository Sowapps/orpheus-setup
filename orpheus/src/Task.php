<?php

abstract class Task {
	
	/* @var FrontInterface $interface */
// 	protected $interface;
	
	public abstract function run(FrontInterface $out);
	
// 	public function getInterface() {
// 		return $this->interface;
// 	}
// 	public function setInterface(FrontInterface $interface) {
// 		$this->interface = $interface;
// 		return $this;
// 	}
}
