<!DOCTYPE html>
<html lang="en"><head>
	
	<base href="<?= $view->base; ?>" />
	
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="false" />
	
		<title><?= $view->name; ?> &mdash; Architect Framework</title>
	
	<link href="public/assets/css/reset.css" type="text/css" rel="stylesheet" />
	<link href="public/assets/css/architect.css" type="text/css" rel="stylesheet" />
	<link href="public/assets/css/jarvis.css" type="text/css" rel="stylesheet" />
	
	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
	<![endif]-->
	
</head><body class="status">
<div id="wrapper">

	<header>
	
		<h3>HTTP <?= $view->type; ?></h3>

		<h1><?= $view->code; ?> <?= $view->name; ?></h1>
		
	</header>
	
	<section class="meta">

		<p>The <? if($view->type == 'Client Error' || $view->type == 'Server Error') : ?>request could not be interpreted, <? endif; ?>server responed with a "<mark><?= $view->code; ?> <?= $view->name; ?></mark>" HTTP status.</p>
		
		<p style="margin-top: 15px;"><?= $view->reason; ?></p>

	</section>
	
	<?php if(ARCH_DEBUG_MODE === true) : ?>
	<section class="backtrace">

		<h4>Exception</h4>

		<pre><?= $view->exception; ?></pre>

	</section>
	<?php endif; ?>
	
</div>
</body></html>