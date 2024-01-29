<?php
class Migration_Crea_tabla_proveedores extends CI_Migration{
  public function up(){
    $fields = array(
      'id' => array(
        'type' => 'CHAR',
        'constraint' => 10,
        'null' => FALSE
      ),
      'nombre' => array(
        'type' => 'VARCHAR',
        'constraint' => 200
      ),
      'url_comprobantes' => array(
        'type' => 'TEXT'
      ),
      'url_retenciones' => array(
        'type' => 'TEXT'
      ), 
      'user' => array(
        'type' => 'VARCHAR',
        'constraint' => 250
      ),
      'password' => array(
        'type' => 'VARCHAR',
        'constraint' => 250
      ),
      'test_url_comprobante' => array(
        'type' => 'TEXT'
      ),
      'test_url_retenciones' => array(
        'type' => 'TEXT'
      ),
      'test_user' => array(
        'type' => 'VARCHAR',
        'constraint' => 250
      ),
      'test_password' => array(
        'type' => 'VARCHAR',
        'constraint' => 250
      )
    );
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->add_field($fields);
    $this->dbforge->create_table('proveedores', TRUE);
  }

  public function down(){
    $this->dbforge->drop_column('proveedores', TRUE);
  }
}