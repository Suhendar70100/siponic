document.addEventListener('DOMContentLoaded', function () {
    const deviceSelect = $('#device');
    const monthInput = document.getElementById('select-month');
    const filterButton = document.querySelector('.btn-filter');
    const chartPpm = document.querySelector('#chartPpm');
    const chartWaterPh = document.querySelector('#chartWaterPh');
    const chartTemperature = document.querySelector('#chartTemperature');
    const chartHumidity = document.querySelector('#chartHumidity');
    const totalAverageChart = document.querySelector('#totalAverage');

    function toggleButtonState() {
        const deviceValue = deviceSelect.val();
        const monthValue = monthInput.value;
        
        if (deviceValue && monthValue) {
            filterButton.disabled = false;
        } else {
            filterButton.disabled = true;
        }
    }

    deviceSelect.select2({
        placeholder: 'Pilih Device',
        allowClear: true,
    });

    toggleButtonState();

    deviceSelect.on('select2:select select2:unselect', toggleButtonState);
    monthInput.addEventListener('input', toggleButtonState);

    $('.chart').hide();

    filterButton.addEventListener('click', function () {
        const deviceId = deviceSelect.val();
        const month = monthInput.value;

        $.ajax({
            url: `${BASE_URL}/api/monitoring`,
            method: 'GET',
            dataType: 'JSON',
            data: {
                device_id: deviceId,
                month: month
            },
            success: res => {
                console.log(res);
                $("#cover-spin").hide();
                $('.chart').show();
                totalAverage(res);
                renderChart(chartPpm, 'PPM', 'ppm', 'PPM', res.ppm, '#00E396', res.dates, res.periode);
                renderChart(chartWaterPh, 'pH Air', 'pH', 'pH', res.water_ph, '#FEB019', res.dates, res.periode);
                renderChart(chartTemperature, 'Suhu', '°C', 'Suhu', res.temperature, '#FF4560', res.dates, res.periode);
                renderChart(chartHumidity, 'Kelembaban', '%', 'Kelembaban', res.humidity, '#775DD0', res.dates, res.periode);
            },
            beforeSend: () => {
                console.log("Request is being sent...");
                $("#cover-spin").show();
            },
            error: err => {
                console.error(err);
            }
        });
    });

    function renderChart(element, title, unit, yAxisTitle, data, color, categories, period) {
        let options = {
            series: [{
                name: title,
                data: data ?? []
            }],
            chart: {
                type: 'bar',
                height: 350,
                toolbar: {
                    show: true,
                    tools: {
                        download: true
                    }
                }
            },
            colors: [color],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: categories,
                title: {
                    text: 'Tanggal'
                }
            },
            yaxis: {
                title: {
                    text: yAxisTitle
                },
                decimalsInFloat: false
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return val + ' ' + unit;
                    }
                }
            },
            title: {
                text: 'Data Rata-Rata ' + title,
                align: 'center',
                margin: 10,
                style: {
                    color: '#263238'
                }
            },
            subtitle: {
                text: 'Periode ' + period,
                align: 'center',
                margin: 10,
                offsetY: 20,
                style: {
                    color: '#9699a2'
                }
            }
        };

        // Destroy existing chart if any
        if (element._chart) {
            element._chart.destroy();
        }

        // Render new chart
        element._chart = new ApexCharts(element, options);
        element._chart.render();
    }

    const totalAverage = res => {
        let options = {
            series: [{
                data: res.monthly_averages.map(val => parseFloat(val.toFixed(2)))
            }],
            chart: {
                type: 'bar',
                height: 250
            },
            plotOptions: {
                bar: {
                    barHeight: '100%',
                    distributed: true,
                    horizontal: true,
                    dataLabels: {
                        position: 'bottom'
                    },
                }
            },
            colors: ['#00E396', '#FEB019', '#FF4560', '#775DD0'],
            dataLabels: {
                enabled: true,
                textAnchor: 'start',
                style: {
                    colors: ['#fff']
                },
                formatter: function (val, opt) {
                    return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val;
                },
                offsetX: 0,
                dropShadow: {
                    enabled: true
                }
            },
            stroke: {
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: ['PPM', 'pH Air', 'Suhu', 'Kelembaban'],
            },
            yaxis: {
                labels: {
                    show: false
                }
            },
            title: {
                text: 'Rata-Rata Data Perbulan',
                align: 'center',
                floating: true
            },
            tooltip: {
                x: {
                  show: false
                },
                y: {
                  title: {
                    formatter: function () {
                      return ''
                    }
                  }
                }
              }
        };
    
        // Destroy existing chart if any
        if (totalAverageChart._chart) {
            totalAverageChart._chart.destroy();
        }
    
        // Render new chart
        totalAverageChart._chart = new ApexCharts(totalAverageChart, options);
        totalAverageChart._chart.render();
    }
    
});
