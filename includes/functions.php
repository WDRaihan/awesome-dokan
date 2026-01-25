<?php
// Do not access this file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Force Dokan to use legacy dashboard design.
 */
add_action('admin_init', function() {
    $appearance = get_option( 'dokan_appearance', [] );

    if( $appearance['vendor_layout_style'] !== 'legacy' ){
        $appearance['vendor_layout_style'] = 'legacy';
        update_option( 'dokan_appearance', $appearance );
    }
});