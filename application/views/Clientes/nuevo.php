<br>
<br>
<br>
<br>


   <center><font size="6">Nuevo Cliente</font></center>

<br>
<br>

<form id="form1" name="form1" >

<div class="container">
  	
     <div class="row">
         <div class="col-md-4">
            <label>Nombre: </label>
            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre del Cliente">
         </div>
         <br>
         <div class="col-md-4">
            <label>RFC: </label>
            <input type="text" name="rfc" id="rfc"  class="form-control"  placeholder="RFC del Cliente">
         </div>
         <br>
         <div class="col-md-4">
            <label>Clave:</label>
            <input type="text" name="clave" id="clave"  class="form-control" placeholder="Clave del Cliente">
         </div>
     </div>
   
   <br>
   <br>
   
   <div class="row">
          
            <div class="col-md-4">
               <label>Activo:</label>
               <select class="form-control" id="activo" name="activo">
                     <option value=""> -Seleccione- </option>
                     <option value="1"> Si </option>
                     <option value="0"> No </option>
               </select>
            </div>
         <div class="col-md-4">
               <label>Notificar</label>
               <input type="text" name="notificar" id="notificar" class="form-control">
         </div>
      
         <div class="col-md-4">
               <label>Email: </label>
               <input type="text" nmae="email" id="email" class="form-control" placeholder="Email del Cliente">
         </div>
   </div>

<br>
<br>

	<div class="form-group">
		<center> <input type="button" class="btn btn-primary" value="Guardar" onclick="agregar();"></center>
	</div>

</div>

</form>

<script type="text/javascript">
  function agregar()
  {
     var nom = document.getElementById('nombre').value;
     var rfc = document.getElementById('rfc').value;
     var clave = document.getElementById('clave').value;
     var activo = document.getElementById('activo').value;
    
     var noti = document.getElementById('notificar').value;
     var emai = document.getElementById('email').value;
     var key = '<?php echo $this->session->userdata("key") ?>';

     if(nom == '')
     {
        alert('Llene el campo de Nombre');
     }
     else if (rfc == '')
     {
        alert('Llene el campo RFC');
     }
     else if (clave == '')
     {
        alert('Llene el campo Clave');
     }
     else if (activo == '')
     {
        alert('Elija un opcion de Activo');
     }

     else if (noti == '')
     {
        alert('Elija la cantidad a notificar');
     }
     else if(emai == '')
     {
        alert('Llene el campo Email');
     }
     else
     {
      jQuery.ajax({
            type:"POST",
            url: baseurl + 'Clientes/clave',
            data: {clave:clave},
            dataType: "html",
            success:function(response)
            {   
               if(response == 1)
               {
                  alert('La clave ya se encuentra en uso');
               }
               else
               {
                  jQuery.ajax({
                     type:"POST",
                     url: baseurl + 'api/Cliente',
                     data: 'clave=' + clave + '&' + 'rfc=' + rfc + '&' + 'nombre=' + nom + '&' + 'activo=' + activo + '&' + 'notificar=' + noti + '&' + 'email=' + emai + '&' + 'X-API-KEY=' + key,
                     datatype: "html",
                     success:function(response)
                     {
                        window.location.href = baseurl + 'Clientes';
                     }
                  }); 
               }   
            }
      });
     }
     
  }

  
</script>