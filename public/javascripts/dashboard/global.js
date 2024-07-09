document.addEventListener('DOMContentLoaded', function () {
    const deviceSelect = $('#device');
    const filterButton = document.querySelector('.btn-filter');
    let intervalId;
    let currentDeviceId;

    function toggleButtonState() {
        const deviceValue = deviceSelect.val();
        
        if (deviceValue) {
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

    filterButton.addEventListener('click', function () {
        const deviceId = deviceSelect.val();

        if (deviceId !== currentDeviceId) {
            currentDeviceId = deviceId;
            resetCharts();
        }

        if (intervalId) {
            clearInterval(intervalId);
        }

        fetchData(deviceId);
        intervalId = setInterval(() => fetchData(deviceId), 5000); 
    });

    function fetchData(deviceId) {
        $.ajax({
            url: `${BASE_URL}/api/realtime`,
            method: 'GET',
            dataType: 'JSON',
            data: {
                device_id: deviceId
            },
            success: res => {
                console.log(res);
                updateCharts(res);
            },
            beforeSend: () => {
                console.log("Request is being sent...");
            },
            error: err => {
                console.error(err);
            }
        });
    }

    const optionsChart = (config) => {
        return {
            series: config.data.map(series => ({ name: '', data: series })),
            chart: {
                id: 'realtime',
                animations: {
                    enabled: true,
                    easing: 'linear',
                    dynamicAnimation: {
                        speed: 1000
                    }
                },
                height: 350,
                type: 'line',
                toolbar: {
                    show: false
                }
            },
            noData: {
                text: 'Loading...'
            },
            colors: ['#5272F2', '#F8BDEB', '#FBECB2'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'straight',
            },
            title: {
                text: config.text,
                align: 'left'
            },
            xaxis: {
                categories: config.categories,
                title: {
                    text: 'Time'
                }
            },
            yaxis: {
                title: {
                    text: config.label
                },
                min: config.min,
                max: config.max
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                floating: true,
                offsetY: -25,
                offsetX: -5
            }
        }
    }

    const chartConfigs = {
        waterPh: {
            chart: {
                height: 150,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '70%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            show: true,
                            offsetY: 10,
                            fontWeight: 700,
                            fontSize: '1.2rem',
                            fontFamily: 'Inter',
                            color: '#333333',
                            formatter: function(val) {
                                return val + ' pH';
                            }
                        }
                    },
                    track: {
                        background: '#f2f2f2'
                    }
                }
            },
            stroke: {
                lineCap: 'round'
            },
            colors: ['#20b2aa'],
            series: [0], 
            labels: ['Water pH']
        },
        temperature: {
            chart: {
                height: 150,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '70%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            show: true,
                            offsetY: 10,
                            fontWeight: 700,
                            fontSize: '1.2rem',
                            fontFamily: 'Inter',
                            color: '#333333',
                            formatter: function(val) {
                                return val + ' °C';
                            }
                        }
                    },
                    track: {
                        background: '#f2f2f2'
                    }
                }
            },
            stroke: {
                lineCap: 'round'
            },
            colors: ['#ff7f50'],
            series: [0], 
            labels: ['Temperature']
        },
        humidity: {
            chart: {
                height: 150,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '70%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            show: true,
                            offsetY: 10,
                            fontWeight: 700,
                            fontSize: '1.2rem',
                            fontFamily: 'Inter',
                            color: '#333333',
                            formatter: function(val) {
                                return val + ' %';
                            }
                        }
                    },
                    track: {
                        background: '#f2f2f2'
                    }
                }
            },
            stroke: {
                lineCap: 'round'
            },
            colors: ['#1e90ff'],
            series: [0], 
            labels: ['Humidity']
        },
        ppm: {
            chart: {
                height: 150,
                type: 'radialBar',
                sparkline: {
                    enabled: true
                }
            },
            plotOptions: {
                radialBar: {
                    hollow: {
                        size: '70%'
                    },
                    dataLabels: {
                        name: {
                            show: false
                        },
                        value: {
                            show: true,
                            offsetY: 10,
                            fontWeight: 700,
                            fontSize: '1.2rem',
                            fontFamily: 'Inter',
                            color: '#333333',
                            formatter: function(val) {
                                return val + ' ppm';
                            }
                        }
                    },
                    track: {
                        background: '#f2f2f2'
                    }
                }
            },
            stroke: {
                lineCap: 'round'
            },
            colors: ['#32cd32'],
            series: [0],
            labels: ['PPM']
        }
    };

    const charts = {};

    for (let key in chartConfigs) {
        const chartEl = document.querySelector(`#${key}Chart`);
        if (chartEl) {
            charts[key] = new ApexCharts(chartEl, chartConfigs[key]);
            charts[key].render();
        }
    }

    const realTimeChart1Config = {
        data: [[], [], []],
        categories: [],
        text: 'Real-time Water pH, Temperature, Humidity',
        label: 'Value',
    };

    const realTimeChart2Config = {
        data: [[], [], []],
        categories: [],
        text: 'Real-time PPM, Max PPM, Min PPM',
        label: 'Value',
    };

    const realTimeChart1 = new ApexCharts(document.querySelector("#realTimeChart1"), optionsChart(realTimeChart1Config));
    const realTimeChart2 = new ApexCharts(document.querySelector("#realTimeChart2"), optionsChart(realTimeChart2Config));

    realTimeChart1.render();
    realTimeChart2.render();

    function updateCharts(data) {
        if (charts.waterPh) {
            charts.waterPh.updateSeries([data.water_ph]);
        }
        if (charts.temperature) {
            charts.temperature.updateSeries([data.temperature]);
        }
        if (charts.humidity) {
            charts.humidity.updateSeries([data.humidity]);
        }
        if (charts.ppm) {
            charts.ppm.updateSeries([data.ppm]);
        }

        const currentTime = `${new Date().getHours()}:${new Date().getMinutes()}:${new Date().getSeconds()}`;
        
        updateRealTimeChart(realTimeChart1, [data.water_ph, data.temperature, data.humidity], currentTime);
        updateRealTimeChart(realTimeChart2, [data.ppm, data.max_ppm, data.min_ppm], currentTime);
    }

    function updateRealTimeChart(chart, data, category) {
        const chartConfig = chart === realTimeChart1 ? realTimeChart1Config : realTimeChart2Config;
        
        chartConfig.data.forEach((series, index) => {
            series.push(data[index]);
            if (series.length > 10) {
                series.shift(); 
            }
        });

        chartConfig.categories.push(category);
        if (chartConfig.categories.length > 10) {
            chartConfig.categories.shift(); 
        }

        chart.updateOptions({
            series: chartConfig.data.map((d, i) => ({ name: chartConfig.text.split(', ')[i], data: d })),
            xaxis: {
                categories: chartConfig.categories
            }
        });
    }

    function resetCharts() {
        realTimeChart1Config.data = [[], [], []];
        realTimeChart1Config.categories = [];
        realTimeChart2Config.data = [[], [], []];
        realTimeChart2Config.categories = [];

        realTimeChart1.updateOptions({
            series: realTimeChart1Config.data.map((d, i) => ({ name: realTimeChart1Config.text.split(', ')[i], data: d })),
            xaxis: {
                categories: realTimeChart1Config.categories
            }
        });

        realTimeChart2.updateOptions({
            series: realTimeChart2Config.data.map((d, i) => ({ name: realTimeChart2Config.text.split(', ')[i], data: d })),
            xaxis: {
                categories: realTimeChart2Config.categories
            }
        });
    }
});
