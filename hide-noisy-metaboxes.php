<?php
namespace AJH\HideNoisyBoxes;
/*
Plugin Name: Hide Noisy Metaboxes
Plugin URI: http://aaronjholbrook.com
Description: Hides noisy metaboxes from Dashboard for User 1. Generally used by Developers during site development, cuts down on load time and annoyances from dashboard widgets. Improved to now also hide noisy columns and noisy post meta boxes!
Version: 2.0
Author: Aaron Holbrook
Author URI: http://aaronjholbrook.com
License: GPLv2
Copyright 2013  Aaron Holbrook (email : aaron@holbrook.io, twitter : @aaronjholbrook)
*/

function get_user_id() {
	$user_id = 1;
	return $user_id;
}
/**
 * Runs once for User 1 - hides all noisy dashboard metaboxes and and dismisses Welcome Panel
 */
add_action( 'admin_init', 'AJH\HideNoisyBoxes\hide_noisy_metaboxes' );
function hide_noisy_metaboxes() {
  // Only run this once - allow user to modify metaboxes if desired
  if ( get_user_option( 'metaboxhidden_dashboard_noisy_runonce', 1) != true ) {
	  $user_id = get_user_id();

    $hidden = array(
      'dashboard_primary',
      'dashboard_recent_comments',
      'dashboard_incoming_links',
      'dashboard_plugins',
      'dashboard_recent_drafts',
      'dashboard_secondary',
    );
    update_user_option( $user_id, 'metaboxhidden_dashboard', $hidden, true );
    update_user_option( $user_id, 'metaboxhidden_dashboard_noisy_runonce', true, true );
    update_user_option( $user_id, 'show_welcome_panel', 0, true );
  }
}

function hide_post_meta_boxes( $screen ) {
	if ( 'post' !== $screen->base ) {
		return;
	}

	$noisy_boxes = array(
		'wpseo_meta',
		'bm-corrections',
		'acf_acf_shoes',
		'bm-heading',
	);

	$meta_key = 'metaboxhidden_' . $screen->post_type;

	remove_noisy_items_for_user( $noisy_boxes, $meta_key );
}
add_action( 'current_screen', 'AJH\HideNoisyBoxes\hide_post_meta_boxes' );

function hide_post_columns( $screen ) {
	if ( 'edit' !== $screen->base ) {
		return;
	}

	$noisy_columns = array(
		'wpseo-score',
		'wpseo-title',
		'wpseo-metadesc',
		'wpseo-focuskw',
	);

	$meta_key = 'manageedit-' . $screen->post_type . 'columnshidden';

	remove_noisy_items_for_user( $noisy_columns, $meta_key );
}
add_action( 'current_screen', 'AJH\HideNoisyBoxes\hide_post_columns' );

function remove_noisy_items_for_user( $noisy, $meta_key ) {
	$user_id = get_user_id();
	$options = get_user_option( $meta_key, $user_id );

	if ( ! is_array( $options ) ) {
		$options = [];
	}

	foreach ( $noisy as $item ) {
		if ( ! in_array( $item, $options ) ) {
			$options[] = $item;
		}
	}

	update_user_option( $user_id, $meta_key, $options, true );
}
