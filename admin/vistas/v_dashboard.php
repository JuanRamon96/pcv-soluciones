<div class="row g-3">
  <div class="col-md-4"><div class="card stat-card"><div class="card-body"><div class="text-muted small">Servicios activos</div><div class="display-6" id="statServicios">—</div></div></div></div>
  <div class="col-md-4"><div class="card stat-card"><div class="card-body"><div class="text-muted small">Ítems en catálogo</div><div class="display-6" id="statProductos">—</div></div></div></div>
  <div class="col-md-4"><div class="card stat-card"><div class="card-body"><div class="text-muted small">Cotizaciones nuevas</div><div class="display-6" id="statCotizaciones">—</div></div></div></div>
</div>
<p class="mt-4 text-muted">Usa el menú para administrar productos/servicios (sin precios) y dar seguimiento a cotizaciones.</p>
<script>
(function(){
  $.post('index.php',{metodo:'consultar',accion:'dashboard'}, function(r){
    try{ var d = typeof r==='string'? JSON.parse(r): r; }catch(e){ return; }
    $('#statServicios').text(d.servicios||0);
    $('#statProductos').text(d.productos||0);
    $('#statCotizaciones').text(d.cotizaciones_nuevas||0);
  });
})();
</script>
