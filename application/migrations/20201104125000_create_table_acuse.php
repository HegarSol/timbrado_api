<?php

class Migration_create_table_acuse extends CI_Migration{
    public function up()
    {
        $fields = [
            'empresa' => [
              'type' => 'CHAR',
              'constraint' => 13,
              'null' => FALSE
            ],
            'fecha' => [
              'type' => 'DATETIME',
              'null' => FALSE
            ],
            'xml_acuse' => [
              'type' => 'TEXT',
              'null' => FALSE
            ],
            'uuid' => [
              'type' => 'CHAR',
              'constraint' => 36,
              'null' => FALSE
            ],
            'estatus' => [
              'type' => 'CHAR',
              'constraint' => 20,
              'null' => FALSE
            ],
            'respuesta' => [
              'type' => 'CHAR',
              'constraint' => 20,
              'null' => FALSE
            ]
          ];
          $this->dbforge->add_field($fields);
          $this->dbforge->add_key('uuid', TRUE);
          $this->dbforge->create_table('acuse_cancelacion', TRUE);
    }
    public function down()
    {
        $this->dbforge->drop_table('acuse_cancelacion', TRUE);
    }
}