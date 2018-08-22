var tableReport = null;
var tableReportSold = null;
var chart01 = null;
var chart02 = null;

$(document).ready(function (){
	
	var table = $('#presenceList').DataTable({
		"bInfo": false,
		"ajax": {
			"url":  APP_URL + '/util/listaConvidados',
			"type": "POST",
			"data": {
				"event_id": $("input[name=event_id]").val()
			}
		},
        "columnDefs": [ 
			{
				"targets": [6],
				"visible": false,
				"searchable": false
			},
			{
				"targets": -1,
				"data": null,
				"defaultContent": '<a name="remove" class="btn"><i class="fa fa-trash" aria-hidden="true"></i></a>'
			}
		]
	});
	
	var tableSell = $('#sellList').DataTable({
		"bInfo": false,
		"ajax": {
			"url":  APP_URL + '/util/listaVendas',
			"type": "POST",
			"data": {
				"event_id": $("input[name=event_id]").val()
			}
		},
        "columnDefs": [ 
			{
				"targets": [6],
				"visible": false,
				"searchable": false
			},
			{
				"targets": -1,
				"data": null,
				"defaultContent": '<a name="remove" class="btn"><i class="fa fa-trash" aria-hidden="true"></i></a> \
									<a name="sell" class="btn"><i class="fa  fa-check" aria-hidden="true"></i></a>'
			}
		]
	});
	
    $('#presenceList tbody').on( 'click', 'a', function () {
		removeOrSell(table, this);
	});
	
    $('#sellList tbody').on( 'click', 'a', function () {
		removeOrSell(tableSell, this);
	});
	
	var tableTotal = $('#totalGraduados').DataTable({
		paging: false,
		searching: false,
		ordering:  false,
		"bInfo": false,
		"ajax": {
			"url":  APP_URL + '/util/graduadosContador',
			"type": "POST",
			"data": {
				"event_id": $("input[name=event_id]").val()
			}
		}
	});
	
	var tableVendidos = $('#totalVendidos').DataTable({
		paging: false,
		searching: false,
		ordering:  false,
		"bInfo": false,
		"ajax": {
			"url":  APP_URL + '/util/totalVendidos',
			"type": "POST",
			"data": {
				"event_id": $("input[name=event_id]").val()
			}
		}
	});
	
	$('#sell_form').on('change', function() {
		$('#sell').val(this.value);
	})

    $('#searchMonitor').autocomplete({
		showNoSuggestionNotice: true,
		paramName: 'search',
		serviceUrl: APP_URL + '/util/getMonitor',
		minChars: 3,

		onSelect: function (suggestion) {
			$('input[name="pai_id"]').val(suggestion.data);
		}
	});

    $('#pai_cadastra').autocomplete({
		showNoSuggestionNotice: true,
		paramName: 'search',
		serviceUrl: APP_URL + '/util/getMonitor',
		minChars: 3,

		onSelect: function (suggestion) {
			$('input[name="pai_id_cadastra"]').val(suggestion.data);
		}
	});

    $('#searchStaff').autocomplete({
		showNoSuggestionNotice: true,
		paramName: 'search',
		serviceUrl: APP_URL + '/util/getMonitor',
		minChars: 3,

		onSelect: function (suggestion) {
			$('input[name="pai_id_staff"]').val(suggestion.data);
		}
	});

    $('#searchInvited').autocomplete({
		showNoSuggestionNotice: true,
		paramName: 'search',
		serviceUrl: APP_URL + '/util/getMonitor',
		minChars: 3,

		onSelect: function (suggestion) {
			$('input[name="convidado_id"]').val(suggestion.data);
		}
	});

	/* Datatable Report area */
	
});

function printErrorMsg (msg) {
	$(".print-error-msg").find("ul").html('');
	$(".print-error-msg").css('display','block');
	$.each( msg, function( key, value ) {
		$(".print-error-msg").find("ul").append('<li>'+value+'</li>');
	});
}

$('#btnCadastrar').on('click', function() {
	$.ajax({
		url: APP_URL + '/usuario/cadastrar',
		data: $("#form_cadastro").serialize(),
		method: 'POST',
		dataType: 'json',
		success: function(data) {
			if(data.error)
				printErrorMsg(data.error);
			else
			{
				$('#modal-cadastro').modal('toggle');
				$('#form_cadastro')[0].reset();
			}
		},
		error: function() {
			alert('error');
		}
	});
});

