<?php

defined('BASEPATH') OR exit('No direct script access allowed');

include('SWSDK.php');
use SWServices\Stamp\StampService as StampService;
use SWServices\Cancelation\CancelationService as CancelationService;

if( !function_exists('get_tipo_xml')){

  /**
   * Determina si el tipo del XML es Comprobante o Retenciones
   *
   * @param string $xml   Xml del cual se requiere conocer el tipo
   * @return mixed FALSE si no es valido, string con el tipo de comprobante
   */
  function get_tipo_xml($xml){
    if(!$xml){
      return FALSE;

    }
    $doc = new DOMDocument();
    $doc->loadXML($xml);
    $comprobante = $doc->getElementsByTagName('Comprobante');
    if($comprobante->length > 0){
      return 'comprobante';
    }
    $retenciones = $doc->getElementsByTagName('Retenciones');
    if($retenciones->length > 0){
      return 'retenciones';
    }
    return FALSE;
  }
}

if( !function_exists('pac_en_contingencia')){
  /**
   * Determina si el PAC que se requiere usar se encuentra con fallas
   *
   * @param int $id_pac  Id del PAC a verificar
   */
  function pac_en_contingencia($id_pac){
    $CI =& get_instance();
    $CI->load->model('MdlProveedores');
    return $CI->MdlProveedores->en_contingencia($id_pac) == 1 ? TRUE: FALSE;

  }
}

if( !function_exists('get_pac_by_cliente')){
  /**
   * Obtiene el PAC a utilizar por el cliente que esta solicitando el timbrado
   *
   * @param string $clave  Clave del cliente
   * @return int ID del PAC a utilizar
   */
  function get_pac_by_cliente($clave){
    $CI =& get_instance();
    $CI->load->model('MdlClientes');
    $id_pac = $CI->MdlClientes->get_id_pac($clave);
    if(pac_en_contingencia($id_pac))
    {
      $id_pac = get_pac_disponible();
    }
    return $id_pac;
  }
}

if( !function_exists('get_pac_disponible')){
  /**
   * Regresa el ID del PAC que se encuentre disponible.
   *
   * @return int Id del PAC disponible para realizar el timbrado
   */
  function get_pac_disponible(){
    $CI =& get_instance();
    $CI->load->model('MdlProveedores');
    $disponible = $CI->MdlProveedores->get_disponible();
    if(!$disponible){
      throw new Exception('HGTM0008');
    }
    return $disponible->id;
  }

}

if( !function_exists('timbra_credito')){
  /**
   * Este metodo es para cuando el cliente tiene habilitado un paquete que es
   * de credito y no requiere la activacion de algun paquete
   *
   * @param string $clave_cliente El cliente que quiere realizar el timbrado
   * @param string $xml Es el XML que se quire timbrar
   */
  function timbra_credito($clave_cliente, $xml){
    $id_pac = get_pac_by_cliente($clave_cliente);
    $timbre = realiza_timbrado($clave_cliente, $id_pac, $xml);
    //var_dump($timbre['OperacionExitosa']);
    if (!$timbre){
      return NULL;
    }
    if($timbre['OperacionExitosa']){
      almacena_xml($clave_cliente, $timbre);
    } else
    {
      almacena_xml_error($clave_cliente, $timbre);
      if(existe_local($timbre) === FALSE)
      {
        almacena_xml($clave_cliente, $timbre);
      }
    }
     return $timbre;
  }
}
if(!function_exists('timbra_creditoP')){
  function timbra_creditoP($clave_cliente,$xml,$pruebas)
  {
    $id_pac = get_pac_by_cliente($clave_cliente);
    $timbre = realiza_timbradoP($clave_cliente, $id_pac, $xml, $pruebas);
   // var_dump($timbre);
    if (!$timbre){
      return NULL;
    }
    if($timbre['OperacionExitosa']){
      
      almacena_xml($clave_cliente, $timbre);
    } else
    {
      
      almacena_xml_error($clave_cliente, $timbre);
      if(existe_local($timbre) === FALSE)
      {
        
        almacena_xml($clave_cliente, $timbre);
      }
    }
    return $timbre;
  }
}

