jQuery(document).ready(function() {

// Apply Saved Data on the Preview Button ======================== ||
	var savedButtonSize  = jQuery("input[name='taq_review_button_size']:checked ").val();
	var savedButtonShape = jQuery("input[name='taq_review_button_shape']:checked ").val();
	var savedButtonType  = jQuery("input[name='taq_review_button_type']:checked ").val();
	
	if( jQuery("input[name='taq_button_icon']").length > 0 ){
		var savedButtonIcon  = jQuery("input[name='taq_button_icon']").val();
	}

	if( savedButtonIcon == 'fa.none'){
		jQuery( '#taq-button-preview a i' ).hide();
		jQuery( '#taq-button-preview .taq-button').addClass( 'without-icon' );
	}

	jQuery('#taq-button-preview .taq-button').addClass( 'taq-'+savedButtonSize + ' taq-'+savedButtonShape + ' taq-'+savedButtonType );
	jQuery( '#taq-button-icons li a i.'+savedButtonIcon ).parent().addClass( 'active' );


// Choose Button Icon ============================================ ||
	jQuery("#taq-button-icons li a").click(function() {
	
		if( jQuery(this).is( '#none-icon' ) ){
			jQuery( '#taq-button-preview a i' ).hide();
			jQuery( '#taq-button-preview .taq-button' ).addClass( 'without-icon' );
			jQuery( '#taq_button_icon' ).val( '' );
		}else{
			jQuery( '#taq-button-preview a i' ).show();
			jQuery( '#taq-button-preview .taq-button' ).removeClass( 'without-icon' );
		}
		
		jQuery( '#taq-button-icons li a' ).removeClass( 'active' );
		jQuery(this).addClass( 'active' );
		var iconClass = jQuery(this).find('i').attr('class');
		jQuery( '#taq-button-preview a i' ).attr( 'class', iconClass );
		jQuery( '#taq_button_icon' ).val( iconClass );
		
		return false;
		
	});

// Predefined Buttons ============================================ ||
	jQuery("#taq-predefined-buttons-item a").click(function() {
		var buttonIcon = jQuery(this).attr('data-icon');
		var buttonText = jQuery(this).attr('data-text');
		var buttonColor = jQuery(this).attr('data-color');
		
		jQuery( '#taq-button-preview a i' ).show();
		jQuery('#taq-button-preview .taq-button').removeClass( 'without-icon' );
			
		//Set Icons
		jQuery( '#taq-button-icons li a' ).removeClass( 'active' );
		jQuery( '#taq-button-icons li a i.'+buttonIcon ).parent().addClass( 'active' );
		jQuery("#taq-button-icons").scrollTo( 'li a i.'+buttonIcon, 800, {offset:-150} );
		jQuery( '#taq-button-preview a i' ).attr( 'class', 'fa '+buttonIcon );
		jQuery( '#taq_button_icon' ).val( 'fa '+buttonIcon );

		//Set Button Text
		jQuery('#taq-button-preview .taq-button span.button-text').text( buttonText );
		jQuery('#taq_review_button_text').val( buttonText );

		//Set Button Color
		jQuery('#taq-button-preview .taq-button').css('backgroundColor', '#' + buttonColor);
		jQuery('#taq_review_button_colorcolorSelector div').css('backgroundColor', '#' + buttonColor);
		jQuery('#taq_review_button_color').val('#'+buttonColor);
			
		return false;
	});
	
// Live Preview Button Text ============================================ ||
	jQuery("#taq_review_button_text").keyup(function() {
		var currentText = jQuery(this).val();
		jQuery('#taq-button-preview .taq-button span.button-text').text( currentText );
	});
	
// Enable / Disable Buttons ============================================ ||
	if( jQuery("input[name='taq_review_button_enable']").is(":checked") ){jQuery( '#taq-button-wrapper' ).show();}
	jQuery("input[name='taq_review_button_enable']").change(function( ){
		if( jQuery("input[name='taq_review_button_enable']").is(":checked") ){
			jQuery( '#taq-button-wrapper' ).fadeIn();
		}else{
			jQuery( '#taq-button-wrapper' ).hide();
		}
	 });

// Button Size ============================================ ||
	 jQuery("input[name='taq_review_button_size']").change(function(){
		var buttonSize = jQuery("input[name='taq_review_button_size']:checked ").val();
		jQuery('#taq-button-preview .taq-button').removeClass( 'taq-large taq-small' );
		jQuery('#taq-button-preview .taq-button').addClass( 'taq-'+buttonSize );
	 });	

// Button Shape ============================================ ||	 
	jQuery("input[name='taq_review_button_shape']").change(function(){
		var buttonShape = jQuery("input[name='taq_review_button_shape']:checked ").val();
		jQuery('#taq-button-preview .taq-button').removeClass( 'taq-round taq-pill' );
		jQuery('#taq-button-preview .taq-button').addClass( 'taq-'+buttonShape );
	 });
	
// Button Type ============================================ ||	
	jQuery("input[name='taq_review_button_type']").change(function(){
		var buttonShape = jQuery("input[name='taq_review_button_type']:checked ").val();
		jQuery('#taq-button-preview .taq-button').removeClass( 'taq-flat taq-gradient' );
		jQuery('#taq-button-preview .taq-button').addClass( 'taq-'+buttonShape );
	 });
	 
});

