$('.editType').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        type: 'GET',
        url: $(this).attr('href'),
        success: function(data) {
            $('#modal-edit').modal('toggle');

            $('#form_edit').attr('action', APP_URL + '/eventos/tipos/' + data.info.id + '/editar');
            $('#edit_type').val(data.info.id);
        }  
    });
});