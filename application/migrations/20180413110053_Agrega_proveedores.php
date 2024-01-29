<?php
class Migration_Agrega_proveedores extends CI_Migration{
  public function up(){
    $this->load->library('encryption');
    // Insertamos el registro de FEL
    $data = array(
      'id' => 'FEL',
      'nombre' => 'FEL.mx',
      'url_comprobantes' => 'https://www.fel.mx/WSTimbrado33/WSCFDI33.svc?WSDL',
      'url_retenciones' => 'https://timbrado.facturarenlinea.com/WSRetencion.svc?wsdl',
      'user' => $this->encryption->encrypt('HSS130622XX1'),
      'password' => $this->encryption->encrypt('ey$uBBSdcA%'),
      'test_url_comprobante' => 'https://app.fel.mx/WSTimbrado33Test/WSCFDI33.svc?WSDL',
      'test_url_retenciones' => 'https://timbrado.facturarenlinea.com/WSRetencion.svc?wsdl',
      'test_user' => $this->encryption->encrypt('HSS130622D33'),
      'test_password' => $this->encryption->encrypt('contRa$3na')
    );
    $this->db->insert('proveedores', $data);
    
    // Insertamos el registro de DFACTURE
    $data = array(
      'id' => 'DFACTURE',
      'nombre' => 'DFACTURE',
      'url_comprobantes' => 'https://timbradosoap.solucionesdfacture.com/WSTimbradoSOAP.svc?wsdl',
      'url_retenciones' => 'https://timbradosoap.solucionesdfacture.com/WSTimbradoSOAP.svc?wsdl',
      'user' => $this->encryption->encrypt('Hegarss'),
      'password' => $this->encryption->encrypt('Hegarss.2016'),
      'test_url_comprobante' => 'http://timbradosoap33.testdfacture.com/WSTimbradoSOAP.svc?wsdl',
      'test_url_retenciones' => 'http://timbradosoap33.testdfacture.com/WSTimbradoSOAP.svc?wsdl',
      'test_user' => $this->encryption->encrypt('DEMOHegarss'),
      'test_password' => $this->encryption->encrypt('cfdi')
    );
    $this->db->insert('proveedores', $data);

    //Insertamos el registro de SW
    $data = array(
      'id' => 'SW',
      'nombre' => 'SW.com.mx',
      'url_comprobantes' => 'https://services.sw.com.mx',
      'url_retenciones' => 'https://services.sw.com.mx',
      'user' => $this->encryption->encrypt('pedroherrera@mail.com'),
      'password' => $this->encryption->encrypt('1234567890'),
      'test_url_comprobante' => 'http://services.test.sw.com.mx',
      'test_url_retenciones' => 'http://services.test.sw.com.mx',
      'test_user' => $this->encryption->encrypt('demo'),
      'test_password' => $this->encryption->encrypt('123456789')
    );
    $this->db->insert('proveedores', $data);
  }

  public function down(){
    $this->db->where('id', 'FEL')->delete('proveedores');
    $this->db->where('id', 'DFACTURE')->delete('proveedores');
    $this->db->where('id', 'SW')->delete('proveedores');
  }
}