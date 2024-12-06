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

// modify post insertion logic to assign random 7-digit ids for all post types
add_filter( 'wp_insert_post_data', function( $data, $postarr ) {
    global $wpdb;

    // only apply to new posts (id must be empty or zero)
    if ( empty( $postarr['ID'] ) ) {

        // generate a unique random 7-digit id
        do {
            $random_id = mt_rand( 1000000, 9999999 ); // random id between 1,000,000 and 9,999,999
            $exists    = $wpdb->get_var( 
                $wpdb->prepare( 
                    "SELECT ID FROM $wpdb->posts WHERE ID = %d", 
                    $random_id 
                ) 
            ); // check if id already exists in wp_posts table
        } while ( $exists ); // repeat until a unique id is found

        // set the random id to the post data before insertion
        $data['ID'] = $random_id;
    }

    return $data;
}, 10, 2 );

// Ref: ChatGPT
