<?php

class ConsoleInterface extends FrontInterface {

	public function exec(Task $task) {
		
// 		ob_start();
		
		try {
			$task->run($this);
// 			$this->write('Run ok in console');
// 		} catch( Exception $e ) {
// 			throw $e;
		} finally {
// 			ob_end_flush();
// 			$this->write('Finally flushed console');
		}
// 		$this->write('Terminated task in console');
		
	}

	public function hasInputTask(): bool {
		return isset($_SERVER['argv'][1]);
	}
	
	public function getInputTask(): ?InstallTask {
		switch( $_SERVER['argv'][1] ) {
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
		
		return $task ?? null;
	}

	public function printHelp() {
		echo <<<EOF
USAGE
	{$_SERVER['argv'][0]} {command}

COMMANDS
	install projectname
	Install Orpheus in the projectname directory.
	
EOF;
	}
	
	public function write($text) {
		echo $text."\n";
	}
	
	public function writeMasterTitle($text) {
		$titlePadding = 20;
		$maxTextChar = 80 - $titlePadding;
		if( strlen($text) > $maxTextChar ) {
			$text = substr($text, 0, $maxTextChar);
		}
		$lineLength = strlen($text)+$titlePadding;
		
		$startRow = str_pad('', $lineLength, '*');
		$emptyRow = '*'.str_pad('', $lineLength-2, ' ').'*';
		echo <<<EOF
{$startRow}
{$emptyRow}
*         {$text}         *
{$emptyRow}
{$startRow}


EOF;
	}
	
	public function writeTitle($text) {
		$titlePadding = 20;
		$maxTextChar = 100 - $titlePadding;
		if( strlen($text) > $maxTextChar ) {
			$text = substr($text, 0, $maxTextChar);
		}
		echo <<<EOF
***  {$text}  ***


EOF;
	}
	
	public function writeSmallTitle($text) {
		echo $text . "\n";
	}
	
	public function reportException(Throwable $e) {
		echo '
*** ' . get_class($e) . '  ***

' . $e->getMessage() . '

* Stacktrace *
' . $e->getTraceAsString() . '
';
	}
	
}
