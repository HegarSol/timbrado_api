<br>
<br>
<br>
<br>


<div class="form-group">
 <center><font size="6">Listado de Usuarios</font></center>	

</div>

<div class="container">

<table cellspacing="0" width="100%" class="table table-bordered table-hover" id="table">
	<thead>
		<tr>
			<th>Id</th>
			<th>Email</th>
			<th>Nombre</th>
			<th>Apellido</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>	
	</tbody>
</table>
<a href="<?php base_url();?>Usuarios/nuevo" class="btn btn-success">Nuevo Usuario</a>

</div>

<script>
       $(document).ready(function() {
             $('#table').DataTable({
		      'ajax': {
		        "url":baseurl+"Usuarios/ajax_list",
		        "type":"POST",

		        "dataSrc": function(data){

		           return data;   
		        }
		      },

		      'columns': [
		        {data: 'id'},
		        {data: 'email'},
		        {data: 'firstname'},
		        {data: 'lastname'},
		        {
		            mRender: function (data, type, row) 
		            {
		                return '<center><a class="btn btn-primary" title="Editar Usuario" href="' + baseurl + "Usuarios/editar/" + row.id + '" > Editar</a>' + 
		                '<a class="btn btn-danger" title="Eliminar Usuario" href="' + baseurl + "Usuarios/eliminar/" + row.id + '"> <font style="color:white">Eliminar</font></a></center>';
		            }
		        }
		      ],
		      "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }
          });
       });
</script>