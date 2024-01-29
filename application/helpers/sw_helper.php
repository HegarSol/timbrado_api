<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include('SWSDK.php');
use SWServices\Stamp\StampService as StampService;
use SWServices\Cancelation\CancelationService as CancelationService;
 
 
  error_reporting(E_ERROR);

if (!function_exists('sw_validar_rfc'))
{
  /**
   * SW VALIDAR RFC
   *
   * Valida el rfc proporcionado ante el web service de SW para determinar
   * si el RFC se encuentra registrado ante la autoridad o no.
   *
   * @param $rfc El RFC que se desea consultar
   */
  function sw_validar_rfc($rfc)
  {
    $CI =& get_instance();
    $CI->load->model('MdlProveedores');
    $sw = $CI->MdlProveedores->get_by_id('SW');
    $CI->load->library('encryption');
    $soap_options = array(
      'stream_context' => stream_context_create([
        'ssl' => [
          'verify_peer' => FALSE,
          'allow_self_signed' => TRUE,

        ]
      ])
    );

    $soap = new SoapClient($sw->url_comprobantes, $soap_options);
    $parameters = array(
      'usuario' => $CI->encryption->decrypt($sw->user),
      'password' => $CI->encryption->decrypt($sw->password),
      'rfc' => $rfc
    );
    $result = $soap->ValidarRFC($parameters);
    return [
      'Cancelado' => $result->ValidarRFCResult->Cancelado,
      'RFC' => $result->ValidarRFCResult->RFC,
      'MensajeError' => $result->ValidarRFCResult->MensajeError,
      'RFCLocalizado' => $result->ValidarRFCResult->RFCLocalizado,
      'Subcontratacion' => $result->ValidarRFCResult->Subcontratacion,
      'UnidadSNCF' => $result->ValidarRFCResult->UnidadSNCF
    ];
  }
}
if(!function_exists('sw_realiza_timbrado'))
{
    function sw_realiza_timbrado($xml)
    {
       $CI =& get_instance();
       $CI->load->helper('timbrado_helper');
       $tipo = get_tipo_xml($xml);
       switch($tipo)
       {
          case 'comprobante':
          $response = sw_timbra_comprobante($xml);
          break;
          case 'retenciones':
          $response = sw_timbra_retenciones($xml);
          break;
          default;
          throw new Exception('El xml no es ´Comprobante´ o ´Retenciones´ ');
       }
      // var_dump($response);
       $response['tipo_comprobante'] = $tipo;
       return $response;
    }
}
if(!function_exists('sw_realiza_timbradoP'))
{
   function sw_realiza_timbradoP($xml, $pruebas)
   {
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      $tipo = get_tipo_xml($xml);
      switch($tipo)
      {
         case 'comprobante':
         $response = sw_timbra_comprobanteP($xml,$pruebas);
         break;
         case 'retenciones':
         $response = sw_timbra_retencionesP($xml,$pruebas);
         break;
         default;
         throw new Exception('El xml no es ´Comprobante´ o ´Retenciones´ ');
      }
     // var_dump($response);
      $response['tipo_comprobante'] = $tipo;
      return $response;
   }
}

if( ! function_exists('sw_timbra_comprobante'))
{
    function sw_timbra_comprobante($xml)
    {
       $CI =& get_instance();
       $CI->load->helper('timbrado_helper');
       if(get_tipo_xml($xml) !== 'comprobante')
       {
          return NULL;
       }
       $credentials = get_configuracion_proveedor('SW','comprobante');
      
      $regresa = array();
      try{
                $params = array(
                "url"=>$credentials['url'],
                "user"=>$credentials['user'],
                "password"=>$credentials['password']
                );

        $stamp = StampService::Set($params);
        $result = $stamp::StampV4($xml);
      
        //var_dump($result);
        $regresa['OperacionExitosa'] = $result->status == 'success' ? true : false;
        $regresa['MensajeError'] = $result->message;
        $regresa['MensajeErrorDetallado'] = $result->messageDetail;
        $regresa['CodigoRespuesta'] = $result->message;
        $regresa['XmlResultado'] = base64_encode($result->data->cfdi);
        $regresa['Timbre'] = $result->data;
      } 
      catch (Exception $ex)
      {
        $regresa['exception'] = $ex->getMessage();
      }
      return $regresa;
   }
}
if(!function_exists('sw_timbra_comprobanteP'))
{
   function sw_timbra_comprobanteP($xml,$pruebas)
   {
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      if(get_tipo_xml($xml) !== 'comprobante')
      {
         return NULL;
      }
      $credentials = get_configuracion_proveedorP('SW','comprobante', $pruebas);
     
     $regresa = array();
     try{
               $params = array(
               "url"=>$credentials['url'],
               "token" => "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRmTDFuTyttKzM1dUZFQ0RUSEJlMTExMjBXcWxGQ3N4VSt2OEdzMG1wd3l3WHhCZS9sS2dSTnRwa2lySkpreW9XT3JVR29DTUpjMUhZais4bzE0K1RQK1pnbTRIbWVkamszaUZubEt2bFhMMGxJQVZoY2cybTFjWUQvMDR2aWYyQ0liZkI3QVBIRTNmRlVQQnhtSnRpOTQrYi95Y0hzem1zVTBlNW5xc25TV0N2ZTJGcDZzQkVUZFhHbTRudklCcXQ4VjNNZDNFSmpNR0Rqc0FGYmU4ZnFyVUM5eDFFalN1aUsxTFRtZWdsb1VpVVQyYnJCWW90cHlkL3I0aVppUHVzWHZOaFppZVN4c2xSM0h4ZW1aODYwRy9vQXdxWUpKQTc3bituVjR5V0ZURW5DaVJzMlQzMjhUNjNUTzloNXNaZkVoYy9MWjkwbGhrdjFRWHlacjhtTU5IU241aVVUREsxenFSZmJZZHlLOTVyUTRVNTJ6S0FZeGpwNDc3Q2RjT0c.wNuLEQohzpgRq5sVx3pHVCJ9aLVT51XVjGoXhqRqh_8"
               );

       $stamp = StampService::Set($params);
       $result = $stamp::StampV4($xml);
     
       //var_dump($result);
       $regresa['OperacionExitosa'] = $result->status == 'success' ? true : false;
       $regresa['MensajeError'] = $result->message;
       $regresa['MensajeErrorDetallado'] = $result->messageDetail;
       $regresa['CodigoRespuesta'] = $result->message;
       $regresa['XmlResultado'] = base64_encode($result->data->cfdi);
       $regresa['Timbre'] = $result->data;
     } 
     catch (Exception $ex)
     {
       $regresa['exception'] = $ex->getMessage();
     }
     return $regresa;
   }
}

