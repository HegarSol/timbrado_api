<?php
class Migration_create_table_notificar extends CI_Migration{
  public function up(){

    $fields = array(
      'cliente_clave' => array(
        'type' => 'CHAR',
        'constraint' => 30,
        'null' => FALSE
      ),
      'notificar' => array(
        'type' => 'CHAR',
        'constraint' => 5,
        'null' => FALSE
      ),
      'fecha' => array(
        'type' => 'DATE',
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->create_table('Notificar', TRUE);
  }

  public function down(){
    $this->dbforge->drop_table('Notificar', TRUE);
  }
}