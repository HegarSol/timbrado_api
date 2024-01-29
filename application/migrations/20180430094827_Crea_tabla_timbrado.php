<?php
class Migration_Crea_tabla_timbrado extends CI_Migration{
  public function up()
  {
    $this->dbforge->add_field('id');
    $fields = array(
      'emisor' => array(
        'type' => 'CHAR',
        'constraint' => 13,
      ),
      'receptor' => array(
        'type' => 'CHAR',
        'constraint' => 13
      ),
      'serie' => array(
        'type' => 'CHAR',
        'constraint' => 15
      ),
      'folio' => array(
        'type' => 'CHAR',
        'constraint' => 15
      ),
      'fecha' => array(
        'type' => 'DATETIME'
      ),
      'fecha_timbrado' => array(
        'type' => 'DATETIME'
      ),
      'id_pac' => array(
        'type' => 'CHAR',
        'constraint' => 10
      ),
      'uuid' => array(
        'type' => 'CHAR',
        'constraint' => 38
      ),
      'path' => array(
        'type' => 'TEXT'
      )
    );
    $this->dbforge->add_field($fields);
    $this->dbforge->create_table('timbrado', TRUE);
  }

  public function down()
  {
    $this->dbforge->drop_table('timbrado', TRUE);
  }
}