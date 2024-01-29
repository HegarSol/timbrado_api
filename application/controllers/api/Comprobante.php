<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/libraries/REST_Controller.php';

include('SWSDK.php');
use SWServices\Stamp\StampService as StampService;
use SWServices\Cancelation\CancelationService as CancelationService;


class Comprobante extends REST_Controller {

  public function __construct(){
    parent::__construct();
    $this->load->helper('timbrado_helper');
    $this->load->model('MdlClientes');
    $this->load->model('MdlPaquetes');
    $this->load->model('MdlPaquetesClientes');
    $this->load->model('MdlErrores');
    $this->load->helper('sw_helper');

  }

  /**
   * POST
   *
   * Crea el timbre fiscal del comprobante que se envie
   *
   * @param string clave Es la clave con la que se identifica al cliente
   * @param string xmlBase64    Es el XML que se desea timbrar en base64
   * @param int id_pac Es el ID del PAC con el que se desea timbrar
   */
  public function index_post()
  {
    $clave = $this->post('clave');
    $xml = $this->post('xmlBase64');
//    $id_pac = $this->post('id_pac');

    $inde =  $this->post('identificar');

    $IDENTIFICADOR = $clave . $inde;

    if(!$xml) {
      _send_error('HGTM0001');
    }
    if(!$clave){
      _send_error('HGTM0005');
    }
    $cliente = $this->MdlClientes->get_by_clave($IDENTIFICADOR);
    if (!$cliente) {
      _send_error('HGTM0002');
    }
    if(!$cliente->activo){
      _send_error('HGTM0003');
    }
    $xml = base64_decode($xml);
    //verificamos si el cliente es de pruebas
    if($cliente->pruebas == 1)
    {
        //buscamos el paquete que tiene asignado el cliente
        $paquete = $this->MdlPaquetes->get_by_id($cliente->id_paquete);
        if($paquete->credito == null){
          try
          {
             $timbre = timbra_prepagoP($cliente->clave, $xml, $cliente->id_pac);
            $this->response(['status' => TRUE, 'data' => $timbre], REST_Controller::HTTP_OK);
          } catch(Exception $ex)
          {
            _send_error($ex->getMessage());
          }
          // Buscamos el paquete activado vigente mas antiguo
          $paquete_activo = $this->MdlPaquetesClientes->get_paquete_activo($clave);
          if(!$paquete_activo){
            _send_error('HGTM0004');
          }
        } else {
         // var_dump('es credito');
          try{
            $timbre = timbra_creditoP($cliente->clave, $xml, 1);
            $this->response(['status' => TRUE, 'data' =>$timbre], REST_Controller::HTTP_OK);
          } catch(Exception $ex){
            _send_error($ex->getMessage());
          }
        }
    }
    else
    {
        //buscamos el paquete que tiene asignado el cliente
        $paquete = $this->MdlPaquetes->get_by_id($cliente->id_paquete);
    
        if($paquete->credito == null){
          try
          {
             $timbre = timbra_prepago($cliente->clave, $xml, $id_pac);
            $this->response(['status' => TRUE, 'data' => $timbre], REST_Controller::HTTP_OK);
          } catch(Exception $ex)
          {
            _send_error($ex->getMessage());
          }
          // Buscamos el paquete activado vigente mas antiguo
          $paquete_activo = $this->MdlPaquetesClientes->get_paquete_activo($clave);
          if(!$paquete_activo){
            _send_error('HGTM0004');
          }
        } else {
         // var_dump('es credito');
          try{
            $timbre = timbra_credito($cliente->clave, $xml);
            $this->response(['status' => TRUE, 'data' =>$timbre], REST_Controller::HTTP_OK);
          } catch(Exception $ex){
            _send_error($ex->getMessage());
          }
        }
    }
  }

