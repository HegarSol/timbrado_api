# CHANGELOG

Todos los cambios notables a este proyecto seran registrados en este archivo.

El formato esta basado en [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
y este proyecto se adhiere a  [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## [Unreleased]

- Asignacion de paquetes a los clientes
- Agregar clientes
- Agregar paquetes disponibles

## [0.0.5] - 2018-06-01

- Almacena Comprobantes timbrados en carpeta local y base de datos
- Cancela comprobantes con FEL
- Recupera acuses de cancelacion

## [0.0.4] - 2018-05-10

- Liberacion de la API publicamente
- Agrega soporte para actualizaciones automaticas desde el repositorio oficial
- Integra el timbrado por parte de DFACTURE

## [0.0.3] - 2018-05-10

### Added

- Timbrado de comprobantes a credito
- Timbrado de comprobantes con Paquete
- Almacena los comprobantes timbrados correctamente
- Almacena los mensajes de error regresados por los proveedores
- Pruebas unitarias de los Models y de los Helpers

## [0.0.2] - 2018-05-02

### Added

- Prioridad de los PAC
- Mejor manejo de los errores almacenados en la base de datos

## [0.0.1] - 2018-04-26

### Added

- Este CHANGELOG
- Timbrado con FEL
- Verifica estatus de un RFC con FEL
- Almacena los errores de timbrado retornados por el PAC
- Incluye Unit Tests usando phpunit