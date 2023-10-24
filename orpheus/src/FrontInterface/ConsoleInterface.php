<?php

namespace FrontInterface;

use InvalidArgumentException;
use Task\InstallTask;
use Task\Task;
use Throwable;

class ConsoleInterface extends AbstractFrontInterface {
	
	public function exec(Task $task): void {
		
		$task->run($this);
		// 		ob_start();
		
		//		try {
		//			$task->run($this);
		// 			$this->write('Run ok in console');
		// 		} catch( Exception $e ) {
		// 			throw $e;
		//		} finally {
		// 			ob_end_flush();
		// 			$this->write('Finally flushed console');
		//		}
		// 		$this->write('Terminated task in console');
		
	}
	
	public function hasInputTask(): bool {
		return isset($_SERVER['argv'][1]);
	}
	
	public function getInputTask(): ?InstallTask {
		$task = null;
		switch( $_SERVER['argv'][1] ?? null ) {
			case 'install':
			{
				$task = new InstallTask();
				if( empty($_SERVER['argv'][2]) ) {
					throw new InvalidArgumentException('Missing projectname parameter');
				}
				$task->setProjectName($_SERVER['argv'][2]);
				break;
			}
			// 			case 'update': {
			// 				$this->update();
			// 				break;
			// 			}
		}
		if( !$task ) {
			throw new InvalidArgumentException('Invalid command');
		}
		
		return $task;
	}
	
	public function printHelp(): void {
		echo <<<EOF
{$_SERVER['argv'][0]} {command}

COMMANDS

install projectname
	Install Orpheus in the projectname directory.
	
EOF;
	}
	
	public function write($text): void {
		echo $text . "\n";
	}
	
	public function writeMasterTitle(string $text): void {
		$titlePadding = 20;
		$maxTextChar = 80 - $titlePadding;
		if( strlen($text) > $maxTextChar ) {
			$text = substr($text, 0, $maxTextChar);
		}
		$lineLength = strlen($text) + $titlePadding;
		
		$startRow = str_pad('', $lineLength, '*');
		$emptyRow = '*' . str_pad('', $lineLength - 2) . '*';
		echo <<<EOF
{$startRow}
{$emptyRow}
*         {$text}         *
{$emptyRow}
{$startRow}


EOF;
	}
	
	public function writeTitle(string $text): void {
		$titlePadding = 20;
		$maxTextChar = 100 - $titlePadding;
		if( strlen($text) > $maxTextChar ) {
			$text = substr($text, 0, $maxTextChar);
		}
		echo <<<EOF
***  {$text}  ***


EOF;
	}
	
	public function writeSmallTitle(string $text): void {
		echo $text . "\n";
	}
	
	public function reportException(Throwable $exception): void {
		echo '
*** ' . get_class($exception) . '  ***

' . $exception->getMessage() . '

* Stacktrace *
' . $exception->getTraceAsString() . '
';
	}
	
}
