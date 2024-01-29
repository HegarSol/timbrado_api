<?php
/**
 * DFACTURE HELPER
 * 
 * @package CFDiAPI
 * @author Guadalupe Garza Moreno
 * @copyright Copytight (c) 2013-2018, HEGAR Soluciones en Sistemas (http://hegarss.com)
 * @link https://timbrado.hegarss.com/rest
 * @since Version 0.0.3
 */
 defined('BASEPATH') OR exit('No direct script access allowed');

 if ( ! function_exists('dfacture_realiza_timbrado'))
 {
   /**
    * Realiza el timbrado de un comprobante o una retencion
    * 
    * @param string $xml Archivo que se desea timbrar
    */
    function dfacture_realiza_timbrado($xml)
    {
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      $tipo = get_tipo_xml($xml);
      switch($tipo)
      {
        case 'comprobante':
          $response = dfacture_timbra_comprobante($xml);
        break;
        case 'retenciones':
          $response = dfacture_timbra_retenciones($xml);
        break;
        default:
          throw new Exception('El xml no es `Comprobante` o `Retenciones`');
      }
      $response['tipo_comprobante'] = $tipo;
      return $response;
    }
 }

 if( ! function_exists('dfacture_timbra_comprobante'))
 {
   /**
    * Realiza el timbrado de un comprobante de tipo `Comprobante`
    * 
    * @param string $xml Archivo que se desea timbrar
    */
    function dfacture_timbra_comprobante($xml)
    {
      $CI =& get_instance();
      $CI->load->helper('timbrado_helper');
      if(get_tipo_xml($xml) !== 'comprobante')
      {
        return NULL;
      }
      $credentials = get_configuracion_proveedor('DFACTURE', 'comprobante');
      $regresa = array();
      $soap_options = array(
        'trace' => FALSE,
        'exceptions' => TRUE
      );
      try
      {
        $parameters = array(
          'user' => $credentials['user'],
          'password' => $credentials['password'],
          'xml' => base64_encode($xml)
        );
        $result = call_soap($credentials['url'],'TimbrarCFDI33',$parameters, $soap_options);
        $result = (array) $result->TimbrarCFDI33Result;
        $regresa['OperacionExitosa'] = $result['validate'];
        $regresa['MensajeError'] = $result['mensaje'];
        $regresa['MensajeErrorDetallado'] = $result['mensajedetallado'];
        $regresa['CodigoRespuesta'] = $result['codigo'];
        $regresa['XmlResultado'] = base64_decode($result['xml']);
        $regresa['Timbre'] = $result['Timbre'];
      }
      catch(SoapFault $ex)
      {
        $regresa['exception'] = $ex->getMessage();
        log_message('error', $ex->getMessage());
      }
      catch (Exception $ex)
      {
        $regresa['exception'] = $ex->getMessage();
        log_message('error',$ex->getMessage());
      }
      return $regresa;
    }
 }

 if( !function_exists('dfacture_timbra_retenciones'))
 {
   /**
    * Realiza el timbrado de un comprobante de tipo `Retenciones`
    *
    * @param string $xml Archivo que se desea timbrar
    */
    function dfacture_timbra_retenciones($xml)
    {
      $CI =& get_instance();
      if( get_tipo_xml($xml) !== 'retenciones')
      {
        return NULL;
      }
      $credentials = get_configuracion_proveedor('DFACTURE', 'retenciones');
      $regresa = array();
      $soap_options = array(
        'trace' => FALSE,
        'exceptions' => TRUE
      );
      try
      {
        $parameters = array(
          'user' => $credentials['user'],
          'password' => $credentials['password'],
          'xml' => base64_encode($xml)
        );
        $result = call_soap($credentials['url'],'TimbrarCFDI33',$parameters, $soap_options);
        $result = (array) $result->TimbrarCFDI33Result;
        $regresa['OperacionExitosa'] = $result['validate'];
        $regresa['MensajeError'] = $result['mensaje'];
        $regresa['MensajeErrorDetallado'] = $result['mensajedetallado'];
        $regresa['CodigoRespuesta'] = $result['codigo'];
        $regresa['XmlResultado'] = base64_decode($result['xml']);
        $regresa['Timbre'] = $result['Timbre'];
      }
      catch(SoapFault $ex)
      {
        $regresa['exception'] = $ex->getMessage();
        log_message('error', $ex->getMessage());
      }
      catch (Exception $ex)
      {
        $regresa['exception'] = $ex->getMessage();
        log_message('error',$ex->getMessage());
      }
      return $regresa;
    }
 }

 /* End of file dfacture_helper.php */
 