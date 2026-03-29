# 🚀 Proyecto Final Semana 7: Entornos de Ejecución y Compilador Unificado

**Autor:** Roberandi Durán  
**Matrícula:** 2-19-0333  
**Asignatura:** Compiladores, Principios y Técnicas  

---

## 📚 PARTE I: Investigación - Entorno de Ejecución (Capítulo 7)

### 1. Gestión del Entorno de Ejecución
El compilador es responsable de diseñar un ecosistema dinámico que colabore con el sistema operativo y la arquitectura de hardware. Este entorno organiza la memoria separando el código compilado y las constantes (en áreas estáticas) de los datos cuyo ciclo de vida varía durante la ejecución, los cuales son administrados en estructuras dinámicas como la pila y el montículo.

### 2. Administración de la Pila de Control y Registros de Activación
El flujo de ejecución de los subprogramas se controla mediante una estructura LIFO (pila). Dado que las llamadas a funciones ocurren de manera anidada, la pila mantiene el orden temporal de las activaciones. Cada vez que se invoca un procedimiento, se genera un bloque temporal llamado **registro de activación o marco**. Este espacio aloja las variables locales exclusivas de esa llamada y se destruye automáticamente al finalizar la función.

### 3. Alcance de Variables y Datos No Locales
En lenguajes que soportan funciones anidadas, acceder a variables de un ámbito superior requiere mecanismos especiales. Esto se soluciona implementando **enlaces de acceso** (punteros que conectan con el marco de la función contenedora) o mediante un arreglo global auxiliar llamado *display*, que agiliza la búsqueda en la jerarquía de llamadas activas.

### 4. Gestión del Montículo (Heap) y Fragmentación
El montículo reserva memoria para datos dinámicos cuya vida útil es indefinida. Para evitar el agotamiento de este recurso, se utilizan administradores de memoria y Recolectores de Basura (*Garbage Collectors*). El ciclo de asignación genera "huecos" o espacios vacíos dispersos (fragmentación). Para mitigar esto, se utilizan estrategias como el "mejor ajuste" (ubicar datos en el hueco más pequeño posible) y la coalescencia (fusionar espacios libres adyacentes).

### 5. Algoritmos de Recolección de Basura
* **Conteo de Referencias:** Mantiene un registro de cuántos apuntadores dirigen a un objeto; si llega a cero, se destruye.
* **Basados en Rastreo:** Parten de un "conjunto raíz" de variables y siguen las referencias para identificar lo alcanzable. Variantes incluyen:
    * *Marcar y limpiar:* Marca objetos vivos y borra el resto.
    * *Marcar y compactar:* Mueve los objetos sobrevivientes hacia un extremo, eliminando la fragmentación.
    * *Copiado:* Divide el montículo en dos áreas y copia los datos vivos a la mitad intacta cuando una se llena.

---

## 💻 PARTE II: Compilador Unificado (Dashboard Web)

### 📖 Descripción del Proyecto Práctico
Este proyecto es un **Compilador Unificado (Front-End Completo)** que integra un entorno de desarrollo web (Dashboard) para visualizar en tiempo real todas las fases del proceso de compilación: Análisis Léxico, Sintáctico, Semántico, Gestión de la Tabla de Símbolos y Generación de Código Intermedio/Destino.

### ⚙️ Especificaciones Técnicas y Lenguajes (Source-to-Source)
* **Lenguaje Origen (Entrada):** Subconjunto de lenguaje estructurado de tipado estático (similar a C/Java). Soporta declaraciones (`int`, `float`), asignaciones (`x = 10;`) y llamadas a funciones nativas (`print(x);`).
* **Lenguaje Destino (Salida):** JavaScript moderno. El compilador realiza una traducción semántica adaptando el tipado estricto a tipado dinámico (`let`) y mapeando funciones del sistema (`console.log`).

### 🏗️ Arquitectura del Motor
El núcleo del compilador está escrito en **C** y generado mediante herramientas estándar:
1.  **Analizador Léxico (Scanner):** Generado con `Flex`. Tokeniza la cadena de entrada.
2.  **Analizador Sintáctico (Parser):** Generado con `Bison` (Gramática LALR). Valida la estructura jerárquica.
3.  **Analizador Semántico & Tabla de Símbolos:** Verifica la existencia de variables en memoria antes de permitir su uso o re-declaración.
4.  **Generador de Código (Traductor):** Implementa *Traducción Dirigida por la Sintaxis (TDS)* para emitir código JavaScript simultáneamente a la reducción sintáctica.
5.  **Interfaz Gráfica:** Dashboard construido con PHP y CSS Grid, ejecutado sobre un servidor local (WAMP/XAMPP).

---

## 🛠️ Guía de Instalación y Recompilación

Para que el proyecto funcione en un entorno local de Windows, es necesario contar con los ejecutables binarios de construcción.

### Prerrequisitos
1.  Servidor local activo (WAMP, XAMPP o similar) con PHP habilitado.
2.  Compilador de C instalado (MinGW/GCC) en `C:\mingw64\bin`.
3.  Archivos `win_flex.exe`, `win_bison.exe` y la carpeta `data` alojados en el directorio raíz.

### Pasos para Compilar el Motor (Terminal)

**1. Configurar la variable de entorno (PATH):**
```powershell
$env:Path += ";C:\mingw64\bin"
