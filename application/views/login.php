<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>LogIn</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link href="<?php echo base_url('assets/css/')?>login.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="<?php echo base_url('login/login_validate')?>" method="POST">
      <img class="mb-4" src="https://getbootstrap.com/assets/brand/bootstrap-solid.svg" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Favor de Iniciar Sesi&oacute;n</h1>
      <label for="inputEmail" class="sr-only">Email</label>
      <input type="email" id="inputEmail" name='email' class="form-control" placeholder="Email" required autofocus>
      <label for="inputPassword" class="sr-only">Password</label>
      <input type="password" id="inputPassword" name='password' class="form-control" placeholder="Password" required>
      <div class="checkbox mb-3">
        <label>
          <input type="checkbox" value="remember-me"> Recordarme
        </label>
      </div>
      <?php if(isset($error)):?>
      <div class='alert alert-danger'>
        <?php echo $error; ?>
      </div>
      <?php endif;?>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Iniciar</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2018-2019 HEGAR Soluciones en Sistemas S. de R.L.</p>
    </form>
  </body>
</html>