#!/bin/bash

# Script de prueba rápida para la API del Parking
# Asegúrate de que el servidor esté corriendo: php spark serve

BASE_URL="http://localhost:8080"
echo "========================================="
echo "  Pruebas de API RESTful - Parking CI4"
echo "========================================="
echo ""

# Colores para output
GREEN='\033[0;32m'
RED='\033[0;31m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${BLUE}1. Verificando estado del parking...${NC}"
curl -X GET "$BASE_URL/vehicles/estado" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}2. Registrando entrada de primer vehículo...${NC}"
curl -X POST "$BASE_URL/vehicles" \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "1234ABC",
    "marca": "Toyota",
    "modelo": "Corolla",
    "color": "Blanco"
  }' \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}3. Registrando entrada de segundo vehículo con plaza específica...${NC}"
curl -X POST "$BASE_URL/vehicles" \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "5678DEF",
    "marca": "Honda",
    "modelo": "Civic",
    "color": "Negro",
    "plaza": 10
  }' \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}4. Registrando entrada de tercer vehículo...${NC}"
curl -X POST "$BASE_URL/vehicles" \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "9012GHI",
    "marca": "Ford",
    "modelo": "Focus",
    "color": "Rojo"
  }' \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}5. Listando todos los vehículos...${NC}"
curl -X GET "$BASE_URL/vehicles" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}6. Verificando vehículos estacionados...${NC}"
curl -X GET "$BASE_URL/vehicles/estacionados" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}7. Obteniendo vehículo específico (ID: 1)...${NC}"
curl -X GET "$BASE_URL/vehicles/1" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}8. Buscando vehículo por matrícula exacta...${NC}"
curl -X GET "$BASE_URL/vehicles/matricula/1234ABC" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}9. Intentando obtener vehículo inexistente (ID: 999)...${NC}"
curl -X GET "$BASE_URL/vehicles/999" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}10. Actualizando vehículo (cambiar color)...${NC}"
curl -X PUT "$BASE_URL/vehicles/1" \
  -H "Content-Type: application/json" \
  -d '{
    "color": "Azul Metalizado"
  }' \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}11. Buscando vehículos con término 'Toyota'...${NC}"
curl -X GET "$BASE_URL/vehicles/search?term=Toyota" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}12. Intentando registrar vehículo con datos inválidos...${NC}"
curl -X POST "$BASE_URL/vehicles" \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "AB",
    "marca": "X"
  }' \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}13. Intentando registrar vehículo ya estacionado...${NC}"
curl -X POST "$BASE_URL/vehicles" \
  -H "Content-Type: application/json" \
  -d '{
    "matricula": "1234ABC",
    "marca": "Toyota",
    "modelo": "Yaris",
    "color": "Verde"
  }' \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}14. Verificando estado del parking actualizado...${NC}"
curl -X GET "$BASE_URL/vehicles/estado" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}15. Registrando salida de vehículo (ID: 2)...${NC}"
curl -X DELETE "$BASE_URL/vehicles/2" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}16. Verificando que el vehículo salió (estado actualizado)...${NC}"
curl -X GET "$BASE_URL/vehicles/2" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo -e "${BLUE}17. Estado final del parking...${NC}"
curl -X GET "$BASE_URL/vehicles/estado" \
  -w "\nCódigo HTTP: %{http_code}\n\n"

echo ""
echo -e "${GREEN}=========================================${NC}"
echo -e "${GREEN}  Pruebas completadas${NC}"
echo -e "${GREEN}=========================================${NC}"
echo ""
echo "Para ver los resultados formateados, instala 'jq':"
echo "  brew install jq  (macOS)"
echo "  sudo apt-get install jq  (Linux)"
echo ""
echo "Luego ejecuta:"
echo "  curl http://localhost:8080/vehicles | jq"
echo "  curl http://localhost:8080/vehicles/estado | jq"
