	<?php 
	// don't load directly
	if ( !defined('ABSPATH') )
		die('-1');
		
	$this->printHtmlHeader();
	?>
	
	<link rel="stylesheet" href="<?php echo plugins_url("css/bookmark-frame.css?".mt_rand(), __FILE__); ?>" type="text/css" media="all">
</head>
<body>
	<div class="messageBox okay"><?php _e('Bookmark was successfully added', 'wp_bookmarks').'!'; ?></div>
</body>
</html>
