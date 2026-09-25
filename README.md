# PCV Soluciones Industriales — MVP

Landing pública + panel admin PHP/mysqli/jQuery al estilo Juan (soccer): MVC casero, `POST metodo+accion`, respuesta `Correcto`, SweetAlert, Bootstrap 5, Font Awesome, DataTables (`myDataTable`), IMask.

## Requisitos

- PHP 8+ con extensión `mysqli`
- MariaDB / MySQL 10.5+
- Extensiones recomendadas: `mbstring`, `gd`

## Instalación rápida

```bash
# 1) Crear BD e importar schema + seed
mysql -u root -p < sql/schema.sql

# 2) Ajustar credenciales en includes/config.php
#    DB_HOST, DB_USER, DB_PASS, DB_NAME

# 3) Permisos de subida
chmod -R 775 uploads/productos

# 4) Servidor de desarrollo
cd /workspace/pcv-soluciones
php -S 0.0.0.0:8080 router.php
```

Abre:

- Sitio: http://127.0.0.1:8080/
- Admin: http://127.0.0.1:8080/admin/

## Login admin (default)

| Campo | Valor |
|-------|--------|
| Usuario | `admin` |
| Contraseña | `admin123` |

La contraseña se guarda con `password_hash` / `password_verify` en la tabla `usuarios`. **Cámbiala en producción.**

Para regenerar el hash:

```bash
php -r 'echo password_hash("admin123", PASSWORD_DEFAULT), PHP_EOL;'
```

## Estructura

```
pcv-soluciones/
  index.php          # Landing
  catalogo.php       # Catálogo (servicios primero, luego productos)
  ficha.php          # Detalle + cotizar
  cotizar.php        # Endpoint JSON → DB + wa.me
  assets/            # CSS/JS/img/plugins landing
  public/assets/img/logo.png
  uploads/productos/ # Imágenes subidas / placeholders
  includes/          # config.php, db.php, header, footer
  admin/
    index.php        # Router (metodo+accion)
    app.php          # Alias
    controladores/   # c_controller, login, productos, cotizaciones, dashboard
    modelo/          # m_modelo + conexion mysqli
    vistas/          # v_login, v_html, módulos + assets/plugins
  sql/schema.sql
```

## Reglas de negocio implementadas

- **Sin precios** en schema ni UI
- Catálogo: primero **servicios** (cards), luego **productos**
- Clasificaciones seed: Metal Mecánica, Luminaria, Refaccionaria (+ subtípicos)
- Cotizar: formulario → `cotizaciones` + enlace `https://wa.me/5213311444743`
- Contacto: gerardo.solind@gmail.com, 3311444743, 3310438300, IG `pcvsolind`, Facebook compartido
- Colores: navy `#08295A`, green `#0A3D0A`, accent `#104FA0`
- Hero con eslogan + animación SVG industrial (engranajes / trayectoria)

## Admin — flujo Juan

1. Sin sesión → `v_login`
2. Login POST `accion=login` → responde `Correcto` y recarga
3. Con sesión → layout `v_html`; menú dispara `metodo=cambiar&accion=v_*`
4. CRUD: `metodo=consultar|insertar|modificar|eliminar|detalles` + `accion=productos|cotizaciones|…`

## Credenciales DB locales (box de desarrollo)

Ya provisionadas en este entorno:

- DB: `pcv_soluciones`
- User: `pcv` / `pcv_local`
- Host: `127.0.0.1`

## Notas

- Plugins locales en `admin/vistas/assets/plugins` (y espejo en `assets/plugins`); Font Awesome CSS se carga también desde CDN para webfonts.
- Brochure PDF: `assets/img/pcv-brochure.pdf`
- Organigrama: `assets/img/organigrama.png`
- Logo oficial (fondo negro): `public/assets/img/logo.png` y `assets/img/logo.png`; versión recortada para header: `assets/img/logo-header.png`
