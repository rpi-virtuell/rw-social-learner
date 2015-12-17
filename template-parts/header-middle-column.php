<?php
global $rtl;
$boxed	= 'fluid';

if ( $boxed == 'boxed' ) {
    // <!-- Custom menu -->
    $buddypanel_menu = wp_nav_menu( array(
        'theme_location' => 'left-panel-menu',
        'items_wrap'	 => '%3$s',
        'fallback_cb'	 => '',
        'container'		 => false,
        'echo'			 => false,
        'walker'		 => new BuddybossWalker
    ) );
}

$titlebar_menu = wp_nav_menu( array(
    'theme_location' => 'header-menu',
    'items_wrap'	 => '%3$s',
    'fallback_cb'	 => '',
    'echo'			 => false,
    'container'		 => false,
    'walker'		 => new BuddybossWalker
) );

if ( !empty( $buddypanel_menu ) || !empty( $titlebar_menu )):
    ?>
    <!-- Navigation -->
    <div class="header-navigation">
         <div id="header-menu">
             <ul>
             <?php echo $buddypanel_menu.$titlebar_menu; ?>
             </ul>
        </div>
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