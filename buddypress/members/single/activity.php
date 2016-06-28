<?php
/**
 * BuddyPress - Users Activity
 *
 * @package BuddyPress
 * @subpackage bp-legacy
 */

?>
<style>
    form#whats-new-form {
        position: inherit;
    }
    html.js.csstransforms.csstransforms3d.csstransitions body.activity.bp-user.my-activity.my-account.just-me.buddypress.bbp-user-page.single.singular.bbpress.page.page-id-0.page-parent.page-template-default.logged-in.admin-bar.role-admin.has-activity.is-desktop.bp-active.left-menu-open.buddyboss-wall-active.buddyboss-media-has-photos-type.social-learner.js.customize-support div#panels.with-adminbar div#right-panel div#right-panel-inner div#main-wrap div#inner-wrap div#page.hfeed.site div#main.wrapper div.page-full-width.network-profile.my-profile div#primary.site-content div#content article div.entry-buddypress-content div#buddypress div.full-width div#item-main-content div#item-body div#subnav.no-ajax.no-hidden ul.no-hidden li#just-me-personal-li
    {
        display: inline !important;
        padding-right: 10px;
    }
    html.js.csstransforms.csstransforms3d.csstransitions body.activity.bp-user.my-activity.my-account.just-me.buddypress.bbp-user-page.single.singular.bbpress.page.page-id-0.page-parent.page-template-default.logged-in.admin-bar.role-admin.has-activity.is-desktop.bp-active.left-menu-open.buddyboss-wall-active.buddyboss-media-has-photos-type.social-learner.js.customize-support div#panels.with-adminbar div#right-panel div#right-panel-inner div#main-wrap div#inner-wrap div#page.hfeed.site div#main.wrapper div.page-full-width.network-profile.my-profile div#primary.site-content div#content article div.entry-buddypress-content div#buddypress div.full-width div#item-main-content div#item-body div#subnav.no-ajax.no-hidden ul.no-hidden li#news-feed-personal-li
    {
        display: inline !important;
        padding-right: 10px;
    }
    html.js.csstransforms.csstransforms3d.csstransitions body.activity.bp-user.my-activity.my-account.just-me.buddypress.bbp-user-page.single.singular.bbpress.page.page-id-0.page-parent.page-template-default.logged-in.admin-bar.role-admin.has-activity.is-desktop.bp-active.left-menu-open.buddyboss-wall-active.buddyboss-media-has-photos-type.social-learner.js.customize-support div#panels.with-adminbar div#right-panel div#right-panel-inner div#main-wrap div#inner-wrap div#page.hfeed.site div#main.wrapper div.page-full-width.network-profile.my-profile div#primary.site-content div#content article div.entry-buddypress-content div#buddypress div.full-width div#item-main-content div#item-body div#subnav.no-ajax.no-hidden ul.no-hidden li#favorites-personal-li
    {
        display: inline !important;
        padding-right: 10px;
    }
    html.js.csstransforms.csstransforms3d.csstransitions body.activity.bp-user.my-activity.my-account.just-me.buddypress.bbp-user-page.single.singular.bbpress.page.page-id-0.page-parent.page-template-default.logged-in.admin-bar.role-admin.has-activity.is-desktop.bp-active.left-menu-open.buddyboss-wall-active.buddyboss-media-has-photos-type.social-learner.js.customize-support div#panels.with-adminbar div#right-panel div#right-panel-inner div#main-wrap div#inner-wrap div#page.hfeed.site div#main.wrapper div.page-full-width.network-profile.my-profile div#primary.site-content div#content article div.entry-buddypress-content div#buddypress div.full-width div#item-main-content div#item-body div#subnav.no-ajax.no-hidden ul.no-hidden li#activity-filter-select
    {
        display: inline !important;
    }
</style>
<div class="no-ajax no-hidden" id="subnav" role="navigation">
    <ul class="no-hidden">

        <?php bp_get_options_nav(); ?>
        <li id="activity-filter-select" style="float: right; z-index: 10000; padding-right: 0px;">
            <label for="activity-filter-by"><?php _e( 'Show:', 'buddypress' ); ?></label>
            <select id="activity-filter-by" class="activity-filter-by SumoUnder" multiple="multiple" >
                <option value="-1"><?php _e( '&mdash; Everything &mdash;', 'buddypress' ); ?></option>

                <?php bp_activity_show_filters(); ?>

                <?php

                /**
                 * Fires inside the select input for member activity filter options.
                 *
                 * @since 1.2.0
                 */
                do_action( 'bp_member_activity_filter_options' ); ?>

            </select>
        </li>
    </ul>
</div><!-- .item-list-tabs -->

<?php

/**
 * Fires before the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_post_form' ); ?>

<?php
if ( is_user_logged_in() && bp_is_my_profile() && ( !bp_current_action() || bp_is_current_action( 'just-me' ) ) )
    bp_get_template_part( 'activity/post-form' );

/**
 * Fires after the display of the member activity post form.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_post_form' );

/**
 * Fires before the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_member_activity_content' ); ?>

<div class="activity">

    <?php bp_get_template_part( 'activity/activity-loop' ) ?>

</div><!-- .activity -->

<?php

/**
 * Fires after the display of the member activities list.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_member_activity_content' ); ?>
