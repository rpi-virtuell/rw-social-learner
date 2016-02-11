<?php
/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

add_filter( 'bp_docs_sidebar_template', function(){
    //set my own sidebar template which is compatible to the boss.theme
    $tpl=locate_template( 'docs/sidebar.php' );
    return $tpl;
});

add_action( 'buddyboss_inside_wrapper' , 'add_bp_doc_sidebar');
function add_bp_doc_sidebar(){
    //load the sidebar
    if ( class_exists('RW_BuddyPress_Docs_Tree_Widget') ) {
        //plugin is activated
        if( RW_BuddyPress_Docs_Tree_Widget::is_bp_doc_page() && RW_BuddyPress_Docs_Tree_Widget::is_desktop() ){
            bp_docs_get_sidebar();
            return;
        }
    }

};



// Change the Profile nav tab order 
function change_profile_tab_order() {
	global $bp;
 
	$order = ''; // Add the component slugs coma separated in the order you like to have the nav menu tabs
 
	$order = str_replace(' ','',$order); 
	$order = explode(",", $order);
	$i = 1;
	foreach($order as $item) {
		$bp->bp_nav[$item]['position'] = $i;
		$i ++;
	}
}
add_action( 'wp', 'change_profile_tab_order', 999 );
 
// Change the Group nav tab order
function change_groups_tab_order() {
	global $bp;
 
	$order = 'gpages,forum,docs,members,invite-anyone,hierarchy,invite-anyone,group-chat,notifications'; // Add the component slugs coma separated in the order you like to have the nav menu tabs
 
	$order = str_replace(' ','',$order); 
	$order = explode(",", $order);
	$i = 1;
	foreach($order as $item) {
		$bp->bp_options_nav['groups'][$item]['position'] = $i;
		$i ++;
	}
}
add_action('wp', 'change_groups_tab_order');

/**
 * Remove buggy limitations in bbpress integration from learnpress 
 * @see https://github.com/LearnPress/LearnPress-bbPress/blob/master/init.php 
 */
remove_action('bbp_template_after_single_forum', 'learn_press_restrict_forum_content' );
remove_action('bbp_template_after_single_topic', 'learn_press_restrict_forum_content' );
remove_action( 'bbp_template_before_single_topic', 'learn_press_limit_access_course_forum' );
remove_action( 'bbp_template_before_single_forum', 'learn_press_limit_access_course_forum' );



/**
 * Remove themespecific user options
 */
add_filter( 'buddyboss_get_user_social_array', function() { return array(); }, 9999 );


function rw_boss_social_header() {
	?>
	<div class="btn-group social">
    <?php
    foreach(rw_boss_get_user_social_array() as $social => $arr) {
		$url = xprofile_get_field_data( $social, bp_displayed_user_id(), 'comma' );
        $ico = empty($arr['icon-url'])? '<i class="alt-social-icon alt-'.$social.'"></i>' : '<img class="user-social-icons-btn" src="'.$arr['icon-url'].'">';
        if ( !empty( $url ) ) { ?>
			<a class="btn" href="<?php echo $url; ?>" title="<?php echo esc_attr($arr['title']); ?>"><?php echo $ico; ?></a>
		<?php } ?>
	<?php } ?>
	</div>
	<?php
}

/**
 * add custom icons to social icon array from boss.theme
 * @see https://github.com/timpotter/hello-world-page/tree/gh-pages/images/social-icons
 */
