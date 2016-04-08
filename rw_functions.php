<?php
/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/**
 * Remove buggy limitations in bbpress integration from learnpress 
 * @see https://github.com/LearnPress/LearnPress-bbPress/blob/master/init.php 
 */
remove_action('bbp_template_after_single_forum', 'learn_press_restrict_forum_content' );
remove_action('bbp_template_after_single_topic', 'learn_press_restrict_forum_content' );
remove_action( 'bbp_template_before_single_topic', 'learn_press_limit_access_course_forum' );
remove_action( 'bbp_template_before_single_forum', 'learn_press_limit_access_course_forum' );


add_action( 'wp_enqueue_scripts', function(){

    wp_enqueue_script(
        'rw-socs-tree2',
        get_stylesheet_directory_uri() . '/js/jquery-sortable-lists.min.js',
        array( 'jquery' )
    );

},30 );


//////////////////////////////////////////
////////// LEFT GROUP ////////////////////
//////////////////////////////////////////
add_action( 'groups_leave_group', 'groups_left_group', 10, 2 );
function groups_left_group( $group_id, $user_id = 0 ) {
	global $bp;

	if ( empty( $user_id ) )
		$user_id = bp_loggedin_user_id();

	// Record this in activity streams
	groups_record_activity( array(
		'type'    => 'left_group',
		'item_id' => $group_id,
		'user_id' => $user_id,
	) );

	// Modify group meta
	groups_update_groupmeta( $group_id, 'last_activity', bp_core_current_time() );

	return true;
}
function groups_register_left_actions() {
	$bp = buddypress();

	if ( ! bp_is_active( 'activity' ) ) {
		return false;
	}

	bp_activity_set_action(
		$bp->groups->id,
		'left_group',
		__( 'Left group', 'buddypress' ),
		'bp_groups_format_activity_action_left_group',
		__( 'Group Disbands', 'buddypress' ),
		array( 'activity', 'group', 'member', 'member_groups' )
	);

	do_action( 'groups_register_activity_actions' );
}

add_action( 'bp_register_activity_actions', 'groups_register_left_actions' );
function bp_groups_format_activity_action_left_group( $action, $activity ) {
	$user_link = bp_core_get_userlink( $activity->user_id );

	$group = groups_get_group( array(
		'group_id'        => $activity->item_id,
		'populate_extras' => false,
	) );
	$group_link = '<a href="' . esc_url( bp_get_group_permalink( $group ) ) . '">' . esc_html( $group->name ) . '</a>';

	$action = sprintf( __( '%1$s left the group %2$s', 'buddypress' ), $user_link, $group_link );

	return apply_filters( 'bp_groups_format_activity_action_joined_group', $action, $activity );
}

////////// END LEFT GROUP ////////////////////
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


// BuddyPress Honeypot +++++++++++++++++++++++++++++++++++++++++++++++++ SECURITY
function add_honeypot() {
    echo '<div style="display: none;">';
	echo '<input type="text" name="system55" id="system55" />';
	echo '</div>';
}
add_action('bp_after_signup_profile_fields','add_honeypot');
function check_honeypot( $result ) {
    if (!empty($_POST['system55'])) {
        global $bp;
        wp_redirect(home_url());
        exit;
    }
    return $result;
}
add_filter('bp_core_validate_user_signup','check_honeypot');


/* allow more html tags to users  ++++++++++++++++++++++++++++++++++++++++++++++++ */

//Allow more HTML-Tags in docs
add_action( 'init', function () { 
    global $allowedposttags;

    $allowedposttags['iframe'] = array(
        'src'    		=> array(),
        'height' 		=> array(),
        'width'  		=> array(),
        'frameborder'  	=> array(),
        'style'		  	=> array(),
    );
	
});

// allow iframes for tinyMCE
add_filter('tiny_mce_before_init', function( $a ) {
    
	$a["extended_valid_elements"] = 'iframe[src|height|width|frameborder]';
    
	return $a;
});

/* end allow more html tags to users  +++++++++++++++++++++++++++++++++++++++++++++ */

