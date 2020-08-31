<?php 
/**
 * API reset password
 * @param Type POST
 * @param string $email
 */
function get_reset_password($data){

  $data=$data->get_params('POST');
  extract($data);

  $user_login = !empty($user_login) ? $user_login : false;
  $error = new WP_Error();


		if (empty($user_login)) {
			$error->add(400, __("The field 'user_login' is required.", 'wp-rest-user'), array('status' => 400));
			return $error;
		} else {
			$user_id = username_exists($user_login);
			if ($user_id == false) {
				$user_id = email_exists($user_login);
				if ($user_id == false) {
					$error->add(401, __("User '" . $user_login . "' not found.", 'wp-rest-user'), array('status' => 401));
					return $error;
				}
			}
		}

		// run the action
		// ==============================================================
		//do_action('retrieve_password', $user_login);
		$user = null;
		$email = "";
		if (strpos($user_login, '@')) {
			$user = get_user_by('email', $user_login);
			$email = $user_login;
		} else {
			$user = get_user_by('login', $user_login);
			$email = $user->user_email;
		}
		$key = get_password_reset_key($user);
		$rp_link = '<a href="' . site_url() . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login) . '">' . site_url() . "/wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login) . '';

		function wpdocs_set_html_mail_content_type() {
			return 'text/html';
		}
		add_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
		$email_successful = wp_mail($email, 'Reset password', 'Click here in order to reset your password:<br><br>' . $rp_link);
		// Reset content-type to avoid conflicts -- https://core.trac.wordpress.org/ticket/23578
		remove_filter('wp_mail_content_type', 'wpdocs_set_html_mail_content_type');
    // ==============================================================
    
    if ($email_successful) {
			$response['code'] = 200;
			$response['message'] = __("Reset Password link has been sent to your email.", "wp-rest-user");
		} else {
			$error->add(402, __("Failed to send Reset Password email. Check your WordPress Hosting Email Settings.", 'wp-rest-user'), array('status' => 402));
			return $error;
		}

		return new WP_REST_Response($response, 200);
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'reset/password',array(
    'methods' => 'POST',
    'callback' => 'get_reset_password',
    'args' => array(
      'user_login' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
    )
  ));
});
