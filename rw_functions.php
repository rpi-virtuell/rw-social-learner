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

/**
 * copied from boss  theme functions because of code error
 *
 * @return int
 */

function rw_bp_doc_single_group_id( $return_dummy = true ){

	$group_id = false;

	if ( function_exists( 'bp_is_active' ) && bp_is_active( 'groups' ) ) {
		if ( bp_docs_is_doc_create() ) {
			$group_slug = isset( $_GET[ 'group' ] ) ? $_GET[ 'group' ] : '';
			if ( $group_slug ) {
				global $bp, $wpdb;
				$group_id = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM {$bp->groups->table_name} WHERE slug=%s", $group_slug ) );

			}
			if ( !$group_id ) {
				if ( $return_dummy )
					$group_id = 99999999;
			}
			return $group_id;
		}

		$doc_group_ids	 = bp_docs_get_associated_group_id( get_the_ID(), false, true );
		$doc_groups		 = array();
		foreach ( $doc_group_ids as $dgid ) {
			$maybe_group = groups_get_group(  $dgid );   // since buddypress 2.7 param should be integer

			// Don't show hidden groups if the
			// current user is not a member
			if ( isset( $maybe_group->status ) && 'hidden' === $maybe_group->status ) {
				// @todo this is slow
				if ( !current_user_can( 'bp_moderate' ) && !groups_is_user_member( bp_loggedin_user_id(), $dgid ) ) {
					continue;
				}
			}

			if ( !empty( $maybe_group->name ) ) {
				$doc_groups[] = $dgid;
			}
		}

		if ( !empty( $doc_groups ) && count( $doc_groups ) == 1 ) {
			$group_id = $doc_groups[ 0 ];
		}
	}



	if ( !$group_id ) {
		if ( $return_dummy )
			$group_id = 99999999;
	}
	return $group_id;

}

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
    wp_enqueue_script( 'fancy-js', plugins_url(). '/buddyboss-media/assets/vendor/fancybox/jquery.fancybox.pack.js', false, '2.1.5', false );
} );


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

function rw_docs_disable_folder( $return ) {
    return false;
}
//add_filter( 'bp_docs_enable_folders', 'rw_docs_disable_folder' );


/**
 * save bp-actifity-filter cookie to user metadata
 */
function activity_widget_filter() {
	if ( get_current_user_id() != 0 ) {
		$cookie = $_COOKIE[ 'bp-activity-filter' ];
		update_user_meta( get_current_user_id(), 'bp-activity-filter', $cookie );
	}
}
add_action( 'wp_ajax_activity_widget_filter', 'activity_widget_filter' );

/**
 * get bp-actifity-filter from user metadata and put it in cookie
 */
function set_activity_widget_filter() {
	$data = get_user_meta( get_current_user_id(), 'bp-activity-filter', true );
	setcookie( 'bp-activity-filter', $data, time()+ ( 60 * 60 ));
}
add_action('wp_login', 'set_activity_widget_filter');


/**
* tiny mce buttons
*/
function enable_more_buttons($buttons) {

	if(bp_docs_is_doc_edit() or bp_docs_is_doc_create()){
		$buttons =array(
			'bold',
			'italic',
			'forecolor',
			'backcolor',
			'strikethrough',
			'|',
			'blockquote',
			'bullist',
			'numlist',
			'|',
			'alignleft',
			'aligncenter',
			'alignright',
			'alignjustify',
			'indent',
			'outdent',
			'|',
			'table',
			'pastetext',
			'|',
			'fullscreen',
			'wp_adv'
		);


	}
	return $buttons;
}
add_filter("mce_buttons", "enable_more_buttons",9999);
function enable_more_buttons_2($buttons) {

	if(bp_docs_is_doc_edit() or bp_docs_is_doc_create()){

		$buttons =array(
			'formatselect',
			'fontsizeselect',
			'undo',
			'redo',
			'removeformat',
			'searchreplace',
			'charmap',
			'hr',
			'link',
			'unlink',
			'anchor',
			'wp_page',
			'tabindent',
			'image',
			'media',
			'visualblocks'

		);

	}
	return $buttons;
}
add_filter("mce_buttons_2", "enable_more_buttons_2",9999);
add_filter("mce_buttons_3", function(){return false;});