/* move doc permissions output into a tab ++++++++++++++++++++++++++++++++++++++++++++++++ */
remove_action( 'bp_docs_single_doc_header_fields', 'bp_docs_render_permissions_snapshot' );
add_action( 'bp_docs_header_tabs', function() {

	?>
		<?php if (! bp_docs_is_doc_edit() && ! bp_docs_is_doc_history() && is_user_logged_in()  ) : ?>
			<li class="permissiontab">
				<a href="#rechte" class="rw-doc-permissions-toggle">Zugriffsrechte</a>
			</li>
		<?php endif ?>
	<?php
},999);
add_action( 'bp_docs_before_doc_title',  'rw_bp_docs_render_permissions_snapshot' );
function rw_bp_docs_render_permissions_snapshot() {
	?>
	<style>
		.permissiontab.doc-public{
			background-color: #B2FFB2;
		}
		#doc-group-summary a:first-of-type{
			display:none;
		}
		#doc-group-summary {
			font-size: 120%;
		}
	</style>
	<div id="rw-doc-permissions" style="display:none">
	<?php
	bp_docs_render_permissions_snapshot();
	?>
	<script>
		jQuery(document).ready(function(){
			//jQuery('.doc-permissions').css('display','none');
			jQuery('#doc-permissions-details').css('display','block');
			jQuery('.doc-permissions-toggle').remove();
			jQuery('.rw-doc-permissions-toggle').on('click', function(){
				jQuery('#rw-doc-permissions').toggle();
				jQuery('.rw-doc-permissions-toggle').parent('li').toggleClass('current')
			});
			jQuery('.permissiontab').addClass(jQuery('#doc-permissions-summary').attr('class'));
		});

	</script>
	</div>
	<?php
}
/* end permissions tab ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ */

/**
 *  BP Attachments
 */

/**
 * remove build in attachment downloader
 */
global $bp;
$class = $bp->bp_docs->attachments;
remove_action( 'template_redirect', array( $class, 'catch_attachment_request' ), 20 );

/**
 * add our own downloader with Content-Disposition: inline
 */
function rw_catch_attachment_request() {

    global $bp;

    if ( ! empty( $_GET['bp-attachment'] ) ) {

        $fn = $_GET['bp-attachment'];

        // Sanity check - don't do anything if this is not a Doc
        if ( ! bp_docs_is_existing_doc() ) {
            return;
        }

        if ( ! $bp->bp_docs->attachments->filename_is_safe( $fn ) ) {
            wp_die( __( 'File not found.', 'bp-docs' ) );
        }

        $uploads = wp_upload_dir();
        $filepath = $uploads['path'] . DIRECTORY_SEPARATOR . $fn;

        if ( ! file_exists( $filepath ) ) {
            wp_die( __( 'File not found.', 'bp-docs' ) );
        }

        $headers = $bp->bp_docs->attachments->generate_headers( $filepath );


        foreach( $headers as $name => $field_value ) {

            if( 'Content-Disposition'!= $name ){
                $field_value = str_replace('attachment','inline',$field_value);

                @header("{$name}: {$field_value}");
            }

        }

        readfile( $filepath );
        exit();
    }
}
add_action( 'template_redirect', 'rw_catch_attachment_request' , 20 );

/** add fancy box to attached images */
add_action( 'wp_enqueue_scripts', function(){
    wp_enqueue_script( 'child-js', 'http://lernlog.de/wp-content/plugins/buddyboss-media/assets/vendor/fancybox/jquery.fancybox.pack.js', false, '2.1.5', false );
} );

<<<<<<< HEAD
/** add treeview for docs
 * 	use /docs/docs-tree.php template
 */
add_filter('bp_docs_template',function($template_path, $bp_group_integration){
	if(isset($_GET['tree'])){

        return get_stylesheet_directory().'/docs/docs-tree.php' ;
	}
	return $template_path;
},10, 2);

/**
 * add ajax tree node changer
 */
function rw_buddypress_docs_tree_change_node() {
	check_ajax_referer( 'rw_buddypress_docs_tree_change_node_nonce', 'security' );

	$post_id =  sanitize_text_field( $_POST['post_id'] );
	$parent = sanitize_text_field( $_POST['parent'] );

	$order = ( $_POST['order']);

	foreach($order as $v){
		$post = get_post($v['post_id']);
		$post->menu_order = $v['menu_order'];

		if($post_id == $v['post_id']){
			$post->post_parent = $parent;
		}

		wp_update_post( $post );
	}


	header('Content-Type: application/json');
	echo json_encode(array('success' => true, 'order'=>$order ));
	die;
}
add_action( 'wp_ajax_rw_buddypress_docs_tree_change_node', 'rw_buddypress_docs_tree_change_node' );

add_filter('bp_docs_parent_dropdown_query_args',function($array){
	include_once 'RW_BuddyPress_Docs_Tree.php';
	return RW_BuddyPress_Docs_Tree::bd_get_query_args($array);
});
=======
function rw_docs_disable_folder( $return ) {
    return false;
}
add_filter( 'bp_docs_enable_folders', 'rw_docs_disable_folder' );
>>>>>>> origin/master
