<?php

namespace FrontInterface;

use Task\Task;
use Throwable;

abstract class AbstractFrontInterface {
	
	public abstract function exec(Task $task): void;
	
	public abstract function hasInputTask(): bool;
	
	public abstract function getInputTask(): ?Task;
	
	public abstract function printHelp(): void;
	
	public abstract function write(string $text): void;
	
	public abstract function writeMasterTitle(string $text): void;
	
	public abstract function writeTitle(string $text): void;
	
	public abstract function writeSmallTitle(string $text): void;
	
	public abstract function reportException(Throwable $exception): void;
	
}
