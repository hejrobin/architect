<link href="libs/Jarvis/Debugger/public/assets/css/jarvis.css" type="text/css" rel="stylesheet" />
<?php $logs = \Jarvis\Console::getLogs(); ?>
<pre><?php print_r($logs['Runtime']); ?></pre>
<section id="jarvis">

	<ul id="jarvis-tabs" class="clearfix">
		<li data-index="0" class="active"><a href="#jarvis-overview"><i>³</i><span>Overview</span></a></li>
		<li data-index="1"><a href="#jarvis-console"><i>_</i><span>Console</span></a></li>
		<li data-index="2"><a href="#jarvis-benchmarks"><i>P</i><span>Benchmarks</span></a></li>
		<li data-index="3"><a href="#jarvis-memory"><i>K</i><span>Memory</span></a></li>
		<li data-index="4"><a href="#jarvis-constants"><i>A</i><span>Constants</span></a></li>
		<li data-index="5"><a href="#jarvis-files"><i>a</i><span>Files</span></a></li>
		<li data-index="6"><a href="#jarvis-runtime"><i>S</i><span>Runtime</span></a></li>
		<li data-settings="no-text,right"><a id="jarvis-toggle" href="#jarvis-toggle"><i>%</i><span>Toggle</span></a></li>
	</ul>

	<div id="jarvis-container">
		
		<div id="jarvis-overview" class="panel overview">
			
			<h3><span>Jarvis</span> Overview</h3>
			
			<ol class="overview">
				<li class="clearfix">
					<strong><span>Console</span></strong>
					<ul>
						<li><span>Entries</span>: <var><?php echo $logs['Console']['num_entries']; ?></var></li>
					</ul>
				</li>
				<li class="clearfix">
					<strong><span>Benchmark</span></strong>
					<ul>
						<li><span>Entries</span>: <var><?php echo $logs['Benchmark']['num_entries']; ?></var></li>
						<li><span>Execution Time</span>: <var><?php echo \Jarvis\readable_time($logs['Benchmark']['execution_time']); ?></var></li>
						<li><span>Max Execution Time</span>: <var><?php echo \Jarvis\readable_time($logs['Benchmark']['max_execution_time'], 2); ?></var></li>
					</ul>
				</li>
				<li class="clearfix">
					<strong><span>Memory</span></strong>
					<ul>
						<li><span>Entries</span>: <var><?php echo $logs['Memory']['num_entries']; ?></var></li>
						<li><span>Memory Peak</span>: <var><?php echo \Jarvis\readable_bytesize($logs['Memory']['memory_peak']); ?></var></li>
						<li><span>Memory Limit</span>: <var><?php echo \Jarvis\readable_bytesize($logs['Memory']['memory_limit']); ?></var></li>
					</ul>
				</li>
				<li class="clearfix">
					<strong><span>Constants</span></strong>
					<ul>
						<li><span>Entries</span>: <var><?php echo $logs['Constant']['num_entries']; ?></var></li>
					</ul>
				</li>
				<li class="clearfix">
					<strong><span>Files</span></strong>
					<ul>
						<li><span>Entries</span>: <var><?php echo $logs['File']['num_entries']; ?></var></li>
						<li><span>Largest File</span>: <var><?php echo \Jarvis\readable_bytesize($logs['File']['largest_file']['size']); ?></var></li>
						<li><span>Smallest File</span>: <var><?php echo \Jarvis\readable_bytesize($logs['File']['smallest_file']['size']); ?></var></li>
					</ul>
				</li>
			</ol>

		</div>
		
		<div id="jarvis-console" class="panel">
			<ol class="entries">
				<?php foreach($logs['Console']['entries'] as $entry) : ?>
				<li>
					<ul class="clearfix">
						<li class="time"><?php echo date('H:i:s', $entry['time']); ?></li>
						<li class="type"><?php echo $entry['entry']['name']; ?></li>
						<li class="text"><span><?php echo $entry['entry']['text']; ?></span></li>
						<li class="file"><abbr title="<?php echo $entry['file']; ?>"><?php echo basename($entry['file']); ?></abbr></li>
						<li class="line"><?php echo $entry['line']; ?></li>
						<li class="data"><span>&nbsp;</span></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
		
		<div id="jarvis-benchmark" class="panel">
			<ol class="entries">
				<?php foreach($logs['Benchmark']['entries'] as $entry) : ?>
				<li>
					<ul class="clearfix">
						<li class="time"><?php echo date('H:i:s', $entry['time']); ?></li>
						<li class="type"><?php echo $entry['entry']['name']; ?></li>
						<li class="text"><span><?php echo $entry['entry']['text']; ?></span></li>
						<li class="file"><abbr title="<?php echo $entry['file']; ?>"><?php echo basename($entry['file']); ?></abbr></li>
						<li class="line"><?php echo $entry['line']; ?></li>
						<li class="data"><span><?php if(isset($entry['entry']['time_diff']) === true) { echo \Jarvis\readable_time($entry['entry']['time_diff']); } ?></span></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
		
		<div id="jarvis-memory" class="panel">
			<ol class="entries">
				<?php foreach($logs['Memory']['entries'] as $entry) : ?>
				<li>
					<ul class="clearfix">
						<li class="time"><?php echo date('H:i:s', $entry['time']); ?></li>
						<li class="type"><?php echo ucfirst($entry['entry']['type']); ?></li>
						<li class="text"><span><?php echo $entry['entry']['text']; ?></span></li>
						<li class="file"><abbr title="<?php echo $entry['file']; ?>"><?php echo basename($entry['file']); ?></abbr></li>
						<li class="line"><?php echo $entry['line']; ?></li>
						<li class="data"><span><? echo \Jarvis\readable_bytesize($entry['entry']['bytes']); ?></span></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
		
		<div id="jarvis-constants" class="panel">
			<ol class="entries">
				<?php foreach($logs['Constant']['entries'] as $entry) : ?>
				<li>
					<ul class="clearfix">
						<li class="time"><?php echo date('H:i:s', $entry['time']); ?></li>
						<li class="type"><?php echo ucfirst(gettype(constant($entry['entry']['name']))); ?></li>
						<li class="text"><span><abbr title="<?php if(gettype(constant($entry['entry']['name'])) === 'boolean') { echo ($entry['entry']['text']) ? 'true' : 'false'; } else { echo $entry['entry']['text']; }; ?>"><?php echo $entry['entry']['name']; ?></abbr></span></li>
						<li class="file">&nbsp;</li>
						<li class="line">&nbsp;</li>
						<li class="data"><span>&nbsp;</span></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
		
		<div id="jarvis-file" class="panel">
			<ol class="entries">
				<?php foreach($logs['File']['entries'] as $entry) : ?>
				<li>
					<ul class="clearfix">
						<li class="time"><?php echo date('H:i:s', $entry['time']); ?></li>
						<li class="type">File</li>
						<li class="text"><span><abbr title="<?php echo $entry['entry']['name']; ?>"><?php echo basename($entry['entry']['text']); ?></abbr></span></li>
						<li class="file">&nbsp;</li>
						<li class="line">&nbsp;</li>
						<li class="data"><span><? echo \Jarvis\readable_bytesize($entry['entry']['size']); ?></span></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ol>
		</div>
		
		<div id="jarvis-runtime" class="panel overview">
			
			<h3><span>Runtime</span> Overview</h3>
			
			<ol class="overview">
				<?php foreach($logs['Runtime']['entries'] as $entry) : ?>
				<li class="clearfix">
					<strong><span><?php echo $entry['entry']['name']; ?></span></strong>
					<ul>
						<li><var><?php echo $entry['entry']['text']; ?></var></li>
					</ul>
				</li>
				<?php endforeach; ?>
			</ol>

		</div>
		
	</div>

</section>

<?php require_once(JARVIS_ROOT_PATH . 'Debugger' . DIRECTORY_SEPARATOR . 'js.php'); ?>