//deny "Tiny MCE Advanced" in forums
add_filter( 'mce_buttons', 'rw_tadv_buttons', 1000 );
function rw_tadv_buttons($buttons){
    if(is_bbpress()){
        $buttons = explode(', ','bold, italic, underline, strikethrough, alignleft, aligncenter, alignright, blockquote, bullist, numlist, removeformat, link, unlink');
    }
    return $buttons;
}
add_filter ('bbp_kses_allowed_tags', 'rw_bbp_kses_allowed_tags');
function rw_bbp_kses_allowed_tags() {
    return array(

        // Links
        'a' => array(
            'href'     => array(),
            'title'    => array(),
            'rel'      => array()
        ),

        // Quotes
        'blockquote'   => array(
            'cite'     => array()
        ),

        // Code
        'code'         => array(),
        'pre'          => array(),

        // Formatting
        'em'           => array(),
        'strong'       => array(),
        'del'          => array(
            'datetime' => true,
        ),

        // Lists
        'ul'           => array(),
        'ol'           => array(
            'start'    => true,
        ),
        'li'           => array(),

        // Images
        'img'          => array(
            'src'      => true,
            'border'   => true,
            'alt'      => true,
            'height'   => true,
            'width'    => true,
        ),
        'br'   		   => array(),
        'p'		   => array(
            'align' => true
        ),
        'b'		   => array(),
        'input' 	   => array(
            'name'  => true,
            'type'  => true,
            'value' => true,
            'style' => array()
        ),
        'table'        => array(
            'padding'  => true,
            'border'   => true,
            'spacing'  => true,
            'height'   => true,
            'width'    => true,
            'bgcolor'  => true,
        ),
        'tbody'        => array(),
        'tr'        	=> array(
            'align'  	=> true,
            'valign'   	=> true,
            'style'  	=> true,
            'height'   	=> true,
            'width'    	=> true,
            'colspan'   => true,
        ),
        'th'        	=> array(
            'align'  	=> true,
            'valign'   	=> true,
            'style'  	=> true,
            'height'   	=> true,
            'width'    	=> true,
            'colspan'   => true,
            'bgcolor'   => true,
        ),
        'td'        	=> array(
            'align'  	=> true,
            'valign'   	=> true,
            'height'   	=> true,
            'width'    	=> true,
            'bgcolor'  	=> true,
        )
    );
}


/**
* tiny mce fullscreen mode 
*/
function enable_advanced_tiny_mce_fullscreen_mode(){
	?>
	<script>
	var buddypanel_magin_left= '272px';
	var buddypanel_magin_top = '74px';
	var editor_frame_height = $(window).height();
	
	jQuery( document ).on( 'tinymce-editor-init', function( event, editor ) {
		editor.on('FullscreenStateChanged', function(e) {
			if ( tinymce.editors[0].plugins.fullscreen.isFullscreen() ){
				$('footer').hide();
				
				$('#masthead').hide();
				$('#wpadminbar').hide();
				$('#secondary').hide();
				$('#item-header').hide();
				$('#item-nav').hide();
				$('#left-panel').hide();
				$('.bb-cover-photo').hide();
				
				buddypanel_magin_left=$('#right-panel-inner').css('margin-left');
				
				$('#right-panel').css({'margin-top':'0'});
				$('#right-panel-inner').css({'position':'initial', 'width':'100%', 'margin-left':'0'});
				
				$('.mce-stack-layout').first().css({ 'border': '1px solid #ddd', 'margin-left': 'auto',  'margin-right': 'auto',  'max-width': '1000px'});
				$('.mce-stack-layout-item').first().before($('#doc-content-title'));
				$('#doc-content-title').css({'margin': '0',  'width': '102%'});
				$('#doc-content-title label').hide();
				$('#doc-title').css({'margin': '0 0 5px 0'});
				
				$('#primary').height(editor_frame_height);
				$('#doc_content_ifr').height(editor_frame_height-165);
				
			}else{
				$('footer').show();
				$('#masthead').show();
				$('#wpadminbar').show();
				$('#secondary').show();
				$('#item-header').show();
				$('#item-nav').show();
				$('#left-panel').show();
				$('.bb-cover-photo').show();
				$('#right-panel-inner').css({'position':'relative', 'width':'auto', 'margin-left':buddypanel_magin_left});
				$('#right-panel').css({'margin-top':buddypanel_magin_top});
				$('.mce-stack-layout').first().css({ 'border': '0px solid #ddd', 'margin-left': 'none',  'margin-right': 'none',  'max-width': 'none'});
				$('#doc-content-textarea').before($('#doc-content-title'));
				$('#doc-content-title').css({'margin': '0 0 20px 0',  'width': 'auto'});
				$('#doc-title').css({'margin': '0 0 30px 0'});
				$('#doc-content-title label').show();
				$('#primary').height('auto');
			}
		});
	});
		
	</script>
	<?php
}
add_action( 'bp_docs_after_doc_edit_content', 'enable_advanced_tiny_mce_fullscreen_mode');


