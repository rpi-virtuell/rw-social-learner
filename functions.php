<?php

/**
 * @package Social Learner
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

/**
 * Sets up theme defaults
 *
 * @since Social Learner 1.0.0
 */
function boss_child_theme_setup()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   * Read more at: http://www.buddyboss.com/tutorials/language-translations/
   */
  
  // Translate text from the CHILD theme only.
  load_child_theme_textdomain( 'social-learner', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );


/**
 * Setup Social Learner's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function boss_child_theme_languages() {
    load_child_theme_textdomain( 'social-learner',  get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'boss_child_theme_languages' );



/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Social Learner  1.0.0
 */
add_action( 'wp_enqueue_scripts', 'boss_child_enqueue_styles', 9998 );
function boss_child_enqueue_styles() {
    wp_enqueue_script( 'child-js', get_stylesheet_directory_uri() . '/js/action.js', false, '1.0.2', false );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/css/main.css', false, '1.0.2', 'all' );
}

add_action( 'wp_enqueue_scripts', 'boss_child_enqueue_user_styles', 9999 );
function boss_child_enqueue_user_styles() {
    wp_enqueue_style( 'user-style', get_stylesheet_directory_uri() . '/css/custom.css');
}

/**
 * Add color sheme to customizer
 * 
 */

add_filter( 'buddyboss_customizer_themes_preset', 'boss_edu_add_color_scheme' );
function boss_edu_add_color_scheme($default_themes) {

    $education = array(
        /* below properties are for admin section */
        'name'		=> 'Social Learner',//anything goes
        'palette'	=> array( '#012243', '#00a6dc', '#ea6645', '#00a6dc' ),
        /* below properties are to control the appearance of front end of site */
        'rules'		=> array(
            'boss_title_color' => '#ffffff',
            /** Cover **/
            'boss_cover_color'=> '#012243',
             /** BuddyPanel **/
            'boss_panel_logo_color'=> '#012243',
            'boss_panel_color'=> '#012243',
            'boss_panel_title_color'=> '#ffffff',
            'boss_panel_icons_color'=> '#0e4362',
            'boss_panel_open_icons_color'=> '#0e4362',
             /** Layout **/         
            'boss_layout_titlebar_bgcolor'=> '#fff',
            'boss_layout_titlebar_color'=> '#8091a1',
            'boss_layout_mobiletitlebar_bgcolor'=> '#012243',
            'boss_layout_mobiletitlebar_color'=> '#fff',
            'boss_layout_nobp_titlebar_bgcolor'=> '#012243', 
            'boss_layout_nobp_titlebar_color'=> '#fff',
            'boss_layout_nobp_titlebar_hover_color'=> '#ea6645',
            'boss_layout_body_color'=> '#e3e9f0',
            'boss_layout_footer_top_color'=> '#fff',
            'boss_layout_footer_bottom_bgcolor'=> '#fff',
            'boss_layout_footer_bottom_color'=> '#8091a1',
             /** Text & Buttons **/       
            'boss_links_pr_color'=> '#012243',
            'boss_links_color'=> '#00a6dc',
            'boss_slideshow_font_color'=> '#ffffff',
            'boss_heading_font_color'=> '#012243',
            'boss_body_font_color'=> '#012243',
            /** Additional **/
            'boss_edu_active_link_color'=> '#ea6645',
            'boss_edu_sidebar_bg'=> '#cdd7e2',
        ),
    );

    array_unshift($default_themes, $education);

    return $default_themes;

}


/**
 * Additional css for customizer
 * 
 */
add_filter( 'boss_customizer_css', 'boss_edu_customizer_css' );

