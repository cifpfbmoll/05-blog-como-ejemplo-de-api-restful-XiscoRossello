<?php

namespace App\Models;

use CodeIgniter\Model;

class VehicleModel extends Model
{
    protected $table            = 'vehicles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['matricula', 'marca', 'modelo', 'color', 'plaza', 'fecha_entrada', 'fecha_salida', 'estado'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Obtener vehículos actualmente estacionados
     */
    public function getEstacionados()
    {
        return $this->where('estado', 'estacionado')->findAll();
    }

    /**
     * Obtener plazas ocupadas
     */
    public function getPlazasOcupadas()
    {
        return $this->where('estado', 'estacionado')
                    ->where('plaza IS NOT NULL')
                    ->findColumn('plaza') ?? [];
    }

    /**
     * Verificar si una plaza está disponible
     */
    public function plazaDisponible($plaza)
    {
        $ocupada = $this->where('plaza', $plaza)
                        ->where('estado', 'estacionado')
                        ->first();
        return $ocupada === null;
    }

    /**
     * Verificar si una matrícula ya está estacionada
     */
    public function matriculaEstacionada($matricula)
    {
        return $this->where('matricula', $matricula)
                    ->where('estado', 'estacionado')
                    ->first();
    }
}
