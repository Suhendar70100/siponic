const information = $('.dataTable')
const informationUrl = `${BASE_URL}/api/information`
const addButton = $('#buttonAdd')
const modalTitle = $('.title')
const submitButton = $('#submit-button')
const listDevice = $('.list-device')


const formConfig = {
    fields: [
        {
            id: 'device_id',
            name: 'Nama Perkebunan'
        },
        {
            id: 'seeding_start_date',
            name: 'Penyemaian'
        }, 
        {
            id: 'harvest_date',
            name: 'Panen'
        }, 
        {
            id: 'harvest_yield',
            name: 'Hasil Panen'
        }, 
    ]
}

const getDevice = () => {
    listDevice.select2({
        dropdownParent: $('#addInformationButton'),
        placeholder: 'Pilih Perangkat',
        allowClear: true,
    })
}


const getInitData = () => {
    information.DataTable({
        processing: true,
        serverSide: true,
        ajax: informationUrl,
        columns: [
            {data: 'plants', name: 'plants'},
            {
                data: 'seeding_start_date',
                name: 'seeding_start_date',
                render: function(data, type, row) {
                    return moment(data).format('D MMMM YYYY');
                }
            },
            {
                data: 'harvest_date',
                name: 'harvest_date',
                render: function(data, type, row) {
                    return moment(data).format('D MMMM YYYY');
                }
            },
            {
                data: 'harvest_yield',
                name: 'harvest_yield',
                render: function(data, type, row) {
                    return data === null ? '-' : data + ' Kg';
                }
            },            
            {data: 'aksi', name: 'aksi', orderable: false, searchable: false},
        ]
    });
}

$(function () {
    moment.locale('id');
    getInitData()
})

const resetForm = () => formConfig.fields.forEach(({id}) => $(`#${id}`).val(''))

const reloadDatatable = table => table.DataTable().ajax.reload(null, false);

$(function () {
    addButton.on('click', function () {
        modalTitle.text('Tambah Informasi')
        submitButton.text('Tambah')
        resetForm()
        $('#addInformationButton').modal('show');
        getDevice();
    })

    $('#addInformationButton').on('hidden.bs.modal', function () {
        resetForm();
        $(this).find('.invalid-feedback').text('');
    });
})

submitButton.on('click', function () {
    const id = $('#id').val()
    console.log(id);
    $(this).text().toLowerCase() === "ubah" ? update(id) : store()
})

const dataForm = () => {
    return {
        device_id: $('#device_id').val(),
        seeding_start_date: $('#seeding_start_date').val(),
        harvest_date: $('#harvest_date').val(),
        harvest_yield: $('#harvest_yield').val(),
    };
}

const store = () => {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: informationUrl,
        method: 'POST',
        dataType: 'json',
        data: dataForm(),
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            $('#addInformationButton').modal('hide');
            resetForm();
            toastr.success(res.message, 'Success');
            reloadDatatable(information);
        },
        error: ({responseJSON}) => {
            handleError(responseJSON);
        }
    });
}

const handleError = (responseJSON) => {
    const {errors} = responseJSON
    formConfig.fields.forEach(({id}) => {
        if (!errors.hasOwnProperty(id)) {
            $('#' + id).removeClass('is-invalid')
        } else if ($(`#${id}`).hasClass('list-device')) {
            getDevice()
            $(`#${id}`).addClass("is-invalid").next().next().text(errors[id][0]);
        } else {
            $(`#${id}`).addClass("is-invalid").next().text(errors[id][0]);
        }
    })
}


$(document).on('click', '.btn-edit', function () {
    const informationId = $(this).data('id')
    $.ajax({
        url: `${informationUrl}/${informationId}`,
        method: 'GET',
        dataType: 'json',
        success: res => {
            $('#id').val(res.id)
            submitButton.text('Ubah')
            modalTitle.text('Ubah Informasi')
            formConfig.fields.forEach(({id}) => {
                if (id === 'device_id') {
                    $(`#${id}`).select2({
                        dropdownParent: $("#addInformationButton"),
                        placeholder: "Pilih Perangkat",
                    }).val(res?.[id]).trigger('change')
                } else {
                    $(`#${id}`).val(res?.[id]);
                }
            })
            $('#addInformationButton').modal('show');
        },
        error: err => {
            console.log(err)
        }
    })
})


const update = id => {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: `${informationUrl}/${id}`,
        method: 'PUT',
        dataType: 'json',
        data: dataForm(),
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            $('#addInformationButton').modal('hide');
            resetForm()
            toastr.success(res.message, 'Success')
            reloadDatatable(information)
        },
        error: ({responseJSON}) => {
            handleError(responseJSON)
        }
    })
}

$(document).on('click', '.btn-delete', function () {
    const id = $(this).data('id');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    Swal.fire({
        title: 'Anda Yakin?',
        text: "Data yang dihapus tidak bisa dikembalikan",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Tidak',
        customClass: {
            confirmButton: 'btn btn-danger me-3 waves-effect waves-light',
            cancelButton: 'btn btn-label-secondary waves-effect'
        },
        buttonsStyling: false
    }).then(result => {
        if (result.value) {
            $.ajax({
                url: `${informationUrl}/${id}`,
                method: 'DELETE',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: res => {
                    toastr.success(res.message, 'Success');
                    reloadDatatable(information);
                },
                error: err => {
                        toastr.error('Gagal menghapus data. Silahkan coba lagi.', 'Error');
                }
            });
        }
    });
});
