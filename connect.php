<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'trivia');

global $con;
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if (!$con) {
  die('Could not connect: ' . mysql_error());
}

function sanitizeString($var)
{
  $var = htmlentities(strip_tags(stripslashes($var)));
  return $var;
}

// function sanitizeSQL($con, $var)
function sanitizeSQL($var)
{
  global $con;

  $var = $con->real_escape_string($var);
  $var = sanitizeString($var);
  return $var;
}
