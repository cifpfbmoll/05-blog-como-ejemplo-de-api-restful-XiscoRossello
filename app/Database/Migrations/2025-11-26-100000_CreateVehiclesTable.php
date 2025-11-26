<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'matricula' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
            ],
            'marca' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'modelo' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
            ],
            'plaza' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'fecha_entrada' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'fecha_salida' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'estado' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'default'    => 'estacionado',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('vehicles');
    }

    public function down()
    {
        $this->forge->dropTable('vehicles');
    }
}
