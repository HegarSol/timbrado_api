<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Inicio extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('MdlClientes','clientes');

		if(!$this->session->userdata("login"))
		    {
		      redirect('Login');
		    }
	}

  public function index()
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
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "cache-control: no-cache",
                "Content-length: 0",
                "authorization: bearer ".$token,
            ),
        ));
        if(isset($proxy)){
            curl_setopt($curl , CURLOPT_PROXY, $proxy);
        }
        $response = curl_exec($curl);

     

       $res = json_decode($response);

				
		$id = $this->session->userdata('id');
		$datos = $this->clientes->getall($id);
		$data = array('dato' => $datos, 'saldoT' => $res->data->saldoTimbres); 
  	$this->load->view('Templates/header',$data);
    $this->load->view('Inicio/index',$data);
    $this->load->view('Templates/footer');
  }

}

