function cargarVista(vista, titulo){
  $('#tituloVista').text(titulo || '');
  $('.cargarVista').removeClass('active');
  $('.cargarVista[carga="'+vista+'"]').addClass('active');
  $.post('/admin/index.php', {metodo:'cambiar', accion: vista}, function(html){
    $('#contenido').html(html);
  });
  $('#sidebar').removeClass('open');
}
$(document).on('click', '.cargarVista', function(){
  cargarVista($(this).attr('carga'), $(this).attr('titulo'));
});
$('#bToggleSidebar').on('click', function(){ $('#sidebar').toggleClass('open'); });
$('#bCerrarSesion').on('click', function(){
  Swal.fire({title:'¿Cerrar sesión?', showCancelButton:true, confirmButtonText:'Salir'}).then(function(r){
    if(!r.isConfirmed) return;
    $.post('/admin/index.php', {metodo:'eliminar', accion:'login'}, function(resp){
      if(String(resp).trim()==='Correcto') location.reload();
    });
  });
});
// myDataTable helper (estilo Juan)
window.myDataTable = function(selector, opts){
  if ($.fn.DataTable.isDataTable(selector)) {
    $(selector).DataTable().destroy();
  }
  return $(selector).DataTable($.extend({
    language: { url: 'https://cdn.datatables.net/plug-ins/1.13.8/i18n/es-ES.json' },
    pageLength: 10,
    order: []
  }, opts||{}));
};
