const garden = $('.dataTable')
const gardenUrl = `${BASE_URL}/api/garden`
const addButton = $('#buttonAdd')
const modalTitle = $('.title')
const submitButton = $('#submit-button')


const formConfig = {
    fields: [
        {
            id: 'name',
            name: 'Nama Perumahan'
        },
        {
            id: 'address',
            name: 'Alamat Perumahan'
        },
        {
            id: 'email',
            name: 'Email'
        },
        {
            id: 'password',
            name: 'Password'
        },
    ]
}


const getInitData = () => {
    garden.DataTable({
        processing: true,
        serverSide: true,
        ajax: gardenUrl,
        columns: [
            {
                "orderable": false,
                "searchable": false,
                "render": function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {data: 'name', name: 'name'},
            {data: 'address', name: 'address'},
            {data: 'aksi', name: 'aksi'},
        ]
    });
}

$(function () {
    getInitData()
})

const resetForm = () => formConfig.fields.forEach(({id}) => $(`#${id}`).val(''))

$(function () {
    addButton.on('click', function () {
        modalTitle.text('Tambah Perkebunan')
        submitButton.text('Tambah')
        resetForm()
        $('#addGardenButton').modal('show');
    })

    $('#addGardenButton').on('hidden.bs.modal', function () {
        resetForm();
        $(this).find('.invalid-feedback').text('');
    });
})

submitButton.on('click', function () {
    const id = $('#id').val()
    $(this).text().toLowerCase() === "ubah" ? update(id) : store()
})

const store = () => {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: gardenUrl,
        method: 'POST',
        dataType: 'json',
        data: dataForm(),
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            $('#addGardenButton').modal('hide');
            resetForm();
            toastr.success(res.message, 'Success');
            reloadDatatable(garden);
        },
        error: ({responseJSON}) => {
            handleError(responseJSON);
        }
    });
}

const update = id => {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    $.ajax({
        url: `${gardenUrl}/${id}`,
        method: 'PUT',
        dataType: 'json',
        data: dataForm(),
        headers: {
            'X-CSRF-TOKEN': csrfToken
        },
        success: res => {
            $('#addGardenButton').modal('hide');
            resetForm()
            toastr.success(res.message, 'Success')
            reloadDatatable(garden)
        },
        error: ({responseJSON}) => {
            handleError(responseJSON)
        }
    })
}

const dataForm = () => {
    return {
        name: $('#name').val(),
        address: $('#address').val(),
        email: $('#email').val(),
        password: $('#password').val(),
    };
}

const reloadDatatable = table => table.DataTable().ajax.reload(null, false);

const handleError = (responseJSON) => {
    const { errors } = responseJSON;
    formConfig.fields.forEach(({ id, name }) => {
        if (errors.hasOwnProperty(id)) {
            $(`#${id}`).addClass("is-invalid");
            $(`#${id}`).next('.invalid-feedback').text(errors[id][0]);
        } else {
            $(`#${id}`).removeClass('is-invalid').next('.invalid-feedback').text('');
        }
    });
}








$(document).on('click', '.btn-edit', function () {
    const gardenId = $(this).data('id')
    $.ajax({
        url: `${gardenUrl}/${gardenId}`,
        method: 'GET',
        dataType: 'json',
        success: res => {
            $('#id').val(res.id)
            submitButton.text('Ubah')
            modalTitle.text('Ubah Perkebunan')
            formConfig.fields.forEach(({id}) => {
                if (id === 'email') {
                    $(`#${id}`).val(res?.users?.[0]?.email);
                } else {
                    $(`#${id}`).val(res?.[id]);
                }
            })
            $('#addGardenButton').modal('show');
        },
        error: err => {
            console.log(err)
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
                url: `${gardenUrl}/${id}`,
                method: 'DELETE',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                success: res => {
                    toastr.success(res.message, 'Success');
                    reloadDatatable(garden);
                },
                error: err => {
                        toastr.error('Gagal menghapus data. Silahkan coba lagi.', 'Error');
                }
            });
        }
    });
});
