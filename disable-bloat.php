<?php
/*
Plugin Name: Disable Bloat
Plugin URI: https://bhavyasaggi.github.io/plugins/disable-bloat
Description: Disable Bloat
Version: 0.1.0
Author: Bhavya Saggi
Author URI: https://bhavyasaggi.github.io/
License: MIT

------------------------------------------------------------------------

Copyright 2020 Bhavya Saggi

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

function disable_block_css()
{
    wp_dequeue_style('wp-block-library');
}

function disable_feed()
{
    wp_die(__('No feed available'));
}

function dummy_comments_template()
{
    return dirname(__FILE__) . '/dummy-comments-template.php';
}

function disable_bloat()
{
    if (is_admin())
    {
        return false;
    }

    /**
     * Disable jQuery
     */
    wp_deregister_script('jquery');
    wp_deregister_script('hoverintent-js');

    /**
     * Disable Block CSS
     */
    add_action( 'wp_print_styles', 'disable_block_css' );

    /**
     * Disable Generator Tags
     */
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');

    /**
     * Disable Shortlink
     */
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action( 'template_redirect', 'wp_shortlink_header', 11);

    /**
     * Disable the emoji's
     */
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');

    /**
     * Disable Oembed
     */
    remove_action('rest_api_init', 'wp_oembed_register_route');
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result');
    remove_filter('pre_oembed_result', 'wp_filter_pre_oembed_result');
    remove_action('wp_head', 'wp_oembed_add_host_js');
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    add_filter('embed_oembed_discover', '__return_false');
    wp_dequeue_script('wp-embed');

    /*
     * Disable REST API
    */
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');
    remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
    remove_action('wp_head', 'rest_output_link_wp_head');
    remove_action('template_redirect', 'rest_output_link_header');

    /**
     * Disable the Feed
     */
    add_action('do_feed', 'disable_feed', 1);
    add_action('do_feed_rdf', 'disable_feed', 1);
    add_action('do_feed_rss', 'disable_feed', 1);
    add_action('do_feed_rss2', 'disable_feed', 1);
    add_action('do_feed_atom', 'disable_feed', 1);
    add_action('do_feed_rss2_comments', 'disable_feed', 1);
    add_action('do_feed_atom_comments', 'disable_feed', 1);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);

    /*
     * Disable Comments
    */
    unregister_widget('WP_Widget_Recent_Comments');
    add_filter('show_recent_comments_widget_style', '__return_false');
    add_filter('comments_template', 'dummy_comments_template');
    wp_deregister_script('comment-reply');

}

add_action('init', 'disable_bloat');

