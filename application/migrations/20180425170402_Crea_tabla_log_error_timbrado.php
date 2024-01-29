<?php
class Migration_Crea_tabla_log_error_timbrado extends CI_Migration{
  public function up(){
    $this->dbforge->add_field('id');
    $fields = array(
      'clave_cliente' => array(
        'type' => 'CHAR',
        'constraint' => 20
      ),
      'id_pac' => array(
        'type' => 'CHAR',
        'constraint' => 10
      ),
      'codigo_error' => array(
        'type' => 'CHAR',
        'constraint' => 15
      ),
      'mensaje' => array(
        'type' => 'TEXT'
      ),
      'mensaje_detallado' => array(
        'type' => 'TEXT'
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_field("fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    $this->dbforge->create_table('log_error_timbrado', TRUE);
  }

  public function down(){
    $this->dbforge->drop_table('log_error_timbrado', TRUE);
  }
}