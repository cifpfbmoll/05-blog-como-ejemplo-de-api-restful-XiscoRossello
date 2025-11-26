[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/CwfLSOPe)
# API RESTful para Parking de Coches - CodeIgniter 4 + SQLite

> 🚗 **Proyecto Educativo**: API REST completa para gestionar un parking de coches

## 🎯 Descripción del Proyecto

Este proyecto es una **API RESTful** construida con **CodeIgniter 4** y **SQLite** que implementa operaciones CRUD completas para gestionar un parking de coches. Incluye gestión de entradas/salidas, asignación de plazas, búsqueda de vehículos y control del estado del parking.

### ✨ Características

- ✅ CRUD completo (Create, Read, Update, Delete)
- ✅ Registro de entrada y salida de vehículos
- ✅ Asignación automática o manual de plazas
- ✅ Búsqueda de vehículos por matrícula, marca, modelo o color
- ✅ Control de plazas disponibles/ocupadas
- ✅ Cálculo de tiempo de estacionamiento
- ✅ Validación de datos robusta
- ✅ Códigos de estado HTTP apropiados
- ✅ Respuestas JSON consistentes
- ✅ Base de datos SQLite (sin servidor)
- ✅ Migraciones de base de datos

## 🚀 Inicio Rápido

### Requisitos Previos

- PHP 8.1 o superior
- Composer
- SQLite3 (generalmente incluido en PHP)

### Instalación

1. **Las dependencias ya están instaladas**, pero si necesitas reinstalar:
   ```bash
   composer install
   ```

2. **La base de datos ya está configurada** en `.env` para usar SQLite

3. **Ejecutar migraciones** (crear tabla de vehículos):
   ```bash
   php spark migrate
   ```

4. **Iniciar el servidor de desarrollo**:
   ```bash
   php spark serve
   ```

5. **Acceder a la API**:
   ```
   http://localhost:8080
   ```

### Prueba Rápida

```bash
# Registrar entrada de un vehículo
curl -X POST http://localhost:8080/vehicles \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "1234ABC",
    "marca": "Toyota",
    "modelo": "Corolla",
    "color": "Blanco"
  }'

# Ver estado del parking
curl http://localhost:8080/vehicles/estado

# Listar todos los vehículos
curl http://localhost:8080/vehicles
```

O ejecuta el script de pruebas:
```bash
./test_api.sh
```

## 📋 Endpoints de la API

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/vehicles` | Listar todos los vehículos |
| GET | `/vehicles/{id}` | Obtener un vehículo específico |
| POST | `/vehicles` | Registrar entrada de vehículo |
| PUT | `/vehicles/{id}` | Actualizar datos del vehículo |
| DELETE | `/vehicles/{id}` | Registrar salida del vehículo |
| GET | `/vehicles/search?term={palabra}` | Buscar vehículos |
| GET | `/vehicles/estado` | Ver estado del parking |
| GET | `/vehicles/estacionados` | Ver vehículos estacionados |
| GET | `/vehicles/matricula/{matricula}` | Buscar por matrícula exacta |

### Ejemplos de Uso

#### Registrar entrada de vehículo
```bash
curl -X POST http://localhost:8080/vehicles \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "1234ABC",
    "marca": "Toyota",
    "modelo": "Corolla",
    "color": "Blanco"
  }'
```

#### Registrar entrada con plaza específica
```bash
curl -X POST http://localhost:8080/vehicles \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "5678DEF",
    "marca": "Honda",
    "modelo": "Civic",
    "color": "Negro",
    "plaza": 15
  }'
```

#### Ver estado del parking
```bash
curl http://localhost:8080/vehicles/estado
```

#### Registrar salida de vehículo
```bash
curl -X DELETE http://localhost:8080/vehicles/1
```

## 🗂️ Estructura del Proyecto

```
api-parking/
├── app/
│   ├── Controllers/
│   │   └── Vehicles.php       # Controlador de la API
│   ├── Models/
│   │   └── VehicleModel.php   # Modelo de datos
│   ├── Database/
│   │   └── Migrations/        # Migraciones de BD
│   └── Config/
│       └── Routes.php         # Rutas de la API
├── writable/
│   └── database/
│       └── parking.db         # Base de datos SQLite
└── public/
    └── index.php              # Punto de entrada
