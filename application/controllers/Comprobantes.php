<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comprobantes extends CI_Controller {

	public function __construct(){
        parent::__construct();
        $this->load->model('MdlComprobantes');
        $this->load->model('MdlPaquetesClientes');
        $this->load->model('MdlClientes');
        $this->load->model('MdlAcuse');
		
	}
  public function xml()
  {
      $emisor = $_GET['emisor'];
      $folio = $_GET['folio'];
      $serie = $_GET['serie'];
      header('Content-type: application/json');
      $data = $this->MdlComprobantes->get_xml($emisor,$folio,$serie);

      if(!$emisor)
      {
          echo json_encode(array('status' => FALSE, 'error' => 'No se especifico el EMISOR'));
      }
      else if(!$folio)
      {
          echo json_encode(array('status' => FALSE, 'error' => 'No se especifico el FOLIO'));
      } 
      else if(!$serie)
      {
          echo json_encode(array('status' => FALSE, 'error' => 'No se especifico la SERIE.'));
      }    
      else if(!$data)
      {
          echo json_encode(array('status' => FALSE, 'error' => 'No se encontro el comprobante'));
      }
      else if($data)
      {
         header('Content-type: text/xml');
         $path = $data->path;
 
         $xml = file_get_contents($path, '.xml');
 
         echo $xml;
      }
  }
  public function archivo()
  {
      $uuid = $_GET['uuid'];
      header('Content-type: application/json');
      $data = $this->MdlComprobantes->get_uuid($uuid);

     if(!$uuid)
     {
         echo json_encode(array('status' => FALSE, 'error' => 'No se especifico el UUID a recuperar'));
     }   
     else if(!$data)
     {
         echo json_encode(array('status' => FALSE, 'error' => 'No se encontro el comprobante'));
     }
     else if($data)
     {
        header('Content-type: text/xml');
        $path = $data->path;

        $xml = file_get_contents($path, '.xml');

        echo $xml;
     }
 
  }
  public function timbresrestante()
  {
     $rfc = $_GET['rfc'];
     $iden = $_GET['identificador'];

     $valor = $this->MdlClientes->get_clien_paquete($rfc,$iden);

     if(count($valor) > 0)
     {
        if($valor[0]['id_paquete'] == 1)
        {
         echo json_encode(array('status' => true, 'data' => 999999));
        }
        else
        {
         echo json_encode(array('status' => true, 'data' => $valor[0]['resta']));
        }
     }
     else
     {
       echo json_encode(array('status' => false, 'data' => 'No se encontraron registros'));
     }
  }
  public function SolicitudAceptarRechazar()
  {

    $this->load->model('MdlPaquetesClientes');

    $paquete = $this->MdlPaquetesClientes->get_paquete_activo($_POST['rfc']);

    if($paquete == '')
    {
        echo json_encode(array('status' => false, 'data' => 'No tiene timbres asignados'));
    }
    else
    {
          $token = 'T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo';

          $acer = array(
            'uuid' => $_POST['uuid'],
            'action' => $_POST['action']
          );

          $datos = array(
            'uuids' => [$acer],
            'password' => $_POST['password'],
            'rfc' => $_POST['rfc'],
            'b64Pfx' => $_POST['b64Pfx']
          );

          if($_POST['rfc'] == 'AAA010101AAA')
          {
            $curl = curl_init('http://services.test.sw.com.mx/acceptreject/pfx');
          }
          else
          {
            $curl = curl_init('http://services.sw.com.mx/acceptreject/pfx');
          }
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($datos));
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array(
              'Content-Type: application/json;',
              'Authorization: Bearer '.$token
        ));

          $response = curl_exec($curl);
          $response = json_decode($response);
        //  //  var_dump($response);
          if($response->status == 'success')
          {

                $xml = simplexml_load_string($response->data->acuse);
              //    // Almacenamos el Acuse
                $data['empresa'] = $_POST['rfc'];
                $data['fecha'] = date('Y-m-d H:i:s', strtotime($xml['Fecha']->__toString()));
                $data['xml_acuse'] = $response->data->acuse;
                $data['uuid'] = $xml->Folios->UUID->__toString();
                $data['estatus'] = $xml->Folios->EstatusUUID->__toString();
                $data['respuesta'] = $xml->Folios['Respuesta']->__toString();
                $this->MdlAcuse->add($data);

              $this->MdlPaquetesClientes->resta_cantidad_by_id($paquete->id);

                echo json_encode(array('status' => true, 'data' => ''));
              // $this->response(['data' => $xml->Folios['Respuesta']->__toString()], REST_Controller::HTTP_OK);
          }
          else
          {
              echo json_encode(array('status' => false, 'data' => $response->message));
          }
      }

  }
  public function getPendientes()
  {

       $token = 'T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo';

       $rfc = $_POST['rfc'];

       if($rfc == 'AAA010101AAA')
       {
          $curl = curl_init('http://services.test.sw.com.mx/pendings/'.$rfc);
       }
       else
       {
          $curl = curl_init('http://services.sw.com.mx/pendings/'.$rfc);
       }
       curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
       curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
       curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
       curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json;',
            'Authorization: Bearer '.$token
       ));

       $response = curl_exec($curl);
       //$result = json_decode($response);
       echo $response;

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
  public function descargarpdf($uuid)
  {
    $this->load->helper('download');
    $comp = $this->MdlComprobantes->get_uuid($uuid);
    if(!$comp){
         echo "No se encontro el registro del comprobante";
         return;
    }
    $fileName = $comp->path . '.pdf';
    if(!is_file($fileName)){
       echo 'No se encontro el documento solicitado';
       return;
    }
     $content = file_get_contents($fileName);

     header('Content-type: application/pdf; base64');
     //echo $content;
     force_download($uuid.'.pdf',$content);
  }
  public function compra()
  {
        $clave = $_GET['clave_cliente'];
        $cantidad = $_GET['cantidad'];
        $idpaquete = 0;
        $referencia_compra = date('Y-m-d');
        $id_pac = 'SW';
        $uuid = '';
        $fecha_vence = date('Y-m-d', strtotime('+1 year' , strtotime(date('Y-m-d'))));

        $cliente = $this->MdlClientes->get_by_clave($clave);

        $llave = $this->MdlClientes->llave($cliente->id_user);
        
        if(count($cliente) == 0)
        {
            echo json_encode(array('status' => false, 'data' => 'Cliente no existe'));
        }
        else if(count($llave) == 0)
        {
            echo json_encode(array('status' => false, 'data' => 'Cliente no esta asignado a ningun proveedor'));
        }
        else
        { 

             $url = base_url('api/CompraPre');
    
             $ch = curl_init($url);
             curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
             curl_setopt($ch, CURLOPT_POSTFIELDS, "clave_cliente=" . $clave ."&cantidad=".$cantidad."&X-API-KEY=" . $llave[0]->key ."&referencia=".$referencia_compra. "&id_pac=".$id_pac."&uuid_factura=".$uuid."&fecha_vence=".$fecha_vence);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
             $resu = curl_exec($ch);
             
             echo json_encode(array('status' => true, 'data' => 1));
         }

  }

}