<?php

namespace OperatingSystem;

class UnixOS extends AbstractOperatingSystem {
	
	public function run(string $command): void {
		if( $this->context->isVerbose() ) {
			$this->interface->write("RUN: $command");
		}
		if( !$this->context->isDryRun() ) {
			passthru($command);
		}
	}
	
	public function setPathReadable(string $path, bool $recursive = false): void {
		$this->run(sprintf('chmod %s ugo+r "%s" | grep -v "Operation not permitted"', $recursive ? '-R' : '', $path));
	}
	
	public function setPathWritable(string $path, bool $recursive = false): void {
		$this->run(sprintf('chmod %s ugo+rw "%s" | grep -v "Operation not permitted"', $recursive ? '-R' : '', $path));
	}
	
	public function removePath(string $path): void {
		$this->run(sprintf('rm -rf "%s"', $path));
	}
}
