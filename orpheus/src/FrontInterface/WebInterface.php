<?php

namespace FrontInterface;

use Task\InstallTask;
use Task\Task;
use Throwable;

class WebInterface extends AbstractFrontInterface {
	
	public function exec(Task $task): void {
		
		ob_start();
		
		try {
			$task->run($this);
			
		} catch( Exception $exception ) {
			// TODO: Add back button
			?>
			<div class="panel panel-danger">
				<div class="panel-heading">An error occurred !</div>
				<div class="panel-body exception">
					<h3><?php echo get_class($exception); ?></h3>
					<blockquote>
						<?php echo $exception->getMessage(); ?>
						<footer>In <cite><?php echo $exception->getFile(); ?></cite> at line <?php echo $exception->getLine(); ?></footer>
					</blockquote>
					<pre><?php echo $exception->getTraceAsString(); ?></pre>
				</div>
			</div>
			<?php
			
		} finally {
			$this->writeContent(ob_get_clean());
		}
		
	}
	
	public function hasInputTask(): bool {
		return isset($_POST['submitInstall']);
	}
	
	public function getInputTask(): ?InstallTask {
		if( isset($_POST['submitInstall']) ) {
			return new InstallTask();
		}
		
		return null;
	}
	
	public function printHelp(): void {
		$this->writeContent(<<<EOF

<div class="jumbotron">
	<h1>Orpheus Setup</h1>
	<p class="lead">Welcome to the installation setup of Orpheus,<br>
We will install the last version of Orpheus using composer.</p>
	
	<form method="POST">
	<p>
		<button class="btn btn-lg btn-success" type="submit" name="submitInstall">
			Process installation <i class="fa fa-chevron-right"></i>
		</button>
	</p>
	</form>
</div>
<div class="row marketing">
	<div class="col-lg-6">
		<h4>Light &amp; Optimized</h4>
		<p>The Orpheus framework is very light and optimized for all production uses.</p>

		<h4>Developer Oriented</h4>
		<p>Orpheus is designed to help the developer to make a proper code quickly.</p>

		<h4>Object Oriented</h4>
		<p>Orpheus is designed to fully respect the object features and integrity.</p>
	</div>

	<div class="col-lg-6">
		<h4>Powerful</h4>
		<p>Orpheus is fully-featured, install it and let it do the job for you.</p>

		<h4>Open Source</h4>
		<p>Orpheus is free, open source and available for personal &amp; commercials uses.</p>

		<h4>Unbeatable</h4>
		<p>There is no other PHP solution able to beat the power of Orpheus, try it, you will love it.</p>
	</div>
</div>
EOF
		);
	}
	
	public function write(string $text): void {
		echo '<p>' . $text . '</p>';
	}
	
	public function writeMasterTitle(string $text): void {
		echo '<h1>' . $text . '</h1>';
	}
	
	public function writeTitle(string $text): void {
		echo '<h3>' . $text . '</h3>';
	}
	
	public function writeSmallTitle(string $text): void {
		echo '<h5>' . $text . '</h5>';
	}
	
	public function writeContent(string $content): void {
		include __DIR__ . '/../static/layouts/document.web.php';
	}
	
	public function reportException(Throwable $exception): void {
		echo '
*** Error ' . get_class($exception) . '  ***

' . $exception->getMessage() . '

* Stacktrace *
' . $exception->getTraceAsString() . '
';
	}
	
}
