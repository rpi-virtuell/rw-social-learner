<div id="buddypress">

    <?php include( apply_filters( 'bp_docs_header_template', bp_docs_locate_template( 'docs-header.php' ) ) ) ?>

    <div class="docs-info-header">
        <?php bp_docs_info_header() ?>
    </div>

    <ul class="docs-tree tree-node">

    <?php
    /**
     * Created by PhpStorm.
     * User: Joachim
     * Date: 12.03.2016
     * Time: 10:06
     */
    include_once get_stylesheet_directory().'/RW_BuddyPress_Docs_Tree.php';

    RW_BuddyPress_Docs_Tree::the_docs_tree('ul', $group_id);
?>

    </ul>

</div>

<script>

    jQuery().ready(function($){

        var  options = {
            insertZone:50,
            insertZonePlus:true,
            listSelector:'ul',
            placeholderCss: {'background-color':'#FFC3B7'},
            hintCss: {'background-color':'#B2FFDD'},
            ignoreClass: 'docs-tree-link',
            opener: {
                active: true,
                as: 'html',
                close:  '<i class="fa fa-minus red"></i>',
                open:   '<i class="fa fa-plus green"></i>',
                openerClass: 'docs-tree-opener'
            },
            onChange:function(el){
                var this_id = el.attr('data-id');
                var parent_id = el.parents('li').first().attr('data-id');

                if(parent_id === undefined){
                    parent_id = 0;
                }


                var order = [], n = 0;
                el.prevAll().each(function(i,elem){
                    li = $(elem);
                    if(li.attr('data-id') !== undefined){
                        n++;
                        order.push( {post_id : li.attr('data-id'), menu_order: n } );
                    }

                })
                n++;
                order.push( {post_id : this_id, menu_order: n } );
                el.nextAll().each(function(i,elem){
                    li = $(elem);
                    if(li.attr('data-id') !== undefined){
                        n++;
                        order.push( {post_id : li.attr('data-id'), menu_order: n } );
                    }
                })


                var data = {
                    action: 'rw_buddypress_docs_tree_change_node',
                    security: '<?php echo wp_create_nonce( "rw_buddypress_docs_tree_change_node_nonce" ); ?>',
                    post_id: this_id,
                    parent: parent_id,
                    order: order
                };
                $.post(ajaxurl, data, function(response) {
                    if(! response.success){
                        alert('Der Verzeichnisbaum konnte nicht geändert werden');
                    }
                });
            }
        };

        <?php if(groups_is_user_member(get_current_user_id(), $group_id)):?>
        $('.docs-tree').sortableLists( options );
        <?php endif; ?>

    });


</script>