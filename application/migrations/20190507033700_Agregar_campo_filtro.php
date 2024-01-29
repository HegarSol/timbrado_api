<?php
class Migration_Agregar_campo_filtro extends CI_Migration{
  public function up()
  {
    $field = array(
        'filtro' => array(
            'type' => 'CHAR',
            'constraint' => 20,
            'null' => FALSE
          ),
    );
    $this->dbforge->add_column('users', $field);
  }

  public function down()
  {
    $this->dbforge->drop_column('users', 'filtro');
  }
}