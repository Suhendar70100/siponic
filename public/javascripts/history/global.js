const historyUrl = `${BASE_URL}/api/history`;
let dt_filter_table = $('.dt-column-search');
let dt_filter;

$(document).ready(function() {
    if (dt_filter_table.length) {
        dt_filter = dt_filter_table.DataTable({
            processing: true,
            ajax: {
                url: historyUrl,
                type: 'GET',
                dataSrc: 'data'
            },
            columns: [
                { data: 'device_guid', name: 'device.guid' },
                { data: 'water_ph', name: 'water_ph' },
                { data: 'temperature', name: 'temperature' },
                { data: 'humidity', name: 'humidity' },
                { data: 'ppm', name: 'ppm' },
            ],
            initComplete: function() {
                $('.dt-column-search thead tr').clone(true).appendTo('.dt-column-search thead');
                $('.dt-column-search thead tr:eq(1) th').each(function (i) {
                    let title = $(this).text();

                    let select = $('<select class="form-control select2"><option value="">All</option></select>')
                        .appendTo($(this).empty())
                        .on('change', function () {
                            let val = $.fn.dataTable.util.escapeRegex(
                                $(this).val()
                            );

                            dt_filter.column(i)
                                .search(val ? '^' + val + '$' : '', true, false)
                                .draw();
                        });
                        $('.select2').select2()

                    let uniqueOptions = getUniqueColumnOptions(i);

                    uniqueOptions.forEach(function (val) {
                        select.append('<option value="' + val + '">' + val + '</option>');
                    });
                });
            }
        });

        $('div.head-label').html('<h5 class="card-title mb-0">Daftar Jadwal Mengajar</h5>');
    }
});

function getUniqueColumnOptions(columnIndex) {
    let uniqueOptions = [];
    let columnData = dt_filter.column(columnIndex).data().toArray();

    if (columnData) {
        uniqueOptions = columnData.filter((value, index, self) => {
            return self.indexOf(value) === index;
        });
    }

    return uniqueOptions;
}

$(document).ready(function() {
    let dateInput = document.createElement('input');
    $(dateInput).addClass('form-control').attr('type', 'text').attr('id', 'daterange') .attr('placeholder', 'Pilih Rentang Tanggal').appendTo('.card-datatable .dataTables_filter');
    
    $(dateInput).daterangepicker({
        autoUpdateInput: false,
        locale: {
            cancelLabel: 'Clear'
        }
    });

    $('#daterange').on('apply.daterangepicker', function(ev, picker) {
        let startDate = picker.startDate.format('YYYY-MM-DD');
        let endDate = picker.endDate.format('YYYY-MM-DD');

        $('#daterange').val(startDate + ' - ' + endDate);
        dt_filter.ajax.url(historyUrl + '?start_date=' + startDate + '&end_date=' + endDate).load();
    });

    $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
        $('#daterange').val('');

        dt_filter.ajax.url(historyUrl).load();
    });
});
