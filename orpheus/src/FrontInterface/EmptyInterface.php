<?php

namespace FrontInterface;

use Task\Task;
use Throwable;

class EmptyInterface extends AbstractFrontInterface {
	
	public function exec(Task $task): void {
	}
	
	public function hasInputTask(): bool {
		return false;
	}
	
	public function getInputTask(): null {
		return null;
	}
	
	public function printHelp(): void {
	}
	
	public function write($text): void {
	}
	
	public function writeMasterTitle(string $text): void {
	}
	
	public function writeTitle(string $text): void {
	}
	
	public function writeSmallTitle(string $text): void {
	}
	
	public function reportException(Throwable $exception): void {
	}
	
}
