<?php
class Migration_Agregar_proveedor_correcto extends CI_Migration{
  public function up(){
    $this->load->library('encryption');
  
    //Insertamos el registro de SW
    $data = array(
      'id' => 'SW',
      'nombre' => 'SW.com.mx',
      'url_comprobantes' => 'https://services.sw.com.mx',
      'url_retenciones' => 'https://services.sw.com.mx',
      'user' => $this->encryption->encrypt('hegar@connexial.com.mx'),
      'password' => $this->encryption->encrypt('ey$uBBSdcA%'),
      'test_url_comprobante' => 'http://services.test.sw.com.mx',
      'test_url_retenciones' => 'http://services.test.sw.com.mx',
      'test_user' => $this->encryption->encrypt('demo'),
      'test_password' => $this->encryption->encrypt('123456789')
    );
    $this->db->insert('proveedores', $data);
  }

  public function down(){
  
    $this->db->where('id', 'SW')->delete('proveedores');
  }
}