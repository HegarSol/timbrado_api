<?php
//edit with your data
$repo_dir = '/var/www/repos/cfdi_api.git';
$web_root_dir = '/var/www/api_timbrado';
$onbranch = 'master';
// A simple php script for deploy using bitbucket webhook
// Remember to use the correct user:group permisions and ssh keys for apache user!!
// Dirs used here must exists on server and have owner permisions to www-data
// Full path to git binary is required if git is not in your PHP user's path. Otherwise just use 'git'.
$git_bin_path = 'git';
$update = false;
$payload = json_decode( file_get_contents( 'php://input' ), true );
if(empty($payload)) {
  file_put_contents('deploy.log', date('m/d/Y h:i:s a') . " File accessed with no data\n", FILE_APPEND) or die('log fail');
  die("El proceso no fue llamado desde Bitbucket.org");
}
// Funciona cuando se hace un acepta un pull request
if ( isset( $payload['pullrequest'] ) ) {
  $destination = $payload['pullrequest']['destination']['branch'];
  $branch     = isset( $destination['name'] ) && ! empty( $destination['name'] ) ? $destination['name'] : '';
  if($branch==$onbranch){
    $update = true;
  } 
}

// Se usa cuando se hace un push directo sobre la rama
if ( isset( $payload['push'] ) ) {
  $lastChange = $payload['push']['changes'][ count( $payload['push']['changes'] ) - 1 ]['new'];
  $branch     = isset( $lastChange['name'] ) && ! empty( $lastChange['name'] ) ? $lastChange['name'] : '';
  if($branch==$onbranch){
    $update = true;
  } 
}
if ($update) {
  // Do a git checkout to the web root
  exec('cd ' . $repo_dir . ' && ' . $git_bin_path  . ' fetch');
  exec('cd ' . $repo_dir . ' && GIT_WORK_TREE=' . $web_root_dir . ' ' . $git_bin_path  . ' checkout -f');
  // Log the deployment
  $commit_hash = shell_exec('cd ' . $repo_dir . ' && ' . $git_bin_path  . ' rev-parse --short HEAD');
  echo "Deployed branch: " .  $branch . " Commit: " . $commit_hash . "\n";
  file_put_contents('deploy.log', date('m/d/Y h:i:s a') . " Deployed branch: " .  $branch . " Commit: " . $commit_hash . "\n", FILE_APPEND);
} else {
  echo var_dump($payload);
}
?>
