<?php

$path = '../spb/';
$data_folder = scandir($path);
$jml = count($data_folder)-2;
$hari = 7;
$pathbackup = '../backup/';
for ($i=2; $i < $jml; $i++) { 

  $path_baru = $path.$data_folder[$i];

  if($path_baru != $path."index.php" ){

    $data_folder_baru = scandir($path_baru);

    foreach ($data_folder_baru as $row) {
     
      if($row != "." and $row != ".."){
       $file = realpath($path_baru."/".$row);
       $path_file = $path_baru."/".$row;
       if( (time() - filemtime($file) ) > ($hari * 86400) ){ // 86400 = 60*60*24 = 1 Hari
          copy($path_file, $pathbackup.'spb/'.$row);
          unlink($path_file);
        }
      }
    }

  }

}

$path = '../excel/';
$data_folder= scandir($path);
$jml = count($data_folder)-2;
$hari = 7;
for ($i=2; $i < $jml; $i++) { 
  $path_baru = $path.$data_folder[$i];
  if($path_baru != $path."index.php" ){
    $data_folder_baru = scandir($path_baru);
    foreach ($data_folder_baru as $row) {
      if($row != "." and $row != ".."){
       $file = realpath($path_baru."/".$row);
       $path_file = $path_baru."/".$row;
        if( (time() - filemtime($file) ) > ($hari * 86400) ){ // 86400 = 60*60*24 = 1 Hari
          copy($path_file, $pathbackup.'excel/'.$row);
          unlink($path_file);
        }
      }
    }
  }
}

$path = '../beli/';
$data_folder= scandir($path);
$jml = count($data_folder)-2;
$hari = 7;

for ($i=2; $i < $jml; $i++) { 
  $path_file = $path.$data_folder[$i];
  $file = realpath($path_file);
  if($path_file != $path."index.php" ){
    if( (time() - filemtime($file) ) > ($hari * 86400) ){ // 86400 = 60*60*24 = 1 Hari
      copy($path_file, $pathbackup.'beli/'.$path_file);
      unlink($path_file);
    }
  }
}


$path = '../pajak/';
$data_folder= scandir($path);
$jml = count($data_folder);
$hari = 7;

for ($i=2; $i < $jml; $i++) { 
  $path_file = $path.$data_folder[$i];
  $file = realpath($path_file);
  if($path_file != $path."index.php" ){
    if( (time() - filemtime($file) ) > ($hari * 86400) ){ // 86400 = 60*60*24 = 1 Hari
      copy($path_file, $pathbackup.'pajak/'.$path_file);
      unlink($path_file);
    }
  }
}

$path = '../pstock/';
$data_folder= scandir($path);
$jml = count($data_folder)-2;
$hari = 7;

for ($i=2; $i < $jml; $i++) { 
  $path_file = $path.$data_folder[$i];
  $file = realpath($path_file);
  if($path_file != $path."index.php" ){
    if( (time() - filemtime($file) ) > ($hari * 86400) ){ // 86400 = 60*60*24 = 1 Hari
      copy($path_file, $pathbackup.'pstock/'.$path_file);
      unlink($path_file);
    }
  }
}
?>