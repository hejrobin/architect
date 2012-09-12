<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<?php $arch = \Architect::getInstance(); ?>

	<base href="<?= $arch->uri->getBaseURI(); ?>" />

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />

	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Cache-Control" content="no-cache" />
	<meta http-equiv="imagetoolbar" content="false" />

	<meta name="robots" content="index,nofollow" />
	<meta name="googlebot" content="index,nofollow" />

		<title>Architect Framework</title>

	<link href="public/assets/css/internal.css" type="text/css" rel="stylesheet" />

	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js" type="text/javascript"></script>
	<![endif]-->

	<script type="text/javascript">
	var WebFontConfig = {
	  google: {
	    families: ['Rokkitt:400,700:latin']
	  }
	};

	(function() {
	  var node = document.createElement('script');
	  node.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
	  node.type = 'text/javascript';
	  node.async = 'true';
	  var s = document.getElementsByTagName('script')[0];
	  s.parentNode.insertBefore(node, s);
	})();
	</script>

</head>