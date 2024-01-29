<br>
<br>
<br>
<br>
<br>
<center><h2>Búsqueda de XML</h2></center>

<br>
<br>
<br>
<br>

<div class="container">
       <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-3">
                    <label for="">Busqueda por:</label>
                    <select name="busquepor" id="busquepor" onchange="cambiar()" class="form-control">
                        <option value=""> -Seleccione- </option>
                        <option value="uuid">UUID</option>
                        <option value="esf">Emisor, serie y folio</option>
                    </select>
            </div>
       </div>
       <br>
    <div class="row">
        <div class="col-md-2"></div>     
            <div class="col-md-4" id="uuid_caja">
                <label>UUID:</label>
                <input type="text" class="form-control" id="uuid" name="uuid">
            </div>
            <div class="col-md-3" id="emisor_r">
                 <label for="">RFC Emisor:</label>
                 <input type="text" class="form-control" id="rfc_emisor" name="rfc_emisor">
            </div>
            <div class="col-md-2" id="serie_r">
                 <label for="">Serie:</label>
                 <input type="text" class="form-control" id="serie" name="serie">
            </div>
            <div class="col-md-2" id="folio_r">
                <label for="">Folio:</label>
                <input type="text" class="form-control" id="folio" name="folio">
            </div>
        <div class="col-md-1">
            <br>
            <button type="button" onclick="buscaruuid()" id="dsds" class="btn btn-primary">Buscar</button>
        </div>
        <div class="col-md-1">
            <br>
             <button type="button" id="des" onclick="descargar()" class="btn btn-primary">Descargar</button>
        </div>
    </div>
<br>
<div class="row">
<div class="col-md-2"></div>
  <div class="col-md-6">
       <label for="">UUID: </label>
       <input type="text" class="form-control" readonly id="uuid2">
  </div>
</div>
<br>
<div class="row">
<div class="col-md-2"></div>
   <div class="col-md-2">
       <label for="">Total: </label>
       <input type="text" class="form-control" readonly id="total">
   </div>
   <div class="col-md-2">
      <label for="">Tipo Comprobante:</label>
      <input type="text" class="form-control" readonly id="tipocom">
   </div>
   <div class="col-md-2">
      <label for="">Metodo Pago: </label>
      <input type="text" class="form-control" readonly id="metodo">
   </div>
   <div class="col-md-1">
      <label for="">Version:</label>
      <input type="text" class="form-control" readonly id="version">
   </div>
</div>
<br>
    <div class="row">
    <div class="col-md-2"></div>
        <div class="col-md-4">
           <label for="">Nombre Emisor:</label>
           <input type="text" class="form-control" readonly id="nomemi">
        </div>
        <div class="col-md-3">
           <label for="">Rfc Emisor: </label>
           <input type="text" class="form-control" readonly id="rfcemi">
        </div>
    </div>
    <br>
    <div class="row">
    <div class="col-md-2"></div>
       <div class="col-md-4">
          <label for="">Nombre Receptor:</label>
          <input type="text" class="form-control" readonly id="nomrece">
       </div>
       <div class="col-md-3">
          <label for="">Rfc Receptor: </label>
          <input type="text" class="form-control" readonly id="rfcrecep">
       </div>
    </div>
</div>

<div class ="modal fade" id ="buscartim" role="dialog" tabindex="-1">
    <div class ="modal-dialog ">
      <div class ="modal-content">
          <div class ="modal-header" style="background-color:#222222; color:white;">
             <h4 class ="modal-title">Buscando comprobante</h4>
          </div>
        <div class ="modal-body">
            <br>
            <br>
            <center><h2>POR FAVOR ESPERE.....</h2></center>
        </div>
      </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        document.getElementById('des').style.display = 'none';
        document.getElementById('uuid_caja').style.display = 'none';
        document.getElementById('emisor_r').style.display = 'none';
        document.getElementById('folio_r').style.display = 'none';
        document.getElementById('serie_r').style.display = 'none';
    });
</script>
<script>
    $("#dsds").click(function(){
            $("#buscartim").modal('show');
        });
     function cambiar()
     {
         var algo = document.getElementById('busquepor').value;
         if(algo == 'uuid')
         {
            document.getElementById('uuid_caja').style.display = 'block';
            document.getElementById('emisor_r').style.display = 'none';
            document.getElementById('folio_r').style.display = 'none';
            document.getElementById('serie_r').style.display = 'none';

            document.getElementById('rfc_emisor').value = '';
            document.getElementById('serie').value = '';
            document.getElementById('folio').value = '';
         }
         else
         {
            document.getElementById('uuid_caja').style.display = 'none';
            document.getElementById('emisor_r').style.display = 'block';
            document.getElementById('folio_r').style.display = 'block';
            document.getElementById('serie_r').style.display = 'block';

            document.getElementById('uuid').value = '';
         }
     }
     function descargar()
     {
         var uuid = document.getElementById('uuid').value;
         var rfc = document.getElementById('rfc_emisor').value;
         var seri = document.getElementById('serie').value;
         var foli = document.getElementById('folio').value;

         if(uuid == '')
         {
           window.open(baseurl+"Busqueda/descargarxml2/"+rfc+"/"+seri+"/"+foli,'_blank');
         }
         else
         {
            window.open(baseurl+"Busqueda/descargarxml/"+uuid,'_blank');
         }
     }
    function buscaruuid()
    {
      //  $('#buscartim').modal('show');
        var uuid = document.getElementById('uuid').value;
        var rfc = document.getElementById('rfc_emisor').value;
        var seri = document.getElementById('serie').value;
        var foli = document.getElementById('folio').value;


                jQuery.ajax({
                    type:"POST",
                    url: baseurl + 'Busqueda/getxml',
                    data: {uuid:uuid,rfc:rfc,seri:seri,foli:foli},
                    dataType:'html',
                    success:function(response)
                    {
                      
                        $("#buscartim").removeClass("in");
                        $(".modal-backdrop").remove();
                        $('body').removeClass('modal-open');
                        $('body').css('padding-right', '');
                        $("#buscartim").hide();
                        response=JSON.parse(response);
                        if(response.valor == 0)
                        {
                           
                            alert(response.mensaje);
                            document.getElementById('des').style.display = 'none';
                            document.getElementById('nomemi').value = '';
                            document.getElementById('nomrece').value = '';
                            document.getElementById('rfcemi').value = '';
                            document.getElementById('rfcrecep').value = '';
                            document.getElementById('tipocom').value = '';
                            document.getElementById('total').value = '';
                            document.getElementById('metodo').value = '';
                            document.getElementById('version').value = '';
                            document.getElementById('uuid2').value = '';
                        }
                        else
                        {
                            document.getElementById('des').style.display = 'block';
                            document.getElementById('nomemi').value = response.emisor;
                            document.getElementById('nomrece').value = response.receptor;
                            document.getElementById('rfcemi').value = response.rfcemisor;
                            document.getElementById('rfcrecep').value = response.rfcreceptor;
                            document.getElementById('tipocom').value = response.tipocom;
                            document.getElementById('total').value = response.total;
                            document.getElementById('metodo').value = response.metodopa;
                            document.getElementById('version').value = response.version;
                            document.getElementById('uuid2').value = response.uuid;
                        }

                       
                    }
                });
           
    }
</script>