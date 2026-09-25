jQuery(document).ready(function ($) {
	$('#formLogin').validate({
		rules: {
			usuario: { required: true },
			pass: { required: true }
		},
		messages: {
			usuario: { required: 'El usuario es requerido' },
			pass: { required: 'La contraseña es requerida' }
		},
		submitHandler: function () {
			var data = 'accion=login&usuario=' + encodeURIComponent($.trim($('#usuario').val())) +
				'&contrasena=' + encodeURIComponent($('#pass').val());

			$.ajax({
				url: 'index.php',
				type: 'POST',
				data: data,
				beforeSend: function () {
					$('#mensaAV').html(`<div class="alert alert-primary alert-dismissible fade show" role="alert">
						<i class="fas fa-info-circle"></i> <strong>Cargando . . .</strong>
						<button type="button" class="close" data-dismiss="alert">&times;</button>
					</div>`);
					$('#mensaAV').show();
					$('#bIngresarLogin').prop('disabled', true);
					$('#bIngresarLogin').html('Iniciar <div class="spinner-border spinner-border-sm text-light" role="status"></div>');
				}
			})
			.done(function (res) {
				if ($.trim(res) === 'Correcto') {
					setTimeout(function () {
						$('#mensaAV').html(`<div class="alert alert-success alert-dismissible fade show" role="alert">
							<i class="fas fa-check-circle"></i> <strong>Accediendo . . .</strong>
						</div>`);
						setTimeout(function () { window.location.reload(); }, 800);
					}, 600);
				} else if ($.trim(res) === '0') {
					setTimeout(function () {
						$('#mensaAV').html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="fas fa-exclamation-triangle"></i> <strong>Usuario o contraseña incorrectos.</strong>
						</div>`);
					}, 600);
				} else {
					setTimeout(function () {
						$('#mensaAV').html(`<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="fas fa-exclamation-triangle"></i> <strong>Error inesperado.</strong>
						</div>`);
						console.log($.trim(res));
					}, 600);
				}
			})
			.fail(function () { console.log('Error ajax'); })
			.always(function () {
				setTimeout(function () {
					$('#bIngresarLogin').prop('disabled', false);
					$('#bIngresarLogin').html('Iniciar <i class="fas fa-check"></i>');
				}, 1200);
			});
		}
	});
});
