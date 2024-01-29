<br>
<br>
<br>
<br>


<center><font size="6">Editar Usuario</font></center>

<br>




 <center>
  <div class="col-md-3">
  	<label>Nombre: </label>
  	   <input type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre del Usuario" value="<?php echo isset($data[0]->firstname) ? $data[0]->firstname : ''?>">
       <input type="hidden" name="id" id="id" value="<?php echo isset($data[0]->id) ? $data[0]->id : '' ?>">
  </div>	
  <br>
  <div class="col-md-3">
  	 <label>Apellido: </label>
  	 <input type="text" class="form-control" name="apellido" id="apellido" placeholder="Apellido del Usuario" value="<?php echo isset($data[0]->lastname) ? $data[0]->lastname : '' ?>">
  </div>
   <br>
  <div class="col-md-3">
  	  <label>Email:</label>
  	  <input type="text" class="form-control" name="email" id="email" placeholder="Email del Usuario" value="<?php echo isset($data[0]->email) ? $data[0]->email : '' ?>">
  </div>
   <br>
 
  
  <br>
 
  <br>
</center>
  
  	  <center><input type="button" class="btn btn-primary" value="Guardar" onclick="guardar2();"></center>

<script>
function guardar2()
{
    var id = document.getElementById('id').value;
    var nom = document.getElementById('nombre').value;
    var ape = document.getElementById('apellido').value;
    var emai = document.getElementById('email').value;
  
    jQuery.ajax({
       type:"POST",
       url: baseurl + "Usuarios/edit",
       data: {nom:nom,ape:ape,emai:emai,id:id},
       dataType: "html",
       success:function(response)
       {
           window.location.href = baseurl + 'Usuarios';
       }
    });
}
</script>