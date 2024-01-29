<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Busqueda extends CI_Controller{

    public function __construct(){
        parent::__construct();

        $this->load->model('MdlComprobantes');
        $this->load->model('MdlPaquetesClientes');
        $this->load->model('MdlClientes');
        $this->load->model('MdlAcuse');

    }
     
     public function index()
     {
        if(!$this->session->userdata("login"))
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
             $this->load->view('Busqueda/index');
             $this->load->view('Templates/footer');
       
     }
     public function descargarxml($uuid)
     {
        $this->load->helper('download');
        $comp = $this->MdlComprobantes->get_uuid($uuid);
        if(!$comp){
             echo "No se encontro el registro del comprobante";
             return;
        }
        $fileName = $comp->path;
        if(!is_file($fileName)){
           echo 'No se encontro el documento solicitado';
           return;
        }
        $content = file_get_contents($fileName);
    
        header('Content-Type: text/xml; charset=UTF-8');
    
        force_download($uuid.'.xml',$content);
     }
     public function descargarxml2($rfc,$seri,$foli)
     {
        $this->load->helper('download');
        $comp = $this->MdlComprobantes->get_xml($rfc,$foli,$seri);
        if(!$comp){
             echo "No se encontro el registro del comprobante";
             return;
        }
        $fileName = $comp->path;
        if(!is_file($fileName)){
           echo 'No se encontro el documento solicitado';
           return;
        }
        $content = file_get_contents($fileName);
    
        header('Content-Type: text/xml; charset=UTF-8');
    
        force_download($comp->uuid.'.xml',$content);
     }
     public function getxml()
     {
          $uuid = $this->input->post('uuid');
          $rfc = $this->input->post('rfc');
          $seri = $this->input->post('seri');
          $foli = $this->input->post('foli');

          if($uuid == '')
          {
            $comp = $this->MdlComprobantes->get_xml($rfc,$foli,$seri);
          }
          else
          {
            $comp = $this->MdlComprobantes->get_uuid($uuid);
          }

          if(!$comp){

            $var = array('valor'=>0,'mensaje'=>'No se encontro el registro del comprobante');
               echo json_encode($var);
             //  return;
          }
          else
          {

          
          $fileName = $comp->path;

          $LcResult = file_get_contents($fileName);
        //  header('Content-Type: text/xml; charset=UTF-8');
        //  var_dump($content);

          $this->xmlDom = new DOMDocument();
          $this->xmlDom->loadXML($LcResult);


          $FF = date('Y-m-d H:i:s', strtotime($this->getAttribute('cfdi:Comprobante/cfdi:Complemento/tfd:TimbreFiscalDigital/@FechaTimbrado')));
          $emisor = $this->getAttribute('cfdi:Comprobante/cfdi:Emisor/@Nombre');
          $rfcemisor = $this->getAttribute('cfdi:Comprobante/cfdi:Emisor/@Rfc');

          $receptor = $this->getAttribute('cfdi:Comprobante/cfdi:Receptor/@Nombre');
          $rfcreceptor = $this->getAttribute('cfdi:Comprobante/cfdi:Receptor/@Rfc');

          $total = $this->getAttribute('cfdi:Comprobante/@Total');

          $uuid = $this->getAttribute('cfdi:Comprobante/cfdi:Complemento/tfd:TimbreFiscalDigital/@UUID');

          $tipocom = $this->getAttribute('cfdi:Comprobante/@TipoDeComprobante');

          $meotodpa = $this->getAttribute('cfdi:Comprobante/@MetodoPago');

          $version = $this->getAttribute('cfdi:Comprobante/@Version');

          $valor = array('valor' => 1,
                       'emisor'=>$emisor,
                       'rfcemisor' => $rfcemisor,
                       'receptor'=>$receptor,
                       'rfcreceptor'=>$rfcreceptor,
                       'total'=>$total,
                       'tipocom'=>$tipocom,
                       'metodopa'=>$meotodpa,
                       'version'=>$version,
                       'uuid' => $uuid);

         echo json_encode($valor);
          }
     }

     public function getAttribute($query)
        {
            $xpath = $this->getXpathObj();
            $nodeset = $xpath->query($query, $this->xmlDom);
            if($regresa = $nodeset[0])
            {
            return $regresa->value;
            }
        return "";
        }

        public function getXpathObj()
        {
        if(empty($this->xpath) && !empty($this->xmlDom))
        {
            $this->xpath = new DOMXPath($this->xmlDom);
            $this->xpath->registerNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
            $this->xpath->registerNamespace('tfd', 'http://www.sat.gob.mx/TimbreFiscalDigital');
        }
        return $this->xpath;
        }

}