
	<?php $logs = \Rae\Collection::getStore(); ?>
<style type="text/css">@import url("public/assets/css/rae.css");</style>
<div id="rae-ui" data-active-tab="overview" data-collapsed="true">
	<h4><a href="#rae-toggle">Rae</a></h4>
	<nav>
		<ul>
			<li><a href="#rae-overview">Overview</a></li>
			<li><a href="#rae-console">Console <span><?php echo $logs['Console']['num_entries']; ?></span></a></li>
			<li><a href="#rae-constants">Constants <span><?php echo $logs['Constant']['num_entries']; ?></span></a></li>
			<li><a href="#rae-benchmarks">Benchmarks <span><?php echo $logs['Benchmark']['num_entries']; ?></span></a></li>
			<li><a href="#rae-memory">Memory <span><?php echo $logs['Memory']['num_entries']; ?></span></a></li>
			<li><a href="#rae-files">Files <span><?php echo $logs['File']['num_entries']; ?></span></a></li>
			<li><a href="#rae-environment">Environment</a></li>
		</ul>
	</nav>
	<div class="panes">

		<div id="rae-overview-wrapper">
			<section id="rae-overview">

				<div class="column">

					<h5>Overview</h5>

					<p>
						<strong>Console Entries</strong>: <span><?php echo $logs['Console']['num_entries']; ?></span><br />
						<strong>Environment Entries</strong>: <span><?php echo $logs['Environment']['num_entries']; ?></span><br />
						<strong>Constants</strong>: <span><?php echo $logs['Constant']['num_entries']; ?></span>
					</p>

				</div>

				<div class="column">

					<h5>Benchmarks</h5>

					<p>
						<strong>Entries</strong>: <span><?php echo $logs['Benchmark']['num_entries']; ?></span><br />
						<strong>Execution Time</strong>: <span><?php echo \Rae\getReadableExecutionTime($logs['Benchmark']['execution_time']); ?></span><br />
						<strong>Max Execution Time</strong>: <span><?php echo \Rae\getReadableExecutionTime($logs['Benchmark']['max_execution_time'], 2); ?></span>
					</p>

				</div>

				<div class="column">

					<h5>Memory</h5>

						<p>
						<strong>Entries</strong>: <span><?php echo $logs['Memory']['num_entries']; ?></span><br />
						<strong>Memory Allocated</strong>: <span><?php echo \Rae\getReadableByteSize($logs['Memory']['memory_alloc']); ?></span><br />
						<strong>Memory Peak</strong>: <span><?php echo \Rae\getReadableByteSize($logs['Memory']['memory_peak']); ?></span><br />
						<strong>Memory Limit</strong>: <span><?php echo \Rae\getReadableByteSize($logs['Memory']['memory_limit']); ?></span>
					</p>

				</div>

				<div class="column">

					<h5>File</h5>

						<p>
						<strong>Entries</strong>: <span><?php echo $logs['File']['num_entries']; ?></span><br />
						<strong>Smallest file</strong>: <span><abbr title="<?php echo $logs['File']['smallest_file']['name']; ?>"><?php echo \Rae\getReadableByteSize($logs['File']['smallest_file']['size']); ?></abbr></span><br />
						<strong>Largest file</strong>: <span><abbr title="<?php echo $logs['File']['largest_file']['name']; ?>"><?php echo \Rae\getReadableByteSize($logs['File']['largest_file']['size']); ?></abbr></span>
					</p>

				</div>

			</section>
		</div>

		<div id="rae-console-wrapper">
			<ul class="header">
				<li class="entry">Entry</li>
				<li class="data">Data</li>
				<li class="file">File</li>
				<li class="line">Line</li>
			</ul>
			<section id="rae-console">
				<ol>
					<?php foreach($logs['Console']['entries'] as $log) : $log = (object) $log; ?>
					<li>
						<ul>
							<li class="entry" title="<?php echo htmlentities($log->entry['text']); ?>"><?php echo $log->entry['text']; ?></li>
							<li class="data" title="<?php echo $log->entry['name']; ?>"><?php echo $log->entry['name']; ?></li>
							<li class="file"><abbr title="<?php echo $log->file; ?>"><?php echo basename($log->file); ?></abbr></li>
							<li class="line"><?php echo $log->line; ?></li>
						</ul>
					</li>
					<?php endforeach; ?>
				</ol>
			</section>
		</div>

		<div id="rae-constants-wrapper">
			<ul class="header">
				<li class="data">Name</li>
				<li class="entry">Value</li>
				<li class="file">&nbsp;</li>
				<li class="line">Size</li>
			</ul>
			<section id="rae-constants">
				<ol>
					<?php foreach($logs['Constant']['entries'] as $log) : $log = (object) $log; ?>
					<li>
						<ul>
							<li class="data" title="<?php echo $log->entry['name']; ?>"><?php echo $log->entry['name']; ?></li>
							<li class="entry" title="<?php echo htmlentities($log->entry['text']); ?>"><?php if(is_bool($log->entry['text']) === true) { echo (($log->entry['text'] == true) ? 'true' : 'false'); } else { echo $log->entry['text']; } ?></li>
							<li class="file">&nbsp;</li>
							<li class="line"><?php echo \Rae\getReadableByteSize($log->entry['size']); ?></li>
						</ul>
					</li>
					<?php endforeach; ?>
				</ol>
			</section>
		</div>

		<div id="rae-benchmarks-wrapper">
			<ul class="header">
				<li class="entry">Message</li>
				<li class="data">Time</li>
				<li class="file">File</li>
				<li class="line">Line</li>
			</ul>
			<section id="rae-benchmarks">
				<ol>
					<?php foreach($logs['Benchmark']['entries'] as $log) : $log = (object) $log; ?>
					<li>
						<ul>
							<li class="entry" title="<?php echo htmlentities($log->entry['text']); ?>"><?php if(is_bool($log->entry['text']) === true) { echo (($log->entry['text'] == true) ? 'true' : 'false'); } else { echo $log->entry['text']; } ?></li>
							<li class="data" title="<?php echo $log->entry['name']; ?>"><?php echo \Rae\getReadableExecutionTime($log->entry['time_diff']); ?></li>
							<li class="file"><abbr title="<?php echo $log->file; ?>"><?php echo basename($log->file); ?></abbr></li>
							<li class="line"><?php echo $log->line; ?></li>
						</ul>
					</li>
					<?php endforeach; ?>
				</ol>
			</section>
		</div>

		<div id="rae-memory-wrapper">
			<ul class="header">
				<li class="entry">Message</li>
				<li class="data">Time</li>
				<li class="file">File</li>
				<li class="line">Line</li>
			</ul>
			<section id="rae-memory">
				<ol>
					<?php foreach($logs['Memory']['entries'] as $log) : $log = (object) $log; ?>
					<li>
						<ul>
							<li class="entry" title="<?php echo htmlentities($log->entry['text']); ?>">&lt;<var><?php echo ucfirst($log->entry['type']); ?></var>&gt; <?php if(is_bool($log->entry['text']) === true) { echo (($log->entry['text'] == true) ? 'true' : 'false'); } else { echo $log->entry['text']; } ?></li>
							<li class="data" title="<?php echo $log->entry['name']; ?>"><?php echo \Rae\getReadableByteSize($log->entry['bytes']); ?></li>
							<li class="file"><abbr title="<?php echo $log->file; ?>"><?php echo basename($log->file); ?></abbr></li>
							<li class="line"><?php echo $log->line; ?></li>
						</ul>
					</li>
					<?php endforeach; ?>
				</ol>
			</section>
		</div>

		<div id="rae-environment-wrapper">
			<ul class="header">
				<li class="entry">Message</li>
				<li class="data">Time</li>
				<li class="file">File</li>
				<li class="line">Line</li>
			</ul>
			<section id="rae-environment">
				<ol>
					<?php foreach($logs['Environment']['entries'] as $log) : $log = (object) $log; ?>
					<li>
						<ul>
							<li class="data" title="<?php echo $log->entry['name']; ?>"><?php echo $log->entry['name']; ?></li>
							<li class="entry" title="<?php echo htmlentities($log->entry['text']); ?>"><?php if(is_bool($log->entry['text']) === true) { echo (($log->entry['text'] == true) ? 'true' : 'false'); } else { echo $log->entry['text']; } ?></li>
							<li class="file">&nbsp;</li>
							<li class="line">&nbsp;</li>
						</ul>
					</li>
					<?php endforeach; ?>
				</ol>
			</section>
		</div>

		<div id="rae-files-wrapper">
			<ul class="header">
				<li class="data">Name</li>
				<li class="entry">Value</li>
				<li class="file">&nbsp;</li>
				<li class="line">Size</li>
			</ul>
			<section id="rae-files">
				<ol>
					<?php foreach($logs['File']['entries'] as $log) : $log = (object) $log; ?>
					<li>
						<ul>
							<li class="data" title="<?php echo $log->entry['name']; ?>"><?php echo $log->entry['name']; ?></li>
							<li class="entry" title="<?php echo htmlentities($log->entry['text']); ?>"><?php if(is_bool($log->entry['text']) === true) { echo (($log->entry['text'] == true) ? 'true' : 'false'); } else { echo $log->entry['text']; } ?></li>
							<li class="file">&nbsp;</li>
							<li class="line"><?php echo \Rae\getReadableByteSize($log->entry['size']); ?></li>
						</ul>
					</li>
					<?php endforeach; ?>
				</ol>
			</section>
		</div>

	</div>
</div>
<script type="text/javascript">(function(){var f=document.getElementById("rae-ui");var e=f.getElementsByTagName("a");for(var g=0;g<e.length;g++){var h=e[g];e[g].onclick=function(b){b.preventDefault();var c=b.target;if(b.target.nodeName.toLowerCase()!=="a"){c=b.target.parentNode}var a=c.getAttribute("href").replace("#rae-","");if(["overview","console","constants","benchmarks","memory","environment","files"].indexOf(a)>=0){f.setAttribute("data-active-tab",a);if(f.hasAttribute("data-collapsed")){f.removeAttribute("data-collapsed")}}if(a=="toggle"){if(f.hasAttribute("data-collapsed")){f.removeAttribute("data-collapsed")}else{f.setAttribute("data-collapsed","true")}}}}}).call(window);</script>