/**
 * Copyright (c) 2007-2014 Ariel Flesler - aflesler<a>gmail<d>com | http://flesler.blogspot.com
 * Licensed under MIT
 * @author Ariel Flesler
 * @version 1.4.13
 */
;(function(k){'use strict';k(['jquery'],function($){var j=$.scrollTo=function(a,b,c){return $(window).scrollTo(a,b,c)};j.defaults={axis:'xy',duration:parseFloat($.fn.jquery)>=1.3?0:1,limit:!0};j.window=function(a){return $(window)._scrollable()};$.fn._scrollable=function(){return this.map(function(){var a=this,isWin=!a.nodeName||$.inArray(a.nodeName.toLowerCase(),['iframe','#document','html','body'])!=-1;if(!isWin)return a;var b=(a.contentWindow||a).document||a.ownerDocument||a;return/webkit/i.test(navigator.userAgent)||b.compatMode=='BackCompat'?b.body:b.documentElement})};$.fn.scrollTo=function(f,g,h){if(typeof g=='object'){h=g;g=0}if(typeof h=='function')h={onAfter:h};if(f=='max')f=9e9;h=$.extend({},j.defaults,h);g=g||h.duration;h.queue=h.queue&&h.axis.length>1;if(h.queue)g/=2;h.offset=both(h.offset);h.over=both(h.over);return this._scrollable().each(function(){if(f==null)return;var d=this,$elem=$(d),targ=f,toff,attr={},win=$elem.is('html,body');switch(typeof targ){case'number':case'string':if(/^([+-]=?)?\d+(\.\d+)?(px|%)?$/.test(targ)){targ=both(targ);break}targ=win?$(targ):$(targ,this);if(!targ.length)return;case'object':if(targ.is||targ.style)toff=(targ=$(targ)).offset()}var e=$.isFunction(h.offset)&&h.offset(d,targ)||h.offset;$.each(h.axis.split(''),function(i,a){var b=a=='x'?'Left':'Top',pos=b.toLowerCase(),key='scroll'+b,old=d[key],max=j.max(d,a);if(toff){attr[key]=toff[pos]+(win?0:old-$elem.offset()[pos]);if(h.margin){attr[key]-=parseInt(targ.css('margin'+b))||0;attr[key]-=parseInt(targ.css('border'+b+'Width'))||0}attr[key]+=e[pos]||0;if(h.over[pos])attr[key]+=targ[a=='x'?'width':'height']()*h.over[pos]}else{var c=targ[pos];attr[key]=c.slice&&c.slice(-1)=='%'?parseFloat(c)/100*max:c}if(h.limit&&/^\d+$/.test(attr[key]))attr[key]=attr[key]<=0?0:Math.min(attr[key],max);if(!i&&h.queue){if(old!=attr[key])animate(h.onAfterFirst);delete attr[key]}});animate(h.onAfter);function animate(a){$elem.animate(attr,g,h.easing,a&&function(){a.call(this,targ,h)})}}).end()};j.max=function(a,b){var c=b=='x'?'Width':'Height',scroll='scroll'+c;if(!$(a).is('html,body'))return a[scroll]-$(a)[c.toLowerCase()]();var d='client'+c,html=a.ownerDocument.documentElement,body=a.ownerDocument.body;return Math.max(html[scroll],body[scroll])-Math.min(html[d],body[d])};function both(a){return $.isFunction(a)||typeof a=='object'?a:{top:a,left:a}}return j})}(typeof define==='function'&&define.amd?define:function(a,b){if(typeof module!=='undefined'&&module.exports){module.exports=b(require('jquery'))}else{b(jQuery)}}));