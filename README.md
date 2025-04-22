# 🐾 Rescatando Huellas - Plataforma para Reportar y Encontrar Mascotas

[![Deployment](https://img.shields.io/badge/Deployed_on-000000?style=flat&logo=apache)](http://www.mascotasperdidas.fwh.is)
[![PHP](https://img.shields.io/badge/PHP-8.1-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql)](https://www.mysql.com/)
[![Leaflet](https://img.shields.io/badge/Leaflet-1.9-199900?logo=leaflet)](https://leafletjs.com/)
[![License](https://img.shields.io/badge/License-MIT-green)](https://opensource.org/licenses/MIT)

**Plataforma web comunitaria para reportar mascotas perdidas y ayudar a reunirlas con sus dueños.** Incluye mapa interactivo, sistema de reportes con fotos y temporizador de 60 días (5 minutos en modo prueba).

*"Porque cada huella cuenta y ningún amigo peludo debería perderse para siempre."*

## 🌟 Demo en Vivo
👉 [http://www.mascotasperdidas.fwh.is](http://www.mascotasperdidas.fwh.is)

## ✨ Funcionalidades Principales
- **🗺️ Mapa Interactivo**: Visualización geolocalizada de mascotas reportadas.
- **🔍 Búsqueda Inteligente**: Filtros por especie, raza y color.
- **⏳ Temporizador Visible**: Reportes con cuenta regresiva (60 días reales / 5 mins en pruebas).
- **📸 Reportes Multimedia**: Subida de fotos + descripción detallada.
- **🔐 Autenticación Segura**: Registro/login de usuarios (recuperación de contraseña en desarrollo).
- **📱 Responsive Design**: Adaptable a móviles y tablets.

## 🛠️ Stack Tecnológico
| Tecnología       | Función en el Proyecto               |
|------------------|-------------------------------------|
| **PHP 8.1**      | Lógica del servidor y autenticación.|
| **MySQL**        | Base de datos relacional.           |
| **LeafletJS**    | Mapas interactivos + geolocalización.|
| **JavaScript**   | Dinamismo frontend y llamadas API.  |
| **HTML5/CSS3**   | Estructura y diseño responsive.     |
| **Apache**       | Servidor web de despliegue.         |

## 🖼️ Capturas de Pantalla

![Login](capturas/index.jpg)  
*Página de inicio con formulario de acceso.*

![Mapa](capturas/mapa.jpg)  
*Interfaz principal con mapa y controles de búsqueda.*

![Reporte](capturas/repor.jpg)  
*Formulario para registrar mascotas perdidas.*

## 📂 Estructura del Código
```bash
├── public/
│   ├── css/styles.css            # Estilos principales
│   ├── js/operativa_*.js         # Lógica frontend (login, mascotas, etc.)
├── src/
│   ├── controllers/              # Backend PHP
│   │   ├── add_mascota.php       # Creación de reportes
│   │   ├── get_mascotas.php      # API de consulta
│   │   └── (otros endpoints)
│   └── views/                    # Plantillas HTML/PHP
│       ├── mapa.php              # Vista principal
│       └── (otros formularios)