/**
* fix firefox behavior of preloading pre/next site
* causes a multi generic wpnonce and access denies (403)
*/
add_filter( 'index_rel_link', 'disable_stuff' );
add_filter( 'parent_post_rel_link', 'disable_stuff' );
add_filter( 'start_post_rel_link', 'disable_stuff' );
add_filter( 'previous_post_rel_link', 'disable_stuff' );
add_filter( 'next_post_rel_link', 'disable_stuff' );

function disable_stuff( $data ) {
	return false;
}



/**
 * add group types since buddypress 2.7
 */

function my_bp_custom_group_types() {
    bp_groups_register_group_type( 'further_education', array(
        'labels' => array(
            'name' => 'Fortbildungen',
            'singular_name' => 'Fortbildung'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'further_education',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Fort- und Weiterbildungen',
        'create_screen_checked' => true
    ) );

    bp_groups_register_group_type( 'adult_education', array(
        'labels' => array(
            'name' => 'Erwachsenenbildung',
            'singular_name' => 'Erwachsenenbildung'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'adult_education',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Angebote der Erwachsenbildung',
        'create_screen_checked' => true
    ) );

    bp_groups_register_group_type( 'adult_education', array(
        'labels' => array(
            'name' => 'Erwachsenenbildung',
            'singular_name' => 'Erwachsenenbildung'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'adult_education',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Angebote der Erwachsenbildung',
        'create_screen_checked' => true
    ) );
    bp_groups_register_group_type( 'course', array(
        'labels' => array(
            'name' => 'Lerngruppen',
            'singular_name' => 'Lerngruppe'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'course',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Lerngruppen',
        'create_screen_checked' => true
    ) );
    bp_groups_register_group_type( 'working_community', array(
        'labels' => array(
            'name' => 'Arbeitsgemeinschaften',
            'singular_name' => 'Arbeitsgemeinschaft'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'working_community',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Arbeitsgemeinschaften sind in der Regel Gruppen von erwachsenen Personen die im sich im beruflichen Kontext zusammenschließen',
        'create_screen_checked' => true
    ) );

    bp_groups_register_group_type( 'open_community', array(
        'labels' => array(
            'name' => 'Community',
            'singular_name' => 'Community'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'teams',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Gemeinschaft von Personen',
        'create_screen_checked' => true
    ) );

    bp_groups_register_group_type( 'youth_team', array(
        'labels' => array(
            'name' => 'Teams von Jugendlichen',
            'singular_name' => 'Team von Jugendlichen'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'teams',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Team mit jungen Leuten aus dem kirchlichen Arbeitsfeldern',
        'create_screen_checked' => true
    ) );
    bp_groups_register_group_type( 'class', array(
        'labels' => array(
            'name' => 'Klassen / Kurs',
            'singular_name' => 'Klasse / Kurs'
        ),

        // New parameters as of BP 2.7.
        'has_directory' => 'class',
        'show_in_create_screen' => true,
        'show_in_list' => true,
        'description' => 'Schulklassen oder Kurs einer Schule',
        'create_screen_checked' => true
    ) );
}
//add_action( 'bp_groups_register_group_types', 'my_bp_custom_group_types' );

//add_action( 'bp_groups_register_group_types', 'my_bp_custom_group_types' );

/**
 * Nach dem speichern eines neuen Artikels nicht automatisch auf die Lesen Seite wechseln.
 */
function rw_bp_docs_redirect_fallback($status, $url){

	if(function_exists('bp_docs_is_doc_create') && bp_docs_is_doc_create()  &&  !empty( $_POST['doc-edit-submit'] ) ) {
		header("Location: ".$url.'/edit', true, $status);
		die();
	}
	return $status;
}


