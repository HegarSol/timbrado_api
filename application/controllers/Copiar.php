<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Copiar extends CI_Controller {

	public function __construct(){
        parent::__construct();

        
    }

        public function index()
        {
            if(!$this->session->userdata("login"))
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
        }
        public function ponerfecha()
        {
            $date = $this->input->post('fecha');
            
            date_default_timezone_set("America/Mexico_City");
             
            $fecha = date("Y-m-d H:i:s",strtotime($date.' 12:00:00'));

            $source = '/mnt/s3/apitimbrado/';

            $destination = '/archivos_xml/hegarss_respaldos/xmls/';

           $this->completo_copia($source, $destination,$fecha);

        }
        public function completo_copia( $source, $target ,$fecha) 
        {
            date_default_timezone_set("America/Mexico_City");
            if ( is_dir( $source ) ) 
            {
                @mkdir( $target ,0777,TRUE);
                $d = dir( $source );
                while ( FALSE !== ( $entry = $d->read() ) ) 
                {
                    if ( $entry == '.' || $entry == '..' ) 
                    {
                        continue;
                    }
                    $Entry = $source . '/' . $entry; 
                            if ( is_dir( $Entry ) ) 
                            {                      
                                $this->completo_copia( $Entry, $target . '/'.$entry ,$fecha); 
                                continue;
                            }                  
                            $fecha_archivo = date("Y-m-d H:i:s",filemtime($Entry));
                          
                            if($fecha_archivo <= $fecha)
                            {
                                //var_dump($fecha_archivo);
                                copy( $Entry, $target . '/' .$entry );
                            }
                }      
                $d->close(); 
            }
            else 
            {   
                copy( $source, $target );
            }
        }
}