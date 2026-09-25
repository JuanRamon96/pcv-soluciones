(function(){
  var modal;
  function loadTabla(){
    $.post('index.php', {
      metodo:'consultar', accion:'productos',
      filtro: $('#filtroProductos').val(),
      tipo: $('#filtroTipo').val()
    }, function(html){
      $('#tbodyProductos').html(html);
      if ($.fn.DataTable) {
        if ($.fn.DataTable.isDataTable('#tablaProductos')) $('#tablaProductos').DataTable().destroy();
        myDataTable('#tablaProductos', {columnDefs:[{orderable:false, targets:[0,6]}]});
      }
    });
  }
  function filterSubs(){
    var clas = $('#prodClas').val();
    $('#prodSub option').each(function(){
      var dc = $(this).data('clas');
      if (!dc) { $(this).prop('hidden', false); return; }
      $(this).prop('hidden', String(dc) !== String(clas));
    });
  }
  $('#bAgregarProducto').on('click', function(){
    $('#formProducto')[0].reset();
    $('#prodId').val('');
    $('#prodActivo').prop('checked', true);
    filterSubs();
    modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalProducto'));
    modal.show();
  });
  $('#prodClas').on('change', filterSubs);
  $('#filtroProductos, #filtroTipo').on('change keyup', function(){ loadTabla(); });
  $(document).on('click', '.bEditarProducto', function(){
    var id = $(this).data('id');
    $.post('index.php', {metodo:'detalles', accion:'productos', id:id}, function(r){
      var d = typeof r==='string' ? JSON.parse(r) : r;
      if(!d.ok) return;
      var p = d.data;
      $('#prodId').val(p.id);
      $('#prodTipo').val(p.tipo);
      $('#prodClas').val(p.clasificacion_id);
      filterSubs();
      $('#prodSub').val(p.subtipo_id || '');
      $('#prodNombre').val(p.nombre);
      $('#prodResumen').val(p.resumen||'');
      $('#prodDesc').val(p.descripcion||'');
      $('#prodOrden').val(p.orden||0);
      $('#prodDestacado').prop('checked', p.destacado==1);
      $('#prodActivo').prop('checked', p.activo==1);
      modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalProducto'));
      modal.show();
    });
  });
  $(document).on('click', '.bEliminarProducto', function(){
    var id = $(this).data('id');
    Swal.fire({title:'¿Desactivar registro?', showCancelButton:true, confirmButtonText:'Sí'}).then(function(res){
      if(!res.isConfirmed) return;
      $.post('index.php', {metodo:'eliminar', accion:'productos', id:id}, function(resp){
        if(String(resp).trim()==='Correcto'){ Swal.fire({icon:'success', title:'Listo', timer:1200, showConfirmButton:false}); loadTabla(); }
        else Swal.fire({icon:'error', title:resp});
      });
    });
  });
  $('#formProducto').on('submit', function(e){
    e.preventDefault();
    var fd = new FormData(this);
    var id = $('#prodId').val();
    fd.append('metodo', id ? 'modificar' : 'insertar');
    fd.append('accion', 'productos');
    if(!$('#prodDestacado').is(':checked')) fd.delete('destacado');
    if(!$('#prodActivo').is(':checked')) fd.delete('activo');
    $.ajax({
      url:'index.php', method:'POST', data:fd, processData:false, contentType:false,
      success:function(resp){
        if(String(resp).trim()==='Correcto'){
          Swal.fire({icon:'success', title:'Guardado', timer:1200, showConfirmButton:false});
          bootstrap.Modal.getInstance(document.getElementById('modalProducto')).hide();
          loadTabla();
        } else Swal.fire({icon:'error', title: String(resp)});
      }
    });
  });
  loadTabla();
})();
