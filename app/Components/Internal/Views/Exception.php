<?php $view->import('page.header.php'); ?>
<body class="exception">
<div id="backdrop">Oh no&hellip;</div>
<div id="wrapper">

	<header>

		<h3><?= $view->type; ?></h3>

		<h1><?= $view->name; ?></h1>

	</header>

	<article>

		<p>A <mark><?= $view->name; ?></mark> was thrown in <code><?= basename($view->file); ?></code> on line <?= $view->line; ?>&hellip;</p>

		<p><strong><?= $view->message; ?></strong><br /><?= $view->reason; ?></p>

	</article>

	<?php if(ARCH_ENVIRONMENT !== 'PRODUCTION') : ?>
	<section class="data">

		<table summary="Overview for <?= $view->name; ?> exception.">
			<caption>Overview</caption>
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
					<td>Reason</td>
					<td><?= $view->reason; ?></td>
				</tr>
				<tr>
					<td>Code</td>
					<td><?= $view->code; ?></td>
				</tr>
				<tr>
					<td>Type</td>
					<td><?= $view->code_string; ?></td>
				</tr>
				<tr>
					<td>Class</td>
					<td><?= $view->class; ?></td>
				</tr>
				<tr>
					<td>Context</td>
					<td><?= $view->context; ?></td>
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
	<?php endif; ?>

	<?php if(ARCH_DEBUG_MODE === true) : ?>
	<section class="backtrace">

		<h4>Backtrace</h4>

		<pre><? print_r($view->trace); ?></pre>

	</section>
	<?php endif; ?>

</div>
</body>
<?php $view->import('page.footer.php'); ?>