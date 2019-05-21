$gal = jQuery.noConflict();
var current_gallery_item = "";
var current_tab = "";

$gal(document).ready(function () {
		
	var gal_containers = $gal("#gallery_containers > div");
	var gal_nav_items = $gal("#gallery_nav ul li a");
	
	gal_containers.hide(0);
	
	// TABS ANIMATION
	gal_nav_items.click(function () {
			gal_containers.fadeOut(200);
			gal_containers.delay(200).filter(this.hash).fadeIn(200);
			
			current_tab = $gal(this).attr("href");
			
			gal_nav_items.removeClass("active");
			
			$gal(this).addClass("active");
					
		return false;
		
	}).filter(":first").click();
	
	// GALLERY SORTABLE FUNCTIONALITY
	
	$gal("#main_matrix_sortable").sortable();
	
	// GALLERY NEW IMAGE UPLOAD
	
	$gal('#upload_image_button_new').click(function() {
	 formfield = $gal('#upload_image_new').attr('name');
	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true'); 
	 assignThickBoxTarget();
	 return false;
	});
	
	// GALLERY ADD NEW IMAGE FUNCTION
	
	$gal(".gallery_new_submit").click(function () {
	
		addNewImage($gal(".gallery_new_title").val(),$gal(".gallery_new_model").val(), $gal("#upload_image_new").val(), $gal("#gallery_new_lightbox").val());
				
	});
	
	// GALLERY ROLLOVER ANIM
	
	$gal("#main_matrix_sortable li").hover(function () {
	
		$gal(this).find("div").stop().animate({"bottom": "0px"}, 200);	
			
	}, function () {
	
		$gal(this).find("div").stop().animate({"bottom": "-50px"}, 200);	
	
	});
	
	// GALLERY DELETE ITEM
	
	$gal("#main_matrix_sortable li.icon_remove a").click(function () {
	
		$gal(this).parent().parent().parent().parent().remove();
		
		return false;
	
	});
	
	// GALLERY EDIT ITEM
	
	$gal("#main_matrix_sortable li.icon_edit a").click(function () {
	
		current_gallery_item = $gal(this).parent().parent().parent().parent();
		
		pOpenImageEditor();
				
		$gal(".gallery_editor_title").attr("value", current_gallery_item.find(".gallery_item_title").attr("value"));
		$gal(".gallery_editor_model").attr("value", current_gallery_item.find(".gallery_item_model").attr("value"));
		$gal(".gallery_editor_lightbox").attr("value", current_gallery_item.find(".gallery_item_lightbox").attr("value"));
		
		return false;
	
	});
	
	// GALLERY CANCEL BUTTON FUNCTION
	
	$gal(".cancel_editor_changes").click(function () {
			
		$gal("#gallery_overlap").fadeOut(150);
		$gal("#editor_window").fadeOut(150);
	
		return false;
	
	});

	// GALLERY SAVE BUTTON FUNCTION
	
	$gal(".save_editor_changes").click(function () {
			
		$gal("#gallery_overlap").fadeOut(150);
		$gal("#editor_window").fadeOut(150);
		
		current_gallery_item.find(".gallery_item_title").attr("value", $gal(".gallery_editor_title").val());
		current_gallery_item.find(".gallery_item_model").attr("value", $gal(".gallery_editor_model").val());
		current_gallery_item.find(".gallery_item_lightbox").attr("value", $gal(".gallery_editor_lightbox").val());
	
		return false;
	
	});
	
});

function addNewImage (new_gallery_item_title, new_gallery_item_model, new_gallery_item_image, new_gallery_image_lightbox) {

	//if (!validateNewImage()) return false;

	var new_image_content = '<li><input type="text" value="' + new_gallery_item_title + '" name="gallery_item_title[]" style="display: none;" /><input type="text" value="' + new_gallery_item_model + '" name="gallery_item_model[]" style="display: none;" /><input type="text" value="' + new_gallery_item_image + '" name="gallery_item_image[]" style="display: none;" /><input type="text" value="' + new_gallery_image_lightbox + '" name="gallery_item_lightbox[]" style="display: none;" /></li>';

	$gal("ul#main_matrix_sortable").append(new_image_content);

}

function assignThickBoxTarget () {

	if (current_tab == "#new_gallery") {
	
		window.send_to_editor = function(html) {
		 imgurl = $gal('img',html).attr('src');
		 $gal("#upload_image_new").attr("value", imgurl);
		 tb_remove();
		}
	
	}

}

function validateNewImage () {
}
function pOpenImageEditor () {	
	$gal("#gallery_overlap").fadeTo(150, 0.9);
	$gal("#editor_window").fadeTo(150, 0.9);
}