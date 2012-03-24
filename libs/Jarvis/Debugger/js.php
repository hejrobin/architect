<script type="text/javascript">
(function() {

	var jarvis = this.jarvis = {
	
		panels: document.querySelectorAll('section#jarvis div.panel'),
		
		tabs: document.querySelectorAll('section#jarvis ul#jarvis-tabs li[data-index] a'),
		
		toggle: function(force) {
			
			force = force || true;
			
			var element = document.getElementById('jarvis');
			
			if(element.hasAttribute('class') && force === true)
				element.removeAttribute('class');
			else
				element.setAttribute('class', 'minimized');
		
		},
		
		activate: function(index) {
		
			var panel = jarvis.panels[index];
			var tabs = jarvis.tabs[index];
			
			jarvis.deactivateAll();
			
			document.getElementById('jarvis').removeAttribute('class');

			panel.style.display = 'block';
			tabs.parentNode.setAttribute('class', 'active');
		
		},
		
		deactivate: function(index) {
		
			var panel = jarvis.panels[index];
			var tabs = jarvis.tabs[index];
			
			panel.style.display = 'none';
			tabs.parentNode.removeAttribute('class');
		
		},
		
		deactivateAll: function() {
			
			var panels = jarvis.panels;
			
			for(var n = 0; n < panels.length; n++) {
			
				jarvis.deactivate(n);
			
			}
		
		},
		
		bind: function(index) {
		
			var tab = jarvis.tabs[index];
			
			tab.addEventListener('click', function(event) {
			
				event.preventDefault();
				
				jarvis.activate(tab.parentNode.getAttribute('data-index'));

				return false;
			
			}, false);
		
		},
		
		bindEvents: function() {
		
			var panels = jarvis.panels;
			
			for(var n = 0; n < panels.length; n++) {
			
				jarvis.bind(n);
			
			}
		
		}
	
	};
	
	jarvis.bindEvents();
	
	setTimeout(function() {
	
		jarvis.activate(0);
	
	}, 10);
	
	document.getElementById('jarvis-toggle').addEventListener('click', function(event) {
	
		event.preventDefault();
		
		jarvis.toggle();
		
		return false;
	
	}, false); 

}).call(window);
</script>