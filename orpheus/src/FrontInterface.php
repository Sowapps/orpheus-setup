<?php

abstract class FrontInterface {

	public abstract function exec(Task $task);

	public abstract function hasInputTask();
	
	public abstract function getInputTask();
	
	public abstract function printHelp();
	
	public abstract function write($text);
	
	public abstract function writeMasterTitle($text);
	
	public abstract function writeTitle($text);
	
	public abstract function writeSmallTitle($text);
	
	public abstract function reportException(Throwable $e);
	
}
