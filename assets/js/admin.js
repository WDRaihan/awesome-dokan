(function ($) {
	'use strict';

	jQuery(document).ready(function () {
		//Settings tab
		// Handle tab navigation click
		$('.awesome-dokan-tabs-nav a').on('click', function (e) {
			// Prevent the default link behavior
			e.preventDefault();

			// Get the target tab panel's ID from the href attribute
			var target = $(this).attr('href');

			// Remove the 'active' class from all tab links
			$('.awesome-dokan-tabs-nav a').removeClass('active');
			// Add the 'active' class to the clicked tab link
			$(this).addClass('active');

			// Hide all tab panels
			$('.awesome-dokan-tab-panel').hide();
			// Show the target tab panel with a fade-in effect
			$(target).fadeIn(400);
		});
	});

	jQuery(document).ready(function () {
		$('.awesome-dokan-color-field').wpColorPicker();
	});

})(jQuery);