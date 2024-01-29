<br>
<br>
<br>
<br>
<br>
<center><h2>Registro de Timbres</h2></center>
<br>
<center><h3><?php echo $dato[0]['nombre']; ?></h3></center>
<input type="hidden" readonly name="key" id="key" class="form-control" value="<?php echo $this->session->userdata("key") ?>">

<style type="text/css">
	.hide_column { 
    display : none; 
} 
</style>
<br>
<br>

<div class="container">
	<div class="row">
		<div class="col-md-3">
			<label>Clave Cliente</label>
			<input type="text" name="clave_clie" readonly id="clave_clie" class="form-control" value="<?php echo $dato[0]['clave']; ?>">
		</div>
    <div class="col-md-3">
			<label>PAC: </label>
			<select id="id_pac" name="id_pac" class="form-control">
        <option value="SW">SW</option>
			</select>
		</div>
	   	<!-- <div class="col-md-3">
		  	<br>
		   <button type="button" class="btn btn-primary btn-lg" onclick="agregar()">Agregar</button>
    	</div> -->
	</div>
  
      <!-- <label><h3>Paquetes Timbres</h3></label>
      <table id="paque" cellspacing="0" width="100%" class="table table-bordered table-hover">
         <thead>
            <tr>
               <th>Id</th>
               <th>Cantidad</th>
               <th>Precio</th>
               <th>Precio Adicional</th>
               <th>Credito</th>
               <th>Especial</th>
               <th>Seleccionar</th>
            </tr>
         </thead>
      </table> -->
 <br>
 <br>

<label><h3>Cantidad Timbres</h3></label>
 <div class="row">
     <div class="col-md-3">
         <label>Cantidad: </label>
         <input type="text" class="form-control" name="cantidad" id="cantidad">
     </div> 
     <div class="col-md-3">
         <label>UUID Factura: </label>
         <input type="text" <?php if(empty($this->session->userdata('filtro'))){ }else{echo 'readonly'; }?> class="form-control" name="factura" id="factura">
     </div>

     <div class="col-md-3">
     <br>
        <button type="button" class="btn btn-primary" onclick="agregarpre()">Agregar</button>
     </div>
 </div>


<br>
<br>
	<table  id="timbre" cellspacing="0" width="100%" class="table table-bordered table-hover">
		<thead>
			<tr>
				<th>Clave Cliente</th>
				<th>ID Paquete</th>
				<th>Restantes</th>
				<th>Fecha Activacion</th>
				<th>Fecha Vencimiento</th>
				<th>PAC</th>
				<th>Cantidad</th>
				<th>Fecha Compra</th>
        <th>Factura</th>
				<th>Accion</th>
			</tr>
		</thead>
        <tbody>
        </tbody>
	</table>
</div>

<div class ="modal fade" id ="ventana2" >
    <div class ="modal-dialog">
      <div class ="modal-content">
        <div class ="modal-header">
          <h4 class ="modal-title">Editar Fecha de Vencimiento</h4>
        </div>
        <div class ="modal-body">
          <form method="post">
            <div class ="form-group">
                  <label>Fecha Vencimiento:</label>
                  <input type ="date" name="fechaven" id="fechaven" class ="form-control">
                  <input type="hidden" id="id_paque" name="id_paque" class="form-control">
           </div>
           <input type="button" value ="CONFIRMAR" onclick="modife()"  class="btn btn-primary">
           <input type="button" value ="CANCELAR" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
          </form>
        </div>
      </div>
    </div>
</div>

<div class ="modal fade" id ="ventanacti" >
    <div class ="modal-dialog">
      <div class ="modal-content">
        <div class ="modal-header">
          <h4 class ="modal-title">Editar Fecha de Activacion</h4>
        </div>
        <div class ="modal-body">
          <form method="post">
            <div class ="form-group">
                  <label>Fecha Activacion:</label>
                  <input type ="date" name="fechaacti" id="fechaacti" class ="form-control">
                  <input type="hidden" id="id_paque2" name="id_paque2" class="form-control">
           </div>
           <input type="button" value ="CONFIRMAR" onclick="modifeacti()"  class="btn btn-primary">
           <input type="button" value ="CANCELAR" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
          </form>
        </div>
      </div>
    </div>
