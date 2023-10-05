<?php

namespace Task;

use FrontInterface\AbstractFrontInterface;

abstract class Task {
	
	/* @var AbstractFrontInterface $interface */
	// 	protected $interface;
	
	public abstract function run(AbstractFrontInterface $out);
	
	// 	public function getInterface() {
	// 		return $this->interface;
	// 	}
	// 	public function setInterface(FrontInterface $interface) {
	// 		$this->interface = $interface;
	// 		return $this;
	// 	}
}
