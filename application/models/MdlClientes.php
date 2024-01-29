<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlClientes extends CI_Model {

  public function get_by_clave($clave){
    return $this->db->from('clientes')
    ->where('clave', $clave)
    ->get()
    ->row();
  }

  public function get_id_pac($clave){
    $cliente = $this->db->select(['id_pac'])
    ->from('clientes')
    ->where('clave', $clave)
    ->get()
    ->row();
    if($cliente){
      return $cliente->id_pac;
    }
    return NULL;
  }
  public function get_all($id)
  {
    $this->db->select('*');
    $this->db->from('clientes');
    $this->db->join('users','clientes.id_user = users.id');
    if($this->session->userdata('filtro'))
     {
         $this->db->where('id_user',$id);
     }
    $query = $this->db->get();
      return $query->result();
  }
  public function vencer()
  {
     if($this->session->userdata('filtro') == '')
     {
        $query = $this->db->query("SELECT * FROM paquetes_clientes INNER JOIN clientes ON paquetes_clientes.clave_cliente = clientes.rfc WHERE DATEDIFF(fecha_vence, DATE(NOW())) BETWEEN 0 AND 15 AND cantidad > 0");
        return $query->result();
     }
     else
     {
        $id = $this->session->userdata('id');
        $query = $this->db->query("SELECT * FROM paquetes_clientes INNER JOIN clientes ON paquetes_clientes.clave_cliente = clientes.rfc WHERE DATEDIFF(fecha_vence, DATE(NOW())) BETWEEN 0 AND 15 AND cantidad > 0 AND clientes.id_user = $id");
        return $query->result();
     }     
  }
  public function terminar()
  {
     if($this->session->userdata('filtro') == '')
     {
        $query = $this->db->query("SELECT * FROM clientes INNER JOIN (SELECT clave_cliente, SUM(cantidad) as cantidad, fecha_vence, referencia_compra FROM paquetes_clientes WHERE cantidad != 0 GROUP BY clave_cliente) AS  paquetes_clientes ON clientes.rfc = paquetes_clientes.clave_cliente WHERE Notificar > 0 AND paquetes_clientes.cantidad <= clientes.Notificar AND paquetes_clientes.cantidad > 0");
        return $query->result();
     }
     else
     {
       $id = $this->session->userdata('id');
       $query = $this->db->query("SELECT * FROM clientes INNER JOIN (SELECT clave_cliente, SUM(cantidad) as cantidad, fecha_vence, referencia_compra FROM paquetes_clientes WHERE cantidad != 0 GROUP BY clave_cliente) AS  paquetes_clientes ON clientes.rfc = paquetes_clientes.clave_cliente WHERE Notificar > 0 AND paquetes_clientes.cantidad <= clientes.Notificar AND paquetes_clientes.cantidad > 0 AND clientes.id_user = $id ");
        return $query->result();
     }
     
  }
  public function factura()
  {
     $query = $this->db->query("SELECT *, paquetes_clientes.id as paquete_id FROM paquetes_clientes INNER JOIN clientes ON paquetes_clientes.clave_cliente = clientes.clave INNER JOIN users ON users.id = clientes.id_user WHERE clientes.id_paquete = 0 AND uuid_factura = '' OR uuid_factura IS NULL ORDER BY referencia_compra Desc");
     return $query->result();
  }
  public function editafactura($id,$fac)
  {
      $datos =  array('uuid_factura' => $fac);
      $this->db->where('id',$id);
      $this->db->update('paquetes_clientes',$datos);
  }
  public function get_all_paquete($id)
  {
     $this->db->select('*');
     $this->db->from('paquetes');
     $this->db->where('id_user',$id);
     $res = $this->db->get();
     return $res->result_array();
  }
  public function elimina($id)
  {
   $this->db->where('id',$id);
   $this->db->delete('paquetes_clientes');
  }
  public function getall($id)
  {
     $this->db->select('*');
     $this->db->from('clientes');
     if($this->session->userdata('filtro'))
     {
         $this->db->where('id_user',$id);
     }
     $res = $this->db->get();
     return $res->num_rows();
  }
  public function edittimbre($id)
  {
     $this->db->select('*');
     $this->db->from('paquetes_clientes');
     $this->db->where('id',$id);
     $res = $this->db->get();
     return $res->result_array();
  }
  public function updatetimbres($resta,$com,$id)
  {
     $datos = array('cantidad' => $resta, 'cantidad_comprada' => $com);
     $this->db->where('id',$id);
     $this->db->update('paquetes_clientes',$datos);
  }
  public function get_fecha($id)
  {
    $this->db->select('*');
    $this->db->from('paquetes_clientes');
    $this->db->where('id',$id);
    $res = $this->db->get();
    return $res->result_array();
  }
  public function get_clien_paquete($rfc,$iden)
  {
     $this->db->select('*, SUM(cantidad) as resta');
     $this->db->from('clientes');
     $this->db->join('paquetes_clientes','paquetes_clientes.clave_cliente = clientes.clave');
     $this->db->where('fecha_vence >',date('Y-m-d'));
     $this->db->where('clave',$iden);
     $this->db->where('rfc',$rfc);
     $res = $this->db->get();
     return $res->result_array();
     
  }
  public function get_paquete($cla)
  {
     $this->db->select('*');
     $this->db->from('paquetes_clientes');
     $this->db->where('clave_cliente',$cla);
     $res = $this->db->get();
     return $res->result_array();
  }
  public function get_max()
  {
     $this->db->select_max('id');
     $this->db->from('paquetes');
     $res = $this->db->get();
     return $res->result_array();
  }
  public function cambiafechaacti($fecha,$id_pac)
  {
     $datos =  array('fecha_activacion' => $fecha);
     $this->db->where('id',$id_pac);
     $this->db->update('paquetes_clientes',$datos);
  }
  public function cambiafecha($fecha,$id_pac)
  {
     $datos =  array('fecha_vence' => $fecha);
     $this->db->where('id',$id_pac);
     $this->db->update('paquetes_clientes',$datos);
  }
  public function update($nom,$rfc,$clave,$activo,$pac,$noti,$ema)
  {  
     $datos = array('nombre' => $nom, 'rfc' => $rfc, 'activo' => $activo, 'id_pac' => $pac, 'Notificar' => $noti, 'email' => $ema);
     $this->db->where('clave', $clave);
     $this->db->update('clientes', $datos);
  }
  public function buscar($clave)
  {
     $this->db->select('*');
     $this->db->from('clientes');
     $this->db->where('clave',$clave);
     $res = $this->db->get();
     return $res->result_array();
  }
  public function getclave($clav)
  {
     $this->db->select('*');
     $this->db->from('clientes');
     $this->db->where('clave',$clav);
     $res = $this->db->get()->row();
     return $res;
  }
  public function add($data)
  {
    if($this->exists($data['id_user'], $data['clave']))
    {
      return FALSE;
    }
    $this->db->insert('clientes', $data);
    return TRUE;
  }

  public function exists($id_usuario, $clave)
  {
    $row = $this->db->from('clientes')
    ->where('id_user', $id_usuario)
    ->where('clave', $clave)
    ->get()
    ->row();
    return $row ? TRUE : FALSE;
  }

  public function get_by_clave_user($clave, $id_user)
  {
    return $this->db->select(['clave','rfc', 'nombre', 'activo', 'fecha_alta'])
    ->from('clientes')
    ->where('clave', $clave)
    ->where('id_user', $id_user)
    ->get()
    ->row();
  }

  public function get_by_user($id_user)
  {
    return $this->db->select(['clave','rfc', 'nombre', 'activo', 'fecha_alta'])
    ->from('clientes')
    ->where('id_user', $id_user)
    ->get()
    ->result();
  }

  public function delete($clave)
  {
    $this->db->delete('clientes', ['clave' => $clave]);
    return $this->db->affected_rows();
  }

  public function llave($llave)
  {
     return $this->db->select('*')
     ->from('keys')
     ->where('user_id',$llave)
     ->get()
     ->result();
  }
  public function obtenertable($cliente,$rfc)
  {

      $fecha = date('Y-m-d H:i:s');
      $nuevafecha = strtotime('-2 months', strtotime($fecha));
      $fechan = date('Y-m-d H:i:s',strtotime($nuevafecha));

      $this->db->select('uuid,fecha,folio');
      $this->db->from('timbrado');
      $this->db->where('receptor',$cliente);
      $this->db->where('emisor',$rfc);
      $this->db->where('fecha >=',$fechan);
      $this->db->where('fecha <=',$fecha);
      return $this->db->get()->result();
  }
}

/* End of file mdlClientes.php */
