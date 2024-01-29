<?php

class Auth {

  private $ci;

  public function __construct(){
    $this->ci =& get_instance();
    $this->ci->load->model('MdlUsers');
  }
  public function login($username, $password)
  {
    $user = $this->ci->MdlUsers->get_by_api_user($username);
    if (!$user){
      return false;
    }
    return password_verify($password,$user->api_password);
  }
}