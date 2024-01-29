<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->model('MdlLogin','login');
  }

  public function index()
  {
    if($this->session->userdata("login"))
    {
      redirect('Inicio');
    }
    else
    {
        $this->load->view('login');
    }
  }

  public function login_validate()
  {
    $email = $this->input->post('email');
    $pass = $this->input->post('password');
    $res = $this->login->login($email,$pass);
   
    if($res)
    {
        $data = array(
             'nombre' => $res->firstname,
             'apellido' => $res->lastname,
             'id' => $res->user_id,
             'key' => $res->key,
             'filtro' => $res->filtro,
             'login' => TRUE
        );
        $this->session->set_userdata($data);
        redirect('Inicio');
    }
    else
    {
        $data['error'] = 'No puedes iniciar sesion en estos momentos';
        $this->load->view('login', $data);  
    }
    
  }

  public function logout(){
    $this->session->sess_destroy();
    redirect('Login');
  }
}

/* End of file Login.php */