</div>

<div class ="modal fade" id ="ventanaeditimbres" >
    <div class ="modal-dialog">
      <div class ="modal-content">
        <div class ="modal-header">
          <h4 class ="modal-title">Editar Timbres</h4>
        </div>
        <div class ="modal-body">
          <form method="post">
            <div class ="form-group">
            <input type="hidden" id="idpaque" name="idpaque">
                  <label>Timbres Restantes: </label>
                  <input type="text" name="timbresrestantes" id="timbresrestantes" class ="form-control">
                  <br>
                  <label>Timbres Comprados: </label>
                  <input type="text" name="timbrescomprados" id="timbrescomprados" class="form-control">
           </div>
           <input type="button" value ="CONFIRMAR" onclick="moditimbres()"  class="btn btn-primary">
           <input type="button" value ="CANCELAR" class="btn btn-danger" data-dismiss="modal" aria-hidden="true">
          </form>
        </div>
      </div>
    </div>
</div>

<script type="text/javascript">
 var id = '<?php echo $this->session->userdata('id'); ?>' ;
 
   $(document).ready( function (){
   
   
       $('#paque').DataTable({

         'paging':true,
         'info':true,
         'filter':true,
         'stateSave':true,
         
         'ajax':{
           "url":baseurl + "Clientes/ajax_all_paquete/"+id,
           "type":"POST",

           "dataSrc": function(data){
               return data;
           }
         },
        
        'columns':[
            {data: 'id'},
            {data: 'cantidad'},
            {data: 'precio'},
            {data: 'precio_adicional'},
            {data: 'credito'},
            {data: 'especial'},
            {
               mRender:function(data,type,row)
               {
                  return '<input type="checkbox" class="form-control">';
               }
            }
        ],
        
        "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" },
        columnDefs: [ 
             { 
                className: "dt-right", targets:[2,3]
             }
            ],
       });
   });
</script>

<script type="text/javascript">
	 $(document).ready( function () {

    var valor = "<?php if(empty($this->session->userdata('filtro')))
           { 
               echo 1;
           } 
           else
           {  
               echo 0;
           }
        ?> ";

        var cla = '<?php echo $dato[0]['clave']; ?>';

        var talo =  $('#timbre').DataTable({
              	'paging': true,
    'info': true,
    'filter': true,
    'stateSave': true,

      'ajax': {
        "url":baseurl+"Clientes/ajax_paquete/" + cla,
        "type":"POST",

        "dataSrc": function(data){

           return data;   
        }
      },
      "columnDefs": [ 
	          { className: "hide_column", "targets": [ 1 ] },
	          { className: "hide_column", "targets": [ 0 ] }
	    ] 
      ,
      'columns': [
        {data: 'clave_cliente'},
        {data: 'id'},
        {data: 'cantidad'},
        {data: 'fecha_activacion'},
        {data: 'fecha_vence'},
        {data: 'id_pac'},
        {data: 'cantidad_comprada'},
        {data: 'referencia_compra'},
        {data: 'uuid_factura'},
        {
            mRender: function (data, type, row) 
            {

              if(valor == 1)
              {

                if(row.uuid_factura != '')
                {
                    return '<div class="row"> &nbsp; &nbsp; <button type="button" class="btn btn-primary btn-sm" title="Editar Fecha Vencimiento" onclick="editafecha(' + row.id + ');" >Vencimiento</button>' + 
                    '<button type="button" class="btn btn-success btn-sm" title="Editar Fecha Activacion" onclick="editafechaacti(' + row.id + '); " >Activacion</button>' +
                    '<button type="button" class="btn btn-danger btn-sm" title="Eliminar Paquete" disabled onclick="eliminar();">Eliminar</button>' +
                    '<button type="button" class="btn btn-warning btn-sm" title="Modificar Timbres" onclick="editartimbres('+row.id+')">Editar</button></div>';
                }
                else
                {
                  return '<div class="row"> &nbsp; &nbsp; <button type="button" class="btn btn-primary btn-sm" title="Editar Fecha Vencimiento" onclick="editafecha(' + row.id + ');" >Vencimiento</button>' + 
                    '<button type="button" class="btn btn-success btn-sm" title="Editar Fecha Activacion" onclick="editafechaacti(' + row.id + '); " >Activacion</button>' +
                    '<button type="button" class="btn btn-danger btn-sm" title="Eliminar Paquete" onclick="eliminar('+ row.id +');">Eliminar</button>' +
                    '<button type="button" class="btn btn-warning btn-sm" title="Modificar Timbres" onclick="editartimbres('+row.id+')">Editar</button></div>';
                }

              }
              else
              {
                return '';
              }
                
            }
        }
      ],
      "language": { "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json" }
                      
              });

          talo.column( '1' ).order( 'desc' ).draw();
			});
