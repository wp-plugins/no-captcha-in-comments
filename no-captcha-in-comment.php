<?php

/*
  Plugin Name: No Captcha in Comment
  Plugin URI:
  Description: A good and simple solution to combat spam without using captcha.
  Version: 1.2
  Author: vinoth06, ilavenil, suruthi48, subhashinik
  Author URI:
  License: GPLv3
 */
include('ncc-classify.php');
include('ncc-notification.php');
include('ncc-settings-menu.php');

if ((get_option('ncc_email_notify') == 1)) {
    $ncc_send_spam_comment_to_admin = true;
    if ((get_option('ncc_cus_email') == '')) {
        $ncc_emailto = get_option('admin_email');
    } else {
        $ncc_emailto = get_option('ncc_cus_email');
    }
} else {
    $ncc_send_spam_comment_to_admin = false;
}


$ncc_spam_settings = array(
    'ncc_send_spam_comment_to_admin' => $ncc_send_spam_comment_to_admin,
    'ncc_admin_email' => $ncc_emailto
);

function ncc_spam_enqueue_script() {
    if (is_singular() && comments_open()) {
        wp_enqueue_script('ncc_script', plugins_url('/ncc-spamfilter.js', __FILE__), array('jquery'), null, true);
    }
}

add_action('wp_enqueue_scripts', 'ncc_spam_enqueue_script');

function ncc_form_part() {
    global $ncc_spam_settings;
    $rn = "\r\n";
    if (!is_user_logged_in()) {
        echo '		<p class="ncc_group ncc_group-q" style="clear: both;">
			<label>Current ye@r <span class="required">*</span></label>
			<input type="hidden" name="ncc_spm-a" class="ncc_control ncc_control-a" value="' . date('Y') . '" />
                            <input type="hidden" id="ncc_count" class="ncc_control" value="10" name="ncc_counter"/>
			<input type="text" name="ncc_spm-q" class="ncc_control ncc_control-q" value="" />
		</p>' . $rn; // question (hidden with js)

        echo '		<p class="ncc_group ncc_group-e" style="display: none;">
			<label>Leave this field empty</label>
			<input type="text" name="ncc_email-url-website" class="ncc_control ncc_control-e" value="" />
		</p>' . $rn; // empty field (hidden with css); trap for spammers because many bots will try to put email or url here
    }
}

add_action('comment_form', 'ncc_form_part');

function ncc_spam_check_comment($commentdata) {
    global $ncc_spam_settings;
    $ncc_rn = "\r\n";
    extract($commentdata);

    $ncc_pre_error_message = '<p><strong><a href="javascript:window.history.back()">Go back</a></strong> and try again.</p>';
    $ncc_spam_error_message = '';
    if ($ncc_spam_settings['ncc_send_spam_comment_to_admin']) { // if sending email to admin is enabled
        $ncc_post = get_post($comment->comment_post_ID);
        $ncc_message_spam_info = 'Spam for post: "' . $post->post_title . '"' . $ncc_rn;
        $ncc_message_spam_info .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . $ncc_rn;
        $ncc_message_spam_info .= 'User agent: ' . $_SERVER['HTTP_USER_AGENT'] . $ncc_rn;
        $ncc_message_spam_info .= 'Referer: ' . $_SERVER['HTTP_REFERER'] . $ncc_rn . $ncc_rn;
    }

    $ncc_spam_flag = false;

    if (trim($_POST['ncc_spm-q']) != date('Y')) { // year-answer is wrong - it is spam
        $ncc_spam_flag = true;
    }

    if (!empty($_POST['ncc_email-url-website'])) { // trap field is not empty - it is spam
        $ncc_spam_flag = true;
    }

    if (!empty($_POST['ncc_counter'])) {
        $ncc_spam_flag = TRUE;
    }

    if ($ncc_spam_flag) { // it is spam
        $ncc_error_message .= '<strong>Comment was blocked because it is spam.</strong><br> ';
        if ($ncc_spam_settings['ncc_send_spam_comment_to_admin']) {
            $ncc_subject = 'Spam comment on site [' . get_bloginfo('name') . ']'; // email subject
            $ncc_message = '';
            $ncc_message .= $ncc_error_message . $ncc_rn . $ncc_rn;
            $ncc_message .= $ncc_message_spam_info; // spam comment, post, cookie and other data
            @wp_mail($ncc_spam_settings['ncc_admin_email'], $ncc_subject, $ncc_message); // send spam comment to admin email
        }
        ncc_spam_log_stats();
        wp_die($ncc_pre_error_message . $ncc_error_message); // die - do not send comment and show errors
    } else {
        
        $ncc_spam_prob = ncc_classifier($comment_content);

        if ($ncc_spam_prob < 0.2) {
            $ncc_flag = false;
        } else {
            $ncc_flag = true;
        }
        if ($ncc_flag) { // it is spam
            $ncc_error_message .= '<strong>Comment was blocked because it is spam.</strong><br> ';
            if ($ncc_spam_settings['ncc_send_spam_comment_to_admin']) {
                $ncc_subject = 'Spam comment on site [' . get_bloginfo('name') . ']'; // email subject
                $ncc_message = '';
                $ncc_message .= $ncc_error_message . $ncc_rn . $ncc_rn;
                $ncc_message .= $ncc_message_spam_info; // spam comment, post, cookie and other data
                @wp_mail($ncc_spam_settings['ncc_admin_email'], $ncc_subject, $ncc_message); // send spam comment to admin email
            }
            ncc_spam_log_stats();
            wp_die($ncc_pre_error_message . $ncc_error_message); // die - do not send comment and show errors
        }
    }


    return $commentdata; // if comment does not looks like spam
}

if (!is_admin()) {
    add_filter('preprocess_comment', 'ncc_spam_check_comment', 1);
}

function ncc_spam_log_stats() {
    $ncc_stats = get_option('ncc_spam_stats', array());
    if (array_key_exists('blocked_total', $ncc_stats)) {
        $ncc_stats['blocked_total'] ++;
    } else {
        $ncc_stats['blocked_total'] = 1;
    }
    update_option('ncc_spam_stats', $ncc_stats);
}
