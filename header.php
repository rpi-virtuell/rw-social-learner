<?php
/**
 * The Header for your theme.
 *
 * Displays all of the <head> section and everything up until <div id="main">
 *
 * @package WordPress
 * @subpackage Social Learner
 * @since Social Learner 1.0.0
 */
?><!DOCTYPE html>
<!--[if lt IE 9 ]>
<html class="ie ie-legacy" <?php language_attributes(); ?>> <![endif]-->
<!--[if gte IE 9 ]><!-->
<html class="ie" <?php language_attributes(); ?>>
<!--<![endif]-->
<!--[if ! IE  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="msapplication-tap-highlight" content="no"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.ico" type="image/x-icon">
<!-- BuddyPress and bbPress Stylesheets are called in wp_head, if plugins are activated -->
<?php wp_head(); ?>
</head>

<body <?php if ( current_user_can('manage_options') ) : ?>id="role-admin"<?php endif; ?> <?php body_class(); ?> data-logo="<?php if ( get_theme_mod( 'buddyboss_logo' ) ){ echo'1'; }else{ echo '0'; } ?>">

<?php do_action( 'buddyboss_before_header' ); ?>
    
<div id="scroll-to"></div> 


<header id="masthead" class="site-header" role="banner" data-infinite="<?php echo (esc_attr(get_option('boss_activity_infinite')) !== 'off')?'on':'off'; ?>" data-font="<?php echo get_theme_mod( 'boss_site_title_font_family' ); ?>">

    <div class="header-wrap">
        <div class="header-outher">

            <div class="header-inner">

                <div class="left-col">
                    <div class="table">

                        <div class="header-links">
                            <?php if(!is_page_template( 'page-no-buddypanel.php' ) && !(get_option( 'buddyboss_panel_hide' ) == '0' && !is_user_logged_in())) { ?>
                            
                            <!-- Menu Button -->
                            <a href="#" class="menu-toggle icon" id="left-menu-toggle" title="<?php _e('Menu','social-learner'); ?>">
                                <i class="fa fa-bars"></i>
                            </a><!--.menu-toggle-->
                            
                            <?php } ?>
                            
                        </div><!--.header-links-->
                        
                        <?php
                        $nav = wp_nav_menu( array( 'theme_location' => 'header-menu', 'fallback_cb'=>'','echo'=>false,'container_id' => 'header-menu', 'depth' => -1, 'walker' => new BuddybossWalker ) );	
                        if(!empty($nav)):
                        ?>
                        <!-- Navigation -->
                        <div class="header-navigation">
                            <?php echo $nav; ?>
                            <a href="#" class="responsive_btn"><i class="fa fa-align-justify"></i></a>
                        </div>
                        <?php else: ?>
                            <div class="header-navigation">
                                <p></p>
                            </div>
                        <?php endif; ?>
                        
                        <!-- search form -->
<!--                        <div id="header-search" class="search-form">-->
                        <div id="titlebar-search">
                        <?php
                        get_template_part( 'searchform', 'header' ); 
                        ?>
                        <a href="#" id="search-open" class="header-button" title="<?php _e( 'Search', 'social-learner' ); ?>"><i class="fa fa-search"></i></a>
                        </div><!--.search-form-->

                   </div>
                </div><!--.left-col-->
    
                <?php global $woocommerce; ?>
                <div class="right-col<?php if($woocommerce) { echo ' woocommerce'; } ?>">

                    <?php if ( is_user_logged_in() ) : ?>
                        <?php 
                        $name_class = '';
                        $update_data = wp_get_update_data();

                        if ($update_data['counts']['total'] && current_user_can( 'update_core' ) && current_user_can( 'update_plugins' ) && current_user_can( 'update_themes' )) { 
                            $name_class = 'has_updates';
                            ?>
                            <!-- Notification -->
                            <div class="header-notifications updates">
                                <a class="notification-link fa fa-refresh" href="<?php echo network_admin_url( 'update-core.php' ); ?>">
                                   <span class="ab-label"><?php echo number_format_i18n( $update_data['counts']['total'] ); ?></span>
                                </a>
                            </div>

                        <?php } ?>

                        <?php if(buddyboss_is_bp_active()):  

                            if(function_exists('buddyboss_notification_bp_members_shortcode_bar_notifications_menu')) {
                                echo do_shortcode('[buddyboss_notification_bar]');
                            } else {

                            $notifications = buddyboss_adminbar_notification();
                            $link = $notifications[0];
                            unset($notifications[0]);
                            ?>

                            <!-- Notification -->
                            <div class="header-notifications notifications">
                                <a class="notification-link fa fa-bell" href="<?php if($link) { echo $link->href; } ?>">
                                <?php if($link) { echo $link->title; } ?>
                                </a>

                                <div class="pop">
                                <?php
                                if($link) {
                                    foreach($notifications as $notification) {
                                        echo '<a href="'.$notification->href.'">'.$notification->title.'</a>';
                                    }
                                }
                                ?>
                                </div>
                            </div>

                            <?php 
                            } 
                            ?>

                        <?php endif; ?>



                        <!-- Woocommerce Notification -->
                        <?php 
                        if ($woocommerce) { 
                            $cart_items = $woocommerce->cart->cart_contents_count;
                        ?>
                        <div class="header-notifications">
                            <a class="cart-notification notification-link fa fa-shopping-cart" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">               
                            <?php if($cart_items) { ?>
                                <span><?php echo $cart_items; ?></span>
                            <?php } ?>
                            </a>
                        </div>
                        <?php } ?>

                        <?php if(buddyboss_is_bp_active()): ?> 

                            <!--Account details -->
                            <div class="header-account-login">

                                <?php do_action("buddyboss_before_header_account_login_block"); ?>

                                <a class="user-link" href="<?php echo bp_core_get_user_domain( get_current_user_id() ); ?>">
                                    <span class="name <?php echo $name_class; ?>"><?php echo bp_core_get_user_displayname( get_current_user_id() ); ?></span>
                                    <span>
                                        <?php echo bp_core_fetch_avatar ( array( 'item_id' => get_current_user_id(), 'type' => 'full', 'width' => '100', 'height' => '100' ) );  ?>                        </span>
                                </a>

                                <div class="pop">

                                    <!-- Dashboard links -->
                                    <?php 
                                    if( get_option( 'buddyboss_dashboard' ) !== '0' && ( current_user_can( 'level_10' ) || bp_get_member_type(get_current_user_id()) == 'teacher' || bp_get_member_type(get_current_user_id()) == 'group_leader') ): 
                                    ?>
                                    <div id="dashboard-links" class="bp_components">
                                        <ul>
                                            <?php if( is_multisite() ):?>
                                                <?php if( is_super_admin() ):?>
                                                    <li class="menupop">
                                                        <a class="ab-item" href="<?php echo admin_url( 'my-sites.php' ); ?>"><?php _e('My Sites','social-learner'); ?></a>
                                                        <div class="ab-sub-wrapper">
                                                            <ul class="ab-submenu">
                                                                <li class="menupop network-menu">
                                                                    <a class="ab-item" href="<?php echo network_admin_url(); ?>"><?php _e('Network Admin','social-learner'); ?></a>
                                                                    <div class="ab-sub-wrapper">
                                                                        <ul class="ab-submenu">
                                                                            <li>
                                                                                <a href="<?php echo network_admin_url(); ?>"><?php _e( 'Dashboard', 'social-learner' );?></a>
                                                                                <a href="<?php echo network_admin_url('sites.php'); ?>"><?php _e( 'Sites', 'social-learner' );?></a>
                                                                                <a href="<?php echo network_admin_url('users.php'); ?>"><?php _e( 'Users', 'social-learner' );?></a>
                                                                                <a href="<?php echo network_admin_url('themes.php'); ?>"><?php _e( 'Themes', 'social-learner' );?></a>
                                                                                <a href="<?php echo network_admin_url('plugins.php'); ?>"><?php _e( 'Plugins', 'social-learner' );?></a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                                <?php 
                                                                $current_blog_id = get_current_blog_id();

                                                                global $wp_admin_bar;
                                                                foreach ( (array) $wp_admin_bar->user->blogs as $blog ) {
                                                                    switch_to_blog( $blog->userblog_id );
                                                                    $blogname = empty( $blog->blogname ) ? $blog->domain : $blog->blogname;
                                                                    ?>
                                                                    <li class="menupop">
                                                                        <a class="ab-item" href="<?php echo home_url(); ?>"><?php echo $blogname; ?></a>
                                                                        <div class="ab-sub-wrapper">
                                                                            <ul class="ab-submenu">
                                                                                <li>
                                                                                    <a href="<?php echo admin_url(); ?>"><?php _e( 'Dashboard', 'social-learner' );?></a>
                                                                                    <a href="<?php echo admin_url('users.php'); ?>"><?php _e( 'Users', 'social-learner' );?></a>
                                                                                    <a href="<?php echo admin_url('themes.php'); ?>"><?php _e( 'Themes', 'social-learner' );?></a>
                                                                                    <a href="<?php echo admin_url('plugins.php'); ?>"><?php _e( 'Plugins', 'social-learner' );?></a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </li>
                                                                    <?php 
                                                                }

                                                                //switch back to current blog
                                                                switch_to_blog( $current_blog_id );
                                                                ?>
                                                            </ul>
                                                        </div>
                                                    </li>
                                                <?php endif;?>
                                                    <li class="menupop">
                                                        <a class="ab-item" href="<?php echo admin_url(); ?>"><?php _e('Dashboard','social-learner'); ?></a>
                                                        <div class="ab-sub-wrapper">
                                                            <ul class="ab-submenu">
                                                                <li>
                                                                    <a href="<?php echo admin_url('customize.php'); ?>"><?php _e( 'Customize', 'social-learner' );?></a>
                                                                    <a href="<?php echo admin_url('widgets.php'); ?>"><?php _e( 'Widgets', 'social-learner' );?></a>
                                                                    <a href="<?php echo admin_url('nav-menus.php'); ?>"><?php _e( 'Menus', 'social-learner' );?></a>
                                                                    <a href="<?php echo admin_url('plugins.php'); ?>"><?php _e( 'Plugins', 'social-learner' );?></a>
                                                                    <a href="<?php echo admin_url('themes.php'); ?>"><?php _e( 'Themes', 'social-learner' );?></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </li>
                                            <?php else: ?>
                                                <li class="menupop">
                                                    <a class="ab-item" href="<?php echo admin_url(); ?>"><?php _e('Dashboard','social-learner'); ?></a>
                                                    <div class="ab-sub-wrapper">
                                                        <ul class="ab-submenu">
                                                            <li>
                                                                <a href="<?php echo admin_url('customize.php'); ?>"><?php _e( 'Customize', 'social-learner' );?></a>
                                                                <a href="<?php echo admin_url('widgets.php'); ?>"><?php _e( 'Widgets', 'social-learner' );?></a>
                                                                <a href="<?php echo admin_url('nav-menus.php'); ?>"><?php _e( 'Menus', 'social-learner' );?></a>
                                                                <a href="<?php echo admin_url('plugins.php'); ?>"><?php _e( 'Plugins', 'social-learner' );?></a>
                                                                <a href="<?php echo admin_url('themes.php'); ?>"><?php _e( 'Themes', 'social-learner' );?></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </li>
                                            <?php endif; ?>


                                        </ul>
                                    </div>
                                    <?php endif; ?>

                                    <!-- Adminbar -->
                                    <div id="adminbar-links" class="bp_components">
                                    <?php 
                                        buddyboss_adminbar_myaccount();
                                    ?>
                                    </div>

                                    <?php
                                    wp_nav_menu( array( 'theme_location' => 'header-my-account', 'fallback_cb'=>'','menu_class' => 'links' ) );		
                                    ?>

                                    <span class="logout">
                                        <a href="<?php echo wp_logout_url(); ?>"><?php _e('Logout','social-learner'); ?></a>
                                    </span>
                                </div> 

                                <?php do_action("buddyboss_after_header_account_login_block"); ?>

                            </div><!--.header-account-login-->

                            <?php endif; ?>
                                
                    <?php else: ?>
                         
                        <!-- Register/Login links for logged out users -->
                        <?php if ( !is_user_logged_in() && buddyboss_is_bp_active() && !bp_hide_loggedout_adminbar( false ) ) : ?>

                            <?php if ( buddyboss_is_bp_active() && bp_get_signup_allowed() ) : ?>
                                <a href="<?php echo bp_get_signup_page(); ?>" class="register screen-reader-shortcut"><?php _e( 'Register', 'social-learner' ); ?></a>
                            <?php endif; ?>

                            <a href="<?php echo wp_login_url(); ?>" class="login"><?php _e( 'Login', 'social-learner' ); ?></a>

                        <?php endif; ?>

                         

                    <?php endif; ?> <!-- if ( is_user_logged_in() ) -->

                </div><!--.right-col-->
            

            </div><!-- .header-inner -->

        </div><!-- .header-wrap -->
    </div><!-- .header-outher -->
    
    <div id="mastlogo">
         <!-- Look for uploaded logo -->
            <?php if ( get_theme_mod( 'buddyboss_logo' ) ) : ?>
                <div id="logo">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                        <img class="large" src="<?php echo esc_url( get_theme_mod( 'buddyboss_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                    </a>
                    
                    <?php if ( get_theme_mod( 'buddyboss_small_logo' ) ) : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                        <img class="small" src="<?php echo esc_url( get_theme_mod( 'buddyboss_small_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                    </a>
                    <?php else: ?>
                        <h1 class="site-title small">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                    <?php endif; ?>
                </div>

            <!-- If no logo, display site title and description -->
            <?php else: ?>
                <div class="site-name">
                    <h1 class="site-title">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                            <?php bloginfo( 'name' ); ?>
                        </a>
                    </h1>
                    <p class="site-description"><?php bloginfo( 'description' ); ?></p>
                </div>
            <?php endif; ?>
    </div><!-- .mastlogo -->
</header><!-- #masthead -->

<?php do_action( 'buddyboss_after_header' ); ?>



<div id="mobile-header" class="table"> 
  <!-- Toolbar for Mobile -->
    <div class="mobile-header-outer table-cell">
        <div class="mobile-header-inner table">
           <!-- Custom menu trigger button -->
           <div id="custom-nav-wrap" class="btn-wrap">
               <a href="#" id="custom-nav" class="sidebar-btn fa fa-bars"></a>
            </div>
            
            <?php if(get_option('boss_search_instead') == '1' && is_user_logged_in()) : ?>
                <?php
                    echo get_search_form();
                ?>
            <?php else : ?>
                <!-- Title and Logo -->
                <?php if ( get_theme_mod( 'buddyboss_logo' ) ) : ?>
                    <div id="mobile-logo" class="table-cell">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
                            <img class="large" src="<?php echo esc_url( get_theme_mod( 'buddyboss_logo' ) ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
                        </a>
                    </div>
                <?php else : ?>
                    <h1 class="table-cell"><a class="mobile-site-title" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                <?php endif; ?>
            <?php endif; ?>
            <!-- Profile menu trigger button -->
            <?php if ( is_user_logged_in() || ( !is_user_logged_in() && buddyboss_is_bp_active() && !bp_hide_loggedout_adminbar( false ) ) ) : ?>
            <div id="profile-nav-wrap" class="btn-wrap">
                <a href="#" id="profile-nav" class="sidebar-btn fa fa-user table-cell"><span id="ab-pending-notifications-mobile" class="pending-count no-alert"></span></a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div><!-- #mobile-header -->

<div id="panels">
    <div id="left-panel" class="menu-panel">
        <div id="left-panel-inner">
            <div id="scroll-area">
               
                <?php if(!is_page_template( 'page-no-buddypanel.php' ) && !(get_option( 'buddyboss_panel_hide' ) == '0' && !is_user_logged_in())) { ?>
                <!-- Custom menu -->
                <?php
                echo wp_nav_menu( array( 'theme_location' => 'left-panel-menu', 'container_id' => 'nav-menu','fallback_cb'=>'','depth'=>2,'echo'=>false, 'walker' => new BuddybossWalker ) );
                ?>
                
                <?php } ?>
                
                <!-- Adminbar -->
                <div class="bp_components mobile">
                <?php 
                    buddyboss_adminbar_myaccount();
                ?>
                <!-- Register/Login links for logged out users -->
                <?php if ( !is_user_logged_in() && buddyboss_is_bp_active() && !bp_hide_loggedout_adminbar( false ) ) : ?>

                    <?php if ( buddyboss_is_bp_active() && bp_get_signup_allowed() ) : ?>
                        <a href="<?php echo bp_get_signup_page(); ?>" class="register-link screen-reader-shortcut"><?php _e( 'Register', 'social-learner' ); ?></a>
                    <?php endif; ?>

                    <a href="<?php echo wp_login_url(); ?>" class="login-link screen-reader-shortcut"><?php _e( 'Login', 'social-learner' ); ?></a>

                <?php endif; ?>
                </div>

            </div><!--scroll-area-->
        </div><!--left-panel-inner-->
    </div><!--left-panel-->
    
    <!-- Left Mobile Menu -->
    <div id="mobile-menu" class="menu-panel">
        <div id="mobile-menu-inner" data-titlebar="<?php echo (get_option( 'buddyboss_titlebar_position' ))?get_option( 'buddyboss_titlebar_position' ) : 'top'; ?>">
           
            <?php if(!is_page_template( 'page-no-buddypanel.php' ) && !(get_option( 'buddyboss_panel_hide' ) == '0' && !is_user_logged_in())) { ?>
            <!-- Custom menu -->
            <?php
                echo wp_nav_menu( array( 'theme_location' => 'left-panel-menu', 'container_id' => 'nav-menu','fallback_cb'=>'','depth'=>2,'echo'=>false, 'walker' => new BuddybossWalker ) );
            ?>
            <?php } ?>
        </div> <!--#mobile-menu-->
    </div> <!--#mobile-menu-->
   
    <div id="right-panel">
        <div id="right-panel-inner">
            <div id="main-wrap"> <!-- Wrap for Mobile content -->
                <div id="inner-wrap"> <!-- Inner Wrap for Mobile content -->
                    <?php do_action( 'buddyboss_inside_wrapper' ); ?>
                    <div id="page" class="hfeed site">
                        <div id="main" class="wrapper">
