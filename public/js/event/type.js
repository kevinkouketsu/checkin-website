$('.editType').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        type: 'GET',
        url: $(this).attr('href'),
        success: function(data) {
            $('#error-type').css('display', 'none');
            $('#error-type').find('ul').empty();
            
            $('#success-type').css('display', 'none');
            $('#success-type').find('ul').empty();

            $('#modal-edit').modal('toggle');
            $('#edit_name').val(data.info.name);
            $('#edit_type').val(data.info.id);
        }  
    });
});

$('#btnEdit').on('click', function(e) {
    e.preventDefault();
    
    $.ajax({
        type: 'POST',
		dataType: 'json',
        data: $('#form_edit').serialize(),
        url: $('#form_edit').attr('action'),
        success: function (dados)  {
            if(dados.error == -1) {
                $('#error-type').css('display', 'none');
                $('#error-type').find('ul').empty();
                
                $('#success-type').css('display', 'block');
                $('#success-type').find('ul').empty();

                $("#success-type").find("ul").append('<li>' + dados.msg + '</li>');

                // close modal automatically
                setTimeout(function() {
                    $('#modal-edit').modal('hide');
                }, 2000);
            }
            else{
                $('#error-type').find('ul').empty();
                $('#success-type').css('disply', 'none');
    
                $("#error-type").find("ul").append('<li>'+ dados.msg +'</li>');
                $('#error-type').css('display', 'block');
            }
        },

        error: function(data) {
            $('#error-type').find('ul').empty();
            $('#success-type').css('disply', 'none');

            $.each(data.responseJSON.errors, function( key, value ) {
                $("#error-type").find("ul").append('<li>'+value+'</li>');
            });

            $('#error-type').css('display', 'block');
        }
    });
});