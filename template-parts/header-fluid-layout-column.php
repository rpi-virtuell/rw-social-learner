<?php
global $rtl;
$boxed	= 'fluid';
?>
<div class="<?php echo ($rtl)?'right-col':'left-col'; ?>">
    <div class="table">

        <div class="header-links">
            <?php if(!is_page_template( 'page-no-buddypanel.php' ) && !(!boss_get_option( 'boss_panel_hide' ) && !is_user_logged_in()) && $boxed == 'fluid') { ?>

            <!-- Menu Button -->
            <a href="#" class="menu-toggle icon" id="left-menu-toggle" title="<?php _e('Menu','social-learner'); ?>">
                <i class="fa fa-bars"></i>
            </a><!--.menu-toggle-->

            <?php } ?>

        </div><!--.header-links-->
        <?php get_template_part( 'template-parts/header-middle-column' ); ?>
   </div>
</div><!--.left-col-->