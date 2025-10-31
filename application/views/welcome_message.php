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
            &nbsp;
            <a href="<?php echo base_url('Consultar/index')?>" class="btn btn-outline-info my-2 my-sm-0" >Consultar timbres</a>
          </form>
        </div>
      </nav>
    </header>

    <main role="main">

      <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img class="first-slide" src="<?php echo base_url('assets/img/api-icon.jpg')?>" alt="First slide">
            <div class="container">
              <div class="carousel-caption text-left">
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="second-slide" src="<?php echo base_url('assets/img/secure.jpg');?>" alt="Second slide">
            <div class="container">
              <div class="carousel-caption text-left">
                <h1>Seguridad API</h1>
                <p>Requiere autenticación de los usuarios para poder utilizar los métodos de la API</p>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <img class="third-slide" src="<?php echo base_url('assets/img/banner-clients.jpg');?>" alt="Third slide">
            <div class="container">
              <div class="carousel-caption text-left">
                <h1>Mejor control sobre clientes</h1>
                <p>Controla eficazmente los paquetes asignados a tus clientes y recupera sus comprobantes mas rapido.</p>
              </div>
            </div>
          </div>
        </div>
        <a class="carousel-control-prev" href="#myCarousel" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#myCarousel" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>


      <!-- Marketing messaging and featurettes
      ================================================== -->
      <!-- Wrap the rest of the page in another container to center all the content. -->

      <div class="container marketing">

        <!-- Three columns of text below the carousel -->
        <div class="row">
          <div class="col-lg-4">
            <img class="rounded-circle" src="<?php echo base_url('assets/img/proveedores.jpeg')?>" alt="Generic placeholder image" width="140" height="140">
            <h2>Múltiples PAC</h2>
            <p>Elegir entre distintos PAC para realizar el timbrado por cliente, asignarle paquetes para cada uno de ellos o utilizar el que se encuentre disponible, para que la facturación de su cliente no se detenga.</p>
          </div><!-- /.col-lg-4 -->
          <div class="col-lg-4">
            <img class="rounded-circle" src="<?php echo base_url('assets/img/icon-large-download.png')?>" alt="Generic placeholder image" width="140" height="140">
            <h2>Recuperación de comprobantes</h2>
            <p>Podrás recuperar los comprobantes, acuses de timbrado y acuses de cancelación de todos los documentos que se encuentren timbrados en nuestra plataforma, sin importar con que proveedor fueron timbrados o cancelados.</p>
          </div><!-- /.col-lg-4 -->
          <div class="col-lg-4">
            <img class="rounded-circle" src="<?php echo base_url('assets/img/reports.jpg');?>" alt="Generic placeholder image" width="140" height="140">
            <h2>Reportes</h2>
            <p>Estadísticas sobre el consumo de timbres entre tus clientes registrados, para poder ofrecerles nuevos paquetes que se ajusten a sus necesidades.</p>
          </div><!-- /.col-lg-4 -->
        </div><!-- /.row -->


        <!-- START THE FEATURETTES -->

        <hr class="featurette-divider">

        <div class="row featurette">
          <div class="col-md-7">
            <h2 class="featurette-heading">Multiples formatos de respuesta. <span class="text-muted">Elige el que mejor se adapte a tu sistema</span></h2>
            <p class="lead">En cada petición podrás especificar en que formato deseas la respuesta, podras elegir entre: json, xml, php, csv, html, php serializado.</p>
            <p><a href="#" class="btn btn-primary btn-lg" role="button">Ver Ejemplos</a></p>
          </div>
          <div class="col-md-5">
            <img class="featurette-image img-fluid mx-auto rounded" src="assets/img/codes.jpeg" alt="JSON Format">
          </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
          <div class="col-md-7 order-md-2">
            <h2 class="featurette-heading">Crea tus propios paquetes de timbres. <span class="text-muted">Para tener un mejor control sobre tus ventas</span></h2>
            <p class="lead">Podrás dar de alta los paquetes de timbres que mejor se adapten a tus ventas. Así como asignarlos a tus clientes de una manera fácil y rápida.</p>
          </div>
          <div class="col-md-5 order-md-1">
            <img class="featurette-image img-fluid mx-auto rounded" src="assets/img/idea.jpeg" alt="Generic placeholder image">
          </div>
        </div>

        <hr class="featurette-divider">

        <div class="row featurette">
          <div class="col-md-7">
            <h2 class="featurette-heading">Documentación y Ejemplos. <span class="text-muted">Sobre los métodos de la API</span></h2>
            <p class="lead">Todos los métodos registrados en la API se encuentran documentados con sus respectivos ejemplos de peticiones y posibles respuestas para que la integración a su sistema se lo mas rápida y eficiente.</p>
          </div>
          <div class="col-md-5">
            <img class="featurette-image img-fluid mx-auto rounded" src="assets/img/pexels-photo.jpeg" alt="Generic placeholder image">
          </div>
        </div>

        <hr class="featurette-divider">

        <!-- /END THE FEATURETTES -->

      </div><!-- /.container -->


      <!-- FOOTER -->
      <footer class="container">
        <p class="float-right"><a href="#">Ir al Inicio</a></p>
        <p>&copy; 2018-<?php echo date('Y'); ?> HEGAR Soluciones en Sistemas S. de R.L. &middot;</p>
      </footer>
    </main>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../../../assets/js/vendor/holder.min.js"></script>
    <script src="<?php echo base_url();?>assets/js/holder.min.js"></script>
  </body>
</html>
