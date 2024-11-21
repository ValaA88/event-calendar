<?php

function cleanInputs($input){
  $data = trim($input);
  $data = strip_tags($data);
  $data = htmlspecialchars($data);

  return $data;
}

function fileUpload($image){
  if($image['error'] == 4){
    $imageName = 'default.jpg';
    $message = 'No image has been uploaded, please try again';
  } else {
    $checkIfImage = getimagesize($image["tmp_name"]);
    $message = $checkIfImage ? "Ok" : "Not an Image";
  }
  if($message == "Ok"){
    $ext = strtolower(pathinfo($image["name"],PATHINFO_EXTENSION));
    $imageName =uniqid("").".".$ext;
    move_uploaded_file($image["tmp_name"], "images/{$imageName}");

  }

  return[$imageName, $message];
}