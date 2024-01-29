<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlPaquetes extends CI_Model {

  public function get_by_id($id){
    return $this->db->from('paquetes')
    ->where('id', $id)
    ->get()
    ->row();
  }

  public function add($data)
  {
    $this->db->insert('paquetes', $data);
    return $this->db->insert_id();
  }

  public function get_by_user($id_user)
  {
    return $this->db->from('paquetes')
    ->where('id_user', $id_user)
    ->get()
    ->result();
  }

  public function delete($id)
  {
    $this->db->where('id', $id)
    ->delete('paquetes');
    return $this->db->affected_rows();
  }

  public function update($id, $data)
  {
    $this->db->where('id', $id);
    $this->db->update('paquetes', $data);
    return $this->db->affected_rows();
  }
}

/* End of file MdlPaquetes.php */