  /**
   * Se cambia el metodo DELETE para hacer referencia a la cancelacion del comprobante
   *
   * @param string $clave Es la clave del cliente
   * @param string $uuid  Es el UUID que se desea cancelar
   * @param string $pfx   Es la llave
   * @param string $receptor Es ha quien se le hace la factura
   * @param string $total  Es el total de la factura
   */
  public function index_delete()
  {

     $clave_cliente = $this->query('clave');
     $uuid = $this->query('uuid');
     $pk = $this->query('pfx');
     $pk_pass = $this->query('password');
     $receptor = $this->query('receptor');
     $total = $this->query('total');

     $moti = $this->query('motivo');
     $foliosus = $this->query('folioSustitucion');

     $certi = $this->query('fileCert');
     $tipo = $this->query('tipo');
     $llave = $this->query('fileKey');

     $inde =  $this->query('identificar');

    $IDENTIFICADOR = $clave_cliente . $inde;

   $this->load->helper('fel_helper');
   $this->load->helper('sw_helper');
    $cliente = $this->MdlClientes->get_by_clave($IDENTIFICADOR);
  

    if($cliente->id_pac == 'SW')
    {

      if($cliente->pruebas == 1)
      {
        if(!$cliente)
        {
           _send_error('HGTM0002');
        }
        if(!$cliente->activo)
        {
           _send_error('HGTM0003');
        }
        // if(!$receptor)
        // {
        //    _send_error('HGTM0012');
        // }
        // if(!$total)
        // {
        //    _send_error('HGTM0013');
        // }
        if($tipo == 'RET')
        {
       //   $xml = base64_decode($xml);
           $respuesta = sw_cancelaRP($certi,$uuid,$clave_cliente,$llave,$pk_pass,$moti);

           $cancelado = ($respuesta['CodigoResultado'] == '1201' OR $respuesta['CodigoResultado'] == '1202' OR $respuesta['CodigoResultado'] == '1200' );
           $return = array(
               'status' => $cancelado,
               'data' => $respuesta
           );
        }
        else
        {
           $respuesta = sw_cancelaP($uuid,$pk_pass,$cliente->rfc,$pk,$receptor,$total,$moti,$foliosus);
 
           $cancelado = ($respuesta['CodigoResultado'] == '201' OR $respuesta['CodigoResultado'] == '202' OR $respuesta['CodigoResultado'] == '200');
            $return = array(
                'status' => $cancelado,
                'data' => $respuesta
            );
        }

       
        if($cancelado == TRUE)
        {
           $this->response($return, REST_Controller::HTTP_OK);
        }
        else
        {
           $this->response($return, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
      else
      {  
        if(!$cliente)
        {
           _send_error('HGTM0002');
        }
        if(!$cliente->activo)
        {
           _send_error('HGTM0003');
        }
        // if(!$receptor)
        // {
        //    _send_error('HGTM0012');
        // }
        // if(!$total)
        // {
        //    _send_error('HGTM0013');
        // }

        if($tipo == 'RET')
        {
            //  $xml = base64_decode($xml);
            $respuesta = sw_cancelaR($certi,$uuid,$clave_cliente,$llave,$pk_pass,$moti);

            $cancelado = ($respuesta['CodigoResultado'] == '1201' OR $respuesta['CodigoResultado'] == '1202' OR $respuesta['CodigoResultado'] == '1200');
            $return = array(
              'status' => $cancelado,
              'data' => $respuesta
            );
        }
        else
        {
            $respuesta = sw_cancela($uuid,$pk_pass,$cliente->rfc,$pk,$receptor,$total,$moti,$foliosus);
    
            $cancelado = ($respuesta['CodigoResultado'] == '201' OR $respuesta['CodigoResultado'] == '202' OR $respuesta['CodigoResultado'] == '200');
            $return = array(
                'status' => $cancelado,
                'data' => $respuesta
            );
        }
        
        if($cancelado == TRUE)
        {
           $this->response($return, REST_Controller::HTTP_OK);
        }
        else
        {
           $this->response($return, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
      }
    }
    else
    {
    
        if(!$cliente)
        {
          _send_error('HGTM0002');
        }
        if(!$cliente->activo)
        {
          _send_error('HGTM0003');
        }
        $respuesta = fel_cancela($cliente->rfc, $uuid, $pk, $pk_pass);
        //var_dump($respuesta);
        $cancelado = ($respuesta['CodigoResultado'] == '201' OR $respuesta['CodigoResultado'] == '202' OR $respuesta['CodigoResultado'] == '204');
        $return = array(
          'status' => $cancelado,
          'data' => $respuesta
        );
        if($cancelado == TRUE)
        {

          $this->response($return, REST_Controller::HTTP_OK);
        }
        else
        {
          $this->response($return, REST_Controller::HTTP_INTERNAL_SERVER_ERROR);
        }
     }
    
  }

  /**
   * Obtener el acuse de cancelacion del comprobante
   *
   * @param string $uuid UUID del comprobante que se desea recuperar el acuse de cancelacion
   */
  public function acuse_cancelacion_get()
  {
    $uuid = $this->get('uuid');
    $this->load->helper('fel_helper');
    $result = fel_acuse_cancelacion($uuid);
    if(isset($result['exception']))
    {
      // Ocurrio un error y lo reportamos
      _send_error($result['exception']);
    }
    $response = array(
      'status' => TRUE,
      'data' => $result
    );
    $this->response($response);
  }

    public function datos_cliente_post()
    {
       $cliente = $this->post('cliente');
       $rfclluvia = $this->post('rfc');

       $datos = $this->MdlClientes->obtenertable($cliente,$rfclluvia);
       $this->response(['status' => TRUE, 'data' => $datos], REST_Controller::HTTP_OK);
       
    }

}
