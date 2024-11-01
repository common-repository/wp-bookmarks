	<?php 
	// don't load directly
	if ( !defined('ABSPATH') )
		die('-1');
		
	$this->printHtmlHeader();
	
	$categories = get_terms('bookmark_categories', array(
		'hide_empty' => 0
	));
	
	if(!isset($value_title))   $value_title   = isset($_GET['post_title']) ? $_GET['post_title'] : '';
	if(!isset($value_url))     $value_url     = isset($_GET['post_url']) ? $_GET['post_url'] : '';
	if(!isset($value_content)) $value_content = isset($_GET['post_content']) ? $_GET['post_content'] : '';
	if(!isset($value_tags))    $value_tags = '';
	?>
	<link rel="stylesheet" href="<?php echo plugins_url("css/bookmark-frame.css?".mt_rand(), __FILE__); ?>" type="text/css" media="all">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('*[jquerydefaultvalue]').each(function(index) {
				// set value to default
				if($(this).attr('value')=='') {
					this.value=$(this).attr('jquerydefaultvalue');
					$(this).addClass("containsDefaultValue");
				}
				
				// onclick-handler
				$(this).click(function(){
					if(!this.valueChanged && this.value==$(this).attr('jquerydefaultvalue')) this.value='';
					$(this).removeClass("containsDefaultValue");
				});
				
				$(this).keypress(function(){
					this.valueChanged=true;
				});
				
				// blur-handler
				$(this).blur(function(){
					if(this.value=='')  {
					  this.value=$(this).attr('jquerydefaultvalue');
					  $(this).addClass("containsDefaultValue");
					  this.valueChanged=false;
					}
				});
				
				// form-handler
				var form = $(this).parents('form:first');
				if(!form[0].hasJqueryDefaultValueHandler) {
					form.submit(function(){
						$('*[jquerydefaultvalue]').each(function(index) {
							if(!this.valueChanged && this.value==$(this).attr('jquerydefaultvalue')) {
								this.value='';
								this.valueChanged=true;
								$(this).removeClass("containsDefaultValue");
							}
						});
					});
					form[0].hasJqueryDefaultValueHandler = true;
				}
			});
		});
	</script>
    
    <style type="text/css">
		.category {
			margin:		5px 0px 0px 0px;
			display:	block;
			text-align:	left;
		}
		
		.category input {
			margin-right:	5px;
		}
		
		.button-primary {
			display: inline-block;
			text-decoration: none;
			font-size: 12px;
			line-height: 23px;
			height: 24px;
			margin: 0;
			margin-top:	5px;
			padding: 0px 10px 1px;
			cursor: pointer;
			border-width: 1px;
			border-style: solid;
			-webkit-border-radius: 3px;
			-webkit-appearance: none;
			border-radius: 3px;
			white-space: nowrap;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
			
			background-image: linear-gradient(to bottom,#2a95c5,#21759b);
			border-color: #21759b;
			border-bottom-color: #1e6a8d;
			-webkit-box-shadow: inset 0 1px 0 rgba(120,200,230,0.5);
			box-shadow: inset 0 1px 0 rgba(120,200,230,0.5);
			color: #fff;
			text-decoration: none;
			text-shadow: 0 1px 0 rgba(0,0,0,0.1);
		}
	</style>
</head>
<body>
	<?php 
	if(isset($message)) echo '<div class="messageBox error">'.$message.'</div>';
	?>
	<form name="post" action="" method="post" id="post">
		<input class="wide" type="text" name="post_title" tabindex="1" value="<?php echo $value_title; ?>" jquerydefaultvalue="<?php _e('Title', 'wp_bookmarks'); ?>" />
		<input class="wide" type="text" name="post_url"   tabindex="2" value="<?php echo $value_url; ?>" jquerydefaultvalue="<?php _e('URL', 'wp_bookmarks'); ?>" />
		<textarea class="wide" name="post_content" tabindex="3" jquerydefaultvalue="<?php _e('Description', 'wp_bookmarks'); ?>"><?php echo $value_content; ?></textarea>
		<input class="wide" type="text" name="post_tags"   tabindex="4" value="<?php echo $value_tags; ?>" maxlength="50" jquerydefaultvalue="<?php _e('Tags', 'wp_bookmarks'); ?>" />
		<?php 
		foreach($categories as $category) {
			$tags='';
			if(isset($value_categories) && in_array(($category->term_id), $value_categories))
				$tags.=' checked="checked"';
			
			echo '<div class="category">
			<input type="checkbox" name="category_id_'.$category->term_id.'" value="1" '.$tags.' /><label for="category_id_'.$category->term_id.'">'.$category->name.'</label></div>';
		}
		?>
		<div class="clear"></div>
        <div align="left">
		<input class="button-primary" type="submit" value="<?php _e('Submit', 'wp_bookmarks'); ?>" />
		</div>
	</form>
</body>
</html>
