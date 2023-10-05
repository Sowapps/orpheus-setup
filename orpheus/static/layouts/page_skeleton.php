<?php
/**
 * @var HtmlRendering $rendering
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
use Orpheus\Rendering\HtmlRendering;

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
	<link rel="icon" type="image/png" href="https://orpheus-framework.com/static/images/icon.png"/>
	
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/css/bootstrap.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" media="screen"/>

</head>
<body>

<div class="container">
	
	<div class="header clearfix">
		<nav>
			<ul class="nav nav-pills pull-right">
				<li role="presentation"><a href="https://orpheus-framework.com/">Our Website</a></li>
			</ul>
		</nav>
		<h3 class="text-muted">Orpheus</h3>
	</div>
	
	<?php echo $content; ?>
	
	<footer class="footer">
		<p>Orpheus setup is licensed under the MIT license. Empowered by <a href="https://sowapps.com">Florent HAZARD</a>.</p>
	</footer>

</div>
<!-- JS libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.3/js/bootstrap.min.js"></script>

<?php /** @noinspection HtmlUnknownTag */ ?>
<style>
<?php readfile('static/css/style.css'); ?>
</style>

</body>
</html>