add_action ('bp_before_member_header_meta', 'rw_boss_social_header');
function rw_boss_get_user_social_array() {
	$socials = array(  "facebook" =>    array( "title"=>"Facebook",   'icon-url' => '' ),
	                   "twitter" =>     array( "title"=>"Twitter",    'icon-url' => '' ),
	                   "linkedin" =>    array( "title"=>"Linkedin",   'icon-url' => '' ),
	                   "google-plus" => array( "title"=>"Google+",    'icon-url' => '' ),
	                   "flickr" =>      array( "title"=>"Flickr",     'icon-url' => get_stylesheet_directory_uri() . '/images/flickr.svg' ),
	                   "soundcloud" =>  array( "title"=>"Soundcloud", 'icon-url' => get_stylesheet_directory_uri() . '/images/soundcloud.svg' ),
	                   "wordpress" =>   array( "title"=>"WordPress",  'icon-url' => get_stylesheet_directory_uri() . '/images/wordpress.svg' ),
	                   "vine" =>        array( "title"=>"Vine",       'icon-url' => get_stylesheet_directory_uri() . '/images/vine.svg' ),
	                   "tumblr" =>      array( "title"=>"Tumblr",     'icon-url' => get_stylesheet_directory_uri() . '/images/tumblr.svg' ),
	                   "youtube" =>     array( "title"=>"Youtube",    'icon-url' => '' ),
	                   "instagram" =>   array( "title"=>"Instagram",  'icon-url' => '' ),
	                   "pinterest" =>   array( "title"=>"Pinterest",  'icon-url' => '' )
	);
	return (array) @apply_filters( 'rw_boss_get_user_social_array', $socials );
}


add_action ('bp_docs_tree_widget_before_widgets', 'rw_bp_docs_set_the_doc_relatetd_group');
function rw_bp_docs_set_the_doc_relatetd_group(){
	
	$d_doc_id = $folder_id = $group_id = 0;
	$group = new stdClass();
	$url =  '#';
	$description = '';
	
	if ( bp_docs_is_existing_doc() ) {
		$d_doc_id = get_queried_object_id();
		$folder_id = bp_docs_get_doc_folder( $d_doc_id );
		if($folder_id){
			
			$group_id = bp_docs_get_folder_group( $folder_id );
			
			if($group_id){			
				$group = groups_get_group(array('group_id'=>$group_id));
				
				buddypress()->groups->current_group = $group;
						
				
				if ( ! bp_disable_group_avatar_uploads() ){
					$url =  bp_get_groups_root_slug() . '/' . $group->slug;
				}

				$avatar = bp_get_group_avatar('type=thumb&width=250&height=250');
				 $description = $group->description;
				$name = $group->name;
			}
			
			
			
		}else{
			$post = get_post($d_doc_id);
			$user = get_userdata( $post->post_author );
			$name = $user->display_name;
			$avatar = bp_core_fetch_avatar(array('type'=>'thumb','width'=>250,'height'=>250,'item_id'=>$user->ID));
			$description = '';
			$url = bp_get_members_root_slug() . '/' . $user->nice_name ;
		}
	}
	?>
	<div id="bp_docs_sitebar_info">
		<h2><a href="/<?php echo $url ?>"><?php echo $name; ?></a></h2>
		<div class="group-avatar">
			<a href="/<?php echo $url ?>"><?php echo $avatar ?></a>
		</div>
		
		<div class="group-info">
			<p><?php echo $description; ?></p>
		</div>
	</div>
	<hr>
	<?php
}

/**
 * Enqueues scripts and styles for reliwerk front-end.
 */
add_action( 'wp_enqueue_scripts', 'rw_child_enqueue_styles', 9998 );
function rw_child_enqueue_styles() {
    wp_enqueue_script( 'reliwerk-js', get_stylesheet_directory_uri() . '/js/reliwerk.js', false, '1.0.4', false );
    
}

// BuddyPress Honeypot
function add_honeypot() {
    echo '<div style="display: none;">';
	echo '<input type="text" name="system55" id="system55" />';
	echo '</div>';
}
add_action('bp_after_signup_profile_fields','add_honeypot');
function check_honeypot() {
    if (!empty($_POST['system55'])) {
        global $bp;
        wp_redirect(home_url());
        exit;
    }
}
add_filter('bp_core_validate_user_signup','check_honeypot');

// Shortcodes for Groupinfo
add_filter('bp_get_group_description', 'do_shortcode');
