function v_dashboard() {
	$.post('index.php', { metodo: 'consultar', accion: 'dashboard' }, function (r) {
		try { var d = typeof r === 'string' ? JSON.parse(r) : r; } catch (e) { return; }
		$('#statServicios').text(d.servicios || 0);
		$('#statProductos').text(d.productos || 0);
		$('#statCotizaciones').text(d.cotizaciones_nuevas || 0);
	});
}
