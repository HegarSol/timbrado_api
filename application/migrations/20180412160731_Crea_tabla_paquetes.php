<?php
class Migration_Crea_tabla_paquetes extends CI_Migration{
  public function up(){
    $fields = array(
      'id' => array(
        'type' => 'INT',
        'unsigned' => TRUE,
        'auto_increment' => TRUE,
        'null' => FALSE
      ),
      'id_user' => array(
        'type' => 'INT'
      ),
      'cantidad' => array(
        'type' => 'INT',
        'unsigned' => TRUE,
        'null' => FALSE
      ),
      'precio_adicional' => array(
        'type' => 'DECIMAL',
        'constraint' => '10,2',
        'null' => FALSE,
        'default' => '0.00',
        'unsigned' => TRUE
      ),
      'precio' => array(
        'type' => 'DECIMAL',
        'constraint' => '10,2',
        'null' => FALSE,
        'unsigned' => TRUE
      ),
      'credito' => array(
        'type' => 'TINYINT',
        'constraint' => 1,
        'default' => 0,
        'null' => FALSE
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_key('id', TRUE);
    $this->dbforge->create_table('paquetes');
  }

  public function down(){
    $this->dbforge->drop_table('paquetes');
  }
}