$('#btnGenerate').on('click', function (){	

	if(tableReport != null)
		tableReport.destroy();

	tableReport = $('#reportList').DataTable({
		"bInfo": false,
		"ajax": {
			"url":  APP_URL + '/report/staffOnEventList',
			"type": "POST",
			"data": {
				event_id: $("input[name=event_id]").val(),
				pai_id_staff: $("input[name=pai_id_staff]").val()
			}
		}

	});
	
	$.ajax({
		url: APP_URL + '/report/staffOnEventGraph',
		data: {
			event_id: $("input[name=event_id").val(),
			pai_id_staff: $("input[name=pai_id_staff]").val(),
			type: '2'
		},

		method: 'POST',
		dataType: 'json',
		success: function (dados) {
			let acesso      = [];
			let labels		= [];
			let chartData = {};

			for( var i in dados.data)
			{
				labels.push(dados.data[i][0]);
				acesso.push(dados.data[i][1]);

			}
			chartData = {
				labels: labels,
				datasets: [
					{
						label:'Venda',
						fillColor: "rgba(220,220,220,0.5)",
						strokeColor: "rgba(220,220,220,1)",
						pointColor: "rgba(220,220,220,1)",
						pointStrokeColor: "#fff",
						backgroundColor: [
							'rgba(255, 99, 132, 0.2)',
							'rgba(54, 162, 235, 0.2)',
						],
						data: acesso
					}
				]
			};

			var ctx = document.getElementById("myChart2").getContext("2d");
			if(chart02 != null) 
				chart02.destroy();

			chart02 = new Chart(ctx, {
				data: chartData,
				type: 'pie',
				options: {
				responsive: true,
				legend: {
					position: 'bottom',
				},
				title: {
					display: true,
					text: 'Equipe'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				tooltips: {
					callbacks: {
					label: function(tooltipItem, data) {
						var dataset = data.datasets[tooltipItem.datasetIndex];
						var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
							return previousValue + currentValue;
						});
							var currentValue = dataset.data[tooltipItem.index];
							var precentage = Math.floor(((currentValue/total) * 100)+0.5);         
							return data.labels[tooltipItem.index] + ' (' + currentValue + ') - ' + precentage + "%";
							}
						}
					}
				}
			});
		}
	});
    $.ajax({
		url: APP_URL + '/report/staffOnEventGraph',
		data: {
			event_id: $("input[name=event_id").val(),
			pai_id_staff: $("input[name=pai_id_staff]").val(),
			type: '1'
		},

        method: 'POST',
        dataType: 'json',
        success: function (dados) {
			var acesso      = [];
			var labels		= [];
			var chartData = {};

			for( var i in dados.data)
			{
				labels.push(dados.data[i][0]);
				acesso.push(dados.data[i][1]);

			}
			chartData = {
				labels: labels,
				datasets: [
					{
						label:'Venda',
						fillColor: "rgba(220,220,220,0.5)",
						strokeColor: "rgba(220,220,220,1)",
						pointColor: "rgba(220,220,220,1)",
						pointStrokeColor: "#fff",
						backgroundColor: [
							'rgba(255, 99, 132, 0.2)',
							'rgba(54, 162, 235, 0.2)',
						],
						data: acesso
					}
				]
			};

			var ctx = document.getElementById("myChart").getContext("2d");
			if(chart01 != null) 
				chart01.destroy();

			chart01 = new Chart(ctx, {
				data: chartData,
				type: 'pie',
				options: {
				  responsive: true,
				  legend: {
					position: 'bottom',
				  },
				  title: {
					display: true,
					text: 'Equipe'
				  },
				  animation: {
					animateScale: true,
					animateRotate: true
				  },
				  tooltips: {
					callbacks: {
					  label: function(tooltipItem, data) {
						var dataset = data.datasets[tooltipItem.datasetIndex];
						var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
						  return previousValue + currentValue;
						});
						var currentValue = dataset.data[tooltipItem.index];
						var precentage = Math.floor(((currentValue/total) * 100)+0.5);         
						return data.labels[tooltipItem.index] + ' (' + currentValue + ') - ' + precentage + "%";
					  }
					}
				  }
				}
			});
        }
    });
});

