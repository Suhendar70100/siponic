const device = $('.dataTable')
const deviceUrl = `${BASE_URL}/api/device`
const addButton = $('#buttonAdd')
const modalTitle = $('.title')
const submitButton = $('#submit-button')
const listGarden = $('.list-garden')


const formConfig = {
    fields: [
        {
            id: 'garden_id',
            name: 'Nama Perkebunan'
        },
        {
            id: 'max_ppm',
            name: 'Maksimal Nutrisi'
        }, 
        {
            id: 'min_ppm',
            name: 'Minimal Nutrisi'
        }, 
        {
            id: 'plants',
            name: 'Tanaman'
        },
    ]
}

const getGarden = () => {
    listGarden.select2({
        dropdownParent: $('#addDeviceButton'),
        placeholder: 'Pilih Kebun',
        allowClear: true,
    })
}


const getInitData = () => {
    device.DataTable({
        processing: true,
        serverSide: true,
        ajax: deviceUrl,
        columns: [
            {data: 'guid', name: 'guid'},
            {data: 'garden', name: 'garden'},
            {data: 'max_ppm', name: 'max_ppm'},
            {data: 'min_ppm', name: 'min_ppm'},
            {data: 'plants', name: 'plants'},
            {data: 'status', name: 'status'},
            {data: 'aksi', name: 'aksi'},
        ]
    });
}

$(function () {
    getInitData()
})

const resetForm = () => formConfig.fields.forEach(({id}) => $(`#${id}`).val(''))

const reloadDatatable = table => table.DataTable().ajax.reload(null, false);

$(function () {
    addButton.on('click', function () {
        modalTitle.text('Tambah Perangkat')
        submitButton.text('Tambah')
        resetForm()
        $('#addDeviceButton').modal('show');
        getGarden();
    })

    $('#addDeviceButton').on('hidden.bs.modal', function () {
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
        garden_id: $('#garden_id').val(),
        max_ppm: $('#max_ppm').val(),
        min_ppm: $('#min_ppm').val(),
        plants: $('#plants').val(),
    };
}

const store = () => {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: deviceUrl,
        method: 'POST',
        dataType: 'json',
        data: dataForm(),
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            $('#addDeviceButton').modal('hide');
            resetForm();
            toastr.success(res.message, 'Success');
            reloadDatatable(device);
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
        } else if ($(`#${id}`).hasClass('list-garden')) {
            getGarden()
            $(`#${id}`).addClass("is-invalid").next().next().text(errors[id][0]);
        } else {
            $(`#${id}`).addClass("is-invalid").next().text(errors[id][0]);
        }
    })
}


$(document).on('click', '.btn-edit', function () {
    const deviceId = $(this).data('id')
    $.ajax({
        url: `${deviceUrl}/${deviceId}`,
        method: 'GET',
        dataType: 'json',
        success: res => {
            $('#id').val(res.id)
            submitButton.text('Ubah')
            modalTitle.text('Ubah Perangkat')
            formConfig.fields.forEach(({id}) => {
                if (id === 'garden_id') {
                    $(`#${id}`).select2({
                        dropdownParent: $("#addDeviceButton"),
                        placeholder: "Pilih Perkebunan",
                    }).val(res?.[id]).trigger('change')
                } else {
                    $(`#${id}`).val(res?.[id]);
                }
            })
            $('#addDeviceButton').modal('show');
        },
        error: err => {
            console.log(err)
        }
    })
})


const update = id => {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: `${deviceUrl}/${id}`,
        method: 'PUT',
        dataType: 'json',
        data: dataForm(),
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            $('#addDeviceButton').modal('hide');
            resetForm()
            toastr.success(res.message, 'Success')
            reloadDatatable(device)
        },
        error: ({responseJSON}) => {
            handleError(responseJSON)
        }
    })
}

$(document).on('click', '.switch-status', function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: `${deviceUrl}/status/${$(this).data('id')}`,
        method: 'POST',
        dataType: 'JSON',
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            toastr.success(res.message, 'Success')
            reloadDatatable(device)
        }
    })
})

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
                url: `${deviceUrl}/${id}`,
                method: 'DELETE',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: res => {
                    toastr.success(res.message, 'Success');
                    reloadDatatable(device);
                },
                error: err => {
                        toastr.error('Gagal menghapus data. Silahkan coba lagi.', 'Error');
                }
            });
        }
    });
});
