<?php

/**
 * Plugin Name: WP Async Email
 * Plugin URI: https://github.com/wzul/wp-async-email
 * Description: Slow email using SMTP? No problem!
 * Author: Wan Zulkarnain
 * Author URI: http://www.wanzul.net
 * Version: 1.0
 * Requires PHP: 7.0
 * Requires at least: 4.6
 * License: GPLv3
 */

/**
 * Based on https://wordpress.stackexchange.com/questions/185295/how-to-make-wordpress-emails-async
 */

/**
 * Email Async.
 * 
 * We override the wp_mail function for all non-cron requests with a function that simply
 * captures the arguments and schedules a cron event to send the email.
 */
if ( ! defined( 'DOING_CRON' ) || ( defined( 'DOING_CRON' ) && ! DOING_CRON ) ) {

  if ( ! function_exists('wp_mail') ) :

    function wp_mail() {

        // Get the args passed to the wp_mail function
        $args = func_get_args();

        // Add a random value to work around that fact that identical events scheduled within 10 minutes of each other
        // will not work. See: http://codex.wordpress.org/Function_Reference/wp_schedule_single_event
        $args[] = mt_rand();

        // Schedule the email to be sent
        wp_schedule_single_event( time() + 2, 'cron_send_mail', $args );
    }

  endif;
}

/**
 * This function runs during cron requests to send emails previously scheduled by our
 * overrided wp_mail function. We remove the last argument because it is just a random
 * value added to make sure the cron job schedules correctly.
 * 
 * @hook    cron_send_mail  10
 */
function wp_email_async_cron_send_mail() {

    $args = func_get_args();

    // Remove the random number that was added to the arguments
    array_pop( $args );
  
    call_user_func_array( 'wp_mail', $args );
}

/**
 * Hook the mail sender. We accept more arguments than wp_mail currently takes just in case
 * they add more in the future.
 */
add_action( 'cron_send_mail', 'wp_email_async_cron_send_mail', 10, 10 );