$('#btnDelete').on('click', function (){
	$.confirm({
		title: 'Deletar',
		type: 'red',
		theme: 'modern',
		typeAnimated: true,
		backgroundDismiss: false,       
		backgroundDismissAnimation: 'shake',
		content: 'Tem certeza que deseja deletar este evento? Esta ação não poderá ser desfeita',
		buttons: {
			sim: function () {
				$.ajax({
					url: APP_URL + '/eventos/deletar',
					headers : {
						'X-CSRF-Token' : $("input[name=_token]").val()
					},
					data: {
						event_id: $("input[name=event_id").val()
					},

					method: 'POST',
					dataType: 'json',
					success: function (dados) {
						if(dados.error == -1)
							window.location.href = dados.url;
					}
				});
			},
			cancelar: function( ) {

			}
		}
	});
});

$('#btnDoCheckin').on('click', function () {
    var data = $("#doCheckin").serialize();
    $.ajax({
        url: $("#doCheckin").attr('action'),
        type: 'POST',
        data: data,
		success: function(data)
		{
			var dados = JSON.parse(data);
			if(dados.error == -1) {
				reloadTables();
				
				$( "#searchMonitor" ).val('');
				$( "#searchInvited" ).val('');
				$( "#sell_form").val('0');
				$( "#sell").val('0');

				document.getElementById("showSuccess").style.display = 'block';
				document.getElementById("successMsg").innerHTML = dados.successMsg;
        	}
			else if(dados.error == 1) 
			{
				document.getElementById("showError").style.display = 'block';
				document.getElementById("errorMsg").innerHTML = dados.errorMsg;
			}
        },
        error: function(){
        }
    });
});

function removeCheckin(id) {
	// fazemos uma requisição
	$.ajax({
		url: APP_URL + '/eventos/removerCheckin',
		type:'POST',
		data: {
			id: id
		},
		success: function(data){
			reloadTables();
		},
		error: function() {
		}
	});
}

function sold(id, type) {
	// fazemos uma requisição
	$.ajax({
		url: APP_URL + '/eventos/sold',
		type:'POST',
		data: {
			id: id,
			type : type
		},
		success: function(data){
			var dados = JSON.parse(data);

			if(dados.error == -1) {
				reloadTables();
			}
			else{ 
				$.alert({
					title: 'Ops... Ocorreu um erro!',
					content: dados.msg,
					type: 'red',
					theme: 'modern',
					typeAnimated: true
				});
			}
		},
		error: function() {
		}
	});
}

function reloadTables() {
	$('#presenceList').DataTable().ajax.reload();
	$('#totalGraduados').DataTable().ajax.reload();
	$('#totalVendidos').DataTable().ajax.reload();
	$('#sellList').DataTable().ajax.reload();
}

function removeOrSell(table, parent)
{
	var data = table.row( $(parent).parents('tr') ).data();
	let cmd = $(parent).attr('name');

	if(cmd === "remove") {
		$.confirm({
			title: 'Remover',
			type: 'red',
			theme: 'modern',
			typeAnimated: true,
			autoClose: 'cancelar|10000',
			backgroundDismiss: false,
			backgroundDismissAnimation: 'shake',
			content: 'Tem certeza que deseja remover o checkin de ' + data[1] + '?',
			buttons: {
				confirmar: function () {
					removeCheckin(data[6]);
				},
				cancelar: function () {
				}
			}
		});
	}
	else if(cmd === "sell") {
		$.confirm({
			title: data[1],
			type: 'red',
			theme: 'modern',
			typeAnimated: true,
			backgroundDismiss: false,       
			backgroundDismissAnimation: 'shake',
			content: 'Você realizou uma venda para ' + data[1] + ' ou foi cancelado?',
			buttons: {
				vendi: function () {
					sold(data[6], 1);
				},
				cancelei: function () { 
					sold(data[6], 0);
				},
				voltar: function (){
				}
			}
		});
	}
}
