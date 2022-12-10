$( document ).ready( function () {

	jQuery.validator.addMethod("campoValidar", function(value, element, param) {
		var titulo = $('#titulo').val();
		var texto = $('#texto').val();
		var hddTitulo = $('#hddTitulo').val();
		var hddTexto = $('#hddTexto').val();
		if ( titulo == hddTitulo && texto == hddTexto) {
			return false;
		}else{
			return true;
		}
	}, "Si desea guardar, debe hacer cambios en los textos.");
		
	$( "#form" ).validate( {
		rules: {
			titulo: 		{ required: true, minlength: 12, maxlength:200 },
			texto: 			{ required: true, campoValidar: true }
		},
		errorElement: "em",
		errorPlacement: function ( error, element ) {
			// Add the `help-block` class to the error element
			error.addClass( "help-block" );
			error.insertAfter( element );

		},
		highlight: function ( element, errorClass, validClass ) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-error" ).removeClass( "has-success" );
		},
		unhighlight: function (element, errorClass, validClass) {
			$( element ).parents( ".col-sm-12" ).addClass( "has-success" ).removeClass( "has-error" );
		},
		submitHandler: function (form) {
			return true;
		}
	});
	
	$("#btnSubmit").click(function(){		
	
		if ($("#form").valid() == true){
		
				//Activa icono guardando
				$('#btnSubmitWorker').attr('disabled','-1');
				$("#div_error").css("display", "none");
				$("#div_load").css("display", "inline");
			
				$.ajax({
					type: "POST",	
					url: base_url + "settings/save_procesos",	
					data: $("#form").serialize(),
					dataType: "json",
					contentType: "application/x-www-form-urlencoded;charset=UTF-8",
					cache: false,
					
					success: function(data){
                                            
						if( data.result == "error" )
						{
							$("#div_load").css("display", "none");
							$('#btnSubmitWorker').removeAttr('disabled');							
							return false;
						} 

						if( data.result )//true
						{	                                                        
							$("#div_load").css("display", "none");
							$('#btnSubmitWorker').removeAttr('disabled');

							var url = base_url + "settings/procesos/1";
							$(location).attr("href", url);
						}
						else
						{
							alert('Error. Reload the web page.');
							$("#div_load").css("display", "none");
							$("#div_error").css("display", "inline");
							$('#btnSubmitWorker').removeAttr('disabled');
						}	
					},
					error: function(result) {
						alert('Error. Reload the web page.');
						$("#div_load").css("display", "none");
						$("#div_error").css("display", "inline");
						$('#btnSubmitWorker').removeAttr('disabled');
					}
					
		
				});	
		
		}//if			
	});
});