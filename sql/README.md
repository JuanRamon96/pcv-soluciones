# SQL — PCV Soluciones

## XAMPP (Windows)

1. Arranca Apache + MySQL en el panel de XAMPP.
2. Abre phpMyAdmin o consola:

```bat
C:\xampp\mysql\bin\mysql.exe -u root < C:\xampp\htdocs\pcv-soluciones\sql\schema.sql
```

También puedes importar `install_xampp.sql` (mismo contenido) desde phpMyAdmin.

3. Credenciales por defecto del admin:

| Campo | Valor |
|-------|--------|
| Correo | `gerardo.solind@gmail.com` |
| Contraseña | `admin123` |

4. Sitio: `http://localhost/pcv-soluciones/`
5. Admin: `http://localhost/pcv-soluciones/admin/`
