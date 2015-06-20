<?php
/**
 * WooCommerce Custom Subscriptions Uninstall
 *
 * @package     WooCommerceCustomSubscriptions
 * @category    Core
 * @version     1.0
 * @since       1.0
 * @author      Nick Maraston
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit;
}

global $wpdb;

// Delete pages
wp_trash_post( get_option( 'wccs_mysubscription_page_id' ) );

// Delete tables
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "wccs_uisms" );
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "wccs_uism_products" );

// Delete options
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE 'wccs_%';" );

// Delete custom subscription posts
$wpdb->query(
    "
    DELETE posts
    FROM $wpdb->posts AS posts
    WHERE posts.ID IN
    (
        SELECT DISTINCT(tr.object_id)
        FROM $wpdb->term_relationships tr, $wpdb->term_taxonomy tt, $wpdb->terms t
        WHERE tt.term_id = t.term_id AND tt.term_taxonomy_id = tr.term_taxonomy_id AND t.name = 'custom subscription'
    )
    ;"
);

// Delete custom subscription term relationships
$wpdb->query(
    "
    DELETE term_relationships
    FROM $wpdb->term_relationships AS term_relationships
    WHERE term_relationships.term_taxonomy_id =
    (
        SELECT DISTINCT(tt.term_taxonomy_id)
        FROM $wpdb->term_taxonomy tt, $wpdb->terms t
        WHERE tt.term_id = t.term_id AND t.name = 'custom subscription'
    )
    ;"
);

// Delete custom subscription term taxonomy
$wpdb->query(
    "
    DELETE term_taxonomy
    FROM $wpdb->term_taxonomy AS term_taxonomy
    WHERE term_taxonomy.term_id =
    (
        SELECT DISTINCT(t.term_id)
        FROM wp_terms t
        WHERE t.name = 'custom subscription'
    )
    ;"
);

// Delete custom subscription terms
$wpdb->query(
    "
    DELETE
    FROM wp_terms
    WHERE name = 'custom subscription'
    ;"
);
