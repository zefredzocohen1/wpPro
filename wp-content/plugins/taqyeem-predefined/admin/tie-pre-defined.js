jQuery(document).ready(function() {

	jQuery('.taq-reviews-group').sortable({placeholder: "taqyeem-state-highlight"});

	//------------
	jQuery("#add_review_criteria_group").click(function() {
        var criteria_group_name = prompt( taqyeem_predefined_lang.enter_group_name );
        if( criteria_group_name ){	
			jQuery('#taqyeem-reviews-criteria-group').append('\
				<div data-gropid="'+nextGroup+'" class="taqyeem-item">\
					<h3>'+criteria_group_name+'</h3>\
					<input type="hidden" name="taqyeem_options[group]['+nextGroup+'][title]" value="'+criteria_group_name+'" />\
					<a class="del-review del-group" title="'+taqyeem_lang.del+'">'+taqyeem_lang.del+'</a>\
					<ul class="taq-reviews-group">\
						<li class="taqyeem-option-item taqyeem-review-item">\
							<span class="label">'+taqyeem_lang.review_criteria+'</span>\
							<input name="taqyeem_options[group]['+nextGroup+'][criteria][]" type="text" value="">\
							<a class="del-review del-group" title="'+taqyeem_lang.del+'"></a>\
						</li>\
					</ul>\
					<input type="button" class="button add_review_criteria_item_in_group" value="'+taqyeem_predefined_lang.add_new_group_item+'">\
				</div>'
			);
			jQuery('.taq-reviews-group').sortable({placeholder: "taqyeem-state-highlight"});
			jQuery(this).parent().find('li input').focus();
			nextGroup++ ;
		}
	});
	
	//------------
	jQuery( document ).on( "click", ".add_review_criteria_item_in_group", function() {
		var groubID = jQuery(this).parent().attr( 'data-gropid' );
		jQuery(this).parent().find('.taq-reviews-group').append('\
					<li class="taqyeem-option-item taqyeem-review-item">\
						<span class="label">'+taqyeem_lang.review_criteria+'</span>\
						<input name="taqyeem_options[group]['+groubID+'][criteria][]" type="text" value="">\
						<a class="del-review del-group" title="'+taqyeem_lang.del+'"></a>\
					</li>\
			</div>'
		);
		jQuery(this).parent().find('li input').focus();
				
	});
	
	//------------
	jQuery('#select-pre-defined-criteria').bind('change', function () {
		var itemList = jQuery(this).val();
		if( itemList != '#NONE#'){
			var listArray = itemList.split('|');
			for (var i = 0; i < listArray.length; i++) {
				taqyeem_add_item( listArray[i] );
			}
		}
		return false;
	});

});