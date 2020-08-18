<?php
function get_general_settings(){

  $general_settings = [
    'logo' => get_field('logo_plus_app', 'option'),
    'version' => get_field('version_plus_app', 'option'),
    'links' => get_field('links_plus_app', 'option'),
    'scripts' => get_field('scripts_head', 'option'),
    'advertisement' => get_field('advertisement', 'option'),
  ];

  $result = [
    "success" => true,
    "code" => 200,
    "message" => 'Successfully retrieved',
    "data" => $general_settings,
  ];  
  
  return $result;
}


add_action('rest_api_init' , function(){
  register_rest_route('wp/api/' ,'GeneralSettings',array(
    'methods' => 'GET',
    'callback' => 'get_general_settings',
  ));
});