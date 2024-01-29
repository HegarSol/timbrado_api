<br>
<br>
<br>
<br>



<div class="form-group">
 <center><font size="6">Listado de Clientes</font></center>	

</div>

<div class="container">

<table cellspacing="0" width="100%" class="table table-bordered table-hover" id="table">
	<thead>
		<tr>
			<th>Clave</th>
			<th>RFC</th>
			<th>Nombre</th>
			<!-- <th>Activo</th> -->
			<!-- <th>User</th> -->
			<th>PAC</th>
			<!-- <th>Paquete</th> -->
			<th>Fecha Alta</th>
         <!-- <th>Timbrar</th> -->
         <th>Pertenece</th>
			<th>Acciones</th>
		</tr>
	</thead>
	<tbody>	

	</tbody>
</table>
<a href="<?php base_url();?>Clientes/nuevo" class="btn btn-success">Nuevo Cliente</a>

</div>



<script>
var id = <?php echo $this->session->userdata('id') ?>;
var fil = '<?php echo $this->session->userdata('filtro')?>';
       $(document).ready( function () {
              $('#table').DataTable({
              	'paging': true,
    'info': true,
    'filter': true,
    'stateSave': true,

      'ajax': {
        "url":baseurl+"Clientes/ajax_list/" + id,
        "type":"POST",

        "dataSrc": function(data){

           return data;   
        }
      },
      'columns': [
        {data: 'clave'},
        {data: 'rfc'},
        {data: 'nombre'},
        // {data: 'activo'},
        // {data: 'id_user'},
        {data: 'id_pac'},
        // {data: 'id_paquete'},
        {data: 'fecha_alta'},
        {//data: 'id_user'
            mRender: function (data,type,row)
            {
                if(fil == '')
                        {
                           if(row.filtro == '')
                           {
                              return 'HEGARS';
                           }
                           else
                           {
                              return row.filtro;
                           }
                        }
                        else
                        {
                            return row.filtro;
                        }
            }
        },
        {
            mRender: function (data, type, row) 
            {
                return '<div class="row">  <a class="btn btn-primary btn-sm" title="Editar Cliente" href="' + baseurl + "Clientes/editar/" + row.clave + '"  "> Editar</a>'
                + '<a class="btn btn-success btn-sm" href="' + baseurl + "Clientes/timbres/" + row.clave + '" title="Ver Timbres">Ver</a></div>';
            }
        }
      ],
      "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }
                      
              });
			});
</script>