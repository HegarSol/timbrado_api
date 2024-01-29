<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlProveedores extends CI_Model {

  public function en_contingencia($id_pac){
    return $this->db->select(['contingencia'])
    ->from('proveedores')
    ->where('id', $id_pac)
    ->get()
    ->row()
    ->contingencia;
  }

  public function get_by_id($id){
    return $this->db->from('proveedores')
    ->where('id', $id)
    ->get()
    ->row();
  }

  public function get_by_nombre($nombre){
    return $this->db->from('proveedores')
    ->where('nombre', $nombre)
    ->get()
    ->row();
  }

  public function get_disponible(){
    return $this->db->select(['id'])
    ->from('proveedores')
    ->where('contingencia', 0)
    ->order_by('prioridad', 'DESC')
    ->limit(1)
    ->get()
    ->row();
  }

  public function set_contingencia($id = NULL, $en_contingencia = 1){
    $this->db->set('contingencia', $en_contingencia);
    if(!is_null($id)){
      $this->db->where('id', $id);
    }
    $this->db->update('proveedores');
    return $this->db->affected_rows();
  }

  public function valid_id($id_pac)
  {

    $row = $this->db->select(['id'])
    ->from('proveedores')
    ->where('id', $id_pac)
    ->get()
    ->row();

    return $row ? TRUE : FALSE;
  }

}

/* End of file MdlProveedores.php */
