<?php
class Migration_Crea_tabla_catalogo_errores extends CI_Migration{
  public function up(){
    $fields = array(
      'codigo' => array(
        'type' => 'CHAR',
        'constraint' => 10,
        'null' => FALSE
      ),
      'error' => array(
        'type' => 'TEXT'
      ),
      'extendido' => array(
        'type' => 'TEXT'
      ),
      'solucion' => array(
        'type' => 'TEXT'
      ),
      'codigo_http' => array(
        'type' => 'INT',
        'null' => FALSE
      )
    );
    $this->dbforge->add_key('codigo', TRUE);
    $this->dbforge->add_field($fields);
    $this->dbforge->create_table('errores', TRUE);
  }

  public function down(){
    $this->dbforge->drop_table('errores', TRUE);
  }
}