jQuery(document).ready(function() {

	var reviews_on = jQuery("select[name='taq_review_position'] option:selected ").val();
	if (reviews_on != '') {	jQuery('#taq-reviews-options').show();	}
	if (reviews_on == 'custom') {	jQuery('#taqyeem_custom_position_hint').show();	}
	jQuery("select[name='taq_review_position']").change(function(){
		var reviews_on = jQuery("select[name='taq_review_position'] option:selected ").val();
		if (reviews_on == '') {
			jQuery('#taq-reviews-options').fadeOut();
			jQuery('#taqyeem_custom_position_hint').fadeOut();
		}else if( reviews_on == 'custom' ){
			jQuery('#taq-reviews-options').fadeIn();
			jQuery('#taqyeem_custom_position_hint').fadeIn();
		}else{
			jQuery('#taq-reviews-options').fadeIn();
			jQuery('#taqyeem_custom_position_hint').fadeOut();
		}
	 });

	jQuery(".taqyeem-submit .taqyeem-save").click( function() {
		jQuery('#save-alert').fadeIn();
	});


	jQuery("#add_review_criteria").click(function() {
		taqyeem_add_item( '' );
	});
	
	jQuery(document).on('click', '.del-review' , function () {
		jQuery(this).parent().addClass('taq-removered').fadeOut(function() {
			jQuery(this).remove();
		});
	});

	jQuery(".taq-tabs-wrap").hide();
	jQuery(".taqyeem-panel-tabs ul li:first").addClass("active").show();
	jQuery(".taq-tabs-wrap:first").show(); 
	jQuery("li.tie-tabs:not(.tie-not-tab)").click(function() {
		jQuery(".taqyeem-panel-tabs ul li").removeClass("active");
		jQuery(this).addClass("active");
		jQuery(".taq-tabs-wrap").hide();
		var activeTab = jQuery(this).find("a").attr("href");
		jQuery(activeTab).fadeIn(150);
		return false;
	});
	
	jQuery('.taqyeem-panel-content p:empty').remove();

});

function taqyeem_add_item( defTitle ){
	jQuery('#taqyeem-reviews-list').append('<li id="reviewItem_'+ taqNextReview +'" class="taqyeem-option-item taqyeem-review-item"><span class="label">'+taqyeem_lang.review_criteria+'</span><input name="taq_review_criteria['+ taqNextReview +'][name]" type="text" value="'+defTitle+'" /><div class="clear"></div><span class="label">'+taqyeem_lang.criteria_score+'</span><div id="criteria'+ taqNextReview +'-slider"></div><input type="text" id="criteria'+ taqNextReview +'" value="0" name="taq_review_criteria['+ taqNextReview +'][score]" style="width:40px; opacity: 0.7;" /><a class="del-review" title="Delete">'+taqyeem_lang.del+'</a>	<script>jQuery("#criteria'+ taqNextReview +'-slider").slider({range: "min",min: 0,	max: 100,value: 0 ,	slide: function(event, ui) {jQuery("#criteria'+ taqNextReview+'").attr("value", ui.value );}});</script></li>');
	jQuery('#reviewItem_'+ taqNextReview).hide().fadeIn();
	taqNextReview ++ ;
}