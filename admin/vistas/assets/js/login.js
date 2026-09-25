$(function(){
  $('#formLogin').on('submit', function(e){
    e.preventDefault();
    $.post('/admin/index.php', {
      accion: 'login',
      usuario: $('#usuario').val(),
      contrasena: $('#contrasena').val()
    }, function(resp){
      if (String(resp).trim() === 'Correcto') {
        location.reload();
      } else {
        Swal.fire({icon:'error', title:'Acceso denegado', text:'Usuario o contraseña incorrectos'});
      }
    });
  });
});
