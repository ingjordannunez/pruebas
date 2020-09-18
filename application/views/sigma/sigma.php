<!DOCTYPE html>
<html>
<head>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>sigma</title>
	<style type="text/css">	
		form label {
		  display: inline-block;
		  width: 100px;
		}

		form div {
		  margin-bottom: 10px;
		}

		.error {
		  color: red;
		  margin-left: 5px;
		}

		label.error {
		  display: inline;
		}
	</style>
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-md-6 offset-3 mt-5 mb-3" align="center">
				<img class="mx-auto d-block img-fluid" src="https://sigma-studios.s3-us-west-2.amazonaws.com/test/sigma-logo.png">
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 offset-2 mb-2" align="center">
				<p class="h2">Prueba de Desarrollo Sigma</p>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 offset-2 mb-3" align="center">
				<p class="h6">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
					tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
					quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
					consequat.
				</p>
			</div> 
		</div>
		<div class="row m-3">
			<div class="col-md-6 mb-4">
				<img class="mx-auto d-block img-fluid" src="https://sigma-studios.s3-us-west-2.amazonaws.com/test/sigma-image.png" width="500" height="500">
			</div>
			<div class="col-md-5 offset-1">
				<div class="jumbotron shadow p-3 bg-white rounded" style="background:transparent !important">
					<div class="container">
						<form action="guardar" method="post" id="form-registro">
							<div class="form-group">
							    <label for="departamento"><strong>Departamento pruebas*</strong></label>
							    <select class="form-control" id="departamento" name="departamento">
							    	<option value="">Elegir...</option>
							    	<?php
							    		foreach ($dep_ciud as $departamento => $ciudad) {
							    			echo "<option value='".$departamento."'>".$departamento."</option>";
							    		}
							    	?>
							    </select>
							</div>
							<div class="form-group">
							    <label for="ciudad"><strong>Ciudad xxxxx*</strong></label>
							    <select class="form-control" id="ciudad" name="ciudad">
							    	<option value=""></option>
							    </select>
							</div>
							<div class="form-group">
							    <label for="nombre"><strong>Nombre *</strong></label>
							    <input type="text" class="form-control" id="nombre" name="nombre" maxlength="50">
							</div>
							<div class="form-group">
							    <label for="correo"><strong>Correo *</strong></label>
							    <input type="text" class="form-control" id="correo" name="correo">
							</div>
							<div align="center">
								<button type="submit" class="btn btn-danger btn-lg" id="enviar" style="border-radius: 20px;"><strong>Enviar</strong></button>
							</div>
						</form>						
					</div>
				</div>
			</div>
		</div>
	</div>	

	<div class="modal" tabindex="-1" role="dialog" id="modal-response">
	  	<div class="modal-dialog" role="document">
	    	<div class="modal-content">
	      		<div class="modal-body" align="center">
	        		<p id="resp"></p>
	      		</div>
	      		<div class="modal-footer">
	        		<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
	      		</div>
	    	</div>
	  	</div>
	</div>
</body>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script type="text/javascript">	
	$('#form-registro').submit(function(e) {
		e.preventDefault();
		var departamento = $('#departamento').val();
		var ciudad = $('#ciudad').val();
		var nombre = $("#nombre").val();
		var correo = $("#correo").val();
		var error = false;

    	$(".error").remove();

	    if (departamento=="") {
	      	$('#departamento').after('<span class="error"> Dpto es Obligatorio</span>');
	      	error = true;
	    }
	    if (ciudad=="") {
	      	$('#ciudad').after('<span class="error"> Ciudad es Obligatorio</span>');
	      	error = true;
	    }
	    if(nombre.length<1){
	    	$('#nombre').after('<span class="error">Nombre es Obligatorio</span>');
	    	error = true;
	    } else if(nombre.length>50){
	    	$('#nombre').after('<span class="error">nombre excede la cantidad de caracteres</span>');
	    	error = true;
	    }
	    if(correo.length<1){
	    	$('#correo').after('<span class="error">Correo es Obligatorio</span>');
	    	error = true;
	    } else if(correo.length>30){
	    	$('#correo').after('<span class="error">Correo excede la cantidad de caracteres</span>');
	    	error = true;
	    } else{
	    	var regEx = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,6})+$/;
      		var validEmail = regEx.test(correo);
     		if (!validEmail) {
        		$('#correo').after('<span class="error">Correo no Valido</span>');
        		error = true;
      		}
	    }
	    //console.log(error);
	    if(error==false){
	    	$.ajax({
	    		url: 'index.php/sigma/guardar',
	    		type: 'post',
	    		dataType: 'json',
	    		data: $(this).serialize(),
	    		success:function(data){
	    			if(data.status=="success"){
	    				$("#modal-response").modal("show");
	    				$("#resp").text(data.message);
	    				$('#ciudad').find("option").remove();
	    				$('#departamento').val("");
	    				$("#correo").val("");
	    				$("#nombre").val("");
	    			}
	    		}
	    	});	    	
	    }
	});

	$("#departamento").change(function(e){
		e.preventDefault();
		$.ajax({
			url: "index.php/sigma/buscarCiudad",
			type: 'POST',
			dataType: 'json',
			data: {dpto: $(this).val()},
			success: function(data){
				//console.log(data.ciudades);
            	$('#ciudad').find("option").remove();
            	$('#ciudad').append("<option value=''>Elegir...</option>");
            	$.each(data.ciudades, function(index, val) {
            		//console.log(val);
                	$('#ciudad').append("<option value='"+val+"'>"+val+"</option>");
            	});
			}
		})
	})	
</script>


</html>
