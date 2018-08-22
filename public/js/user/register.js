function autoCompleteStaff()
{
    $('#searchMonitor').autocomplete({
		showNoSuggestionNotice: true,
		paramName: 'search',
		serviceUrl: APP_URL + '/util/getMonitor',
		minChars: 3,

		onSelect: function (suggestion) {
			$('input[name="father"]').val(suggestion.data);
		}
	});
}

$(document).ready(function() {
  	$("#telephone").inputmask({"mask": "(999) 9 9999-9999"});

	autoCompleteStaff();
});