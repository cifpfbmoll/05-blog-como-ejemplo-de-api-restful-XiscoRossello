<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Vehicles extends ResourceController
{
    protected $modelName = 'App\Models\VehicleModel';
    protected $format = 'json';

    // Número total de plazas en el parking
    private const TOTAL_PLAZAS = 50;

    /**
     * Return an array of resource objects, themselves in array format.
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $vehicles = $this->model->findAll();
        return $this->respond([
            'total_plazas' => self::TOTAL_PLAZAS,
            'plazas_ocupadas' => count($this->model->getPlazasOcupadas()),
            'plazas_disponibles' => self::TOTAL_PLAZAS - count($this->model->getPlazasOcupadas()),
            'vehiculos' => $vehicles
        ]);
    }

    /**
     * Return the properties of a resource object.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function show($id = null)
    {
        $vehicle = $this->model->find($id);
        
        if ($vehicle === null) {
            return $this->failNotFound('Vehículo no encontrado');
        }
        
        return $this->respond($vehicle);
    }

    /**
     * Registrar entrada de un vehículo al parking
     *
     * @return ResponseInterface
     */
    public function create()
    {
        $data = $this->request->getJSON(true);
        
        // Validación
        $rules = [
            'matricula' => 'required|min_length[4]|max_length[20]',
            'marca' => 'required|min_length[2]',
            'modelo' => 'required|min_length[1]',
            'color' => 'required|min_length[2]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        // Verificar si la matrícula ya está estacionada
        if ($this->model->matriculaEstacionada($data['matricula'])) {
            return $this->fail('Este vehículo ya está estacionado en el parking', 409);
        }

        // Verificar si hay plazas disponibles
        $plazasOcupadas = $this->model->getPlazasOcupadas();
        if (count($plazasOcupadas) >= self::TOTAL_PLAZAS) {
            return $this->fail('No hay plazas disponibles en el parking', 503);
        }

        // Asignar plaza si se especifica, o asignar una automáticamente
        if (isset($data['plaza'])) {
            if (!$this->model->plazaDisponible($data['plaza'])) {
                return $this->fail('La plaza ' . $data['plaza'] . ' ya está ocupada', 409);
            }
        } else {
            // Encontrar primera plaza disponible
            for ($i = 1; $i <= self::TOTAL_PLAZAS; $i++) {
                if (!in_array($i, $plazasOcupadas)) {
                    $data['plaza'] = $i;
                    break;
                }
            }
        }

        // Establecer fecha de entrada y estado
        $data['fecha_entrada'] = date('Y-m-d H:i:s');
        $data['estado'] = 'estacionado';
        
        $id = $this->model->insert($data);
        
        if ($id === false) {
            return $this->fail($this->model->errors());
        }
        
        $vehicle = $this->model->find($id);
        return $this->respondCreated([
            'mensaje' => 'Vehículo registrado exitosamente',
            'plaza_asignada' => $vehicle['plaza'],
            'vehiculo' => $vehicle
        ]);
    }

    /**
     * Update vehicle information.
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        
        // Validación (menos estricta para actualización)
        $rules = [
            'matricula' => 'permit_empty|min_length[4]|max_length[20]',
            'marca' => 'permit_empty|min_length[2]',
            'modelo' => 'permit_empty|min_length[1]',
            'color' => 'permit_empty|min_length[2]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        
        $vehicle = $this->model->find($id);
        
        if ($vehicle === null) {
            return $this->failNotFound('Vehículo no encontrado');
        }

        // Si se quiere cambiar de plaza, verificar disponibilidad
        if (isset($data['plaza']) && $data['plaza'] != $vehicle['plaza']) {
            if (!$this->model->plazaDisponible($data['plaza'])) {
                return $this->fail('La plaza ' . $data['plaza'] . ' ya está ocupada', 409);
            }
        }
        
        $this->model->update($id, $data);
        
        return $this->respond([
            'mensaje' => 'Vehículo actualizado exitosamente',
            'vehiculo' => $this->model->find($id)
        ]);
    }

    /**
     * Registrar salida de un vehículo (soft delete - marca como salido)
     *
     * @param int|string|null $id
     *
     * @return ResponseInterface
     */
    public function delete($id = null)
    {
        $vehicle = $this->model->find($id);
        
        if ($vehicle === null) {
            return $this->failNotFound('Vehículo no encontrado');
        }

        if ($vehicle['estado'] !== 'estacionado') {
            return $this->fail('Este vehículo ya ha salido del parking', 400);
        }

        // Registrar salida
        $this->model->update($id, [
            'fecha_salida' => date('Y-m-d H:i:s'),
            'estado' => 'salido'
        ]);
        
        $vehicleActualizado = $this->model->find($id);
        
        // Calcular tiempo estacionado
        $entrada = new \DateTime($vehicleActualizado['fecha_entrada']);
        $salida = new \DateTime($vehicleActualizado['fecha_salida']);
        $diferencia = $entrada->diff($salida);
        
        return $this->respond([
            'mensaje' => 'Vehículo ha salido del parking',
            'plaza_liberada' => $vehicle['plaza'],
            'tiempo_estacionado' => $diferencia->format('%H horas, %I minutos'),
            'vehiculo' => $vehicleActualizado
        ]);
    }

    /**
     * Buscar vehículos por matrícula, marca, modelo o color.
     *
     * @return ResponseInterface
     */
    public function search()
    {
        $term = $this->request->getGet('term');
        
        if ($term === null || $term === '') {
            return $this->fail('Debes proveer un término de búsqueda');
        }
        
        $vehicles = $this->model
            ->like('matricula', $term)
            ->orLike('marca', $term)
            ->orLike('modelo', $term)
            ->orLike('color', $term)
            ->findAll();
        
        return $this->respond([
            'termino_busqueda' => $term,
            'resultados_encontrados' => count($vehicles),
            'vehiculos' => $vehicles
        ]);
    }

    /**
     * Obtener vehículos actualmente estacionados
     *
     * @return ResponseInterface
     */
    public function estacionados()
    {
        $vehicles = $this->model->getEstacionados();
        return $this->respond([
            'total_estacionados' => count($vehicles),
            'vehiculos' => $vehicles
        ]);
    }

    /**
     * Obtener estado del parking (plazas disponibles/ocupadas)
     *
     * @return ResponseInterface
     */
    public function estado()
    {
        $plazasOcupadas = $this->model->getPlazasOcupadas();
        $plazasDisponibles = [];
        
        for ($i = 1; $i <= self::TOTAL_PLAZAS; $i++) {
            if (!in_array($i, $plazasOcupadas)) {
                $plazasDisponibles[] = $i;
            }
        }
        
        return $this->respond([
            'total_plazas' => self::TOTAL_PLAZAS,
            'plazas_ocupadas' => count($plazasOcupadas),
            'plazas_disponibles' => count($plazasDisponibles),
            'lista_plazas_ocupadas' => $plazasOcupadas,
            'lista_plazas_disponibles' => $plazasDisponibles
        ]);
    }

    /**
     * Buscar vehículo por matrícula exacta
     *
     * @return ResponseInterface
     */
    public function porMatricula($matricula = null)
    {
        if ($matricula === null || $matricula === '') {
            return $this->fail('Debes proveer una matrícula');
        }
        
        $vehicle = $this->model->where('matricula', $matricula)->first();
        
        if ($vehicle === null) {
            return $this->failNotFound('Vehículo con matrícula ' . $matricula . ' no encontrado');
        }
        
        return $this->respond($vehicle);
    }
}