if(!function_exists('timbra_prepagoP'))
{
  function timbra_prepagoP($clave_cliente, $xml, $id_pac = NULL)
  {
    $CI =& get_instance();
    $CI->load->model('MdlPaquetesClientes');
    $id_pac = get_pac_by_cliente($clave_cliente);
    $paquete = obtener_paquete_activo($clave_cliente, $id_pac);
    if(!is_null($id_pac)) // Revisa que todo este bien con el PAC especificado
    {
      if(pac_en_contingencia($id_pac))
      {
        throw new Exception('HGTM0010'); // PAC en contingencia
      }
      if(is_null($paquete))
      {
        throw new Exception('HGTM0011'); // No tiene paquete asignado
      }
    }
    if($id_pac === NULL)
    {
      $id_pac = get_pac_by_cliente($clave_cliente);
    }
    $timbre = realiza_timbradoP($clave_cliente, $id_pac, $xml , 1);
    if(!$timbre)
    {
      return NULL;
    }
    if(isset($timbre['exception']))
    {
      throw new Exception($timbre['exception']);
    }
    if($timbre['OperacionExitosa'])
    {
      almacena_xml($clave_cliente, $timbre);
      $CI->MdlPaquetesClientes->resta_cantidad_by_id($paquete->id);
    }
    else
    {
      almacena_xml_error($clave_cliente, $timbre);
      if(existe_local($timbre) === FALSE)
      {
        almacena_xml($clave_cliente, $timbre);
        $CI->MdlPaquetesClietnes->resta_cantidad_by_id($paquete->id);
      }
    }
    $timbre['Paquete'] = $CI->MdlPaquetesClientes->get_by_id($paquete->id);
    return $timbre;
  }
}
if(!function_exists('timbra_prepago'))
{
  /**
   * Timbra un comprobante realizando el restado del timbre en el paquete
   * que se usa
   *
   * @param string $clave_cliente Cliente que desea realizar el timbrado
   * @param string $xml XML que se desea timbrar
   * @param string $id_pac Si se pide un PAC en especifico
   */
  function timbra_prepago($clave_cliente, $xml, $id_pac = NULL)
  {
    $CI =& get_instance();
    $CI->load->model('MdlPaquetesClientes');
    $id_pac = get_pac_by_cliente($clave_cliente);
    $paquete = obtener_paquete_activo($clave_cliente, $id_pac);
    if(!is_null($id_pac)) // Revisa que todo este bien con el PAC especificado
    {
      if(pac_en_contingencia($id_pac))
      {
        throw new Exception('HGTM0010'); // PAC en contingencia
      }
      if(is_null($paquete))
      {
        throw new Exception('HGTM0011'); // No tiene paquete asignado
      }
    }
    if($id_pac === NULL)
    {
      $id_pac = get_pac_by_cliente($clave_cliente);
    }
    $timbre = realiza_timbrado($clave_cliente, $id_pac, $xml);
    if(!$timbre)
    {
      return NULL;
    }
    if(isset($timbre['exception']))
    {
      throw new Exception($timbre['exception']);
    }
    if($timbre['OperacionExitosa'])
    {
      almacena_xml($clave_cliente, $timbre);
      $CI->MdlPaquetesClientes->resta_cantidad_by_id($paquete->id);
    }
    else
    {
      almacena_xml_error($clave_cliente, $timbre);
      if(existe_local($timbre) === FALSE)
      {
        almacena_xml($clave_cliente, $timbre);
        $CI->MdlPaquetesClietnes->resta_cantidad_by_id($paquete->id);
      }
    }
    $timbre['Paquete'] = $CI->MdlPaquetesClientes->get_by_id($paquete->id);
    return $timbre;
  }
}

if(!function_exists('obtener_paquete_activo'))
{
  /**
   * Obtiene el paquete disponible para usar en el timbrado
   *
   * @param string $clave_cliente Cliente del cual se desea obtener el paquete que se encuentra activo
   * @param string $id_pac Si se trata de usar un PAC en especifico
   *
   * @return object Resultado del Paquete obtenido
   */
  function obtener_paquete_activo($clave_cliente, $id_pac = NULL)
  {
    $CI =& get_instance();
    $CI->load->model('MdlPaquetesClientes');
    $paquete = $CI->MdlPaquetesClientes->get_paquete_activo($clave_cliente, $id_pac);
    return $paquete;
  }
}

