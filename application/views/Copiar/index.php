<br>
<br>
<br>
<br>
<br>
<center><h2>Respaldo de archivos del timbrado API</h2></center>

<br>
<br>
<br>
<br>

<div class="container">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-3">
            <label>Fecha</label>
            <input type="date" class="form-control" id="fecha" name="fecha">
        </div>
        <div class="col-md-3">
            <br>
            <button type="button" onclick="respaldar()" class="btn btn-primary">Respaldar archivos</button>
        </div>
    </div>
</div>


<script>
function respaldar()
{
    var fecha = document.getElementById('fecha').value;
    if(fecha == '')
    {
       alert('Seleccione una fecha');
    }
    else
    {
        jQuery.ajax({
             type:"POST",
             url: baseurl + 'Copiar/ponerfecha',
             data: {fecha:fecha},
             dataType:'html',
             success:function(response)
             {
                  alert('Archivos respaldados correctamente');
             }
        });
    }
}
</script>