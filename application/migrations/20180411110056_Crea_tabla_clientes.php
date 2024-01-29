<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Crea_tabla_clientes extends CI_Migration{
  public function up(){
    $fields = array(
      'clave' => array(
        'type' => 'char',
        'constraint' => 20,
        'null' => FALSE,
        'unique' => TRUE
      ),
      'rfc' => array(
        'type' => 'CHAR',
        'constraint' => 13,
        'null' => FALSE
      ),
      'nombre' => array(
        'type' => 'VARCHAR',
        'constraint' => 250,
        'null' => FALSE
      ),
      'activo' => array(
        'type' => 'TINYINT',
        'constraint' => 1,
        'null' => FALSE,
        'default' => 0
      ),
      'id_user' => array(
        'type' => 'INT',
        'null' => FALSE,
        'default' => 0
      ),
      'id_pac' => array(
        'type' => 'CHAR',
        'null' => FALSE,
        'constraint' => 10,
        'default' => 'SW'
      ),
      'id_paquete' => array(
        'type' => 'INT',
        'null' => FALSE,
        'default' => 0
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->add_field("fecha_alta TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
    $this->dbforge->add_key('clave', TRUE);
    $this->dbforge->create_table('clientes',TRUE);
  }

  public function down(){
    $this->dbforge->drop_table('clientes', TRUE);
  }
}