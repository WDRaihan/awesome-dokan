/**
 * Awesome Dokan Dashboard Scripts
 */
(function($) {
    'use strict';

    $(document).ready(function() {
		//Toggle dashboard menu on mobile
		jQuery('.awesome-header-left .awesome-navigation-toggle .awesome-navigation-toggle-button').on('click', function(e){
			e.preventDefault();
			jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dash-sidebar').slideToggle();
		});
		//Toggle dashboard menu on desktop
		jQuery('.awesome-navigation-toggle-button.awesome-desktop-navigation').on('click', function(e){
			e.preventDefault();
			jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dash-sidebar').toggle();
			jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dashboard-content').toggleClass('padding-left-15');
		});
		
		jQuery('.awesome-dokan-wrapper .dokan-dashboard-menu li.settings.has-submenu i.menu-dropdown').on('click', function(e){
			e.preventDefault();
		});
		
		//Avatar dropdown
		jQuery('.awesome-user-avatar').on('click', function(){
			jQuery('.awesome-avatar-dropdown').toggle();
		});
		
		//Full screen
		jQuery(document).on('click', '.awesome-fullscreen-toggle-button', function() {
			let isChecked = jQuery(this).is(':checked');
			
			if( isChecked ){
				jQuery('.awesome-dokan-wrapper').addClass('awesome-dokan-fullscreen-mode');
			}else{
				jQuery('.awesome-dokan-wrapper').removeClass('awesome-dokan-fullscreen-mode');
			}
			
			let metaValue = isChecked ? 'on' : '';

			jQuery.ajax({
				url: awesome_dokan_obj.ajax_url,
				type: 'POST',
				data: {
					action: 'awesome_dokan_save_fullscreen_mode',
					meta_value: metaValue,
					nonce: awesome_dokan_obj.nonce
				},
				success: function(response) {
					console.log(response);
				}
			});

		});
    });

})(jQuery);
