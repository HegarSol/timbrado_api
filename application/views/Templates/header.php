<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    <script src = "https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.18/css/jquery.dataTables.min.css">

    
    <script type="text/javascript"> var baseurl = "<?php echo base_url(); ?>";</script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/')?>dashboard.css">
    <title>HEGARSS - CFDi</title>
  </head>
  <body>

    <header>
      <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <label style="color:white"><font size="5">REST API CFDI</font></label>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">

          <ul class="navbar-nav mr-auto">
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>Inicio">Inicio</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo base_url(); ?>Clientes">Clientes</a>
            </li>
            <li class="nav-item">
              <?php if($this->session->userdata('filtro') == '') { ?>
                 <a class="nav-link" href="<?php echo base_url(); ?>Usuarios">Usuarios</a>
              <?php }?>
            </li>
            <li class="nav-item">
                 <a class="nav-link" href="<?php echo base_url(); ?>Busqueda">Búsqueda XML</a>
            </li>
            <li class="col-md-12">
              
              </li>
            <li class="col-md-10">
            <center>
            <?php if($this->session->userdata('filtro') == '') { ?>
                    <font color="white">Saldo Timbres:</font><h5> <font color="white"> <?php echo $saldoT;?> </font></h5>
                    <?php }?>
                    </center>
            </li>
          </ul>
          
          
          
        </div>
        
           <div class="col-md-0">
           <label style="color:white"><font size="4"><?php echo $this->session->userdata('nombre')." ".$this->session->userdata('apellido') ?></font></label> &nbsp; &nbsp; &nbsp; <a href="<?php echo base_url();?>Login/logout" > <label style="color:white"> <font size="4"> Cerrar Sesion </font> </label> </a>
           </div>
        
      </nav>

    </header>

    

    