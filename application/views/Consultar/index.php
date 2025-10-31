<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../../../favicon.ico">

    <title>REST API CFDI</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/')?>carousel.css" rel="stylesheet">
  </head>
  <body>

    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">REST API CFDI</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
<!--
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="#">Caracter&iacute;sticas <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Documentaci&oacute;n</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Ejemplos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">Soporte</a>
            </li>
          </ul>
-->
          <form class="form-inline mt-2 mt-md-0" method="POST" action="<?php echo base_url('login/login_validate')?>">
            <input class="form-control mr-sm-2" type="text" placeholder="E-Mail" aria-label="E-Mail">
            <input class="form-control mr-sm-2" type="password" placeholder="Password" aria-label="Password">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Iniciar</button>
          </form>
        </div>
      </nav>
    </header>
        <main role="main">

              <div class="container marketing">
                   <h1><b>Consultar Timbres</b></h1>
                    <h4><p><b>En esta secci&oacute;n podr&aacute; consultar el saldo de timbres disponibles en su cuenta.</b></p></h4>
                   <div class="row">
                           <div class="col-md-4">
                                  <label for=""><b>Ingrese el RFC:</b></label>
                                  <input type="text" id="rfc" name="rfc" class="form-control" placeholder="RFC" aria-label="RFC">
                           </div>
                           <div class="col-md-3">
                            <br>
                                  <button type="button" onclick="consultartimbres()" class="btn btn-primary">Consultar</button>
                           </div>
                   </div>
                   <br>
                   <br>
                   <div class="row">
                        	<table  id="timbre" cellspacing="0" width="100%" class="table table-bordered table-hover">
                          <thead>
                            <tr>
                              <th>Cantidad</th>
                              <th>Fecha vencimiento</th>
                            </tr>
                          </thead>
                              <tbody>
                              </tbody>
                        </table>
                   </div>
              </div>

          <!-- FOOTER -->
      <footer class="container">
        <p>&copy; 2018-<?php echo date('Y'); ?> HEGAR Soluciones en Sistemas S. de R.L. &middot;</p>
      </footer>
    </main>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <!-- <script src="../../../../assets/js/vendor/holder.min.js"></script> -->
    <script src="<?php echo base_url();?>assets/js/holder.min.js"></script>
  </body>
</html>

<script>
function consultartimbres()
{
     var rfc = document.getElementById("rfc").value;
     if(rfc=="")
     {
                        $('#timbre tbody').empty();
          alert("Debe ingresar un RFC.");
          return false;
     }
     else
     {
          jQuery.ajax({
              type:"POST",
              url:"<?php echo base_url('Consultar/consultartimbres')?>",
              data:{rfc:rfc},
              dataType:"html",
              success:function(response)
              {
                  $('#timbre tbody').empty();
                  response=JSON.parse(response);
                  if(response.status == false)
                  {
                      alert(response.data);
                  }
                  else
                  {

                       for(var i in response.data)
                       {

                            var tbody = document.getElementById('timbre').getElementsByTagName("TBODY")[0];
                            var row = document.createElement("TR")
                            
                            var td0 = document.createElement("TD")
                            td0.appendChild(document.createTextNode(response.data[i].cantidad))
                            var td1 = document.createElement("TD")
                            td1.appendChild(document.createTextNode(response.data[i].fecha_vence))
                            

                            row.appendChild(td0);
                            row.appendChild(td1);
                            tbody.appendChild(row);
                       }
                  }
              }
          });
     }
}
</script>