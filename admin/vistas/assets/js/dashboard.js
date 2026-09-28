/**
 * Dashboard General — PCV Soluciones
 * Estadísticas y Gráfica de Cotizaciones en el Tiempo
 */

var chartCotizacionesInstancia = null;

function v_dashboard() {
    $.post('index.php', { metodo: 'consultar', accion: 'dashboard' }, function (r) {
        try {
            var d = typeof r === 'string' ? JSON.parse(r) : r;
        } catch (e) {
            console.error('Error al procesar datos del dashboard:', e);
            return;
        }

        // Estadísticas principales
        $('#statServicios').text(d.servicios || 0);
        $('#statProductos').text(d.productos_servicios_activos || d.productos || 0);
        $('#statCotizaciones').text(d.cotizaciones_nuevas || 0);
        $('#statCotizacionesTotales').text(d.cotizaciones_totales || 0);

        // Renderizar gráfica de cotizaciones en el tiempo
        if (d.timeline_dias && Array.isArray(d.timeline_dias)) {
            renderGraficaCotizacionesDashboard(d.timeline_dias);
        }
    });
}

function renderGraficaCotizacionesDashboard(timeline) {
    var $contenedor = $('#chartCotizacionesTiempo');
    if ($contenedor.length === 0) return;

    // Destruir instancia previa si existe
    if (chartCotizacionesInstancia) {
        try {
            chartCotizacionesInstancia.destroy();
        } catch (err) {
            // Ignorar si ya fue destruida
        }
        chartCotizacionesInstancia = null;
    }

    $contenedor.empty();

    var categorias = timeline.map(function (item) { return item.label; });
    var valores = timeline.map(function (item) { return item.total; });

    // Verificar si ApexCharts está disponible
    if (typeof ApexCharts === 'undefined') {
        $contenedor.html('<div class="alert alert-light border text-center py-4 text-muted small"><i class="bi bi-info-circle me-1"></i> Cargando componente de gráficas...</div>');
        return;
    }

    var options = {
        series: [{
            name: 'Cotizaciones',
            data: valores
        }],
        chart: {
            type: 'area',
            height: 290,
            toolbar: {
                show: false
            },
            zoom: {
                enabled: false
            },
            fontFamily: "'Plus Jakarta Sans', system-ui, sans-serif"
        },
        colors: ['#1A4D2E'],
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'light',
                type: 'vertical',
                shadeIntensity: 0.5,
                gradientToColors: ['#2E7D32'],
                inverseColors: false,
                opacityFrom: 0.45,
                opacityTo: 0.05,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth',
            width: 3,
            colors: ['#1A4D2E']
        },
        markers: {
            size: 4,
            colors: ['#1A4D2E'],
            strokeColors: '#FFFFFF',
            strokeWidth: 2,
            hover: {
                size: 7,
                sizeOffset: 3
            }
        },
        xaxis: {
            categories: categorias,
            labels: {
                style: {
                    colors: '#64748B',
                    fontSize: '11px',
                    fontWeight: 500
                }
            },
            axisBorder: {
                show: false
            },
            axisTicks: {
                show: false
            },
            tooltip: {
                enabled: false
            }
        },
        yaxis: {
            min: 0,
            forceNiceScale: true,
            labels: {
                formatter: function (val) {
                    return Math.round(val);
                },
                style: {
                    colors: '#64748B',
                    fontSize: '11px'
                }
            }
        },
        grid: {
            borderColor: '#F1F5F9',
            strokeDashArray: 4,
            yaxis: {
                lines: {
                    show: true
                }
            },
            xaxis: {
                lines: {
                    show: false
                }
            },
            padding: {
                top: 0,
                right: 15,
                bottom: 0,
                left: 10
            }
        },
        tooltip: {
            theme: 'dark',
            x: {
                show: true
            },
            y: {
                formatter: function (val) {
                    return val + (val === 1 ? ' cotización' : ' cotizaciones');
                }
            }
        }
    };

    chartCotizacionesInstancia = new ApexCharts($contenedor[0], options);
    chartCotizacionesInstancia.render();
}
