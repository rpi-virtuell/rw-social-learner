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
<html <?php language_attributes(); ?>>
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

<?php
global $rtl;
    $logo	 = ( boss_get_option( 'logo_switch' ) && boss_get_option( 'boss_logo', 'id' ) ) ? '1' : '0';
    $inputs	 = ( boss_get_option( 'boss_inputs' ) ) ? '1' : '0';
    $boxed	 = 'fluid';
?>

<body <?php body_class(); ?> data-logo="<?php echo $logo; ?>" data-inputs="<?php echo $inputs; ?>" data-rtl="<?php echo ($rtl) ? 'true' : 'false'; ?>">

<?php do_action( 'buddyboss_before_header' ); ?>
    
<div id="scroll-to"></div> 

<header id="masthead" class="site-header" role="banner" data-infinite="<?php echo ( boss_get_option( 'boss_activity_infinite' ) ) ? 'on' : 'off'; ?>">

    <div class="header-wrap">
        <div class="header-outher">

            <div class="header-inner">
                <?php get_template_part( 'template-parts/header-fluid-layout-column' ); ?>
                <?php get_template_part( 'template-parts/header-profile' ); ?>
            </div><!-- .header-inner -->

        </div><!-- .header-wrap -->
    </div><!-- .header-outher -->
    
    <div id="mastlogo">
        <?php get_template_part( 'template-parts/header-logo' ); ?>
        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
    </div><!-- .mastlogo -->
</header><!-- #masthead -->

<?php do_action( 'buddyboss_after_header' ); ?>

<?php get_template_part( 'template-parts/header-mobile' ); ?>

<div id="panels" class="<?php echo (boss_get_option( 'boss_adminbar' )) ? 'with-adminbar' : ''; ?>">

    <!-- Left Panel -->
    <?php get_template_part( 'template-parts/left-panel' ); ?>

    <!-- Left Mobile Menu -->
    <?php get_template_part( 'template-parts/left-mobile-menu' ); ?>
   
    <div id="right-panel">
        <div id="right-panel-inner">
            <div id="main-wrap"> <!-- Wrap for Mobile content -->
                <div id="inner-wrap"> <!-- Inner Wrap for Mobile content -->
                    <?php do_action( 'buddyboss_inside_wrapper' ); ?>
                    <div id="page" class="hfeed site">
                        <div id="main" class="wrapper">