if( !function_exists('sw_timbre_retenciones'))
{
   function sw_timbra_retenciones($xml)
   {
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      if( get_tipo_xml($xml) !== 'retenciones')
      {
         return NULL;
      }
      $credentials = get_configuracion_proveedor('SW','retenciones');
      $regresa = array();

      $token2 = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";

      $curl = curl_init();

      try
      {

        curl_setopt_array($curl,array(
            CURLOPT_URL => "http://cfdi.smartweb.com.mx/Timbrado/wcfTimbradoRetenciones.svc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:tem=\"http://tempuri.org/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <tem:TimbrarRetencionXMLV2>\r\n         <!--Optional:-->\r\n         <tem:xmlRetencion><![CDATA[".$xml."]]></tem:xmlRetencion>\r\n         <!--Optional:-->\r\n    <tem:tokenAutenticacion>".$token2."</tem:tokenAutenticacion>   </tem:TimbrarRetencionXMLV2>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
                "Accept: text/xml",
                "Cache-Control: no-cache",
                "Content-type: text/xml;charset=\"utf-8\"",
                "SOAPAction: http://tempuri.org/IwcfTimbradoRetenciones/TimbrarRetencionXMLV2"
            ),
        ));

            $response = curl_exec($curl);

            $dom = new DOMDocument('1.0','UTF-8');
            $dom->loadXML($response);

            $resultadoCorrecto = $dom->getElementsByTagName('TimbrarRetencionXMLV2Result')[0]->nodeValue;
            if($resultadoCorrecto)
            {
               $status = true;
               $error = '';
               $errordetallado = '';

               $dom = new DOMDocument('1.0','utf-8');
               $dom->loadXML($resultadoCorrecto);

               foreach($dom->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital','*') as $elemento)
               {
                   $uuid = $elemento->getAttribute('UUID');
                   $fecha_timbrado = $elemento->getAttribute('FechaTimbrado');
               }
            }
            else
            {
                $errordetallado = $dom->getElementsByTagName('Message')[0]->nodeValue;
                $error = $dom->getElementsByTagName('faultstring')[0]->nodeValue;
                $status = false;
                $resultadoCorrecto = '';
            }

          $arrayAntes = array('cfdi' => $resultadoCorrecto, 'UUID' => $uuid, 'tipo' => 'Retencion');

          $arrayAntes2 = (object) $arrayAntes;
          $array = array('status' => $status, 'message' => $error, 'messageDetail' => $errordetallado, 'data' => $arrayAntes2);

          $array2 = (object) $array;

           $regresa['OperacionExitosa'] = $array2->status;
           $regresa['MensajeError'] = $array2->message;
           $regresa['MensajeErrorDetallado'] = $array2->messageDetail;
           $regresa['CodigoRespuesta'] = $array2->message;
           $regresa['XmlResultado'] = base64_encode($array2->data->cfdi);
           $regresa['Timbre'] = $array2->data;
      }
      catch (Exception $e)
      {
          $regresa['exception'] = $ex->getMessage();
          log_message('error', $ex->getMessage());
      }
      return $regresa;
   }
}

