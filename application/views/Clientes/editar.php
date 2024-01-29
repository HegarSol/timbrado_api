<br>
<br>
<br>
<br>

<center><font size="6">Editar Cliente</font></center>




<form id="form1" name="form1" action="../../Clientes/actuali" method="POST">

<div class="container">
  	<br>
     <br>
     <br>

       <div class="row">
          <div class="col-md-4">
               <label>Nombre: </label>
               <input type="text" name="nom" id="nom" required class="form-control" value="<?php echo isset($dato) ? $dato[0]['nombre'] : '' ?>">
          </div>

          <div class="col-md-4">
               <label>RFC: </label>
               <input type="text" name="rfc" id="rfc" required  class="form-control" value="<?php echo isset($dato) ? $dato[0]['rfc'] : '' ?>">
          </div>

          <div class="col-md-4">
               <label>Clave:</label>
               <input type="text" name="clave" id="clave" required  class="form-control" value="<?php echo isset($dato) ? $dato[0]['clave'] : '' ?>">
          </div>
       </div>
       
       <br>
       <br>
       <div class="row">
               <div class="col-md-4">
                    <label>Activo:</label>
                    <select class="form-control" id="activo" name="activo">
                         <?php echo $dato[0]['activo'] == 1 ?  '<option value="'.$dato[0]['activo'].'"> Si </option>' : '<option value="'.$dato[0]['activo'].'"> No </option>'?>
                         <option value="1"> Si </option>
                         <option value="0"> No </option>
                    </select>
               </div>
               <div class="col-md-4">
                    <label>PAC: </label>
                    <select class="form-control" name="pac" id="pac">
                         <?php echo '<option value="'.$dato[0]['id_pac'].'">'.$dato[0]['id_pac'].'</option>'; ?>
                         <option value="SW">SW</option>
                    </select>
               </div>
               <div class="col-md-4">
                    <label>Notificar: </label>
                    <input type="text" name="notificar" id="notificar" required class="form-control" value="<?php echo isset($dato) ? $dato[0]['Notificar'] : '' ?>">
               </div>
       </div>

  	<br>
	<br>
     <div class="row">
          
          <div class="col-md-4">
                <label>Email: </label>
                <input type="text" name="email" id="email" required class="form-control" value="<?php echo isset($dato) ? $dato[0]['email'] : '' ?>">
          </div>
     </div>

   <br>  

	<div class="form-group">
		<center> <input type="submit" class="btn btn-primary" value="Guardar" ></center>
	</div>

</div>

</form>

<!-- <script type="text/javascript">
  function modifi()
  {
       var nom = document.getElementById('nombre').value;
       var rfc = document.getElementById('rfc').value;
       var clave = document.getElementById('clave').value;
       var activo = document.getElementById('activo').value;
       

       jQuery.ajax({
            type:"POST",
            url: baseurl + 'Clientes/actuali',
            data: {nom:nom,rfc:rfc,clave:clave,activo:activo},
            dataType:"html",
            success:function(response)
            {
               window.location.href = baseurl + 'Clientes';
            }
       });
  }
</script> -->