function boss_edu_customizer_css($css) {
    
      $sidebar_color = esc_attr( get_option( 'boss_edu_sidebar_bg', '#cdd7e2' ) );
      $active_link_color = esc_attr( get_option( 'boss_edu_active_link_color', '#ea6645' ) );
    
      $css .= "
            #certificates_user_settings input[type=\"checkbox\"] +strong,
            .quiz form ol#sensei-quiz-list li ul li input[type='checkbox'] + label,
            .quiz form ol#sensei-quiz-list li ul li input[type='radio'] + label,
            #buddypress div#group-create-tabs ul > li span,
            .tax-module .course-container .archive-header h1,
            .widget_course_progress footer a.btn,
            .widget .my-account .button, .widget_course_teacher footer a.btn,
            .widget-area .widget_course_teacher header span a,
            .widget_course_progress .module header h2 a,
            #main .widget_course_progress .course header h4 a,
            .widget-area .widget li.fix > a:first-child,
            .widget-area .widget li.fix > a:nth-child(2),
            #main .course-container .module-lessons .lesson header h2, .module .module-lessons ul li.completed a, .module .module-lessons ul li a, #main .course .course-lessons-inner header h2 a,
            #post-entries a,
            .comments-area article header cite a,
            .course-inner h2 a,
            .header-inner .left-col .header-navigation ul li a,
            h1, h2, h3, h4, h5, h6, body, p {
                color: ". esc_attr( get_option( 'boss_heading_font_color' ) ) .";
            }
            .widget_course_progress footer a.btn,
            .widget .my-account .button, .widget_course_teacher footer a.btn {
                border-color: ". esc_attr( get_option( 'boss_heading_font_color' ) ) .";
            }
            body #main-wrap {
                background-color: ". esc_attr( get_option( 'boss_layout_body_color' ) ) .";
            }
            .bp-avatar-nav ul.avatar-nav-items li.current {
                border-bottom-color: ". esc_attr( get_option( 'boss_layout_body_color' ) ) .";
            }
            #secondary {
                background-color: {$sidebar_color};
            }
            .page-right-sidebar {
                background-color: {$sidebar_color};
            }
            .is-mobile.single-item.groups .page-right-sidebar,
            #primary {
                background-color: ". esc_attr( get_option( 'boss_layout_body_color' ) ) .";
            }
            .tablet .menu-panel #nav-menu > ul > li.dropdown > a:before, .tablet .menu-panel .bp_components ul li ul li.menupop.dropdown > a:before, body:not(.tablet) .menu-panel .screen-reader-shortcut:hover:before, body:not(.tablet) .menu-panel #nav-menu > ul > li:hover > a:before, body:not(.tablet) .menu-panel .bp_components ul li ul li.menupop:hover > a:before {
                color: #fff;
            }
            .course-buttons .status.in-progress,
            .course-container a.button, .course-container a.button:visited, .course-container a.comment-reply-link, .course-container #commentform #submit, .course-container .submit, .course-container input[type=submit], .course-container input.button, .course-container button.button, .course a.button, .course a.button:visited, .course a.comment-reply-link, .course #commentform #submit, .course .submit, .course input[type=submit], .course input.button, .course button.button, .lesson a.button, .lesson a.button:visited, .lesson a.comment-reply-link, .lesson #commentform #submit, .lesson .submit, .lesson input[type=submit], .lesson input.button, .lesson button.button, .quiz a.button, .quiz a.button:visited, .quiz a.comment-reply-link, .quiz #commentform #submit, .quiz .submit, .quiz input[type=submit], .quiz input.button, .quiz button.button {
                border-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
                background-color: transparent;
            }
            .sensei-content .item-list-tabs ul li:hover, .sensei-content .item-list-tabs ul li.current,
            #learner-info #my-courses.ui-tabs .ui-tabs-nav li:hover a,
            #learner-info #my-courses.ui-tabs .ui-tabs-nav li.ui-state-active a,
            #buddypress div#group-create-tabs ul > li,
            #buddypress div#group-create-tabs ul > li:first-child:not(:last-child),
            .quiz form ol#sensei-quiz-list li ul li.selected {
                border-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .woocommerce #respond input#submit, 
            .woocommerce a.button, 
            .woocommerce button.button, .woocommerce input.button,
            .woocommerce #respond input#submit:hover, 
            .woocommerce a.button:hover,
            .woocommerce button.button, .woocommerce input.button:hover,
            .sensei-content .item-list-tabs ul li span,
            body:not(.tablet) .menu-panel #nav-menu > ul > li:hover, body:not(.tablet) .menu-panel ul li .menupop:hover,
            .menu-panel ul li a span,
            #course-video #hide-video,
            .quiz form ol#sensei-quiz-list li ul li input[type='checkbox']:checked + label:after,
            .widget_sensei_course_progress header,
            #my-courses .meter > span,
            .widget_course_progress .widgettitle,
            .widget-area .widget.widget_course_progress .course-lessons-widgets > header,
            .course-header,
            #search-open {
                background-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            body:not(.tablet) .menu-panel #nav-menu > ul > li:hover a span, body:not(.tablet) .menu-panel ul li .menupop:hover a span {
                background-color: #fff;
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            nav.navigation.post-navigation .nav-links .nav-previous:before,
            nav.navigation.post-navigation .nav-links .nav-next:after,
            .bp-learndash-activity h4 i.fa-spinner,
            .bp-sensei-activity h4 i.fa-spinner,
            .bp-user.achievements #item-body > #subnav li.current a,       
            #content .woocommerce-message .wc-forward,
            .widget_sensei_course_progress .course-progress-lessons .course-progress-lesson a:before,
            #learner-info .my-messages-link:before,
            .post-type-archive-lesson #module_stats span,
            .sensei-course-participants,
            .nav-previous .meta-nav:before,
            .nav-prev .meta-nav:before, .nav-next .meta-nav:before,
            #my-courses .meter-bottom > span > span,
            #my-courses section.entry span.course-lesson-progress,
            .quiz form ol#sensei-quiz-list li>span span,
            .module-archive #module_stats span,
            .widget_course_progress .module header h2 a:hover,
            #main .widget_course_progress .course header h4 a:hover,
            .course-statistic,
            #post-entries a:hover,
            #main .course-container .sensei-course-meta .course-author a,
            #main .course .sensei-course-meta .course-author a,
            .course-inner h2 a:hover,
            .menu-toggle i {
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .site-header .right-col,
            #search-open {
                color: #fff;
            }
            body,
            .site-header .right-col,
            .menu-panel, .menu-panel #nav-menu .sub-menu-wrap, 
            .bp_components ul li ul li.menupop .ab-sub-wrapper,
            #mastlogo {
                background-color: ". esc_attr( get_option( 'boss_panel_color' ) ) .";
            }
            .header-account-login a .name {
                color: rgba(255,255,255,0.9);   
            }
            .single-badgeos article .badgeos-item-points,
            .widget-area .widget:not(.widget_buddyboss_recent_post) .widget-achievements-listing li.has-thumb .widget-badgeos-item-title,
            .badgeos-achievements-list-item .badgeos-item-description .badgeos-item-points,
            .widget-area .widget_course_teacher header span p,
            .header-account-login .user-link span.name:after,
            .header-notifications a.notification-link {
                color: ". esc_attr( get_option( 'boss_layout_titlebar_color' ) ) .";
            }
            .mobile-site-title .colored,
            .site-title a .colored,
            section.entry span.course-lesson-count,
            .widget_course_progress .module.current header h2 a,
