<?php
/*
Plugin Name: Miln Photo Feed
Plugin URI: http://miln.eu/open/photofeed/
Description: Provides an RSS photo feed from WordPress
Version: 1.0
Author: Graham Miln
Author URI: http://miln.eu/
License: GPL2

*/

/*  Copyright 2013  Graham Miln  (email : graham.miln@miln.eu)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* Make sure we don't expose any info if called directly */
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

function photofeed_add_endpoint() {
		//error_log("photofeed_add_endpoint",0);
        add_rewrite_endpoint( 'photos', EP_ALL );
}
add_action('init','photofeed_add_endpoint');

function photofeed_template_redirect() {

		global $wp_query;

		// helpbook in query
        if ( ! isset( $wp_query->query_vars['photos'] ) )
                return;

		// Load the photos template, allowing themes to override
		if ( $overridden_template = locate_template( 'miln-photo-feed-template.php' ) ) {
			// locate_template() returns path to file
			// if either the child theme or the parent theme have overridden the template
			load_template( $overridden_template );
		} else {
			// If neither the child nor parent theme have overridden the template,
			// we load the template from the 'templates' sub-directory of the directory this file is in
			load_template( dirname( __FILE__ ) . '/miln-photo-feed-template.php' );
		}
		
		exit;
}
add_action( 'template_redirect', 'photofeed_template_redirect' );

function photofeed_activate() {
        // ensure our endpoint is added before flushing rewrite rules
        photofeed_add_endpoint();
        // flush rewrite rules - only do this on activation as anything more frequent is bad!
        flush_rewrite_rules();
}
register_activation_hook(__FILE__,'photofeed_activate');

function photofeed_deactivate() {
        // flush rules on deactivate as well so they're not left hanging around uselessly
        flush_rewrite_rules();
}
register_deactivation_hook(__FILE__,'photofeed_deactivate');
