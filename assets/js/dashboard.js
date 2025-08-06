/**
 * Awesome Dokan Dashboard Scripts
 */
(function($) {
    'use strict';

    $(document).ready(function() {
		//Toggle dashboard menu on mobile
		jQuery('.header-left .awesome-navigation-toggle .awesome-navigation-toggle-button').on('click', function(e){
			e.preventDefault();
			jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dash-sidebar').slideToggle();
		});
		//Toggle dashboard menu on desktop
		jQuery('.header-right .awesome-navigation-toggle-button').on('click', function(e){
			e.preventDefault();
			jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dash-sidebar').toggle();
			jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dashboard-content').toggleClass('padding-left-15');
		});
		
		//Toggle settings menu on mobile
		jQuery('.awesome-dokan-wrapper .dokan-dashboard-menu li.settings.has-submenu i.menu-dropdown').on('click', function(e){
			e.preventDefault();
			
			//jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dash-sidebar ul.dokan-dashboard-menu li ul.navigation-submenu').show();
			
			//jQuery(this).addClass('awesome-ratate');
		});
		
		jQuery(document).on('click', '.awesome-dokan-wrapper .dokan-dashboard-menu li.settings.has-submenu i.menu-dropdown.awesome-ratate', function(e){
			e.preventDefault();
			
			//jQuery('.dokan-dashboard .awesome-dokan-wrapper .dokan-dash-sidebar ul.dokan-dashboard-menu li ul.navigation-submenu').hide();
			//jQuery(this).removeClass('awesome-ratate');
		});
		
		jQuery('.awesome-user-avatar').on('click', function(){
			jQuery('.avatar-dropdown').toggle();
		});
    });

})(jQuery);
