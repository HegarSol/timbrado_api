<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlPaquetesClientes extends CI_Model {

  public function get_paquete_activo($clave_cliente, $id_pac = NULL){
    return $this->db->from('paquetes_clientes')
    ->where('clave_cliente', $clave_cliente)
    ->where('fecha_vence >=', date('Y-m-d'))
    ->where('cantidad >', 0)
    ->where('fecha_activacion <=', date('Y-m-d'))
    ->order_by('fecha_activacion', 'ASC')
    ->limit(1)
    ->get()
    ->row();
  }

  public function resta_cantidad_by_id($id_paquete){
    $this->db->set('cantidad','cantidad - 1', FALSE)
    ->where('id', $id_paquete)
    ->update('paquetes_clientes');
    return $this->db->affected_rows();
  }

  public function get_paquetes_vigentes($clave_cliente){
    return $this->db->from('paquetes_clientes')
    ->where('clave_cliente', $clave_cliente)
    ->where('fecha_vence >=', date('Y-m-d'))
    ->where('cantidad >', 0)
    ->order_by('fecha_activacion', 'ASC')
    ->get()
    ->result();
  }

  public function get_by_id($id)
  {
    return $this->db->select(['id_paquete', 'cantidad', 'fecha_activacion', 'fecha_vence'])
    ->from('paquetes_clientes')
    ->where('id', $id)
    ->get()
    ->row_array();
  }

  public function count_clientes_by_paquete($id_paquete)
  {
    return $this->db->select(['COUNT(id) AS total'])
    ->from('paquetes_clientes')
    ->where('id_paquete', $id_paquete)
    ->get()
    ->row()
    ->total;
  }

  public function add($data)
  {
    $this->db->insert('paquetes_clientes', $data);
    return $this->db->insert_id();
  }

  public function ultimacompra($clave)
  {
    return $this->db->select('*')
    ->from('paquetes_clientes')
    ->where('clave_cliente',$clave)
    ->order_by('referencia_compra','DESC')
    //->limit(1)
    ->get()
    ->result();
  }
}

/* End of file MdlPaquetesClientes.php */