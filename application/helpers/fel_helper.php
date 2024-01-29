<?php

if (!function_exists('fel_validar_rfc'))
{
  /**
   * FEL VALIDAR RFC
   * 
   * Valida el rfc proporcionado ante el web service de FEL para determinar
   * si el RFC se encuentra registrado ante la autoridad o no.
   * 
   * @param $rfc El RFC que se desea consultar
   */
  function fel_validar_rfc($rfc)
  {
    $CI =& get_instance();
    $CI->load->model('MdlProveedores');
    $fel = $CI->MdlProveedores->get_by_id('FEL');
    $CI->load->library('encryption');
    $soap_options = array(
      'stream_context' => stream_context_create([
        'ssl' => [
          'verify_peer' => FALSE,
          'allow_self_signed' => TRUE,

        ]
      ])
    );
  
    $soap = new SoapClient($fel->url_comprobantes, $soap_options);
    $parameters = array(
      'usuario' => $CI->encryption->decrypt($fel->user),
      'password' => $CI->encryption->decrypt($fel->password),
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

if(!function_exists('fel_realiza_timbrado'))
{
  /**
   * Realiza el timbrado de un comprobante o de una retencion
   * 
   * @param string #xml Archivo que se desea timbrar
   */
  function fel_realiza_timbrado($xml){
    $CI =& get_instance();
    $CI->load->helper('timbrado_helper');
    $tipo = get_tipo_xml($xml);
    switch($tipo)
    {
      case 'comprobante':
        $response = fel_timbra_comprobante($xml);
      break;
      case 'retenciones':
        $response = fel_timbra_retenciones($xml);
      break;
      default:
        throw new Exception('El XML no es `Comprobante` o `Retenciones`');
    }
    $response['tipo_comprobante'] = $tipo;
    return $response;
  }
}

if(!function_exists('fel_timbra_comprobante'))
{
  /**
   * Timbra el comprobante enviado
   * 
   * @param $xml Es el XML que se desea timbrar
   * @return array Respuesta desiganda por HEGARSS para todas las peticiones
   */
  function fel_timbra_comprobante($xml)
  {
    $CI =& get_instance();
    $CI->load->helper('timbrado_helper');
    $soap_options = array(
      'stream_context' => stream_context_create([
        'ssl' => [
          'allow_self_signed' => TRUE,
          'verify_peer' => FALSE
        ]
      ]),
      'exceptions' => TRUE,
      'trace' => FALSE
    );
    // Buscamos que URL usar para el timbrado dependiendo del tipo de XML enviado
    $credentials = get_configuracion_proveedor('FEL','comprobante','saldo');
    // Inicia Peticion de timbrado
    $regresa = array();
    try{
      $parameters = array(
        'usuario' => $credentials['user'],
        'password' => $credentials['password'],
        'cadenaXML' =>  $xml,
        'referencia' => fel_genera_referencia($xml)
      );
      $result = call_soap($credentials['url'],'TimbrarCFDI',$parameters, $soap_options);
      $resp = $result->TimbrarCFDIResult;
      $regresa['OperacionExitosa'] = $resp->OperacionExitosa;
      $regresa['MensajeError'] = $resp->MensajeError;
      $regresa['MensajeErrorDetallado'] = $resp->MensajeErrorDetallado;
      $regresa['CodigoRespuesta'] = $resp->CodigoRespuesta;
      $regresa['XmlResultado'] = base64_encode($resp->XMLResultado);
      $regresa['Timbre'] = $resp->Timbre;
    } catch (Exception $ex)
    {
      $regresa['exception'] = $ex->getMessage();
    }
    return $regresa;
  }
}

if(!function_exists('fel_timbra_retenciones'))
{
  /**
   * Realiza el timbrado de un comprobante de retenciones
   * 
   * @param string $xml Comprobante que se desea timbrar
   * @return array
   */
  function fel_timbra_retenciones($xml)
  {
    $CI =& get_instance();
    $CI->load->helper('timbrado_helper');
    $soap_options = array(
      'stream_context' => stream_context_create([
        'ssl' => [
          'allow_self_signed' => TRUE,
          'verify_peer' => FALSE
        ]
      ]),
      'exceptions' => 1,
      'trace' => 0
    );
    // Buscamos que URL usar para el timbrado dependiendo del tipo de XML enviado
    $credentials = get_configuracion_proveedor('FEL','retenciones');
    // Inicia Peticion de timbrado
    $regresa = array();
    try{
      $parameters = array(
        'usuario' => $credentials['user'],
        'password' => $credentials['password'],
        'cadenaXML' =>  $xml,
        'referencia' => fel_genera_referencia($xml)
      );
      $result = call_soap($credentials['url'],'TimbrarRetencion',$parameters, $soap_options);
      $resp = $result->TimbrarRetencionResult;
      $regresa['OperacionExitosa'] = $resp->OperacionExitosa;
      $regresa['MensajeError'] = $resp->MensajeError;
      $regresa['MensajeErrorDetallado'] = $resp->MensajeErrorDetallado;
      $regresa['CodigoRespuesta'] = $resp->CodigoRespuesta;
      $regresa['XmlResultado'] = $resp->XMLResultado;
      $regresa['Timbre'] = array(
        'Estado' => $resp->Timbre['Estado'],
        'FechaTimbrado' => $resp->Timbre['FechaTimbrado'],
        'NumeroCertificadoSAT' => $resp->Timbre['NumeroCertificadoSAT'],
        'SelloCFD' => $resp->Timbre['SelloCFD'],
        'SelloSAT' => $resp->Timbre['SelloSAT'],
        'UUID' => $resp->Timbre['UUID']
      );
    } catch (Exception $ex)
    {
      $regresa['exception'] = $ex->getMessage();
    }
    return $regresa;
  }
}

if(!function_exists('fel_genera_referencia'))
{
  /**
   * FEL GENERA REFERENCIA
   * 
   * Devuelve una referencia partiendo del contenido del XML que se desea
   * timbrar, esta funcion se usa solo para el FEL ya que piden este dato
   * 
   * @param string $xml Es el XML de donde se optiene la informacion
   * @return string Referencia del Comprobante
   */
  function fel_genera_referencia($xml)
  {
    $CI =& get_instance();
    $CI->load->helper('timbrado_helper');
    $tipo = get_tipo_xml($xml);
    switch($tipo)
    {
      case 'retenciones':
        $folio = xpath_get_attribute(
          $xml,
          'retenciones:Retenciones/@FolioInt'
        );
        $emisor = xpath_get_attribute(
          $xml,
          'retenciones:Retenciones/retenciones:Emisor/@RFCEmisor'
        );
        return 'ret_' . $emisor . $folio;
        break;
      case 'comprobante':
        $folio = xpath_get_attribute(
          $xml,
          'cfdi:Comprobante/@Folio'
        );
        $serie = xpath_get_attribute(
          $xml,
          'cfdi:Comprobante/@Serie'
        );
        $emisor = xpath_get_attribute(
          $xml,
          'cfdi:Comprobante/cfdi:Emisor/@Rfc'
        );
        return 'comp_' . $emisor . $serie . $folio;
        break;
      default:
        throw new Exception("No se puede generar una referencia con el XML enviado.");
        break;
    }
  }
}

if( !function_exists('fel_cancela'))
{
  /**
   * Cancela un comprobante ante el SAT
   * 
   * @param string $rfc_emisor  Quien es el que timbro y desea cancelar el comprobante
   * @param string $uuid        UUID del comprobante que se desea cancelar
   * @param string $pk          PrivateKey del emisor del comprobante
   * @param string pk_pass      Contraseña del la llave privada del emisor del comprobante
   */
  function fel_cancela($rfc_emisor, $uuid, $pk, $pk_pass)
  {
    $CI =& get_instance();
    $CI->load->helper('timbrado_helper');
    $credentials = get_configuracion_proveedor('FEL','comprobante');
    //var_dump($credentials['url']);
    $soap_options = array(
      'stream_context' => stream_context_create([
        'ssl' => [
          'allow_self_signed' => TRUE,
          'verify_peer' => FALSE
        ]
      ]),
      'exceptions' => TRUE,
      'trace' => FALSE
    );
    $regresa = array();
    $parameters = array(
      'usuario' => $credentials['user'],
      'password' => $credentials['password'],
      'rFCEmisor' => $rfc_emisor,
      'listaCFDI' => ['string' => [$uuid]],
      'clavePrivada_Base64' => $pk,
      'passwordClavePrivada' => $pk_pass
    );
    try{
      $result = call_soap($credentials['url'],'CancelarCFDI',$parameters,$soap_options);
      $resp = $result->CancelarCFDIResult;
      $regresa['OperacionExitosa'] = $resp->OperacionExitosa;
      $regresa['MensajeError'] = $resp->MensajeError;
      $regresa['MensajeErrorDetallado'] = $resp->MensajeErrorDetallado;
      $regresa['CodigoResultado'] = $resp->DetallesCancelacion->DetalleCancelacion->CodigoResultado;
      $regresa['MensajeResultado'] = $resp->DetallesCancelacion->DetalleCancelacion->MensajeResultado;

    } catch(Exception $ex)
    {
      $regresa['exception'] = $ex->getMessage();
    }
    return $regresa;
  }
}

if(!function_exists('fel_acuse_cancelacion'))
{
  /**
   * Recupera el acuse de cancelacion del comprobante especificado
   * 
   * @param string $uuid  El UUID del comprobante que se desea recuperar el acuse de cancelacion
   * 
   */
  function fel_acuse_cancelacion($uuid)
  {
    $CI =& get_instance();
    $CI->load->helper('timbrado_helper');
    $credentials = get_configuracion_proveedor('FEL', 'comprobante', 'production');
    $soap_options = array(
      'stream_context' => stream_context_create([
        'ssl' => [
          'allow_self_signed' => TRUE,
          'verify_peer' => FALSE
        ]
      ]),
      'exceptions' => TRUE,
      'trace' => FALSE
    );
    $parameters = array(
      'usuario' => $credentials['user'],
      'password' => $credentials['password'],
      'uUID' => $uuid
    );
    $regresa = array();
    try{
      $result = call_soap($credentials['url'],'ObtenerAcuseCancelacion',$parameters, $soap_options);
      $regresa['MensajeError'] = $result->ObtenerAcuseCancelacionResult->MensajeError;
      $regresa['MensajeErrorDetallado'] = $result->ObtenerAcuseCancelacionResult->MensajeErrorDetallado;
      $regresa['OperacionExitosa'] = $result->ObtenerAcuseCancelacionResult->OperacionExitosa;
      $regresa['XMLResultado'] = base64_encode($result->ObtenerAcuseCancelacionResult->XMLResultado);

    }
    catch (Exception $ex)
    {
      $regresa['exception'] = $ex->getMessage();
    }
    return $regresa;
  }
}