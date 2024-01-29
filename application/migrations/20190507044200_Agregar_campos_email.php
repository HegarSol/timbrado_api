<?php
class Migration_Agregar_campos_email extends CI_Migration{
  public function up()
  {
    $field = array(
        'email' => array(
            'type' => 'char',
            'constraint' => 20,
            'null' => FALSE
          ),
    );
    $this->dbforge->add_column('clientes', $field);
  }

  public function down()
  {
    $this->dbforge->drop_column('clientes', 'email');
  }
}