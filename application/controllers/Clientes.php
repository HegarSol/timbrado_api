<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Clientes extends CI_Controller {

	public function __construct(){
		parent::__construct();
    
        $this->load->model('MdlClientes','clientes');
        $this->load->model('MdlPaquetesClientes');
	}

  public function index()
  {
      if(!$this->session->userdata("login") )
      {
        redirect('Login');
      }

      $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";
      $url = 'https://services.sw.com.mx';
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
            $this->load->view('Clientes/index');
            $this->load->view('Templates/footer'); 	 
  }
  public function ajax_list($id)
  {
  	 echo json_encode($this->clientes->get_all($id));
  }
  public function paquetevencer()
  {
     echo json_encode($this->clientes->vencer());
  }
  public function terminartim()
  {
     echo json_encode($this->clientes->terminar());
  }
  public function facturar()
  {
    echo json_encode($this->clientes->factura());
  }
  public function actuali_pac()
  {
    $fac = $this->input->post('fac');
    $id_pac = $this->input->post('id_pa');
    $this->clientes->editafactura($id_pac,$fac);
  }
  public function nuevo()
  { 
      if(!$this->session->userdata("login") )
     {
       redirect('Login');
     }

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
            $this->load->view('Clientes/nuevo');
            $this->load->view('Templates/footer');
  }
  public function maximo()
  {
    echo json_encode($this->clientes->get_max());
  }
  public function ajax_paquete($cla)
  {
    echo json_encode($this->clientes->get_paquete($cla));
  }
  public function ajax_all_paquete($id)
  {
    echo json_encode($this->clientes->get_all_paquete($id));
  }
  public function cambiofechaacti()
  {
     $fecha = $this->input->post('fech');
     $id_pa = $this->input->post('idpa');
     $this->clientes->cambiafechaacti($fecha,$id_pa);
  }
  public function cambiofecha()
  {
     $fecha = $this->input->post('fech');
     $id_pa = $this->input->post('idpa');
     $this->clientes->cambiafecha($fecha,$id_pa);
  }
  public function editar($clave)
  {

      if(!$this->session->userdata("login") )
      {
        redirect('Login');
      }

      $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";

      $url = 'http://services.sw.com.mx';
      $curl = curl_init();
      curl_setopt_array($curl,array(
          CURLOPT_URL => $url . "/account/balance/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "UTF-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
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
      $res2 = json_decode($response);

     $res = $this->clientes->buscar($clave);
     $data = array('dato' => $res, "saldoT" => $res2->data->saldoTimbres); 
     $this->load->view('Templates/header', $data);
     $this->load->view('Clientes/editar',$data);
     $this->load->view('Templates/footer');
  }
  public function timbres($clave)
  {

    $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";
      $url = "https://services.sw.com.mx";
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_URL => $url . "/account/balance/",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "UTF-8",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => "GET",
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

      $kit = $this->clientes->buscar($clave);
      $data = array('dato' => $kit, 'saldoT' => $res->data->saldoTimbres);
      $this->load->view('Templates/header',$data);
      $this->load->view('Clientes/timbres',$data);
      $this->load->view('Templates/footer');
  }
  public function paquete($id)
  {
     $this->clientes->elimina($id);
  }
  public function resta()
  {
    $resta = $this->input->post('resta');
    $com = $this->input->post('compra');
    $id = $this->input->post('id');

    $this->clientes->updatetimbres($resta,$com,$id);
  }
  public function timbreseditar($id)
  {
    echo json_encode($this->clientes->edittimbre($id));
  }
  public function fecha($id)
  {
     echo json_encode($this->clientes->get_fecha($id));
  }
  public function actuali()
  {
    $nom = $this->input->post('nom');
    $rfc = $this->input->post('rfc');
    $clave = $this->input->post('clave');
    $activo = $this->input->post('activo');
    $pac = $this->input->post('pac');
    $noti = $this->input->post('notificar');
    $ema = $this->input->post('email');
    $this->clientes->update($nom,$rfc,$clave,$activo,$pac,$noti,$ema);

    redirect('Clientes');
   
  }
  public function clave()
  {
     $clav = $this->input->post('clave');
     $data = $this->clientes->getclave($clav);
     if($data)
     {
         echo 1;
     }
     else
     {
         echo 0;
     }
  }
  public function clientes()
  {
      $this->load->view('Templates/header2');
      $this->load->view('Clientes/clientes');
      $this->load->view('Templates/footer');
  }
  public function getcliente()
  {
       $var = $this->input->post('data');
       $resu = $this->clientes->get_by_clave($var);
       if($resu == NULL)
       {
           echo json_encode(0);
       }
       else
       {
           echo json_encode($resu);
       }
  }
  public function ultimacompra()
  {
     $clave = $this->input->post('data');
     
     $data['data'] = $this->MdlPaquetesClientes->ultimacompra($clave);
    // var_dump($data);
     $this->load->view('Clientes/tabla', $data);
  }

}

/* End of file Clientes.php */
