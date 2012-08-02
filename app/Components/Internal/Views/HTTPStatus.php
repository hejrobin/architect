<?php $view->import('page.header.php'); ?>
<body class="<? if($view->type == 'Client Error') : ?>status<? endif; ?><? if($view->type == 'Server Error') : ?>exception<? endif; ?>">
<div id="backdrop"><?= $view->code; ?></div>
<div id="wrapper">

	<header>

		<h3><?= $view->type; ?></h3>

		<h1><?= $view->code; ?> <?= $view->name; ?></h1>

	</header>

	<article>

		<p>The <? if($view->type == 'Client Error' || $view->type == 'Server Error') : ?>request could not be interpreted,<br/> <? endif; ?>server responed with a "<mark><?= $view->code; ?> <?= $view->name; ?></mark>" status.</p>

	</article>

	<?php if(ARCH_DEBUG_MODE === true && ARCH_ENVIRONMENT !== 'PRODUCTION') : ?>
	<section class="backtrace">

		<h4>Backtrace</h4>

		<pre><? print_r($view->trace); ?></pre>

	</section>
	<?php endif; ?>

</div>
</body>
<?php $view->import('page.footer.php'); ?>