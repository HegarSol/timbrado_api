<?php
class Migration_Agregar_campo_pruebas extends CI_Migration{
  public function up()
  {
    $field = array(
        'pruebas' => array(
            'type' => 'int',
            'constraint' => 2,
            'null' => TRUE
          ),
    );
    $this->dbforge->add_column('clientes', $field);
  }

  public function down()
  {
    $this->dbforge->drop_column('clientes', 'pruebas');
  }
}