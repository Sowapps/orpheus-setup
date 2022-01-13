<?php
/**
 * @var HTMLRendering $rendering
 * @var HttpController $controller
 * @var HttpRequest $request
 * @var HttpRoute $route
 *
 * @var string $CONTROLLER_OUTPUT
 * @var string $content
 */

use Orpheus\InputController\HttpController\HttpController;
use Orpheus\InputController\HttpController\HttpRequest;
use Orpheus\InputController\HttpController\HttpRoute;
use Orpheus\Rendering\HTMLRendering;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Orpheus</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="Description" content=""/>
	<meta name="Author" content="Florent HAZARD"/>
	<meta name="application-name" content="Orpheus"/>
	<meta name="Keywords" content="carnet"/>
	<meta name="Robots" content="Index, Follow"/>
	<meta name="revisit-after" content="16 days"/>
	<link rel="icon" type="image/png" href="http://orpheus-framework.com/static/images/icon.png" />
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css" type="text/css" media="screen" />

</head>
<body>

<!-- 
<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="http://flo.orpheus-framework.com/">Orpheus</a>
		</div>
	</div>
</div>
 -->

<div class="container">
	
	<div class="header clearfix">
		<nav>
			<ul class="nav nav-pills pull-right">
<!-- 				<li role="presentation" class="active"><a href="#">Home</a></li> -->
				<li role="presentation"><a href="http://orpheus-framework.com/">Our Website</a></li>
			</ul>
		</nav>
		<h3 class="text-muted">Orpheus</h3>
	</div>
	
	<?php echo $content; ?>
	
	<footer class="footer">
		<p>Orpheus setup is licensed under the MIT license. Empowered by <a href="http://sowapps.com">Florent HAZARD</a>.</p>
	</footer>

</div>
<!-- JS libraries -->
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js" type="text/javascript"></script>
<!-- 	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script> -->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>

<style>
<?php readfile('static/css/style.css'); ?>
</style>

</body>
</html>
