(function() {
	tinymce.PluginManager.add('tie_insta_mce_button', function( editor, url ) {
		editor.addButton( 'tie_insta_mce_button', {
            icon: 		' tie-insta-shortcodes-icon ',
			tooltip: 	tieinsta_js.shortcodes_tooltip,
			type: 		'button',
			minWidth: 	210,
			
			onclick: function() {
				var tieinstaWin = editor.windowManager.open({
					title: 'Instagram',
					body: [
						{
							type: 'listbox',
							name: 'BoxStyle',
							label: tieinsta_js.skin,
							'values': [
								{text: tieinsta_js.default_skin, 	value: 'default'},
								{text: tieinsta_js.lite_skin, 		value: 'lite'},
								{text: tieinsta_js.dark_skin, 		value: 'dark'}
							]
						},
						{
							type: 'checkbox',
							name: 'InstagramLogo',
							label: tieinsta_js.logo_bar,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'newWindow',
							label: tieinsta_js.new_window,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'Nofollow',
							label: tieinsta_js.nofollow,
							value: 'true',
						},
						{
							type: 'checkbox',
							name: 'Credit',
							label: tieinsta_js.credit,
							value: 'true',
						},		
						{
							type: 'label',
							name: 'sep1',
							onPostRender : function() {this.getEl().innerHTML = "<br />"}
						},
						{
							type: 'listbox',
							name: 'mediaFrom',
							label: tieinsta_js.mediaFrom,
							'values': [
								{text: tieinsta_js.usname, value: 'username'},
								{text: tieinsta_js.hatag, value: 'hashtag'},
							],
							onselect: function( ) {
			                    if (this.value() == 'hashtag') {
									
									tinyMCE.DOM.setStyle( 'hashtag-l',		'opacity', '1');
									tinyMCE.DOM.setStyle( 'hashtag',		'opacity', '1');
									tinyMCE.DOM.setStyle( 'hashtagName-l',	'opacity', '1');
									tinyMCE.DOM.setStyle( 'hashtagName',	'opacity', '1');

									tinyMCE.DOM.setStyle( 'Username',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'Username-l',		'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'InfoArea',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'InfoArea-l',		'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'InfoLayout',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'InfoLayout-l',	'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'Website',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'Website-l',		'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'bio',			'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'bio-l',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'Stats',			'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'Stats-l',		'opacity', '0.4');	
									tinyMCE.DOM.setStyle( 'Position',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'Position-l',		'opacity', '0.4');	
									tinyMCE.DOM.setStyle( 'FullName',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'FullName-l',		'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'AvatarShape',	'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'AvatarShape-l',	'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'AvatarSize',		'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'AvatarSize-l',	'opacity', '0.4');
			                    } 
			                    else {
									
									tinyMCE.DOM.setStyle( 'hashtag-l',		'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'hashtag',		'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'hashtagName-l',	'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'hashtagName',	'opacity', '0.4');

									tinyMCE.DOM.setStyle( 'Username',		'opacity', '1');     
									tinyMCE.DOM.setStyle( 'Username-l',		'opacity', '1');
									tinyMCE.DOM.setStyle( 'InfoArea',		'opacity', '1');     
									tinyMCE.DOM.setStyle( 'InfoArea-l',		'opacity', '1');
									tinyMCE.DOM.setStyle( 'InfoLayout',		'opacity', '1');     
									tinyMCE.DOM.setStyle( 'InfoLayout-l',	'opacity', '1');
									tinyMCE.DOM.setStyle( 'Website',		'opacity', '1');     
									tinyMCE.DOM.setStyle( 'Website-l',		'opacity', '1');
									tinyMCE.DOM.setStyle( 'bio',			'opacity', '1');     
									tinyMCE.DOM.setStyle( 'bio-l',			'opacity', '1');
									tinyMCE.DOM.setStyle( 'Stats',			'opacity', '1');     
									tinyMCE.DOM.setStyle( 'Stats-l',		'opacity', '1');	
									tinyMCE.DOM.setStyle( 'Position',		'opacity', '1');     
									tinyMCE.DOM.setStyle( 'Position-l',		'opacity', '1');	
									tinyMCE.DOM.setStyle( 'FullName',		'opacity', '1');     
									tinyMCE.DOM.setStyle( 'FullName-l',		'opacity', '1');
									tinyMCE.DOM.setStyle( 'AvatarShape',	'opacity', '1');     
									tinyMCE.DOM.setStyle( 'AvatarShape-l',	'opacity', '1');
									tinyMCE.DOM.setStyle( 'AvatarShape',	'opacity', '1');     
									tinyMCE.DOM.setStyle( 'AvatarSize-l',	'opacity', '1');
			                    }   
			                },
						},
						{
							type: 		'textbox',
							name: 		'hashtag',
							id: 		'hashtag',
							minWidth: 	250,
							label: 		tieinsta_js.hashtag,
						},
						{
							type: 		'checkbox',
							name: 		'hashtagName',
							id: 		'hashtagName',
							label: 		tieinsta_js.hashtag_name_show,
							value: 		'true',
						},
						{
							type: 		'textbox',
							name: 		'Username',
							id: 		'Username',
							minWidth: 	250,
							label: 		tieinsta_js.username,
							value: 		'imo3aser'
						},
						{
							type: 		'checkbox',
							name: 		'InfoArea',
							id: 		'InfoArea',
							label: 		tieinsta_js.account_info,
							value: 		'true',
						},		
						{
							type: 		'listbox',
							name: 		'Position',
							id: 		'Position',
							label: 		tieinsta_js.position,
							'values': [
								{text: tieinsta_js.top, value: 'top'},
								{text: tieinsta_js.bottom, value: 'bottom'},
							]
						},			
						{
							type: 		'listbox',
							name: 		'InfoLayout',
							id: 		'InfoLayout',
							label: 		tieinsta_js.layout,
							'values': [
								{text: tieinsta_js.layout1, value: '1'},
								{text: tieinsta_js.layout2, value: '2'},
								{text: tieinsta_js.layout3, value: '3'},
							]
						},
						{
							type: 		'checkbox',
							name: 		'FullName',
							id: 		'FullName',
							label: 		tieinsta_js.full_name,
							value: 		'true',
						},
						{
							type: 		'checkbox',
							name: 		'Website',
							id: 		'Website',
							label: 		tieinsta_js.website_url,
							value: 		'true',
						},
						{
							type: 		'checkbox',
							name: 		'bio',
							id: 		'bio',
							label: 		tieinsta_js.bio,
							value: 		'true',
						},
						{
							type: 		'checkbox',
							name: 		'Stats',
							id: 		'Stats',
							label: 		tieinsta_js.account_stats,
							value: 		'true',
						},
						{
							type: 		'listbox',
							name: 		'AvatarShape',
							id: 		'AvatarShape',
							label: 		tieinsta_js.avatar_shape,
							'values': [
								{text: tieinsta_js.square, value: 'square'},
								{text: tieinsta_js.round, value: 'round'},
								{text: tieinsta_js.circle, value: 'circle'},
							]
						},
						{
							type: 		'textbox',
							name: 		'AvatarSize',
							id: 		'AvatarSize',
							label: 		tieinsta_js.avatar_size,
							value: 		'70'
						},
						{
							type: 		'label',
							name: 		'sep2',
							onPostRender : function() {this.getEl().innerHTML = "<br />"}
						},
						{
							type: 		'textbox',
							name: 		'NumberMedia',
							label: 		tieinsta_js.media_items,
							value: 		'20'
						},
						{
							type: 		'listbox',
							name: 		'link',
							label: 		tieinsta_js.link_to,
							'values': [
								{text: tieinsta_js.media_file, value: 'file'},
								{text: tieinsta_js.media_page, value: 'page'},
								{text: tieinsta_js.none, value: 'none'},
							]
						},
						{
							type: 		'listbox',
							name: 		'MediaLayout',
							id: 		'MediaLayout',
							label: tieinsta_js.layout,
							'values': [
								{text:  tieinsta_js.grid, value: 'grid'},
								{text:  tieinsta_js.slider, value: 'slider'},
							],
							onselect: function( ) {
			                    if (this.value() == 'slider') {
									
									tinyMCE.DOM.setStyle( 'GridColumns-l',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridColumns',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridFlat-l',				'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridFlat',				'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMore-l',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMore',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber-l',	'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber',		'opacity', '0.4');

									tinyMCE.DOM.setStyle( 'SliderSpeed',			'opacity', '1.0');     
									tinyMCE.DOM.setStyle( 'SliderSpeed-l',			'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderAnima',			'opacity', '1.0');     
									tinyMCE.DOM.setStyle( 'SliderAnima-l',			'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'SliderMedia',			'opacity', '1.0');     
									tinyMCE.DOM.setStyle( 'SliderMedia-l',			'opacity', '1.0');
			                    } 
			                    else {
									
									tinyMCE.DOM.setStyle( 'GridColumns-l',			'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridColumns',			'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridFlat-l',				'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridFlat',				'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMore-l',			'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMore',			'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber-l',	'opacity', '1.0');
									tinyMCE.DOM.setStyle( 'GridLoadMoreNumber',		'opacity', '1.0');

									tinyMCE.DOM.setStyle( 'SliderSpeed', 			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderSpeed-l',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderAnima',			'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'SliderAnima-l',			'opacity', '0.4');
									tinyMCE.DOM.setStyle( 'SliderMedia',			'opacity', '0.4');     
									tinyMCE.DOM.setStyle( 'SliderMedia-l',			'opacity', '0.4');
			                    }   
			                },
						},
						{
							type: 		'listbox',
							name: 		'Columns',
							id: 		'GridColumns',
							label: 		tieinsta_js.columns,
							'values': [
								{text: '1', value: '1'},
								{text: '2', value: '2'},
								{text: '3', value: '3'},
								{text: '4', value: '4'},
								{text: '5', value: '5'},
								{text: '6', value: '6'},
								{text: '7', value: '7'},
								{text: '8', value: '8'},
								{text: '9', value: '9'},
								{text: '10', value: '10'},
							]
						},
						{
							type: 		'checkbox',
							name: 		'Flat',
							id: 		'GridFlat',
							label: 		tieinsta_js.flat,
						},
						{
							type: 		'checkbox',
							name: 		'LoadMore',
							id: 		'GridLoadMore',
							label: 		tieinsta_js.load_more,
						},
						{
							type: 		'textbox',
							name: 		'LoadMoreNumber',
							id:   		'GridLoadMoreNumber',
							label: 		tieinsta_js.load_more_number,
							value: 		'6'
						},
						{
							type: 		'textbox',
							name: 		'speed',
							id: 		'SliderSpeed',
							label: 		tieinsta_js.slider_speed,
							value: 		'3000'
						},		
						{
							type: 		'listbox',
							name: 		'Effect',
							id: 		'SliderAnima',
							label: 		tieinsta_js.slider_effect,
							'values': [
								{text: 'blindX', 		value: 'blindX'},
								{text: 'blindY', 		value: 'blindY'},
								{text: 'blindZ', 		value: 'blindZ'},
								{text: 'cover', 		value: 'cover'},
								{text: 'curtainX', 		value: 'curtainX'},
								{text: 'curtainY', 		value: 'curtainY'},
								{text: 'fade', 			value: 'fade'},
								{text: 'fadeZoom', 		value: 'fadeZoom'},
								{text: 'growX', 		value: 'growX'},
								{text: 'growY', 		value: 'growY'},
								{text: 'scrollUp', 		value: 'scrollUp'},
								{text: 'scrollDown',	value: 'scrollDown'},
								{text: 'scrollLeft',	value: 'scrollLeft'},
								{text: 'scrollRight', 	value: 'scrollRight'},
								{text: 'scrollHorz', 	value: 'scrollHorz'},
								{text: 'scrollVert', 	value: 'scrollVert'},
								{text: 'slideX', 		value: 'slideX'},
								{text: 'slideY', 		value: 'slideY'},
								{text: 'toss', 			value: 'toss'},
								{text: 'turnUp', 		value: 'turnUp'},
								{text: 'turnDown', 		value: 'turnDown'},
								{text: 'turnLeft', 		value: 'turnLeft'},
								{text: 'turnRight', 	value: 'turnRight'},
								{text: 'uncover', 		value: 'uncover'},
								{text: 'wipe', 			value: 'wipe'},
								{text: 'zoom', 			value: 'zoom'},
							]
						},
						{
							type: 		'checkbox',
							name: 		'commentsLikes',
							id: 		'SliderMedia',
							label: 		tieinsta_js.comments_likes,
							value: 		'true',
						},
					],
					onsubmit: function( e ) {
						var output ;
						output = '[instanow';
						
						if( e.data.mediaFrom == 'hashtag' ){

							if( e.data.hashtag )				output += ' hashtag= ' + e.data.hashtag; 
							if( e.data.hashtagName )			output += ' show_hashtag= 1';

						}else{

							if( e.data.Username )		output += ' name= ' + e.data.Username; 
							if( e.data.Position )		output += ' info_pos= ' + e.data.Position; 
							if( e.data.InfoLayout )		output += ' info_layout= ' + e.data.InfoLayout; 
							if( e.data.AvatarShape )	output += ' shape= ' + e.data.AvatarShape; 
							if( e.data.AvatarSize )		output += ' size= ' + e.data.AvatarSize; 
							if( e.data.InfoArea )		output += ' info= 1'; 
							if( e.data.FullName )		output += ' full_name= 1'; 
							if( e.data.Website )		output += ' website= 1'; 
							if( e.data.bio )			output += ' bio= 1'; 
							if( e.data.Stats )			output += ' stats= 1'; 

						}

						if( e.data.newWindow )			output += ' window= 1'; 
						if( e.data.InstagramLogo )		output += ' logo= 1'; 
						if( e.data.Nofollow )			output += ' nofollow= 1'; 
						if( e.data.Credit )				output += ' credit= 1'; 
						if( e.data.BoxStyle )			output += ' style= ' + e.data.BoxStyle; 
						if( e.data.NumberMedia )		output += ' media= ' + e.data.NumberMedia; 
						if( e.data.link )				output += ' link= ' + e.data.link; 
						if( e.data.MediaLayout )		output += ' layout= ' + e.data.MediaLayout; 

						if( e.data.MediaLayout == 'slider' ){

							if( e.data.Effect )			output += ' effect= ' + e.data.Effect; 
							if( e.data.speed )			output += ' speed= ' + e.data.speed; 
							if( e.data.commentsLikes )	output += ' com_like= 1';

						}else{

							if( e.data.Columns )		output += ' columns= ' + e.data.Columns; 
							if( e.data.Flat )			output += ' flat= 1'; 
							if( e.data.LoadMore )		output += ' lm= 1'; 
							if( e.data.LoadMoreNumber )	output += ' lm_num= ' + e.data.LoadMoreNumber; 

						}

						output += ' ]'; 
						editor.insertContent( output );

					},
					onrepaint: function( e ) {
						tinyMCE.DOM.setStyle( 'hashtag-l',		'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'hashtag',		'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'hashtagName-l',	'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'hashtagName',	'opacity', '0.4');

						tinyMCE.DOM.setStyle( 'SliderSpeed', 	'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderSpeed-l',	'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderAnima',	'opacity', '0.4');     
						tinyMCE.DOM.setStyle( 'SliderAnima-l',	'opacity', '0.4');
						tinyMCE.DOM.setStyle( 'SliderMedia',	'opacity', '0.4');     
						tinyMCE.DOM.setStyle( 'SliderMedia-l',	'opacity', '0.4');
					},

				});
			}
		});
	});
})();