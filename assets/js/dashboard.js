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
		
		jQuery('.awesome-dokan-wrapper .dokan-dashboard-menu li.settings.has-submenu i.menu-dropdown').on('click', function(e){
			e.preventDefault();
		});
		
		//Avatar dropdown
		jQuery('.awesome-user-avatar').on('click', function(){
			jQuery('.awesome-avatar-dropdown').toggle();
		});
    });

})(jQuery);
