<?php
/**
 * Plugin Name: Gigya hooks example
 * Plugin URI: http://gigya.com
 * Description: A Gigya hooks example.
 * Version: 0.1
 * Author: Gigya
 * Author URI: http://gigya.com
 * License: GPL2+
 */

add_action( 'gigya_after_raas_login', 'gigyaAfterRaasLogin', 10, 2 );
add_filter( 'get_avatar', 'getAvatar', 1, 4 );


/**
 * Implements gigya_after_raas_login hook.
 *
 * @param $gig_user
 * @param $wp_user
 */
function gigyaAfterRaasLogin( $gig_user, $wp_user ) {

	// Get image path from gigya user object.
	$img_path = $gig_user['profile']['thumbnailURL'];

	// Update the WP user with Gigya image.
	update_user_meta( $wp_user->ID, 'gig_avatar', $img_path );


	// Get nickname from gigya user object.
	$nick = $gig_user['profile']['nickname'];

	// Update the WP nickname with Gigya nickname.
	update_user_meta( $wp_user->ID, 'nickname', $nick );

}

/**
 * Implements get_avatar hook. Replace the the user avatar on the fly.
 *
 * @param $avatar
 * @param $id_or_email
 * @param $size
 * @param $default
 * @param $alt
 *
 * @return string
 */
function getAvatar( $avatar, $id_or_email, $size, $default, $alt ) {

	// Insure we using user ID.
	$uid = $id_or_email;
	if ( ! is_numeric( $uid ) ) {
		$user = get_user_by( 'email', $uid );
		$uid  = $user->ID;
	}

	// Get the Gigya avatar from the DB.
	$img_path = get_user_meta( $uid, 'gig_avatar', true );

	// Make The switch if exist.
	if ( ! empty( $img_path ) ) {
		$avatar = "<img alt='{$alt}' src='{$img_path}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
	}

	// Return Gigya (or orig) avatar.
	return $avatar;

}
