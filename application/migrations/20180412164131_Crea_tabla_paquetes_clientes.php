<?php
class Migration_Crea_tabla_paquetes_clientes extends CI_Migration{
  public function up(){
    $this->dbforge->add_field('id');
    $fields = array(
      'clave_cliente' => array(
        'type' => 'CHAR',
        'constraint' => 20,
        'null' => FALSE
      ),
      'id_paquete' => array(
        'type' => 'INT',
        'unsigned' => TRUE,
        'null' => FALSE
      ),
      'cantidad' => array(
        'type' => 'INT',
        'unsigned' => TRUE,
        'null' => FALSE
      ),
      'fecha_activacion' => array(
        'type' => 'DATE'
      ),
      'fecha_vence' => array(
        'type' => 'DATE',
        'null' => TRUE,
        'default' => NULL
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->create_table('paquetes_clientes', TRUE);
  }

  public function down(){
    $this->dbforge->drop_table('paquetes_clientes', TRUE);
  }
}