if( !function_exists('realiza_timbrado')){
  /**
   * Manda llamar la funcion de timbrado dependiendo del PAC seleccionado
   *
   * @param string $clave_cliente Clave del cliente que realiza el timbrado
   * @param int $id_pac ID del PAC para timbrar el comprobante
   * @param string $xml XML que se desea timbrar
   * @return array Contenido del TimbreFiscalDigital
   * @throws Exception
   */
  function realiza_timbrado($clave_cliente, $id_pac, $xml){
    $CI =& get_instance();
    switch($id_pac){
      case 'FEL':
        $CI->load->helper('fel_helper');
        $timbre = fel_realiza_timbrado($xml);
        $timbre['id_pac'] = 'FEL';
        return $timbre;
        break;
      case 'SW':
        $CI->load->helper('sw_helper');
        $timbre = sw_realiza_timbrado($xml);
        $timbre['id_pac'] = 'SW';
        return $timbre;
        break;
      case 'DFACTURE':
        $CI->load->helper('dfacture_helper');
        $timbre = dfacture_realiza_timbrado($xml);
        $timbre['id_pac'] = 'DFACTURE';
        return $timbre;
        return NULL;
      break;
    }
    return NULL;
  }
}
if(!function_exists('realiza_timbradoP')){
   function realiza_timbradoP($clave_cliente, $id_pac, $xml, $pruebas){
      $CI =& get_instance();
      switch($id_pac){
        case 'FEL':
          $CI->load->helper('fel_helper');
          $timbre = fel_realiza_timbrado($xml);
          $timbre['id_pac'] = 'FEL';
          return $timbre;
          break;
        case 'SW':
          $CI->load->helper('sw_helper');
          $timbre = sw_realiza_timbradoP($xml, $pruebas);
          $timbre['id_pac'] = 'SW';
          return $timbre;
          break;
        case 'DFACTURE':
          $CI->load->helper('dfacture_helper');
          $timbre = dfacture_realiza_timbrado($xml);
          $timbre['id_pac'] = 'DFACTURE';
          return $timbre;
          return NULL;
        break;
      }
      return NULL;
   }
}

if(!function_exists('existe_local'))
{
  /**
   * Revisa que el comprobante ya se encuentra registrado en la base de datos local o no
   *
   * @param array $timbre es el resultado de la operacion de timbrado
   */
  function existe_local($timbre)
  {

    if($timbre['Timbre']->tipo == 'Retencion')
    {
        if(!isset($timbre['Timbre']) OR !isset($timbre['Timbre']->UUID))
        {
          return NULL;
        }
        $CI =& get_instance();
        $CI->load->model('MdlTimbrado');
        $local = $CI->MdlTimbrado->get_by_uuid($timbre['Timbre']->UUID);
        return is_null($local) ? FALSE : TRUE;
    }
    else
    {
        if(!isset($timbre['Timbre']) OR !isset($timbre['Timbre']['UUID']))
        {
          return NULL;
        }
        $CI =& get_instance();
        $CI->load->model('MdlTimbrado');
        $local = $CI->MdlTimbrado->get_by_uuid($timbre['Timbre']['UUID']);
        return is_null($local) ? FALSE : TRUE;
    }
  }
}

if( !function_exists('execute_xpath_query')){
  /**
   * Ejecuta un QUERY XPATH en el XML proporcionado y retorna el resultado
   *
   * @param string $xml El XML sobre el cual se desea ejecutar el Query
   * @param string $query Es el query que se desea ejecutar
   * @return DOMNodeList
   */
  function execute_xpath_query($xml, $query){
    $doc = new DOMDocument();
    if(empty($xml)){
      return NULL;
    }
    $doc->loadXML($xml);
    $xpath = new DOMXPath($doc);
    $xpath->registerNamespace('cfdi', 'http://www.sat.gob.mx/cfd/4');
    $xpath->registerNamespace('retenciones','http://www.sat.gob.mx/esquemas/retencionpago/2');
    $resultado = $xpath->query($query, $doc);
    
    return $resultado;
  }
}

if( !function_exists('xpath_get_attribute')){
  /**
   * Devuelve el valor del atributo requerido
   *
   * @param string $xml El xml para ejecutar el Query
   * @param string $query La consulta a ejecutar
   * @return mixed El valor encontrado o FALSE si no encontro nada
   */
  function xpath_get_attribute($xml, $query){
    $list = execute_xpath_query($xml, $query);
    if($list == FALSE){
      return '';
    }
    if($list->length < 1){
      return '';
    }
    return $list->item(0)->nodeValue;
  }
}

if(!function_exists('call_soap'))
{
  /**
   * Hace un llamado a un webservice Soap
   *
   * @param string $url Url del webservice
   * @param string $function Funcion que se va a ejecutar
   * @param array $params Parametros a enviar al webservice
   * @param array $options Opciones al crear el cliente Soap
   * @return object El resultado de la llamada al web service
   * @throws SoapFault
   */
  function call_soap($url, $function, $params, $options = NULL)
  {
    $soap = new SoapClient($url, $options);
    $result = $soap->$function($params);
    if(is_soap_fault($result)){
      throw new Exception('Error en la conexión con el WebService de FEL');
    }
    return $result;
  }
}

