<?php
global $rtl, $woocommerce;
$boxed	= 'fluid';
?>
<div class="<?php echo ($rtl)?'left-col':'right-col'; ?><?php if($woocommerce) { echo ' woocommerce'; } ?>">

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
        <?php echo boss_edu_cart_icon_html(); ?>

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
                    if ( boss_get_option( 'boss_dashboard' ) && ( current_user_can( 'level_10' ) || bp_get_member_type( get_current_user_id() ) == 'teacher' || bp_get_member_type( get_current_user_id() ) == 'group_leader') ) :
                        get_template_part( 'template-parts/header-dashboard-links' );
                    endif;
                    ?>

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

        <!-- Woocommerce Notification for guest users-->
        <?php echo boss_edu_cart_icon_html(); ?>

        <!-- Register/Login links for logged out users -->
        <?php if ( !is_user_logged_in() && buddyboss_is_bp_active() && !bp_hide_loggedout_adminbar( false ) ) : ?>

            <?php if ( buddyboss_is_bp_active() && bp_get_signup_allowed() ) : ?>
                <a href="<?php echo bp_get_signup_page(); ?>" class="register screen-reader-shortcut"><?php _e( 'Register', 'social-learner' ); ?></a>
            <?php endif; ?>

            <a href="<?php echo wp_login_url(); ?>" class="login"><?php _e( 'Login', 'social-learner' ); ?></a>

        <?php endif; ?>



    <?php endif; ?> <!-- if ( is_user_logged_in() ) -->

</div><!--.right-col-->