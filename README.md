# presentacion-kafka-fullstack-sevilla

# Kafka demo – Wikimedia → Kafka → Mercure

Pequeña demo para experimentar con Apache Kafka como sistema de event streaming,
usando un flujo real de eventos y varios pasos de procesamiento.

La idea es observar cómo Kafka encaja en un escenario donde los eventos:
- se ingieren desde una fuente externa
- se persisten en un topic
- se consumen por otro proceso
- se exponen a clientes en tiempo real

## Contexto

El código de este repositorio surge como apoyo a una presentación realizada en la
comunidad de Fullstack Sevilla, centrada en una explicación práctica de Kafka para desarrolladores.

- https://www.meetup.com/es-ES/fullstacksevilla/
- https://www.meetup.com/es-es/fullstacksevilla/events/312444429/

Se utiliza el stream público de eventos de Wikimedia como fuente de datos,
simplemente por ser continuo, real y fácil de observar.


## Flujo general


- Los eventos se consumen desde el stream de Wikimedia
- Se publican en un topic de Kafka
- Otro consumer procesa el topic
- Los eventos se reenvían a Mercure
- El navegador los recibe en tiempo real mediante SSE

## Qué demuestra esta demo

- Kafka como buffer y sistema de distribución de eventos
- Separación clara entre ingestión y consumo
- Consumers independientes y desacoplados
- Procesamiento asíncrono sin request/response
- Integración con un canal de tiempo real hacia el frontend

## Qué NO intenta demostrar

- Uso de Kafka como cola tradicional
- Configuraciones de producción
- Seguridad o autenticación
- Schema Registry
- Exactly-once semantics
- Optimización o tuning

La demo está pensada para entender el flujo y el modelo mental, no para ser
copiada directamente en un sistema real.

## Componentes

- Consumer de Wikimedia: se conecta al stream público de eventos
- Producer Kafka: publica los eventos en un topic
- Consumer Kafka: procesa los eventos del topic
- Mercure Hub: distribuye los eventos a los clientes
- Frontend: muestra los eventos en tiempo real usando SSE

## Ejecutar en local

1. Levantar la infraestructura (Kafka, Mercure, etc.)

```bash
make up
