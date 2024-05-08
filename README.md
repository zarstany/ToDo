# API de Gestión de Tareas en Laravel

Este repositorio albergará una API de gestión de tareas construida con Laravel. Se ha estructurado utilizando el patrón de repositorio y casos de uso para organizar la lógica de negocio de manera eficiente. La API proporcionará funcionalidades para registrar y autenticar usuarios, además de gestionar tareas.

## Características

- **Autenticación de Usuarios**: Registro y login de usuarios.
- **Gestión de Tareas**: Permite a los usuarios crear, actualizar, eliminar y consultar tareas.
- **Arquitectura Limpia**: Implementa el patrón de repositorio y casos de uso para una clara separación de la lógica.
- **Contratos**: Define interfaces claras para los repositorios y casos de uso.
- **Transformadores**: Utilizados para estandarizar las respuestas de la API.
- **Manejo de Excepciones**: Configuración centralizada de respuestas de error para facilitar el mantenimiento y la escalabilidad.
- **Pruebas Unitarias e Integración**: Cobertura de pruebas para los métodos del repositorio y los endpoints, incluyendo casos de error.

## Requisitos

- PHP >= 8.2
- Composer
- MySQL o cualquier otro DBMS soportado por Laravel

## Instalación

1. Clonar el repositorio:
   ```bash
   git clone git@github.com:zarstany/ToDo.git
2. Instalar dependencias
   ```Bash
    composer install

3. Generate Key
      ```Bash

   php artisan key:generate

5. Correr migraciones
   ```Bash
    php artisan migrate

## Uso de la api
