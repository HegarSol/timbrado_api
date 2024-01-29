<?php
class Migration_Agregar_campo_timbrar extends CI_Migration{
  public function up()
  {
    $field = array(
        'timbrar' => array(
            'type' => 'CHAR',
            'constraint' => 20,
            'null' => FALSE
          ),
    );
    $this->dbforge->add_column('clientes', $field);
  }

  public function down()
  {
    $this->dbforge->drop_column('clientes', 'timbrar');
  }
}