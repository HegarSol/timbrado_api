<br>
<br>
<br>
<br>


<center><font size="6">Nuevo Usuario</font></center>

<br>


 <center>
  <div class="col-md-3">
  	<label>Nombre: </label>
  	   <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del Usuario" >
  </div>	
  <br>
  <div class="col-md-3">
  	 <label>Apellido: </label>
  	 <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido del Usuario" >
  </div>
   <br>
  <div class="col-md-3">
  	  <label>Email:</label>
  	  <input type="text" class="form-control" name="email" id="email" placeholder="Email del Usuario" >
  </div>
   <br>
  
            <div class="col-md-3">
              <label>Contraseña: </label>
              <input type="password" class="form-control" placeholder="Contraseña del Usuario" name="pass" id="pass" >
            </div>
  
  <br>

  <br>
</center>
  
  	  <center><input type="button" class="btn btn-primary" value="Guardar" onclick="guardar();"></center>




<script>
	function guardar()
	{
  
		var nom = document.getElementById('nombre').value;
		var ape = document.getElementById('apellido').value;
		var emai = document.getElementById('email').value;
		var pass = document.getElementById('pass').value;
  
		jQuery.ajax({
            type:"POST",
            url: baseurl + "Usuarios/guardar",
            data: {nom:nom,ape:ape,emai:emai,pass:pass},
            dataType: "html",
            success:function(response)
            {
            	
                   window.location.href = baseurl + 'Usuarios';
            	
            }
		});
	}
</script>