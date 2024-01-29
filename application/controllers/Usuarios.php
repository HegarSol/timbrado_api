<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usuarios extends CI_Controller {

	public function __construct(){
		parent::__construct();
		// if(!$this->session->userdata("login") )
		//     {
		//       redirect('Inicio');
		//     }
		    $this->load->model('MdlUsers','usuarios');
	}

  public function index()
  { 
    if(!$this->session->userdata("login") )
    {
      redirect('Login');
      }
    if($this->session->userdata('filtro') == '')
    {

      $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";
       $url = 'https://services.sw.com.mx';
       $curl = curl_init();
       curl_setopt_array($curl, array(
         CURLOPT_URL => $url . "/account/balance/",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "UTF-8",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "Content-length: 0",
              "authorization: bearer ".$token,
         ),
       ));

       if(isset($proxy)){
          curl_setopt($curl, CURLOPT_PROXY, $proxy);
       }
       $response = curl_exec($curl);
       $res = json_decode($response);
       $data = array('saldoT' => $res->data->saldoTimbres);

        $this->load->view('Templates/header',$data);
        $this->load->view('Copiar/index');
        $this->load->view('Templates/footer');
    }
    else
    {
      redirect('Inicio');
    }
  }
  public function ajax_list()
  {
  	 echo json_encode($this->usuarios->get_all());
  }
  public function nuevo()
  {
    if(!$this->session->userdata("login") )
    {
      redirect('Login');
      }
  	if($this->session->userdata('filtro') == '')
    {

      $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";

      $url = 'http://services.sw.com.mx';
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_URL => $url . "/account/balance/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => 'UTF-8',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_HTTPHEADER => array(
             "cache-control: no-cache",
             "Content-length: 0",
             "authorization: bearer ".$token,
          ),
      ));

      if(isset($proxy)){
          curl_setopt($curl, CURLOPT_PROXY, $proxy);
      }
      $response = curl_exec($curl);
      $res = json_decode($response);
      $data = array('saldoT' => $res->data->saldoTimbres);

        $this->load->view('Templates/header',$data);
        $this->load->view('Usuarios/nuevo');
        $this->load->view('Templates/footer');
    }
    else
    {
      redirect('Inicio');
    }
  }
  public function guardar()
  {   
      $nombre = $this->input->post('nom');
      $apellido = $this->input->post('ape');
      $email = $this->input->post('emai');
      $password = $this->input->post('pass');
     
      $llave = $this->key();
      
      $this->usuarios->insert($nombre,$apellido,$email,$password,$llave);
      
  }
  public function edit()
  {
     $id = $this->input->post('id');
     $nombre = $this->input->post('nom');
     $email = $this->input->post('emai');
     $apellido = $this->input->post('ape');
     
     $this->usuarios->actualiza($id,$nombre,$apellido,$email);
  }
  public function key()
  {
    $caracteres = '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
      for($x = 0; $x < 25; $x++)
      {
        $aleatoria = substr(str_shuffle($caracteres), 0, 25);      
      }
     return $aleatoria;
  }
  public function llave()
  {
     $this->usuarios->insertarllave($id_ser,$key);
  }
  public function editar($id)
  {
    if(!$this->session->userdata("login") )
    {
      redirect('Login');
      }

        if($this->session->userdata('filtro') == '')
        {

          $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";
           $url = 'https://services.sw.com.mx';
           $curl = curl_init();
           curl_setopt_array($curl, array(
                CURLOPT_URL => $url . "/account/balance",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "UTF-8",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "cache-control: no-cache",
                    "Content-length: 0",
                    "authorization: bearer ".$token, 
                ),
           ));
           if(isset($proxy)){
             curl_setopt($curl, CURLOPT_PROXY, $proxy);
           }
           $response = curl_exec($curl);
           $res = json_decode($response);

        $valor = $this->usuarios->buscar($id);
        $dato = array('data' => $valor, 'saldoT' => $res->data->saldoTimbres);
        $this->load->view('Templates/header',$dato);
        $this->load->view('Usuarios/editar',$dato);
        $this->load->view('Templates/footer');
    }
    else
    {
      redirect('Inicio');
    }
  }
  public function eliminar($id)
  {
    if(!$this->session->userdata("login") )
    {
      redirect('Login');
      }
    if($this->session->userdata('filtro') == '')
    {
      $this->usuarios->elimina($id);
      redirect('Usuarios');
    }
    else
    {
      redirect('Inicio');
    }  
  }
  public function getcliente()
  {
     $res = $_POST['rfc'];
     echo json_encode($this->usuarios->buscarcliente($res));
  }

}

