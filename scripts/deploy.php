<?php
/*
 * EXPERIMENTAL FEATURE ! UNSTABLE !
 */

const PROJECT_PATH = './';
const COMPOSER_CONFIG_PATH = PROJECT_PATH . 'composer.json';
const COMPOSER_SETUP_PATH = PROJECT_PATH . 'composer-setup.php';
const COMPOSER_PHAR_PATH = './composer.phar';
const VENDOR_PATH = PROJECT_PATH . 'vendor';

$fatalError = null;
try {
	// Fatal system error
	if( !is_writable(PROJECT_PATH) ) {
		throw new Exception('This folder should be writable to use composer.');
	}
	if( !is_readable(COMPOSER_CONFIG_PATH) ) {
		throw new Exception(sprintf('Application is unable to read composer file at "%s".', COMPOSER_CONFIG_PATH));
	}
} catch( Exception $e ) {
	$fatalError = $e;
}

$userError = null;
try {
	// Non-Fatal system error
	if( is_readable(VENDOR_PATH) ) {
		throw new Exception('This project is already deployed !');
	}
} catch( Exception $e ) {
	$userError = $e;
}

function execCommand(string $cmd, ?string &$output = null): int {
	if( !$output ) {
		$output = '';
	}
	$output .= 'Run command: ' . $cmd . '<br>';
	// exec($cmd.' 2>&1', $output);
	// $output = implode("\n", $output);
	
	// $output .= shell_exec($cmd);
	
	ob_start();
	$return = null;
	system($cmd . ' 2>&1', $return);
	$cmdOutput = ob_get_clean();
	// $output .= $cmdOutput;
	$output .= 'Got Result: ' . $return . ' [' . ($return ? 'ERROR' : 'OK') . ']<br>';
	
	$output .= 'Got Output [' . strlen($cmdOutput) . ']: <pre class="m-b-0">' . $cmdOutput . '</pre>';
	// $output .= 'Got Output ['.strlen($cmdOutput).']: <pre>'.$cmdOutput.'</pre><br>';
	return 1;
	// return $return;
}

$output = null;
try {
	// User Action Error
	
	if( isset($_POST['submitStartDeployment']) ) {
		//		$output = 'Start to deploy<br>';
		//		putenv('COMPOSER_HOME="'.PROJECT_PATH.'"');
		//		// TODO: Add deploy lock on server
		//		if( !is_readable(COMPOSER_PHAR_PATH) ) {
		//			copy('https://getcomposer.org/installer', COMPOSER_SETUP_PATH);
		//			execCommand('php '.COMPOSER_SETUP_PATH.' --install-dir="'.PROJECT_PATH.'"', $output);
		//			unlink(COMPOSER_SETUP_PATH);
		//			if( !is_readable(COMPOSER_PHAR_PATH) ) {
		//				throw new Exception('Failed to get composer.phar');
		//			}
		//		}
		//		execCommand('cd "'.PROJECT_PATH.'"; php '.COMPOSER_PHAR_PATH.' install', $output);
	}
	
} catch( Exception $e ) {
	$userError = $e;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags always come first -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Deploy an Orpheus Application</title>
	
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/css/bootstrap.min.css"/>
</head>
<body style="padding-top: 5rem;">

<nav class="navbar navbar-fixed-top navbar-dark bg-inverse">
	<a class="navbar-brand" href="deploy.php">Orpheus Deploy Tool</a>
</nav>

<div class="container text-xs-center">
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			
			<h1>Welcome to the composer's hell !</h1>
			<p>This tool is designed to help you to deploy your app on a new server or a new instance.</p>
			
			<?php
			if( $fatalError ) {
				?>
				<div class="alert alert-danger" role="alert">
					<strong title="This is a fatal error">Mayday !</strong> <?php echo $fatalError->getMessage(); ?>
				</div>
				<?php
			} else {
				if( $userError ) {
					?>
					<div class="alert alert-warning" role="alert">
						<strong title="This is a user-action error">Oops !</strong> <?php echo $userError->getMessage(); ?>
					</div>
					<?php
				}
				if( $output ) {
					if( !$userError ) {
						?>
						<div class="alert alert-success" role="alert">
							<strong title="This is a success">Yeah !</strong> We successfully deployed your application.
						</div>
						<?php
					}
					?>
					<blockquote class="blockquote text-xs-left" style="font-size: 0.9rem;">
						<?php echo $output; ?>
					</blockquote>
					<?php
					
				}
				if( !$output || $userError ) {
					?>
					<h3>Deploy your App !</h3>
					<p>Orpheus is ready to start the deployment, just press this big button. ;-)</p>
					<form method="POST">
						<input type="hidden" name="submitStartDeployment" value="1"/>
						<button type="submit" class="action-deploy btn btn-primary btn-lg">START</button>
					</form>
					<?php
				}
			}
			
			
			?>
		</div>
	</div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.2/js/bootstrap.min.js"></script>

<script>
	window.addEventListener("DOMContentLoaded", () => {
		document.querySelectorAll(".action-deploy")
			.forEach($element => {
				$element.addEventListener("click", () => {
					$(this).prop("disabled", true).text("Deploying your app...");
					$(this).closest("form").submit();
					return true;
				});
			});
	});
</script>
</body>
</html>
