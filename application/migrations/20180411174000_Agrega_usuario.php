<?php
class Migration_Agrega_usuario extends CI_Migration{
  public function up(){

    $this->db->insert('users', $data);
    $data2 = array(
      'email' => 'pagos@hegarss.com',
      'password' => 'P4g0s',
      'firstname' => 'Hegarss',
      'lastname' => 'Soluciones',
      'created_at' => date('Y-m-d G:i:s')
    );
    $this->db->insert('users',$data2);
  }

  public function down(){
    $this->db->where('email', 'ggarza@hegarss.com')
    ->delete('users');
  }
}