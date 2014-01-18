	<script type="text/javascript">

	function buscar(objeto)
	{

		$.ajax({
				type: 'POST',
				url: 'localhost/odalyssubastas/odalys/index.php?r=site/pujar',
				dataType: "html",
				data: {
					correo : objeto.value,
					datosDeControl: 1

				},
					beforeSend: function () {
					//$('#otro').text("cargando");
	            },
					success : function(data){
						json = data
				},
					error : function(XMLHttpRequest, textStatus, errorThrown) {
				},
					complete : function() { 
						$('#resultado').html(json);			 
			    }
		});

	}
	</script>

	<select onchange="buscar(this);" name="buscar" id="buscar">
    	<option value="">Seleccione usuario</option>

    	<?php
    		if($usuarios)
    		{
    			foreach ($usuarios as $t) {
    		
    		
    				echo '<option value="'.$t.'">'.$t.'</option>';
		
    			}
    		}
    		

    	?>
    </select>
	
	<div id="resultado">
		
	</div>