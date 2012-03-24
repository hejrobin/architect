<!DOCTYPE html>
<html lang="en"><head>
	<?php $arch = \Architect::getInstance(); ?>
	<base href="<?= $view->base; ?>" />
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="false" />
	
		<title>Architect Framework</title>
	
	<link href="public/assets/css/reset.css" type="text/css" rel="stylesheet" />
	<link href="public/assets/css/architect.css" type="text/css" rel="stylesheet" />
	<link href="public/assets/css/jarvis.css" type="text/css" rel="stylesheet" />
	
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
	<![endif]-->
	
</head><body>
<div id="wrapper">

	<header>
	
		<h3>Architect Framework</h3>

		<h1>Congratulations!</h1>
		
	</header>
	
	<section class="meta">

		<p>If you see this message, you're in luck, because you've just successfully installed Architect Framework on your web server, and it's time for you to start developing your website or web application. Happy coding!</p>
		
		<p style="margin-top:15px;font-size:14px;">&mdash; Architect Framework Development Team</p>
		
	</section>
	
</div>
<?php \Jarvis\render_debugger(); ?>
</body></html>