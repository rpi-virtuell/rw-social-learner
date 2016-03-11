<?php if ( bp_docs_is_doc_edit() ) : ?>
	<?php bp_docs_media_buttons( 'doc_content' ) ?>
<?php endif; ?>
<?php if ( bp_docs_is_doc_create() ) : ?>
	<?php _e( 'First save, then upload.' ) ?>
<?php endif; ?>
<style>
	#doc-attachments a.fancybox .doc-image{
		border-radius: 3px;
		display: block;
		height: 155px;
		margin: 5px;
		width: 150px;
	}
	#doc-attachments a.fancybox{
		float:left;
	}

	#subnav{
		display:none
	}
</style>
<div id="doc-attachments">
	<?php foreach ( bp_docs_get_doc_attachments() as $attachment ) : ?>

		<?php


		switch($attachment->post_mime_type){
			case 'image/jpg':
			case 'image/jpeg':
			case 'image/png':
			case 'image/gif':
				$li = bp_docs_attachment_item_markup( $attachment->ID ) ;
				preg_match('#<a href="([^"]*)".*</a>#',$li,$matches);

				$image_url = $matches[1];
				$thumb_url = str_replace('.jpg', '-150x150.jpg',$image_url);
				$thumb_url = str_replace('.png', '-150x150.png',$thumb_url);
				$thumb_url = str_replace('.gif', '-150x150.gif',$thumb_url);

				$li = '<a class="fancybox" rel="doc"  href="'.$image_url.'">'.
						'<img src="'.$thumb_url.'" class="doc-image">'.
						'</a>';

				$images[]=$li;
			break;

			default:
				$files[] = bp_docs_attachment_item_markup( $attachment->ID ) ;
		}


		?>

	<?php endforeach;
	foreach($images  as $image){
		echo $image;
	}
	?>
</div>
<div style="clear:both"></div>
<hr>
<ul>
	<?php
	foreach($files  as $file){
		echo $file;
	}

	?>
</ul>
<br>
<div style="clear:both"></div>
<?php if ( !bp_docs_is_doc_edit() && !bp_docs_is_doc_create() ) : ?>
<script type="text/javascript">
	jQuery(".fancybox").fancybox();
	var images=[];
	jQuery.each(jQuery('a.fancybox'), function(i,el){
		console.log(el.href);
		if(images[el.href]){
			jQuery(el).remove();
		}else{
			images[el.href]=1;
		}
	});

	var navbar = jQuery('#item-nav .item-list-tabs ul')[0];
	navbar.id='nav-bar-filter';

	jQuery('#bp-create-doc-button').prependTo('.doc-tabs ul').removeClass('button');

</script>
<?php endif; ?>