if(!function_exists('almacena_xml_error'))
{
  /**
   * Almacena un XML con error para futuras referencias y costos extras
   *
   * @param array $timbre Array devuelto por el intento de timbrado con algun PAC
   *
   */
  function almacena_xml_error($clave_cliente, $timbre)
  {
    $CI =& get_instance();
    $CI->load->model('MdlLogError');
    $CI->MdlLogError->create(
      $clave_cliente,
      $timbre['id_pac'],
      $timbre['CodigoRespuesta'],
      $timbre['MensajeError'],
      is_null($timbre['MensajeErrorDetallado']) ? '' : $timbre['MensajeErrorDetallado']
    );
  }
}

if (!function_exists('almacena_xml'))
{
  /**
   * Almacena el timbre obtenido en la base de datos
   *
   * @param string $clave_cliente El cliente que realiza el timbrado
   * @param array $timbre Array del timbre fiscal retornado por alguna funcion de timbrado
   */
  function almacena_xml($clave_cliente, $timbre)
  {
    //var_dump($timbre['Timbre']->FechaTimbrado);
    if($timbre['id_pac'] == 'SW')
    {
        $CI =& get_instance();
        $CI->load->model('MdlTimbrado');
        $xml = base64_decode($timbre['XmlResultado']);

        $checar = get_tipo_xml($xml);
        if($checar == 'comprobante')
        {
            $data['emisor'] = xpath_get_attribute($xml, 'cfdi:Comprobante/cfdi:Emisor/@Rfc');
            $data['receptor'] = xpath_get_attribute($xml, 'cfdi:Comprobante/cfdi:Receptor/@Rfc');
            $data['serie'] = xpath_get_attribute($xml, 'cfdi:Comprobante/@Serie');
            $data['folio'] = xpath_get_attribute($xml, 'cfdi:Comprobante/@Folio');
            $data['fecha'] = xpath_get_attribute($xml, 'cfdi:Comprobante/@Fecha');
            $data['fecha_timbrado'] = $timbre['Timbre']->fechaTimbrado;
            $data['id_pac'] = $timbre['id_pac'];
            $data['uuid'] = $timbre['Timbre']->uuid;
            $data['path'] = almacena_xml_path($clave_cliente, $data, $xml);
            $CI->MdlTimbrado->create($data);
            return $data['path'];
        }
        else
        {

          $dom = new DOMDocument('1.0','utf-8');
          $dom->loadXML($xml);

          foreach($dom->getElementsByTagNameNS('http://www.sat.gob.mx/TimbreFiscalDigital','*') as $elemento)
          {
            $uuid = $elemento->getAttribute('UUID');
            $fecha_timbrado = $elemento->getAttribute('FechaTimbrado');
          }

           $data['emisor'] = xpath_get_attribute($xml, 'retenciones:Retenciones/retenciones:Emisor/@RFCEmisor');
           $data['receptor'] = xpath_get_attribute($xml, 'retenciones:Retenciones/retenciones:Receptor/retenciones:Nacional/@RFCRecep');
           $data['serie'] = '';
           $data['folio'] = xpath_get_attribute($xml, 'retenciones:Retenciones/@FolioInt');
           $data['fecha'] = xpath_get_attribute($xml, 'retenciones:Retenciones/@FechaExp');
           $data['fecha_timbrado'] = $fecha_timbrado;
           $data['id_pac'] = $timbre['id_pac'];
           $data['uuid'] = $uuid;
           $data['path'] = almacena_xml_path($clave_cliente, $data, $xml);
           
           $CI->MdlTimbrado->create($data);
           return $data['path'];
        }
    }
    else
    {
        $CI =& get_instance();
        $CI->load->model('MdlTimbrado');
        $xml = base64_decode($timbre['XmlResultado']);
        $data['emisor'] = xpath_get_attribute($xml, 'cfdi:Comprobante/cfdi:Emisor/@Rfc');
        $data['receptor'] = xpath_get_attribute($xml, 'cfdi:Comprobante/cfdi:Receptor/@Rfc');
        $data['serie'] = xpath_get_attribute($xml, 'cfdi:Comprobante/@Serie');
        $data['folio'] = xpath_get_attribute($xml, 'cfdi:Comprobante/@Folio');
        $data['fecha'] = xpath_get_attribute($xml, 'cfdi:Comprobante/@Fecha');
        $data['fecha_timbrado'] = $timbre['Timbre']->FechaTimbrado;
        $data['id_pac'] = $timbre['id_pac'];
        $data['uuid'] = $timbre['Timbre']->UUID;
        $data['path'] = almacena_xml_path($clave_cliente, $data, $xml);
        $CI->MdlTimbrado->create($data);
        return $data['path'];
    }
    
  }
}

