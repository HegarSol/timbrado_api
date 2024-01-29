<?php
class Migration_Agrega_campo_contingencia_proveedores extends CI_Migration{
  public function up(){
    $field = array(
      'contingencia' => array(
        'type' => 'TINYINT',
        'constraint' => 1,
        'default' => 0,
        'null' => FALSE
      )
    );
    $this->dbforge->add_column('proveedores', $field);
  }

  public function down(){
    $this->dbforge->drop_column('proveedores', 'contingencia');
  }
}