<?php

function cleanInputs($input)
{
  $data = trim($input);
  $data = strip_tags($data);
  $data = htmlspecialchars($data);

  return $data;
}
