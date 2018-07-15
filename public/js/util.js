function fillStates(select, $text, $dropdown) {    
	let dropdown = $($dropdown);

    if(select.options[select.selectedIndex].value == '-1')
    {
        $(select).prop('selectedIndex', 0);

        dropdown.empty();
        return;
    }

	dropdown.empty();

	dropdown.append('<option selected="true" disabled>Escolha a Cidade</option>');
	dropdown.prop('selectedIndex', 0);    

	$.ajax({
	    url: APP_URL + '/util/getCities/' + select.options[select.selectedIndex].value,
	    type: 'GET',
	    success: function(data){
	    	var dados = JSON.parse(data);

			$.each(dados, function (key, entry) {
				dropdown.append($('<option></option>').attr('value', entry.code).text(entry.name));
			});
	    },
	    error: function(){
	        alert('error');
	    }  
	});    
}