if( !(function_exists('sw_timbra_retencionesP')))
{
   function sw_timbra_retencionesP($xml)
   {
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      if( get_tipo_xml($xml) !== 'retenciones')
      {
         return NULL;
      }
      $credentials = get_configuracion_proveedorP('SW','retenciones',1);
      $regresa = array();


      $token2 = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRmTDFuTyttKzM1dUZFQ0RUSEJlMTExMjBXcWxGQ3N4VSt2OEdzMG1wd3l3WHhCZS9sS2dSTnRwa2lySkpreW9XT3JVR29DTUpjMUhZais4bzE0K1RQK1pnbTRIbWVkamszaUZubEt2bFhMMGxJQVZoY2cybTFjWUQvMDR2aWYyQ0liZkI3QVBIRTNmRlVQQnhtSnRpOTQrYi95Y0hzem1zVTBlNW5xc25TV0N2ZTJGcDZzQkVUZFhHbTRudklCcXQ4VjNNZDNFSmpNR0Rqc0FGYmU4ZnFyVUM5eDFFalN1aUsxTFRtZWdsb1VpVVQyYnJCWW90cHlkL3I0aVppUHVzWHZOaFppZVN4c2xSM0h4ZW1aODYwRy9vQXdxWUpKQTc3bituVjR5V0ZURW5DaVJzMlQzMjhUNjNUTzloNXNaZkVoYy9MWjkwbGhrdjFRWHlacjhtTU5IU241aVVUREsxenFSZmJZZHlLOTVyUTRVNTJ6S0FZeGpwNDc3Q2RjT0c.wNuLEQohzpgRq5sVx3pHVCJ9aLVT51XVjGoXhqRqh_8";

      $curl = curl_init();

      try
      {
         curl_setopt_array($curl, array(
            CURLOPT_URL => "http://pruebascfdi.smartweb.com.mx/Timbrado/wcfTimbradoRetenciones.svc",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:tem=\"http://tempuri.org/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <tem:TimbrarRetencionXMLV2>\r\n         <!--Optional:-->\r\n         <tem:xmlRetencion><![CDATA[".$xml."]]></tem:xmlRetencion>\r\n         <!--Optional:-->\r\n    <tem:tokenAutenticacion>".$token2."</tem:tokenAutenticacion>   </tem:TimbrarRetencionXMLV2>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
            CURLOPT_HTTPHEADER => array(
              "Accept: text/xml",
              "Cache-Control: no-cache",
              "Content-type: text/xml;charset=\"utf-8\"",
              "SOAPAction: http://tempuri.org/IwcfTimbradoRetenciones/TimbrarRetencionXMLV2"
            ),
          ));
   
         $response = curl_exec($curl);
         
         
         $dom = new DOMDocument('1.0','UTF-8');
         $dom->loadXML($response);


         $resultadoCorrecto = $dom->getElementsByTagName('TimbrarRetencionXMLV2Result')[0]->nodeValue;
         if($resultadoCorrecto)
         {
            $status = true;
            $error = '';
            $errordetallado = '';

            $dom = new DOMDocument('1.0','utf-8');
            $dom->loadXML($resultadoCorrecto);
  
            foreach($dom->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital','*') as $elemento)
            {
              $uuid = $elemento->getAttribute('UUID');
              $fecha_timbrado = $elemento->getAttribute('FechaTimbrado');
            }

         }
         else
         {

            $errordetallado = $dom->getElementsByTagName('Message')[0]->nodeValue;
            $error = $dom->getElementsByTagName('faultstring')[0]->nodeValue;
            $status = false;
            $resultadoCorrecto = '';
           
         }



         $arrayAntes = array('cfdi' => $resultadoCorrecto , 'UUID' => $uuid ,'tipo' => 'Retencion');

         $arrayAntes2 = (object) $arrayAntes; 
         $array = array('status' => $status,'message' => $error,'messageDetail' => $errordetallado,'data' => $arrayAntes2);
        
         $array2 = (object) $array;


        $regresa['OperacionExitosa'] = $array2->status;
        $regresa['MensajeError'] = $array2->message;
        $regresa['MensajeErrorDetallado'] = $array2->messageDetail;
        $regresa['CodigoRespuesta'] = $array2->message;
        $regresa['XmlResultado'] = base64_encode($array2->data->cfdi);
        $regresa['Timbre'] = $array2->data;
         
      }
      catch (Exception $e)
      {
          $regresa['exception'] = $e->getMessage();
      }

      return $regresa;

   }
}

if(!function_exists('sw_cancelaR'))
{
   function sw_cancelaR($certi,$uuid,$clave_cliente,$llave,$pk_pass,$moti)
   {

      $curl = curl_init();

      try
      {
      $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";

      curl_setopt_array($curl,array(
         CURLOPT_URL => "http://cfdi.smartweb.com.mx/Cancelacion/CancelacionRetencion.svc",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:tem=\"http://tempuri.org/\">
         <soapenv:Header/>
         <soapenv:Body>
         <tem:CancelaUnoCSD>
         <tem:tokenAutenticacion>".$token."</tem:tokenAutenticacion>
         <tem:CSDCer>".$certi."</tem:CSDCer>
         <tem:CSDKey>".$llave."</tem:CSDKey>
         <tem:password>".$pk_pass."</tem:password>
         <tem:RFCEmisor>".$clave_cliente."</tem:RFCEmisor>
         <tem:UUID>".$uuid."</tem:UUID>
         <tem:motivo>02</tem:motivo>
         </tem:CancelaUnoCSD>
         </soapenv:Body>
</soapenv:Envelope>",
         CURLOPT_HTTPHEADER => array(
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Content-type: text/xml;charset=\"utf-8\"",
            "SOAPAction: http://tempuri.org/ICancelacionRetencion/CancelaUnoCSD"
         ),
      ));

      $response = curl_exec($curl);
     // var_dump($response);

     $dom = new DOMDocument('1.0','UTF-8');
     $dom->loadXML($response);

     $result = $dom->getElementsByTagName('CancelaUnoCSDResult')[0]->nodeValue;

     if($result)
     {
        $xml = simplexml_load_string($result);
        $codigouuid = (int) $xml->Folios->EstatusUUID;
        $status = true;
        $mensaje = 'Cancelado';
     }
     else
     {
        $status = false;
        $error = $dom->getElementsByTagName('faultstring')[0]->nodeValue;
     }

     $regresa = array();

     $regresa['OperacionExitosa'] = $status == true ? true : false;
     $regresa['MensajeError'] = $status == true ? '' : $error;
     $regresa['MensajeErrorDetallado'] = $status == true ? '' : '';
     $regresa['CodigoResultado'] = $status == true ? $codigouuid : '';
     $regresa['MensajeResultado'] = $status == true ? $result : '';
     $regresa['Respuesta'] = $status == true ? $mensaje : '';

     }
     catch(Exception $ex)
     {
        $regresa['exception'] = $ex->getMessage();
     }

     return $regresa;
   }
}

if(!function_exists('sw_cancelaRP'))
{
   function sw_cancelaRP($certi,$uuid,$clave_cliente,$llave,$pk_pass,$moti)
   {

      $curl = curl_init();

      try
      {
      $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRmTDFuTyttKzM1dUZFQ0RUSEJlMTExMjBXcWxGQ3N4VSt2OEdzMG1wd3l3WHhCZS9sS2dSTnRwa2lySkpreW9XT3JVR29DTUpjMUhZais4bzE0K1RQK1pnbTRIbWVkamszaUZubEt2bFhMMGxJQVZoY2cybTFjWUQvMDR2aWYyQ0liZkI3QVBIRTNmRlVQQnhtSnRpOTQrYi95Y0hzem1zVTBlNW5xc25TV0N2ZTJGcDZzQkVUZFhHbTRudklCcXQ4VjNNZDNFSmpNR0Rqc0FGYmU4ZnFyVUM5eDFFalN1aUsxTFRtZWdsb1VpVVQyYnJCWW90cHlkL3I0aVppUHVzWHZOaFppZVN4c2xSM0h4ZW1aODYwRy9vQXdxWUpKQTc3bituVjR5V0ZURW5DaVJzMlQzMjhUNjNUTzloNXNaZkVoYy9MWjkwbGhrdjFRWHlacjhtTU5IU241aVVUREsxenFSZmJZZHlLOTVyUTRVNTJ6S0FZeGpwNDc3Q2RjT0c.wNuLEQohzpgRq5sVx3pHVCJ9aLVT51XVjGoXhqRqh_8";

      curl_setopt_array($curl,array(
         CURLOPT_URL => "http://cfdi.smartweb.com.mx/Cancelacion/CancelacionRetencion.svc",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:tem=\"http://tempuri.org/\">
                           <soapenv:Header/>
                           <soapenv:Body>
                           <tem:CancelaUnoCSD>
                           <tem:tokenAutenticacion>".$token."</tem:tokenAutenticacion>
                           <tem:CSDCer>".$certi."</tem:CSDCer>
                           <tem:CSDKey>".$llave."</tem:CSDKey>
                           <tem:password>".$pk_pass."</tem:password>
                           <tem:RFCEmisor>".$clave_cliente."</tem:RFCEmisor>
                           <tem:UUID>".$uuid."</tem:UUID>
                           <tem:motivo>02</tem:motivo>
                           </tem:CancelaUnoCSD>
                           </soapenv:Body>
               </soapenv:Envelope>",
         CURLOPT_HTTPHEADER => array(
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Content-type: text/xml;charset=\"utf-8\"",
            "SOAPAction: http://tempuri.org/ICancelacionRetencion/CancelaUnoCSD"
         ),
      ));

      $response = curl_exec($curl);
      // var_dump($response);
        $dom = new DOMDocument('1.0','UTF-8');
        $dom->loadXML($response);

         $result = $dom->getElementsByTagName('CancelaUnoCSDResult')[0]->nodeValue;

         if($result)
         {
            $xml = simplexml_load_string($result);
            $codigouuid = (int) $xml->Folios->EstatusUUID;
            $status = true;
            $mensaje = 'Cancelado';
         }
         else
         {
            $status = false;
            $error = $dom->getElementsByTagName('faultstring')[0]->nodeValue;
         }

         $regresa = array();

         $regresa['OperacionExitosa'] = $status == true ? true : false;
         $regresa['MensajeError'] = $status == true ? '' : $error;
         $regresa['MensajeErrorDetallado'] = $status == true ? '' : '';
         $regresa['CodigoResultado'] = $status == true ? $codigouuid : '';
         $regresa['MensajeResultado'] = $status == true ? $result : '';
         $regresa['Respuesta'] = $status == true ? $mensaje : '';
      }
      catch(Exception $ex)
      {
         $regresa['exception'] = $ex->getMessage();
      }

         return $regresa;
   }
}

if(!function_exists('sw_cancelaP'))
{
   function sw_cancelaP($uuid,$password2,$rfc,$b64Pfx2,$receptor,$total,$moti,$foliosus)
   {
  
      $total2 = str_pad(number_format($total,6,".",""), 17, 0, STR_PAD_LEFT);
  
      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://pruebacfdiconsultaqr.cloudapp.net/ConsultaCFDIService.svc?wsdl=",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:tem=\"http://tempuri.org/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <tem:Consulta>\r\n         <!--Optional:-->\r\n         <tem:expresionImpresa><![CDATA[?re=".$rfc."&rr=".$receptor."&tt=".$total2."&id=".$uuid."]]></tem:expresionImpresa>\r\n      </tem:Consulta>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
         CURLOPT_HTTPHEADER => array(
           "Accept: text/xml",
           "Cache-Control: no-cache",
           "Content-type: text/xml;charset=\"utf-8\"",
           "SOAPAction: http://tempuri.org/IConsultaCFDIService/Consulta"
         ),
       ));

      $response = curl_exec($curl);
  
      $dom = new DOMDocument('1.0','UTF-8');
      $dom->loadXML($response);
      $resulte = array(
        'codigo_estatus' => $dom->getElementsByTagName('CodigoEstatus')[0]->nodeValue,
        'es_cancelable' => $dom->getElementsByTagName('EsCancelable')[0]->nodeValue,
        'estado' => $dom->getElementsByTagName('Estado')[0]->nodeValue,
        'estatusCancelacion' => $dom->getElementsByTagName('EstatusCancelacion')[0]->nodeValue,
      );

        if($resulte['estatusCancelacion'] == '')
        {
            if($resulte['es_cancelable'] == 'Cancelable sin aceptación')
            {
               $resultado = 'Cancelable sin aceptacion';
               $codigo = 201;
            }
            if($resulte['es_cancelable'] == 'Cancelable con aceptación')
            {
               $resultado = 'Cancelable con aceptacion';
               $codigo = 201;
            }
            if($resulte['es_cancelable'] == 'No cancelable')
            {
               $resultado = 'El CFDI no se puede cancelar porque contiene comprobantes relacionados vigentes, para cancelarlo debera cancelar previamente todos los comprobantes relacionados ';
               $codigo = 'CANC102';
            } 
        }
        else
        {
           if($resulte['estatusCancelacion'] == 'Cancelado sin aceptación')
           {
               $resultado = 'Cancelable sin aceptacion';
               $codigo = 201;
           }
           if($resulte['estatusCancelacion'] == 'Cancelado con aceptación')
           {
               $resultado = 'Cancelable con aceptacion';
               $codigo = 201;
           }
           if($resulte['estado'] == 'Cancelado')
            {
               $resultado = 'Ya fue cancelado previamente ';
               $codigo = 202;
            }
            if($resulte['estatusCancelacion'] == 'En proceso')
            {
               $resultado = 'El CFDI no se puede cancelar por que tiene estatus de "En proceso" ';
               $codigo = 'CANC106';
            }
            if($resulte['estatusCancelacion'] == 'Plazo Vencido')
            {
               $resultado = 'El CFDI ha sido cancelado previamente por plazo vencido';
               $codigo = 'CANC107';
            }
            if($resulte['estatusCancelacion'] == 'Solicitud rechazada')
            {
               $resultado = 'El CFDI no se puede cancelar por que fue rechazado previamente';
               $codigo = 'CANC104';
            }
                      
            
      }
       
           'CANC102';'El CFDI no se puede cancelar porque contiene comprobantes relacionados vigentes, para cancelarlo deberá cancelar previamente todos los comprobantes relacionados';
           'CANC103';'El CFDI ha sido cancelado previamente por aceptacion del receptor';
           'CANC104';'El CFDI no se puede cancelar por que fue rechazado previamente';
           'CANC105';'El CFDI no se puede cancelar por que tiene estatus de "En espera de aceptacion" ';
           'CANC106';'El CFDI no se puede cancelar por que tiene estatus de "En proceso" ';
           'CANC107';'El CFDI ha sido cancelado previamente por plazo vencido';
                      'Cancelable con aceptacion Solicitud de cancelacion enviada';

      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      $credentials = get_configuracion_proveedorP('SW','comprobante', '1');
      $regresa = array();

      try
      {
         
         $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRmTDFuTyttKzM1dUZFQ0RUSEJlMTExMjBXcWxGQ3N4VSt2OEdzMG1wd3l3WHhCZS9sS2dSTnRwa2lySkpreW9XT3JVR29DTUpjMUhZais4bzE0K1RQK1pnbTRIbWVkamszaUZubEt2bFhMMGxJQVZoY2cybTFjWUQvMDR2aWYyQ0liZkI3QVBIRTNmRlVQQnhtSnRpOTQrYi95Y0hzem1zVTBlNW5xc25TV0N2ZTJGcDZzQkVUZFhHbTRudklCcXQ4VjNNZDNFSmpNR0Rqc0FGYmU4ZnFyVUM5eDFFalN1aUsxTFRtZWdsb1VpVVQyYnJCWW90cHlkL3I0aVppUHVzWHZOaFppZVN4c2xSM0h4ZW1aODYwRy9vQXdxWUpKQTc3bituVjR5V0ZURW5DaVJzMlQzMjhUNjNUTzloNXNaZkVoYy9MWjkwbGhrdjFRWHlacjhtTU5IU241aVVUREsxenFSZmJZZHlLOTVyUTRVNTJ6S0FZeGpwNDc3Q2RjT0c.wNuLEQohzpgRq5sVx3pHVCJ9aLVT51XVjGoXhqRqh_8";
         
         $regresa = array();

         if($moti == '01')
         {
            $parameters = array(
               'uuid' => $uuid,
               'password' => $password2,
               'rfc' => $rfc,
               'b64Pfx' => $b64Pfx2,
               'motivo' => $moti,
               'folioSustitucion' => $foliosus
            );
         }
         else
         {
            $parameters = array(
               'uuid' => $uuid,
               'password' => $password2,
               'rfc' => $rfc,
               'b64Pfx' => $b64Pfx2,
               'motivo' => $moti
            );
         }

         $curl = curl_init($credentials['url'].'/cfdi33/cancel/pfx');
         curl_setopt($curl , CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl , CURLOPT_CUSTOMREQUEST, "POST");
         curl_setopt($curl , CURLOPT_HTTPHEADER , array(
             'Content-Type: application/json;',
             'Authorization: Bearer '.$token
             ));  
         curl_setopt($curl , CURLOPT_POSTFIELDS, json_encode($parameters));
 
         $response = curl_exec($curl);
         $result = json_decode($response);
         curl_close($curl);
       
         $xml = simplexml_load_string($result->data->acuse);

         if($resulte['estatusCancelacion'] != 'Cancelado sin aceptación' OR 'Cancelado con aceptación')
         {
            $varri = $codigo;
         }
         else
         {
            $varri = (int) $xml->Folios->EstatusUUID;
         }
    

           $regresa['OperacionExitosa'] = $result->status == 'success' ? true : false; 
           $regresa['MensajeError'] = $result->message;
           $regresa['MensajeErrorDetallado'] = $result->messageDetail;
           $regresa['CodigoResultado'] =  $result->status == 'success' ? $varri : '';
           $regresa['MensajeResultado'] = $result->data;
           $regresa['Respuesta'] = $result->status == 'success' ? $resultado : '';
         
      }
      catch(Exception $ex)
      {
         $regresa['exception'] = $ex->getMessage();
      }
      //var_dump($regresa);
       return $regresa;

   }
}
if(!function_exists('sw_cancela'))
{
   function sw_cancela($uuid,$password2,$rfc,$b64Pfx2,$receptor,$total,$moti,$foliosus)
   {

      $total2 = str_pad(number_format($total,6,".",""), 17, 0, STR_PAD_LEFT);
  
      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => "https://consultaqr.facturaelectronica.sat.gob.mx/ConsultaCFDIService.svc?wsdl=",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_SSL_VERIFYHOST => 0,
         CURLOPT_SSL_VERIFYPEER => 0,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:tem=\"http://tempuri.org/\">\r\n   <soapenv:Header/>\r\n   <soapenv:Body>\r\n      <tem:Consulta>\r\n         <!--Optional:-->\r\n         <tem:expresionImpresa><![CDATA[?re=".$rfc."&rr=".$receptor."&tt=".$total2."&id=".$uuid."]]></tem:expresionImpresa>\r\n      </tem:Consulta>\r\n   </soapenv:Body>\r\n</soapenv:Envelope>",
         CURLOPT_HTTPHEADER => array(
           "Accept: text/xml",
           "Cache-Control: no-cache",
           "Content-type: text/xml;charset=\"utf-8\"",
           "SOAPAction: http://tempuri.org/IConsultaCFDIService/Consulta"
         ),
       ));

      $response = curl_exec($curl);
  
      $dom = new DOMDocument('1.0','UTF-8');
      $dom->loadXML($response);
      $resulte = array(
        'codigo_estatus' => $dom->getElementsByTagName('CodigoEstatus')[0]->nodeValue,
        'es_cancelable' => $dom->getElementsByTagName('EsCancelable')[0]->nodeValue,
        'estado' => $dom->getElementsByTagName('Estado')[0]->nodeValue,
        'estatusCancelacion' => $dom->getElementsByTagName('EstatusCancelacion')[0]->nodeValue,
      );


      if($resulte['estatusCancelacion'] == '')
      {
          if($resulte['es_cancelable'] == 'Cancelable sin aceptación')
          {
             $resultado = 'Cancelable sin aceptacion';
             $codigo = 201;
          }
          if($resulte['es_cancelable'] == 'Cancelable con aceptación')
          {
             $resultado = 'Cancelable con aceptacion';
             $codigo = 201;
          }
          if($resulte['es_cancelable'] == 'No cancelable')
          {
             $resultado = 'El CFDI no se puede cancelar porque contiene comprobantes relacionados vigentes, para cancelarlo debera cancelar previamente todos los comprobantes relacionados ';
             $codigo = 'CANC102';
          } 
      }
      else
      {
         if($resulte['estatusCancelacion'] == 'Cancelado sin aceptación')
           {
               $resultado = 'Cancelable sin aceptacion';
               $codigo = 201;
           }
           if($resulte['estatusCancelacion'] == 'Cancelado con aceptación')
           {
               $resultado = 'Cancelable con aceptacion';
               $codigo = 201;
           }
         if($resulte['estado'] == 'Cancelado')
          {
             $resultado = 'Ya fue cancelado previamente ';
             $codigo = 202;
          }
          if($resulte['estatusCancelacion'] == 'En proceso')
          {
             $resultado = 'El CFDI no se puede cancelar por que tiene estatus de "En proceso" ';
             $codigo = 'CANC106';
          }
          if($resulte['estatusCancelacion'] == 'Plazo Vencido')
          {
             $resultado = 'El CFDI ha sido cancelado previamente por plazo vencido';
             $codigo = 'CANC107';
          }
          if($resulte['estatusCancelacion'] == 'Solicitud rechazada')
          {
             $resultado = 'El CFDI no se puede cancelar por que fue rechazado previamente';
             $codigo = 'CANC104';
          }
                    
          
    }
     
         'CANC102';'El CFDI no se puede cancelar porque contiene comprobantes relacionados vigentes, para cancelarlo deberá cancelar previamente todos los comprobantes relacionados';
         'CANC103';'El CFDI ha sido cancelado previamente por aceptacion del receptor';
         'CANC104';'El CFDI no se puede cancelar por que fue rechazado previamente';
         'CANC105';'El CFDI no se puede cancelar por que tiene estatus de "En espera de aceptacion" ';
         'CANC106';'El CFDI no se puede cancelar por que tiene estatus de "En proceso" ';
         'CANC107';'El CFDI ha sido cancelado previamente por plazo vencido';
                    'Cancelable con aceptacion Solicitud de cancelacion enviada';

   
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      $credentials = get_configuracion_proveedor('SW','comprobante');
      $regresa = array();

      try
      {
         
         $token = "T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbXB3YVZxTHdOdHAwVXY2NTdJb1hkREtXTzE3dk9pMmdMdkFDR2xFWFVPUXpTUm9mTG1ySXdZbFNja3FRa0RlYURqbzdzdlI2UUx1WGJiKzViUWY2dnZGbFloUDJ6RjhFTGF4M1BySnJ4cHF0YjUvbmRyWWpjTkVLN3ppd3RxL0dJPQ.T2lYQ0t4L0RHVkR4dHZ5Nkk1VHNEakZ3Y0J4Nk9GODZuRyt4cE1wVm5tbFlVcU92YUJTZWlHU3pER1kySnlXRTF4alNUS0ZWcUlVS0NhelhqaXdnWTRncklVSWVvZlFZMWNyUjVxYUFxMWFxcStUL1IzdGpHRTJqdS9Zakw2UGRTWFFYWDAyb1o0TkhDakpGTmY4Ty92dklveGYrYy9uT1lsRzBDU2FzcFRUd2gxNUZpU2Z4YlNzS01Nb3c0b1NHaU04b0o2b2grM3RIVG5ZMGNEc0psMklSa29FZVoyOWdwZWZkbWpaL3pNMDRBb2YrZmhEN0ltcERDcGRTYzVZcFM3dXZyRjhTOHUwcklJKzhkaFQxV05lRDcxYW1scGFEcFVpanNwbW9iak42YUJDdzRHNTJrcmpEakh5bGpBSnJoN1Nvc2RmQXgvSEJWZ0s5SVc3bHJEbUNwa2pReEorS09LTXJjVlIvbFo0eklUQVNpZkIvd09mV2Vza2dUQ2VmcDBjemYxN2RLRlRCRm1sL0RzbTlEaHVqRFRlRENkM3ZGY2xoMjlwTGtUK2ptNnk1bWRTMDRWL0wxNG11M1Bhc1djSCtYeWlHVE45QjdBam1YUkpLKzVSRlR0aW9RMVVWUEFpWHFqUlJLMFA5NFJDNGlwKytQSlZkSjFqRkpQYXA.KkYT-3mddTcCww-x8NeEOPN1KSo_xLQzsJakIAa4Geo";
         
        // $b64Pfx = 'MIIMEQIBAzCCC9cGCSqGSIb3DQEHAaCCC8gEggvEMIILwDCCBncGCSqGSIb3DQEHBqCCBmgwggZkAgEAMIIGXQYJKoZIhvcNAQcBMBwGCiqGSIb3DQEMAQYwDgQIeVd9uOsZwEcCAggAgIIGMGcRBehEKirFa6B//B2ZYMjPW7fQWLDUa3grAs8gBnlEHiQBfpGAj4BE1WB1gvR+MVzdaHh8WssoHi0cU8OorTQ4RODslb9RqCa7CbFbhQkWiz7CsAO1r79ET5huhDj1jOth6ie1lnA3JTDnlwHvoujO3zGRItZ3ay0bR948JmNvhTmBLiR5Lv3x4/M8rNaErofFtxJV8kmjoSTI+Fno8JIGCzfAl0o/Hs4Bb4PQYLFt7cVEGCGxHT/FiDMs/+gO8sqcgPv9/ke32XjRgfFuwkjTyL8gAm1yw/x3AFuZhSbjIWxM/Mnr+kiPxnXXOWDOMUnsTKhVpAlmwY1VMp68AxYpAvmRq/jcAaHG3uOhIDG1bJkbnjGF6tNMJgBeKhR1Ari6yMeHsOX+JbxXnLDqY62Uzr4nSVdFzx7SY2zRAjttlDaoALDOCOFkksi2JMh5G8+IMeOkLFQGqKs0AzbfsjbZnbvpVqPgtveyuc444D/623+jWTr2H7bMbHqyZW4OCYwkR4nK2sGo63vKJUW4wdGhpfdaKmL7GsgHugYsEUNbkBE9tHPAccWpEIGEGsn0OtRCBjB51wxQUGhzdLa9JH1OiyrDKxdtb8bbeX375Jj59P97RR7gIQvPLOYa0f5ZJUuUVChEZUlaZriylD41GxIjMiOoIC+lPbwyRqiG27tVHLsaFyB4rzHcOJTO7EGBdgBguej1UJ1k4jGZ2j2fC8Ug8CS6+z/5X2ifCxmCVZ5nem5jFtidz8rS/R1MLEHi67wo0m1ehxAnXWRW2nz+ZroG7PA22Wx7K/VvtRM5++Em+C67/qLhjzSmiujbjJVShmniX8Ncb1eVOu3/CeB/duYm6IyDdezfx+0c2U81wonS1SVLsn4g6+nSWFiJrehq5p3A5KsOLuBnlLD5J/TGNsYG5zLQMSPAimLt448IqV4mLPU12t/GHGFMDyircnNlUiuf7R4U7ZwVgGphtHfumiTOdciPGkPzLMDwCi4kMIhe3aL82K7YuQcdomUZs0agwKYH87Ph65iPk10uBWN6aLO0yV9QW7jmhlWjHPrBJ/Q8TV4CH8vKEXqKyZKWteLQVp9A8lI8tuHvcx7ywpIqFYjAIGMggnAf8FHQOXEGXo4X6CHq5AvOiuXwcTbheqno9DfowoJZMF7h41ABqgXa7auCPImrVTm2LgB1JgyGkU/S8vOozojIfyd7IRMWnHOC4YYdM81XxY2c/ZUls35jexcmDmii9z3merAS4myJLAb8OoAQqUsai7Dbl/SUwkZno67UKGQV7S90sVz5M/rqUBUzXwxgdB8j/faX2wW9ghHHDazOyHJhYwbYPLrxEMZZZjIRHiMbIR/O6ptVy0KpytdlxzKKf2aKtI4PKZP/eZvMPlSlxkoen+Jy3xzqly30UxhhIWUmFpqmIgkZCwhl8cgL0AlesiQFY0kleFpPLEz7zKpVlqI1qMKQxGcMSasuBy4Vjp1cVzES4CFFSunnr4wyKqtsxlimN3bfzXRknzwcMwB177HOGmPyvDaDjX8x2rqp0usmFoLJ3TZln5R3JAJH4ZDm2O2E4TAujXsMBrdgkYQszCAvTRzQMFjMoNJcYY8mS1kdyYLtdw+fWVx073cw00DZsysCnozn7+e+wpj+i2jhr0qxLIAF6A/pX/2OeDIU+lPIhYpUgZw3Cet3BTxtvn02Se0RSgaCUrcR6Cb2tr3dQbTQOaMytmIUrL4l2vqq6+7YvWpzjEv6hJdNkNZgnnZs9Y5Qs8lkrxqzA0lgRwntZvb6Fb0yXPwkWS+3ZOEevWsN4seLgxM/Yk4dbZUvF8RQBO3AcuMr3pSdDMdHOh7TOHLoqug+ipmNFHU/GFGq2V74+dO12cSm32RRQkrKuqmLVQ7utuuNz4OxTIxVCzH87gPeYsgtzD0Gh8wgh2G5jY5eqU++adbQFvU/9BTbJPKLVH3lG44Mzl0R/1mFJfOFdSqVvBiCFUe6XQ8REVlXUXKbDLNrwLXFGqe4ctvopdgZqegGP168RZwTpJk4/wYvHxcm8hcg+AwDLfSa6D9jnncek1qwOaZUCG5Y47h5cbAUoX18AHFelVhQ0jej7eMIHJQpVC+cNvqnTZYhzDCCBUEGCSqGSIb3DQEHAaCCBTIEggUuMIIFKjCCBSYGCyqGSIb3DQEMCgECoIIE7jCCBOowHAYKKoZIhvcNAQwBAzAOBAi8wbeC5JopRAICCAAEggTIsB1QxfWY0GMGywTuKn2vfLgT5FhEbMroduH203uVWD7qDtgge6J7LF9Q/jHZAKTr3DyjUpClyR7j3j7+k9db2d/WOBEfD6/9p4Qz39rdJHMbV/5VjhDZ2WQsMTK1kylMqwAaJHwhspQY1UjN4QOr6McZLPs8R/PIeXzrO/wVZ88+YFbLAtpynln8fRYOtRe09nvdMhV8fueTIpyR/pAxtm0Ol+zjiFULaj+l6JQhdPkktUw4wwqL6Ozro9L0JXrYKwyCtw+t9kkUaf6nFIuTg3Fj7a+mrdUjVL+dKbM21dWfD2lx/xRZCtxc9g4IopZ6woSePHgRZ2ZFjNFY+pKeQHluDMxMIqUE1msyLOyo1QIIZcFHjbWHX/P7l/k0mClcf4BN7O1cyXoIZaBVF4PmxT6iLbewauH5z0l3aC0E9Yp6CiSbZxkLzk+8qB3t6mSBvrSDpfkM7S5D40Fnw+ZwvEiFzyieq/6d7T8hm6wyl6tRsLiFcd5UpZApPnzQJWIPen1fVSVGr7sZV8D84L0whas3L6e+Zde7z/eyZc+rOAmsjpSTFMx4U/d9EzciRMXIWsnYPXParJIiNP6qoZ60tR98ZtG+QzC1gQMlVU/U3ELZF5w8Hn+W/V7nI9DiUplOcmX5iaZMDUbCkIx27pbUe87pem3kZo4XclykjfQ7TWmxGQtRhbcyVDuL5IVHzyrURWxq/3fL4kuEAhpEweuXVz0TglhLpCh+aaJwrZS2FTcA3c9ZxGgrWW6H3oEfh7fBWYZeCc9Oew3SNtP6d79SgH50sf/fXNfPLAZZMoeoKrxJYXEhyKBUNFLm9LigO07fYUI/TmKzZZAYitD2TTJL8zkJrRLYloAfasNqEnU58OuM2smk1F+5ufFcBrjbbVxrQq/QpWfGryDWTkDXkscJRrv27F69kWFKhD1BvfRG7dGiZJ0sTLkL7HIsYVqqDRH8kRpDF0ZOAHeJ1VyW0vTPrGaEsBk7ihXwOI229doL8F8VX7sUUgwTeSKFvdSHCXsCsKdCVkTvR+bLhA1uCMBrEPcctAZ+RTM4R6/5zMdV9uESPXz4fj/qZt0YAH+qV2nIWI70LgYUjMJbFierz/4FHuyypX6SgaPGloFc0DjYtDh1bNRV3ZMVpTEEpU8fDnG5BzNT4p53XM02KcBL1+aKZpzJrR/sDx50LvIjxb9UFG9wyw0MQ96oveXvBxCii/w2x4EN8Udmwj1Ag01OwQno/CyJLIAyarIhyX+uJSTLgSX0zUMInQ/4PHZIzr0u3wp0/oHgJ03Nyyvp9TynmLNpY/QdISfwUH12Lrm5OyPwZXif0w8PkeJTYamL7kxFRmVvaHKnwmJcqjXbFGFL5A0BHnRkzBC1hfTvZJegH+XNOcoSYbDCa/tiY2yNha+OyLAOTXtR1nrHH9L6MCrW5G1Zb2qra67ry7LaLE4N/5eo7QbXAGfgrE1VEbeEnLl6JCIwS/c5KVXyiTrfJISSvdTSVuNe0yZc8fwJyUpjlv9FOj7Rn8+0ZnQgU+eHR/qCcKgOfIbB+1PwXvCzgTM9t57TN6i7G8Xc81/5NirP02UpvHjcyExm6D5RgcmEHMegflNwKykHt6zJ5OD7MhP7oOc5i8nUN3lBeAOVMSUwIwYJKoZIhvcNAQkVMRYEFPWTAihdi2vFZJFqaq8AeAHVsE0VMDEwITAJBgUrDgMCGgUABBTteyiX0UOcHTvgNNk88/AXmpjqGgQInaeSAr8rgk4CAggA';

         $regresa = array();
         
         if($moti == '01')
         {
            $parameters = array(
               'uuid' => $uuid,
               'password' => $password2,
               'rfc' => $rfc,
               'b64Pfx' => $b64Pfx2,
               'motivo' => $moti,
               'folioSustitucion' => $foliosus
            );
         }
         else
         {
            $parameters = array(
               'uuid' => $uuid,
               'password' => $password2,
               'rfc' => $rfc,
               'b64Pfx' => $b64Pfx2,
               'motivo' => $moti
            );
         }

         $curl = curl_init($credentials['url'].'/cfdi33/cancel/pfx');
         curl_setopt($curl , CURLOPT_RETURNTRANSFER, true);
         curl_setopt($curl , CURLOPT_CUSTOMREQUEST, "POST");
         curl_setopt($curl , CURLOPT_HTTPHEADER , array(
             'Content-Type: application/json;',
             'Authorization: Bearer '.$token
             ));  
         curl_setopt($curl , CURLOPT_POSTFIELDS, json_encode($parameters));
 
         $response = curl_exec($curl);
         $result = json_decode($response);
         curl_close($curl);

         $xml = simplexml_load_string($result->data->acuse);

         if($resulte['estatusCancelacion'] != 'Cancelado sin aceptación' OR 'Cancelado con aceptación')
         {
            $varri = $codigo;
         }
         else
         {
            $varri = (int) $xml->Folios->EstatusUUID;
         }
       
           $regresa['OperacionExitosa'] = $result->status == 'success' ? true : false; 
           $regresa['MensajeError'] = $result->message;
           $regresa['MensajeErrorDetallado'] = $result->messageDetail;
           $regresa['CodigoResultado'] = $result->status == 'success' ? $varri : '';
           $regresa['MensajeResultado'] = $result->data;
           $regresa['Respuesta'] = $result->status == 'success' ? $resultado : '';
         
      }
      catch(Exception $ex)
      {
         $regresa['exception'] = $ex->getMessage();
      }
     
      return $regresa;
   }
}

/* End of file sw_helper.php */
