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
	
</head><body class="error">
<div id="wrapper">

	<header>
	
		<h3><?= $view->type; ?></h3>

		<h1><?= str_ireplace('_', ' ',  $view->name); ?></h1>
		
	</header>
	
	<section class="meta">

		<p>A <mark><?= str_ireplace('_', ' ', strtolower($view->name)); ?></mark> was triggered in <code><?= basename($view->file); ?></code>, more information specified below&hellip;</p>

	</section>
	
	<section class="overview">

		<table summary="Overview for <?= $view->name; ?> error exception.">
			<caption>Error overview</caption>
			<thead>
				<tr>
					<th scope="col">Context</th>
					<th scope="col">Information</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>Message</td>
					<td><?= $view->message; ?></td>
				</tr>
				<tr>
					<td>Error Code</td>
					<td><?= $view->code; ?></td>
				</tr>
				<tr>
					<td>Error Type</td>
					<td><?= $view->code_string; ?></td>
				</tr>
				<tr>
					<td>File</td>
					<td><?= $view->file; ?></td>
				</tr>
				<tr>
					<td>Line</td>
					<td><?= $view->line; ?></td>
				</tr>
			</tbody>
		</table>

	</section>
	
	<?php if(ARCH_DEBUG_MODE === true) : ?>
	<section class="backtrace">

		<h4>Exception backtrace</h4>

		<pre><? print_r($view->trace); ?></pre>

	</section>
	<?php endif; ?>
	
</div>
<?php af_debug_console(); ?>
</body></html>