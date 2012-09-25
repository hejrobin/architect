<?php $view->import('page.header.php'); ?>
<body>
<div id="backdrop">Yay!</div>
<div id="wrapper">

	<header>

		<h3>Architect Framework</h3>

		<h1>Congratulations</h1>

	</header>

	<article>

		<p>If you see this message, you're in luck, because you've just successfully installed <strong>Architect Framework <span><?= ARCH_FRAMEWORK_VERSION; ?></span></strong> on your web server, and it's time for you to<br/> <mark>start developing</mark> your website or web application. Happy coding!</p>

		<p>Don&#8217;t forget to check out our <a href="http://github.com/lessthanthree/architect">GitHub repository</a> for updates.</p>

	</article>

</div>
<?php \Rae\renderOutput(); ?>
</body>
<?php $view->import('page.footer.php'); ?>