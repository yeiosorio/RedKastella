jQuery(document).ready( function($) {
	jQuery('.customlinks_count').live('change',function(){
		
		var wrap = jQuery(this).closest('p').siblings('.customlinks_wrap');
		wrap.children('div').hide();
		var count = jQuery(this).val();
		for(var i = 1; i <= count; i++){
			wrap.find('.customlinks_'+i).show();
		}
	});
});