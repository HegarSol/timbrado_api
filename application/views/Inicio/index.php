<br>
<br>
<br>
<br>


<div class="container">
  <b>
  Cantidad de Clientes:<h5><span class="badge badge-primary"> <?php echo $dato; ?> </span></h5>
  </b> 
</div>

<br>


<?php

if(empty($this->session->userdata('filtro')))
{
?>

<center><h2>Faltan de Facturar</h2></center>

<div class="container">
<table cellspacing="0" width="100%" class="table table-bordered table-hover" id="table1">
	<thead>
		<tr>
         <th>Id</th>
			<th>RFC</th>
			<th>Cliente</th>
			<th>Fecha Compra</th>
			<th>Cantidad</th>
			<th>Factura</th>
			<th>Pertenece</th>
         <th>Accion</th>
		</tr>
	</thead>
	<tbody>	

	</tbody>
</table>
</div>

<?php 

}

?>

<br>
<br>

<center><h2>Ya se les van a terminar los timbres</h2></center>
<div class="container">
<table cellspacing="0" width="100%" class="table table-bordered table-hover" id="table2">
    <thead>
        <tr>
            <th>RFC</th>
            <th>Cliente</th>
            <th>Notificar</th>
            <th>Restantes</th>
            <th>E-mail</th>
            <th>Vence</th>
            <th>Compra</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</div>

<br>
<br>

<center><h2>El paquete ya va a vencer</h2></center>
<div class="container">
<table cellspacing="0" width="100%" class="table table-bordered table-hover" id="table3">
   <thead>
        <tr>
           <th>RFC</th>
           <th>Cliente</th>
           <th>Notificar</th>
           <th>Restantes</th>
           <th>E-mail</th>
           <th>Vence</th>
           <th>Compra</th>
           <th>Cantidad</th>
        </tr>
   </thead>
</table>
</div>

<br>
<br>

<script>
$(document).ready( function () {

     var fil = '<?php $this->session->userdata('filtro'); ?>';
              $('#table1').DataTable({
                'paging':true,
                'info':true,
                'filter':true,
                'stateSave':true,

                'ajax':{
                  "url":baseurl+"Clientes/facturar",
                  "type":"POST",

                  "dataSrc":function(data){
                     return data;
                  }
                },
                'columns':[
                  {data: 'paquete_id'},
                  {data: 'rfc' },
                  {data: 'nombre' },
                  {data: 'referencia_compra' },
                  {data: 'cantidad_comprada' },
                  {data: 'uuid_factura' },
                  { //data: 'id_user'
                     mRender:function(data,type,row)
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
                     }
                  },
                  {
                     mRender:function(data,type,row)
                     {
                        return '<button class="btn btn-primary" onclick="edita(' + row.paquete_id + ');">Agregar Factura</button>';
                     }
                  }
                ],
                
                "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }
                

              });
});

$(document).ready( function () {
              $('#table2').DataTable({

                  'paging':true,
                  'info':true,
                  'filter':true,
                  'stateSave':true,

                  'ajax':{
                    "url":baseurl+"Clientes/terminartim",
                    "type":"POST",

                    "dataSrc":function(data){
                       return data;
                    }
                  },
                  'columns':[
                      {data: 'rfc' },
                      {data: 'nombre' },
                      {
                         mRender:function(data,type,row)
                         {
                            return row.Notificar;
                         }
                      },
                      {data: 'cantidad' },
                      {
                         mRender:function(data,type,row)
                         {
                            return row.email;
                         }
                      },
                      {data: 'fecha_vence' },
                      {data: 'referencia_compra' }
                  ],

                "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }

              });
});

$(document).ready(function (){
         $('#table3').DataTable({

            'paging' :true,
            'info':true,
            'filter':true,
            'stateSave':true,
            'ajax': {
               "url":baseurl+"Clientes/paquetevencer",
               "type":"POST",
               "dataSrc":function(data){
                   return data;
               }
            },
            'columns':[
             {data: 'rfc' },
             {data: 'nombre' },
             {
                  mRender:function(data,type,row)
                  {
                     return row.Notificar;
                  }
             },
             {data: 'cantidad' },
             {
                  mRender:function(data,type,row)
                  {
                     return row.email;
                  }
             },
             {data: 'fecha_vence' },
             {data: 'referencia_compra' },
             {data: 'cantidad_comprada'}
            ],
            "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }
         });
});
</script>
<script>
function edita(id)
{
   $('#ventana2').modal('show');
   document.getElementById('id_paque').value = id;
}
function editafactu()
{
   var fac = document.getElementById('factu').value;
   var id_pa = document.getElementById('id_paque').value;

   jQuery.ajax({
       type:"POST",
       url: baseurl + 'Clientes/actuali_pac',
       data:{fac:fac,id_pa:id_pa},
       dataType:"html",
       success:function(response)
       {
          $('#ventana2').modal('hide');
          $('#table1').DataTable().ajax.reload();
       }
   });
}
</script>

<div class ="modal fade" id ="ventana2" >
    <div class ="modal-dialog">
      <div class ="modal-content">
        <div class ="modal-header">
          <h4 class ="modal-title">Agregar Factura</h4>
        </div>
        <div class ="modal-body">
          <form method="post">
            <div class ="form-group">
                  <label>Factura:</label>
                  <input type ="text" name="factu" id="factu" class ="form-control">
                  <input type="hidden" id="id_paque" name="id_paque" class="form-control">
           </div>
           <input type="button" value ="CONFIRMAR" onclick="editafactu();"  class="btn btn-primary">
           <input type="button" value ="CANCELAR" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
          </form>
        </div>
      </div>
    </div>
</div>