</script>


<script type="text/javascript">
  function eliminar(id)
  {

    var r = confirm("¿Esta seguro de eliminar el paquete?");
    if (r == true) 
    {
      jQuery.ajax({
            type:"POST",
            url: baseurl + "Clientes/paquete/" +id,
            data: {id:id},
            dataType:"html",
            success:function(response)
            {
              $('#timbre').DataTable().ajax.reload();
            }
        });
    } 
    else 
    {

    }
      
  }
  function editartimbres(id)
  {
    jQuery.ajax({
        type:"POST",
        url: baseurl + "Clientes/timbreseditar/" + id,
        data: {id:id},
        dataType:"html",
        success:function(response)
        {
           response=JSON.parse(response);
           document.getElementById('idpaque').value = response[0].id;
           document.getElementById('timbrescomprados').value = response[0].cantidad_comprada;
           document.getElementById('timbresrestantes').value = response[0].cantidad;
           
           $('#ventanaeditimbres').modal('show');
        }
    });
  }
  function moditimbres()
  {
    var resta = document.getElementById('timbresrestantes').value;
    var compra = document.getElementById('timbrescomprados').value;
    var id = document.getElementById('idpaque').value;

    jQuery.ajax({
         type:"POST",
         url: baseurl + "Clientes/resta",
         data: {resta:resta,compra:compra,id:id},
         dataType:"html",
         success:function(response)
         {
          $('#ventanaeditimbres').modal('hide');
          $('#timbre').DataTable().ajax.reload();
         }
    });
  }
	function editafechaacti(id)
	{
    
		$('#ventanacti').modal('show');
         jQuery.ajax({
             type:"POST",
             url: baseurl + "Clientes/fecha/" + id,
             data: {id:id},
             dataType: "html",
             success:function(response)
             {
             	response=JSON.parse(response);
             	document.getElementById('fechaacti').value = response[0].fecha_activacion;
             	document.getElementById('id_paque2').value = response[0].id;
             }
         });
	}
	function modifeacti()
	{
		var fech = document.getElementById('fechaacti').value;
		var idpa = document.getElementById('id_paque2').value;
		jQuery.ajax({
            type:"POST",
            url: baseurl + 'Clientes/cambiofechaacti',
            data: {fech:fech,idpa:idpa},
            dataType:"html",
            success:function(response)
            {
                $('#ventanacti').modal('hide');
                $('#timbre').DataTable().ajax.reload();
            } 
		});
	}
	function editafecha(id)
	{
		$('#ventana2').modal('show');
         jQuery.ajax({
              type:"POST",
              url: baseurl + "Clientes/fecha/" + id,
              data: {id:id},
              dataType:"html",
              success:function(response)
              {
                   response=JSON.parse(response);
                   document.getElementById('fechaven').value = response[0].fecha_vence;
                   document.getElementById('id_paque').value = response[0].id;               
              }
         });
	}
	function modife()
	{
		var fech = document.getElementById('fechaven').value;
		var idpa = document.getElementById('id_paque').value;
		jQuery.ajax({
            type:"POST",
            url: baseurl + 'Clientes/cambiofecha',
            data: {fech:fech,idpa:idpa},
            dataType:"html",
            success:function(response)
            {
                $('#ventana2').modal('hide');
                $('#timbre').DataTable().ajax.reload();
            } 
		});
	}
	function agregar()
	{

    var paquete_id = [];
          $("input[type=checkbox]:checked").each(function(){
                var id_pac = [];
                var tipo = $(this).parent().parent().find('td').eq(0).html();
                id_pac = [tipo];
                paquete_id.push(id_pac);
            });

	     var clacli = document.getElementById('clave_clie').value; 
       //  var cant = document.getElementById('cantidad').value;
       //  var pre = document.getElementById('precio').value;
         var key = document.getElementById('key').value;
       //  var preadi = document.getElementById('preadi').value;
       //  var cre = document.getElementById('credito').value;
       //  var esp = document.getElementById('especial').value;
         var pac = document.getElementById('id_pac').value;

         var fecom = '<?php echo date('Y-m-d'); ?>';

         var nuevo = '<?php echo date ( 'Y-m-d' , strtotime ( '+1 year' , strtotime ( date('Y-m-d') ) ) ); ?>';

        //  jQuery.ajax({
        //       type:"POST",
        //       url: baseurl + 'api/Paquete',
        //       data: 'cantidad=' + cant + '&precio_adicional=' + preadi + '&precio=' + pre + '&credito=' + cre + '&especial=' + esp + '&X-API-KEY=' + key,
        //       dataType: "html",
        //       success:function(response)
        //       {

              	// jQuery.ajax({
                //      type:"POST",
                //      url: baseurl + 'Clientes/maximo',
                //      data: {id : 1},
                //      dataType: "html",
                //      success:function(response)
                //      {
                     //	response=JSON.parse(response);
                         jQuery.ajax({
		                       type:"POST",
		                       url: baseurl + 'api/Compra',
		                       data:'referencia=' + fecom + '&clave_cliente=' + clacli + '&id_pac=' + pac + '&fecha_vence=' + nuevo + '&X-API-KEY=' + key + '&id_paquete=' + paquete_id,
		                       dataType: "html",
		                       success:function(response)
		                       {
		                       	  $('#timbre').DataTable().ajax.reload();
                               var checkboxes = $('input:checkbox').not(this);
                               for (var i = 0; i < checkboxes.length; i++)
                                {
                                    if (checkboxes[i].type == 'checkbox')
                                      {
                                        checkboxes[i].checked = false;
                                    }
                                }
		                       }
		                   });
                //      }
              	// });
                  
        //       }
        //  });     
  }
  function agregarpre()
  {
    var clacli = document.getElementById('clave_clie').value; 
    var key = document.getElementById('key').value;
    var pac = document.getElementById('id_pac').value;
    var can = document.getElementById('cantidad').value;
    var factu = document.getElementById('factura').value;


    var fecom = '<?php echo date('Y-m-d'); ?>';

    var nuevo = '<?php echo date ( 'Y-m-d' , strtotime ( '+1 year' , strtotime ( date('Y-m-d') ) ) ); ?>';

     if(can == '' || can == 0 || can < 0)
     {
        alert('Falta de agregar la cantidad de timbres');        
     }
     else
     {

        jQuery.ajax({
          type:"POST",
          url: baseurl + 'api/CompraPre',
          data: 'referencia=' + fecom + '&clave_cliente=' + clacli + '&id_pac=' + pac + '&cantidad=' + can + '&fecha_vence=' + nuevo + '&uuid_factura=' + factu + '&X-API-KEY=' + key,
          dataType: "html",
          success:function(response)
          {
              $('#timbre').DataTable().ajax.reload();
              document.getElementById('cantidad').value = '';
              document.getElementbyId('factura').value = '';
          }
        }); 
     }
         
  }
</script>