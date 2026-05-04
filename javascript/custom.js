$(document).on('ready', init);

function init(){
	console.log("My custom script");
	helloWorld();
}

function helloWorld(){
	
	var params = {
		"aParam" : 0
	};

	var context = {
		data:  params,
		url:   '../core/php/dispatcher.php',
		type:  'POST',
		
		beforeSend: function () {
			$("#main-content").html("Waiting...");
		},
		success:  function (response) {
			$("#main-content").html("<h1>" + response + "</h1>");
		},
		error:  function () {
			$("#main-content").html("Im broken");
		}
	};

	$.ajax(context);
}