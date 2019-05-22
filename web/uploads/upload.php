<?php
  $filename=uniqid('f_').'.'.$_GET['filetype'];
  $fileData=file_get_contents('php://input');
  if (!file_exists('avatar')) {
    mkdir('avatar', 0777, true);
  }
  $fhandle=fopen("avatar/".$filename, 'wb');
  fwrite($fhandle, $fileData);
  fclose($fhandle);
  echo($filename);
?>