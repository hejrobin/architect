<div id="jarvis" class="clearfix" data-active-tab="overview" data-collapsed="true">
	<div class="header">
		<ul>
			<li><a href="#jarvis-overview"><i>u</i> Overview</a></li>
			<li><a href="#jarvis-console"><i>_</i> Console</a></li>
			<li><a href="#jarvis-benchmarks"><i>P</i> Benchmarks</a></li>
			<li><a href="#jarvis-memory"><i>K</i> Memory</a></li>
			<li><a href="#jarvis-files"><i>A</i> Files</a></li>
			
			<li class="right"><a href="#jarvis-toggle"><i class="down">×</i><i class="up">%</i></a></li>
		</ul>
	</div>
	<div class="container">
		
		<ul class="tab-header clearfix">
			<li class="time"><span>Time</span></li>
			<li class="message"><span>Message</span></li>
			<li class="file"><span>File</span></li>
			<li class="line"><span>Line</span></li>
			<li class="data"><span>Data</span></li>
		</ul>

		<?php $logs = \Jarvis\Console::getLogs(); ?>
		
		<div id="jarvis-overview" class="tab clearfix">
			
			<div class="overview-console col">
			
				<p><strong>Console</strong></p>
				
				<p>Entries: <var><?php echo $logs['Console']['num_entries']; ?></var></p>
			
			</div>
			
			<div class="overview-benchmarks col">
			
				<p><strong>Benchmarks</strong></p>
				
				<p>Entries: <var><?php echo $logs['Benchmark']['num_entries']; ?></var></p>
				<p>Execution Time: <var><?php echo \Jarvis\readable_time($logs['Benchmark']['execution_time']); ?></var></p>
				<p>Max Execution Time: <var><?php echo \Jarvis\readable_time($logs['Benchmark']['max_execution_time'], 2); ?></var></p>
			
			</div>

			<div class="overview-benchmarks col">
			
				<p><strong>Memory</strong></p>
				
				<p>Entries: <var><?php echo $logs['Memory']['num_entries']; ?></var></p>
				<p>Memory Limit: <var><?php echo \Jarvis\readable_bytesize($logs['Memory']['memory_limit']); ?></var></p>
				<p>Memory Peak: <var><?php echo \Jarvis\readable_bytesize($logs['Memory']['memory_peak']); ?></var></p>
			
			</div>

			<div class="overview-benchmarks col">
			
				<p><strong>Files</strong></p>
				
				<p>Entries: <var><?php echo $logs['File']['num_entries']; ?></var></p>
				<p>Largest: <var><abbr title="<?php echo $logs['File']['largest_file']['name']; ?>"><?php echo \Jarvis\readable_bytesize($logs['File']['largest_file']['size']); ?></abbr></var></p>
				<p>Smallest: <var><abbr title="<?php echo $logs['File']['smallest_file']['name']; ?>"><?php echo \Jarvis\readable_bytesize($logs['File']['smallest_file']['size']); ?></abbr></var></p>
			
			</div>

		</div>
		
		<div id="jarvis-console" class="tab">
			<?php foreach($logs['Console']['entries'] as $entry) : ?>
			<ol class="clearfix">
				<li class="time"><span><?php echo date('H:i:s', $entry['time']); ?></span></li>
				<li class="message"><span>&lt;<var><?php echo $entry['entry']['name']; ?></var>&gt; <?php echo $entry['entry']['text']; ?></span></li>
				<li class="file"><span><abbr title="<?php echo $entry['file']; ?>"><?php echo basename($entry['file']); ?></abbr></span></li>
				<li class="line"><span><?php echo $entry['line']; ?></span></li>
				<li class="data"><span>&nbsp;</span></li>
			</ol>
			<?php endforeach; ?>
		</div>
		
		<div id="jarvis-benchmarks" class="tab">
			<?php foreach($logs['Benchmark']['entries'] as $entry) : ?>
			<ol class="clearfix">
				<li class="time"><span><?php echo date('H:i:s', $entry['time']); ?></span></li>
				<li class="message"><span>&lt;<var><?php echo $entry['entry']['name']; ?></var>&gt; <?php echo $entry['entry']['text']; ?></span></li>
				<li class="file"><span><abbr title="<?php echo $entry['file']; ?>"><?php echo basename($entry['file']); ?></abbr></span></li>
				<li class="line"><span><?php echo $entry['line']; ?></span></li>
				<li class="data"><span><?php if(isset($entry['entry']['time_diff']) === true) { echo \Jarvis\readable_time($entry['entry']['time_diff']); } ?></span></li>
			</ol>
			<?php endforeach; ?>
		</div>

		<div id="jarvis-memory" class="tab">
			<?php foreach($logs['Memory']['entries'] as $entry) : ?>
			<ol class="clearfix">
				<li class="time"><span><?php echo date('H:i:s', $entry['time']); ?></span></li>
				<li class="message"><span>&lt;<var><?php echo ucfirst($entry['entry']['type']); ?></var>&gt; <?php echo $entry['entry']['name']; ?></span></li>
				<li class="file"><span><abbr title="<?php echo $entry['file']; ?>"><?php echo basename($entry['file']); ?></abbr></span></li>
				<li class="line"><span><?php echo $entry['line']; ?></span></li>
				<li class="data"><span><? echo \Jarvis\readable_bytesize($entry['entry']['bytes']); ?></span></li>
			</ol>
			<?php endforeach; ?>
		</div>
		
		<div id="jarvis-files" class="tab">
			<?php foreach($logs['File']['entries'] as $entry) : ?>
			<ol class="clearfix">
				<li class="time"><span><?php echo date('H:i:s', $entry['time']); ?></span></li>
				<li class="message"><span><abbr title="<? echo $entry['entry']['text']; ?>"><? echo $entry['entry']['name']; ?></abbr></span></li>
				<li class="file"><span><abbr title="<? echo $entry['entry']['text']; ?>"><? echo $entry['entry']['name']; ?></abbr></span></li>
				<li class="line"><span></span></li>
				<li class="data"><span><? echo \Jarvis\readable_bytesize($entry['entry']['size']); ?></span></li>
			</ol>
			<?php endforeach; ?>
		</div>

	</div>
</div>
<script type="text/javascript">
(function(){var a=document.getElementById("jarvis");var b=a.getElementsByTagName("a");for(var d=0;d<b.length;d++){var c=b[d];b[d].onclick=function(f){f.preventDefault();var e=f.target;if(f.target.nodeName.toLowerCase()!=="a"){e=f.target.parentNode}var g=e.getAttribute("href").replace("#jarvis-","");if(["overview","console","benchmarks","memory","files"].indexOf(g)>=0){a.setAttribute("data-active-tab",g);if(a.hasAttribute("data-collapsed")){a.removeAttribute("data-collapsed")}}if(g=="toggle"){if(a.hasAttribute("data-collapsed")){a.removeAttribute("data-collapsed")}else{a.setAttribute("data-collapsed","true")}}}}}).call(window);
</script>