if(!function_exists('almacena_xml_path'))
{
  /**
   * Almacena el timbrado en la carpeta configurada
   *
   * @param string $clave_cliente Cliente que realiza el timbrado
   * @param array $data Array de la informacion que se inserta en la base de datos
   * @param string $xml Archivo XML a almacenar en el disco
   */
  function almacena_xml_path($clave_cliente, $data, $xml)
  {
    $CI =& get_instance();
    $CI->config->load('hegarss_api', TRUE);
    $base_path = $CI->config->item('xml_base_path', 'hegarss_api');
    $full_path = $base_path . $clave_cliente . DIRECTORY_SEPARATOR .
      $data['emisor'] . DIRECTORY_SEPARATOR . $data['receptor'] . DIRECTORY_SEPARATOR;
    $file_name = $data['id_pac'] . '_' . $data['uuid'] . '.xml';

    if(!file_exists($full_path))
    {
      mkdir($full_path,0777,TRUE);
    }

    file_put_contents($full_path . $file_name, $xml);
    return $full_path . $file_name;
  }
}

if(!function_exists('get_configuracion_proveedor'))
{
  /**
   * Obtiene la configuracion de un proveedor almacenado en la base de datos local para
   * para poder acceder al servicio proporcionado
   *
   * @param string $id_pac Identificador del proveedor del cual se quiere recuperar la informacion
   * @param string $tipo Si se requiere para un Comprobante o Retencion
   */
  function get_configuracion_proveedor($id_pac, $tipo, $env = NULL)
  {
    $CI =& get_instance();
    $CI->load->model('MdlProveedores');
    $proveedor = $CI->MdlProveedores->get_by_id($id_pac);
    if($proveedor === NULL)
    {
      return NULL;
    }
    if(empty($env))
    {
      $env = ENVIRONMENT;
    }
    switch($tipo)
    {
      case 'comprobante':
        $url = $env == 'production' ? $proveedor->url_comprobantes : $proveedor->test_url_comprobante;
      break;
      case 'retenciones':
        $url = $env == 'production' ? $proveedor->url_retenciones : $proveedor->test_url_retenciones;
      break;
      default:
        $url = '';
      break;
    }
    $user = $env == 'production' ? $proveedor->user : $proveedor->test_user;
    $password = $env == 'production' ? $proveedor->password : $proveedor->test_password;
    $CI->load->library('encryption');
    return array(
      'url' => $url,
      'user' => $CI->encryption->decrypt($user),
      'password' => $CI->encryption->decrypt($password)
    );
  }
}
if(!function_exists('get_configuracion_proveedorP'))
{
   function get_configuracion_proveedorP($id_pac, $tipo, $pruebas)
   {
    $CI =& get_instance();
    $CI->load->model('MdlProveedores');
    $proveedor = $CI->MdlProveedores->get_by_id($id_pac);

    switch($tipo)
    {
      case 'comprobante':
        $url =  $proveedor->test_url_comprobante;
      break;
      case 'retenciones':
        $url =  $proveedor->test_url_retenciones;
      break;
      default:
        $url = '';
      break;
    }
    $user =  $proveedor->test_user;
    $password =  $proveedor->test_password;
    $CI->load->library('encryption');
    return array(
      'url' => $url,
      'user' => $CI->encryption->decrypt($user),
      'password' => $CI->encryption->decrypt($password)
    );
   }
}


if(!function_exists('_send_error'))
{
  /**
   * Envia un mensaje de error a la respuesta del controllador de REST_Controller
   *
   * @param string $codigo
   */
  function _send_error($codigo)
  {
    $CI =& get_instance();
    $CI->load->model('MdlErrores');
    if(preg_match('/HG(SR|TM|GN)[0-9]{4}/', $codigo))
    {
      $error = $CI->MdlErrores->get_by_codigo($codigo);
    }
    else
    {
      $error = (object) array(
        'error' => 'Error no catalogado',
        'codigo' => 'HGGN0001',
        'extendido' => $codigo,
        'solucion' => 'Reportar el problema con el proveedor',
        'codigo_http' => 400
      );
    }
    $CI->response([
      'status' => FALSE,
      'error' => $error->error,
      'codigo' => $error->codigo,
      'extendido' => $error->extendido,
      'solucion' => $error->solucion
    ], $error->codigo_http == 0 ? 400 : $error->codigo_http);
  }
}