.module .module-lessons ul li.current a, 
            .header-inner .left-col .header-navigation ul li a:hover, 
            .header-inner .left-col .header-navigation ul li.current-menu-item a, 
            .header-inner .left-col .header-navigation ul li.current-page-item a {
                color: {$active_link_color};
            }
            #main .course .module-status, 
            .module-archive #main .status,
            #main .course .module-status:before, 
            .module-archive #main .status:before,
            .lesson-status.in-progress, .lesson-status.not-started, 
            .module .module-lessons ul li a:before, 
            .module .module-lessons ul li a:hover:before,
            .widget_course_progress .module.current header h2 a:hover,
            .module .module-lessons ul li a:hover,
            #main .course .course-lessons-inner header h2 a:hover {
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .lesson-status.complete, 
            .module .module-lessons ul li.completed a:before {
                color: #61a92c;
            }
            #profile-nav span, 
            .widget_categories .cat-item i, 
            #wp-admin-bar-shortcode-secondary .alert, 
            .header-notifications a.notification-link span,
            .header-navigation ul li a:hover:after, 
            .header-navigation ul li.current-menu-item a:after, 
            .header-navigation ul li.current-page-item a:after {
                background-color: {$active_link_color};
            }
            .widget_categories .cat-item i {
                background-color: {$active_link_color};
            }          
            .page-template-page-no-buddypanel .header-account-login > a,
            .page-template-page-no-buddypanel .site-header #wp-admin-bar-shortcode-secondary .ab-icon:before,
            .page-template-page-no-buddypanel #wp-admin-bar-shortcode-secondary .thread-from a,
            .page-template-page-no-buddypanel .header-inner .left-col .header-navigation ul li a,
            .page-template-page-no-buddypanel .header-inner .left-col a {
                color: " .esc_attr( get_option( 'boss_layout_nobp_titlebar_color' ) ) .";
            }
            .page-template-page-no-buddypanel .header-inner .left-col .header-navigation ul li a:hover, 
            .page-template-page-no-buddypanel .header-inner .left-col .header-navigation ul li.current-menu-item a, 
            .page-template-page-no-buddypanel .header-inner .left-col .header-navigation ul li.current-page-item a {
                color: {$active_link_color};
            }
            .page-template-page-no-buddypanel .header-notifications a.notification-link {
                color: ". esc_attr( get_option( 'boss_layout_titlebar_color' ) ) .";
            }
            .page-template-page-no-buddypanel #masthead #searchsubmit {
                color: ". esc_attr( get_option( 'boss_heading_font_color' ) ) .";
            }
            .course-inner .course-price del,
            .widget_sensei_course_progress .course-progress-lessons .course-progress-lesson.current span,
            .page-template-page-no-buddypanel .header-account-login a:hover,
            .page-template-page-no-buddypanel .header-notifications .pop a:hover,
            .page-template-page-no-buddypanel .header-inner .left-col .header-navigation ul li a:hover {
                color: {$active_link_color};
            } 
            .is-mobile #buddypress div#subnav.item-list-tabs ul li.current a {
                color: #fff;
            }
            
            .wpProQuiz_questionList input[type=\"checkbox\"] + strong, 
            .wpProQuiz_questionList input[type=\"radio\"] + strong {
                color: ". esc_attr( get_option( 'boss_heading_font_color' ) ) .";
            }
            .single-sfwd-lessons u + table td .button-primary,
            .wpProQuiz_button2,
            input[type=\"button\"]:not(.button-small).wpProQuiz_button,
            #sfwd-mark-complete input[type=\"submit\"],
            .sfwd-courses a.button {
                border-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";                
            }
            .wpb_row .woocommerce ul.products li.product a img:hover {
                border-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            body .wpb_gallery .wpb_flexslider .flex-control-paging .flex-active {
                background-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            body .entry-content #students .vc_col-sm-3 a,
            body .entry-content #counters h3 {
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .wpProQuiz_formFields input[type=\"radio\"]:checked+strong,
            .courses-quizes-results .percent,
            .wpProQuiz_forms table td:nth-child(2) div,
            .quiz_title a,
            .learndash_profile_quizzes .failed .scores,
            #learndash_profile .list_arrow:before,
            .learndash_profile_heading .ld_profile_status,
            .profile_edit_profile a,
            #course_navigation .learndash_topic_widget_list .topic-notcompleted:before,
            .wpProQuiz_question_page,
            .learndash .in-progress:before,
            .learndash .notcompleted:before {
                color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .wpProQuiz_quiz_time,
            #learndash_profile dd.course_progress div.course_progress_blue,
            .widget_ldcourseprogress,
            .lms-post-content dd.course_progress div.course_progress_blue,
            .type-sfwd-courses .item-list-tabs ul li span,
            .single-sfwd-quiz dd.course_progress div.course_progress_blue,
            .wpProQuiz_time_limit .wpProQuiz_progress {
                background-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .type-sfwd-courses .item-list-tabs ul li:hover, .type-sfwd-courses .item-list-tabs ul li.current {
                border-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .wpProQuiz_questionList .wpProQuiz_questionListItem label.selected {
                border-color: ". esc_attr( get_option( 'boss_links_color' ) ) .";
            }
            .quiz_title a:hover,
            #learndash_profile .learndash_profile_details b,
            .profile_edit_profile a:hover {
                color: ". esc_attr( get_option( 'boss_heading_font_color' ) ) .";
            }
            .wpProQuiz_catName,
            span.wpProQuiz_catPercent {
                background-color: ". esc_attr( get_option( 'boss_layout_body_color' ) ) .";
            }
            #course_navigation .topic_item a.current,
            #course_navigation .active .lesson a {
                color: {$active_link_color};
            }
            #learndash_profile .learndash_profile_heading.course_overview_heading {
                background-color: {$sidebar_color};
            }
            ";
    return $css;
}

/**
 * Additional fields to customizer
 * 
 */
function boss_edu_customize_register( $wp_customize ) {
    
    $wp_customize->add_section( 'boss_edu_color_section' , array(
	    'title'       => __( 'Social Learner Options', 'social-learner' ),
	    'priority'    => 99999,
	    'description' => __( 'These are additional options added by "Social Learner child theme".', 'social-learner' )
	) );

		// Active link color
		$wp_customize->add_setting( 'boss_edu_active_link_color', array(
                'default'   		=> '#ea6645',
		        'transport' 		=> 'postMessage',
		        'sanitize_callback' => 'sanitize_hex_color',
		        'capability'        => 'edit_theme_options',
		        'type'           	=> 'option',
		    ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'boss_edu_active_link_color', array(
			    'label'  	    	=> __( 'Active link color', 'social-learner' ),
			    'section'    		=> 'boss_edu_color_section',
			    'settings'  	 	=> 'boss_edu_active_link_color',
			    'priority'  	  	=> 1
			) ) );
    
		// Sidebar Color
		$wp_customize->add_setting( 'boss_edu_sidebar_bg', array(
                'default'   		=> '#cdd7e2',
		        'transport' 		=> 'postMessage',
		        'sanitize_callback' => 'sanitize_hex_color',
		        'capability'        => 'edit_theme_options',
		        'type'           	=> 'option',
		    ) );
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'boss_edu_sidebar_bg', array(
			    'label'  	    	=> __( 'Sidebar BG', 'social-learner' ),
			    'section'    		=> 'boss_edu_color_section',
			    'settings'  	 	=> 'boss_edu_sidebar_bg',
			    'priority'  	  	=> 2
			) ) );
       
}
add_action( 'customize_register', 'boss_edu_customize_register' );

/**
 * Output badges on profile
 * 
 */
function boss_edu_profile_achievements (){
    global $user_ID;

    //user must be logged in to view earned badges and points

    if ( is_user_logged_in() && function_exists('badgeos_get_user_achievements')) {

        $achievements = badgeos_get_user_achievements(array( 'user_id' => bp_displayed_user_id()));

        if ( is_array( $achievements ) && ! empty( $achievements ) ) {

            $number_to_show = 5;
            $thecount = 0;

            wp_enqueue_script( 'badgeos-achievements' );
            wp_enqueue_style( 'badgeos-widget' );

            //load widget setting for achievement types to display
            $set_achievements = ( isset( $instance['set_achievements'] ) ) ? $instance['set_achievements'] : '';

            //show most recently earned achievement first
            $achievements = array_reverse( $achievements );

            echo '<ul class="profile-achievements-listing">';
            
            foreach ( $achievements as $achievement ) {

                //verify achievement type is set to display in the widget settings
                //if $set_achievements is not an array it means nothing is set so show all achievements
                if ( ! is_array( $set_achievements ) || in_array( $achievement->post_type, $set_achievements ) ) {

                    //exclude step CPT entries from displaying in the widget
                    if ( get_post_type( $achievement->ID ) != 'step' ) {

                        $permalink  = get_permalink( $achievement->ID );
                        $title      = get_the_title( $achievement->ID );
                        $img        = badgeos_get_achievement_post_thumbnail( $achievement->ID, array( 50, 50 ), 'wp-post-image' );
                        $thumb      = $img ? '<a style="margin-top: -25px;" class="badgeos-item-thumb" href="'. esc_url( $permalink ) .'">' . $img .'</a>' : '';
                        $class      = 'widget-badgeos-item-title';
                        $item_class = $thumb ? ' has-thumb' : '';

                        // Setup credly data if giveable
                        $giveable   = credly_is_achievement_giveable( $achievement->ID, $user_ID );
                        $item_class .= $giveable ? ' share-credly addCredly' : '';
                        $credly_ID  = $giveable ? 'data-credlyid="'. absint( $achievement->ID ) .'"' : '';

                        echo '<li id="widget-achievements-listing-item-'. absint( $achievement->ID ) .'" '. $credly_ID .' class="widget-achievements-listing-item'. esc_attr( $item_class ) .'">';
                        echo $thumb;
                        echo '<a class="widget-badgeos-item-title '. esc_attr( $class ) .'" href="'. esc_url( $permalink ) .'">'. esc_html( $title ) .'</a>';
                        echo '</li>';

                        $thecount++;

                        if ( $thecount == $number_to_show && $number_to_show != 0 ) {
                            echo '<li id="widget-achievements-listing-item-more" class="widget-achievements-listing-item">';
                            echo '<a class="badgeos-item-thumb" href="' . bp_core_get_user_domain( get_current_user_id() ) . '/achievements/"><span class="fa fa-ellipsis-h"></span></a>';
                            echo '<a class="widget-badgeos-item-title '. esc_attr( $class ) .'" href="' . bp_core_get_user_domain( get_current_user_id() ) . '/achievements/">'. __('See All', 'social-learner') .'</a>';
                            echo '</li>';
                            break;
                        }

                    }

                }
            }

            echo '</ul><!-- widget-achievements-listing -->';

        }

    }
}

/**
* Filter cover sizes
*
**/
add_filter( 'boss_profile_cover_sizes', 'boss_edu_profile_cover_sizes' );

function boss_edu_profile_cover_sizes () {
    if($GLOBALS['badgeos']) {
        return array('322'=>'Big', 'none' => 'No photo');
    }
    return array('322'=>'Big', '200'=>'Small', 'none' => 'No photo');
}
