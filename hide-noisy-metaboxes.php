<?php 
/*
Plugin Name: Hide Noisy Metaboxes
Plugin URI: http://aaronjholbrook.com
Description: Hides noisy metaboxes from Dashboard for User 1. Generally used by Developers during site development, cuts down on load time and annoyances from dashboard widgets.
Version: 1.0
Author: Aaron Holbrook
Author URI: http://aaronjholbrook.com
License: GPLv2
Copyright 2013  Aaron Holbrook (email : aaron@a7web.com, twitter : @aaronjholbrook)
*/


/**
 * Runs once for User 1 - hides all noisy dashboard metaboxes and and dismisses Welcome Panel
 */
add_action( 'admin_init', 'hide_noisy_metaboxes' );
function hide_noisy_metaboxes() {
  // Only run this once - allow user to modify metaboxes if desired
  if ( get_user_option( 'metaboxhidden_dashboard_noisy_runonce', 1) != true ) {
    $hidden = array(
      'dashboard_primary',
      'dashboard_recent_comments', 
      'dashboard_incoming_links', 
      'dashboard_plugins', 
      'dashboard_recent_drafts', 
      'dashboard_secondary'
    );
    update_user_option( 1, 'metaboxhidden_dashboard', $hidden, true );
    update_user_option( 1, 'metaboxhidden_dashboard_noisy_runonce', true, true );
    update_user_option( 1, 'show_welcome_panel', 0, true );
  }
}

