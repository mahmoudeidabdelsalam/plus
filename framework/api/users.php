<?php
function all_users($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $password = !empty($password) ? $password : false;

  $args = array(
    'count_total'  => false,
    'fields'       => 'all',
  ); 
  
  $users = get_users( $args );

  if($email && $password){

    $emails = [];
    $passwords = [];

    foreach ($users as $user) {
      $passwords[] = $user->user_pass;
      $emails[] =  strtolower($user->user_email);
    }

    $user = get_user_by( 'email', $email );
    $userId = $user->ID;
    $user_meta = get_userdata($userId);
    $user_roles = $user_meta->roles;

    if(in_array("administrator", $user_roles)){
      $admin = true;
    } else {
      $admin = false;
    }


    $premium = get_field('user_premium', 'user_'. $userId);

    $email = strtolower($email);


    if (in_array($email, $emails) && wp_check_password( $password, $user->data->user_pass, $user->ID)) { 
      $login = true;
      $message = 'successfully Login';
    } else { 
      $login = false;
      $message = 'Try to login again (login or password) error';
    } 

    $array =  [
      'IsSuccess' => $login,
      'IsAdmin'   => $admin,
      'IsPremium' => $premium
    ];

    $result = [
      'success' => true,
      'code'    => 200,
      'message' => $message,
      'data'    => $array,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code'    => 404,
      'message' => 'login or password not fund',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'users/login',array(
    'methods' => 'POST',
    'callback' => 'all_users',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),      
    )
  ));
});



function register_plus($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $password = !empty($password) ? $password : false;

  if($email && $password){

    $email = strtolower($email);
    $user = get_user_by( 'email', $email );

    if($user) {
      $message = [ 
        "message" => "The email already exists",
        "signup" => false
      ];
    } else {
      $user_login = strtolower($email);
      $data = array(
        'user_email'    => $email,
        'user_pass'     => $password,
        'user_login'  => $user_login,
        'role'          => 'subscriber'
      );
      $user = wp_insert_user($data);

      if (!is_wp_error($user)){
        $message = [ 
          "message" => "Your account has been Registered successfully",
          "signup" => true
        ];
      } else {
        $message = [ 
          "message" => "Occured kindly fill up the sign up form carefully",
          "signup" => false
        ];
      }
    }

    $result = [
      'success' => true,
      'code' => 200,
      'message' => "successfully",
      'data' => $message,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'Email and password are required',
    ];
    return $result;
  }
}



add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'users/register',array(
    'methods' => 'POST',
    'callback' => 'register_plus',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),      
    )
  ));
});





function premium_plus($data){

  $data=$data->get_params('POST');
  extract($data);

  $email = !empty($email) ? $email : false;
  $premium = !empty($premium) ? $premium : 0;



  if($email && $premium == 1 || $premium == 0) {

    $user = get_user_by( 'email', $email );

    if($premium == 1) {
      $message = 'Your account has been premium successfully';
    } else {
      $message = 'Your account has been canceled premium';
    }

    update_user_meta( $user->ID, 'user_premium', $premium);

    $result = [
      'success' => true,
      'code' => 200,
      'message' => "successfully",
      'data' => $message,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'There is an error please check',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'users/premium',array(
    'methods' => 'POST',
    'callback' => 'premium_plus',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'premium' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),      
    )
  ));
});







function change_password($data){

  $data=$data->get_params('POST');
  extract($data);

  $email          = !empty($email) ? $email : false;
  $old_password   = !empty($old_password) ? $old_password : false;
  $new_password   = !empty($new_password) ? $new_password : false;

  $args = array(
    'count_total'  => false,
    'fields'       => 'all',
  ); 
  
  $users = get_users( $args );

  if($email && $old_password){

    $emails = [];
    $passwords = [];

    foreach ($users as $user) {
      $passwords[] = $user->user_pass;
      $emails[] =  strtolower($user->user_email);
    }

    $user = get_user_by( 'email', $email );
    $userId = $user->ID;

    $email = strtolower($email);

    if (in_array($email, $emails) && wp_check_password( $old_password, $user->data->user_pass, $user->ID)) { 

      $password = sanitize_text_field($new_password);

      $userdata = array(
          'ID'        =>  $userId,
          'user_pass' =>  $password
      ); 
      $user_id = wp_update_user($userdata);

      $change = true;
      $message = 'reset password successfully';
    } else { 
      $change = false;
      $message = 'old password or email wrong';
    } 

    $array =  [
      'passwordChnage' => $change,
    ];

    $result = [
      'success' => true,
      'code' => 200,
      'message' => $message,
      'data' => $array,
    ];
    return $result;
  } else {
    $result = [
      'success' => false,
      'code' => 404,
      'message' => 'login or password wrong',
    ];
    return $result;
  }
}

add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'users/change/password',array(
    'methods' => 'POST',
    'callback' => 'change_password',
    'args' => array(
      'email' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),
      'old_password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ), 
      'new_password' => array(
        'validate_callback' => function($param,$request,$key){
          return true;
        }
      ),       
    )
  ));
});

