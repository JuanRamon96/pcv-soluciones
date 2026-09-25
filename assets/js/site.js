$(function(){
  var tel = document.getElementById('cotTel');
  if (tel && window.IMask) {
    IMask(tel, {mask:'00 0000 0000'});
  }
  $('#formCotizar').on('submit', function(e){
    e.preventDefault();
    var $f = $(this);
    $.post($f.attr('action') || 'cotizar.php', $f.serialize(), function(resp){
      var r = resp;
      try { if (typeof resp === 'string') r = JSON.parse(resp); } catch(err) {}
      if (r && r.ok) {
        Swal.fire({icon:'success', title:'¡Cotización enviada!', text:'Te redirigimos a WhatsApp…', timer:1800, showConfirmButton:false})
          .then(function(){ if(r.whatsapp) window.open(r.whatsapp, '_blank'); });
        $f[0].reset();
      } else {
        Swal.fire({icon:'error', title:'No se pudo enviar', text:(r && r.error) || 'Intenta de nuevo'});
      }
    }).fail(function(){
      Swal.fire({icon:'error', title:'Error de red'});
    });
  });
});
