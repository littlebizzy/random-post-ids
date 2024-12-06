<?php
/*
Plugin Name: Random Post IDs
Plugin URI: https://www.littlebizzy.com/plugins/random-post-ids
Description: Random 7-digit IDs for usability
Version: 1.0.0
Author: LittleBizzy
Author URI: https://www.littlebizzy.com
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html
GitHub Plugin URI: littlebizzy/random-post-ids
Primary Branch: master
*/

// prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// disable wordpress.org updates for this plugin
add_filter( 'gu_override_dot_org', function( $overrides ) {
    $overrides[] = 'random-post-ids/random-post-ids.php';
    return $overrides;
}, 999 );

add_filter('wp_insert_post_data', function ($data, $postarr) {
    global $wpdb;

    // Only apply to new posts of type 'post'
    // Check that this is a truly new post (ID empty or zero in the incoming array)
    if ($data['post_type'] === 'post' && empty($postarr['ID'])) {
        // Generate a unique random 7-digit ID
        do {
            $random_id = mt_rand(1000000, 9999999);
            $exists = $wpdb->get_var($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE ID = %d", $random_id));
        } while ($exists);

        // Set the custom ID right before insertion
        $data['ID'] = $random_id;
    }

    return $data;
}, 10, 2);

// Ref: ChatGPT
