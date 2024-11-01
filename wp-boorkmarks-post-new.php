<?php

class WP_Plugin_Bookmarks_Post_New {
	
	
	public function renderPage() {
		// set headers
		$this->setHeaders();
		
		// handle post
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$this->handlePost();
		}
		else if(isset($_GET['success'])) {
			include dirname(__FILE__).'/templates/post-success.php';
		}
		else {
			include dirname(__FILE__).'/templates/post-new.php';
		}
	}
	
	private function handlePost() {
		// get values
		$value_title = $title = $_POST['post_title'];
		$value_url = $url = $_POST['post_url'];
		$value_content= $content = $_POST['post_content'];
		$value_tags = $tags = $_POST['post_tags'];
		$categories = array();
		
		// get categories
		foreach($_POST as $name=>$value) {
			if($value!='1') continue;
			
			$split = explode('category_id_', $name);
			if(count($split)==2 && empty($split[0])) {
				$categories[] = ($split[1]);
			}
		}
		$value_categories = $categories;
		
		// validate
		if(empty($title)) {
			$message= __("No Title given", 'wp_bookmarks').'!';
			include dirname(__FILE__).'/templates/post-new.php';
			die;
		}
		else if(empty($url)) {
			$message= __("No URL given", 'wp_bookmarks').'!';
			include dirname(__FILE__).'/templates/post-new.php';
			die;
		}
		else if($this->linkExists($url)) {
			$message= __("This Bookmark already exists", 'wp_bookmarks').'!';
			include dirname(__FILE__).'/templates/post-new.php';
			die;
		}
		
		// add post
		$addpostresult = $this->addPost($title, $content, $url, $tags, $categories);
		if($addpostresult!=true) {
			if($addpostresult==false) $message = 'Meta: '.__('Invalid Post-ID', 'wp_bookmarks').'!';
			elseif(get_class($addpostresult)=='WP_Error') $message= 'Meta: '.__('Invalid Taxonomy', 'wp_bookmarks').'!';
			elseif(is_string($addpostresult)) $message='Meta: '.__('Invalid Term', 'wp_bookmarks').': '.$addpostresult;
			else $message = __('Unknown Error', 'wp_bookmarks').'!';
			
			include dirname(__FILE__).'/templates/post-new.php';
			die;
		}
		
		wp_redirect($_SERVER["PHP_SELF"].'?post_type=bookmarks&action=browserbookmark&success');
	}
	
	private function linkExists($url) {
		$query = new WP_Query(array(
				'post_type' => 'bookmarks',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'caller_get_posts'=> 1
		));
		
		foreach($query->posts as $post) {
			$meta_url = get_post_meta($post->ID, '_WP_Plugin_Bookmarks-bookmarks-url', true);
			if($meta_url==$url) return true;
		}
		wp_reset_query();
		
		return false;
	}
	
	private function addPost($title, $content, $link, $tags, $categories) {
		// get user-info
		$current_user = wp_get_current_user();
		
		// construct entry
		$new_entry = array();
		$new_entry['post_title'] = esc_html($title);
		$new_entry['post_content'] = ($content);
		$new_entry['post_status'] = 'publish';
		$new_entry['post_type'] = 'bookmarks';
		$new_entry['post_author'] = $current_user->ID;
		$new_entry['tags_input'] = esc_html($tags);
		$new_entry['post_category'] = $categories;
		
		// insert the post into the database
		$post_id = wp_insert_post( $new_entry );
		if($post_id==0 || is_object($post_id)) return false;
		
		// add meta
		add_post_meta($post_id, '_WP_Plugin_Bookmarks-bookmarks-url', strip_tags($link), true);
		
		// set categories
		$metaresult=wp_set_post_terms($post_id, $categories, 'bookmark_categories', false);
		if(!is_array($metaresult)) {
			return $metaresult;
		}
		
		return true;
	}
	
	private function setHeaders() {
		header_remove('X-Frame-Options');
	}
	
	private function printHtmlHeader() {
		global $title, $hook_suffix, $current_screen, $wp_locale, $pagenow, $wp_version, $current_site, $update_title, $total_update_count, $parent_file;
		
		_wp_admin_html_begin();
		wp_enqueue_style( 'colors' );
		wp_enqueue_style( 'ie' );
		wp_enqueue_script('utils');
		do_action('admin_enqueue_scripts', $hook_suffix);
		do_action("admin_print_styles-$hook_suffix");
		do_action('admin_print_styles');
		do_action("admin_print_scripts-$hook_suffix");
		do_action('admin_print_scripts');
		do_action("admin_head-$hook_suffix");
		do_action('admin_head');
	}
}