```

## 🧪 Pruebas

### Con cURL
```bash
./test_api.sh
```

### Con Postman

Postman es una herramienta gráfica muy útil para probar APIs. Sigue estos pasos:

#### 1. Configuración inicial
1. Descarga e instala [Postman](https://www.postman.com/downloads/)
2. Asegúrate de que el servidor esté corriendo: `php spark serve`
3. La URL base será: `http://localhost:8080`

#### 2. Probar los endpoints

**📥 GET - Listar todos los vehículos**
- Método: `GET`
- URL: `http://localhost:8080/vehicles`
- Click en "Send"

**📥 GET - Ver estado del parking**
- Método: `GET`
- URL: `http://localhost:8080/vehicles/estado`

**📥 GET - Ver vehículos estacionados**
- Método: `GET`
- URL: `http://localhost:8080/vehicles/estacionados`

**📥 GET - Obtener vehículo por ID**
- Método: `GET`
- URL: `http://localhost:8080/vehicles/1`

**📥 GET - Buscar por matrícula**
- Método: `GET`
- URL: `http://localhost:8080/vehicles/matricula/1234ABC`

**🔍 GET - Buscar vehículos**
- Método: `GET`
- URL: `http://localhost:8080/vehicles/search?term=Toyota`

**📤 POST - Registrar entrada de vehículo**
- Método: `POST`
- URL: `http://localhost:8080/vehicles`
- Headers: `Content-Type: application/json`
- Body (raw JSON):
```json
{
    "matricula": "1234ABC",
    "marca": "Toyota",
    "modelo": "Corolla",
    "color": "Blanco"
}
```

**📤 POST - Registrar con plaza específica**
- Método: `POST`
- URL: `http://localhost:8080/vehicles`
- Headers: `Content-Type: application/json`
- Body (raw JSON):
```json
{
    "matricula": "5678DEF",
    "marca": "Honda",
    "modelo": "Civic",
    "color": "Negro",
    "plaza": 15
}
```

**✏️ PUT - Actualizar vehículo**
- Método: `PUT`
- URL: `http://localhost:8080/vehicles/1`
- Headers: `Content-Type: application/json`
- Body (raw JSON):
```json
{
    "color": "Azul Metalizado"
}
```

**🗑️ DELETE - Registrar salida de vehículo**
- Método: `DELETE`
- URL: `http://localhost:8080/vehicles/1`

#### 3. Códigos de respuesta esperados

| Código | Significado |
|--------|-------------|
| 200 | Operación exitosa |
| 201 | Vehículo creado correctamente |
| 400 | Datos inválidos (validación fallida) |
| 404 | Vehículo no encontrado |
| 409 | Conflicto (vehículo ya estacionado o plaza ocupada) |
| 503 | Parking lleno |

#### 4. Tips para Postman

- **Crear una colección**: Agrupa todas las peticiones en una colección llamada "Parking API"
- **Variables de entorno**: Crea una variable `{{base_url}}` con valor `http://localhost:8080` para facilitar cambios
- **Guardar ejemplos**: Guarda las respuestas como ejemplos para documentación
- **Tests automáticos**: Puedes añadir tests en JavaScript para validar respuestas automáticamente

## 🎓 Conceptos Aprendidos

Este proyecto enseña:
- ✅ Patrón MVC con CodeIgniter 4
- ✅ Diseño de APIs RESTful
- ✅ Migraciones de base de datos
- ✅ Validación de datos
- ✅ Manejo de errores HTTP
- ✅ SQLite como base de datos
- ✅ Gestión de estado (estacionado/salido)
- ✅ Asignación automática de recursos (plazas)

## Server Requirements

PHP version 8.1 or higher is required, with the following extensions installed:

- [intl](http://php.net/manual/en/intl.requirements.php)
- [mbstring](http://php.net/manual/en/mbstring.installation.php)

Additionally, make sure that the following extensions are enabled in your PHP:

- json (enabled by default - don't turn it off)
- [libcurl](http://php.net/manual/en/curl.requirements.php) if you plan to use the HTTP\CURLRequest library
