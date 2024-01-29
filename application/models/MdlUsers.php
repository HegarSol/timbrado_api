<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MdlUsers extends CI_Model {

  public function get_by_email($email){
    return $this->db->from('users')
    ->where('email', $email)
    ->get()
    ->row();
  }

  public function get_by_api_user($api_user){
    return $this->db->from('users')
    ->where('api_user', $api_user)
    ->get()
    ->row();
  }
  public function get_all()
  {
    $this->db->select('*');
    $this->db->from('users');
    $query = $this->db->get();
      return $query->result();
  }
  public function insert($nom,$ape,$email,$pass,$llave)
  {
      $datos=array('email'=>$email,'password'=>$pass,'firstname'=>$nom,'lastname'=>$ape, 'created_at' => date('Y-m-d H:i:s'));
      $this->db->insert('users',$datos);
      $row = $this->db->query('SELECT MAX(id) AS id FROM users')->row();
      $maxid = $row->id;
      $this->insertarllave($maxid,$llave); 
  }
  public function actualiza($id,$nombre,$apellido,$email)
  {
      $datos = array('email'=>$email,'firstname'=> $nombre, 'lastname'=> $apellido);
      $this->db->where('id',$id);
      $this->db->update('users',$datos);
  }
  public function insertarllave($id_ser,$key)
  {
      $datos = array('user_id' => $id_ser, 'key' => $key, 'level' => 10, 'ignore_limits' => 0, 'is_private_key' => 0,'date_created' => date('Y-m-d H:i:s'));
      $this->db->insert('keys',$datos);
  }
  public function buscar($id)
  {
     $this->db->select('*');
     $this->db->from('users');
     $this->db->where('id',$id);
     $query = $this->db->get();
     return $query->result();
  }
  public function buscarcliente($id)
  {
    $this->db->select('*');
    $this->db->from('clientes');
    $this->db->join('keys', 'keys.user_id = clientes.id_user');
    $this->db->where('clave',$id);
    $res = $this->db->get();
    return $res->result_array();
  }
  public function elimina($id)
  {
     $this->db->where('id',$id);
     return $this->db->delete('users');
  }
}

/* End of file MdlUsers.php */
