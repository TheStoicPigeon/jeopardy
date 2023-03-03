<?php

require_once('connect.php');


if (isset($_GET['reset'])) {
  $offset = $_GET['reset'];
  $result = getCategories($offset);
  print_r($result);
}

if (isset($_GET['checkDB'])) {
  checkDB();
}


function getCategories(int $offset)
{

  global $con;
  $stmt = $con->prepare("SELECT `title`, `question`, `answer`, `value`, `airdate` 
    FROM `questions`
    INNER JOIN `categories`
    ON `catId` = `categories`.`id`
    WHERE `catId` >= ((
      SELECT min(catId) 
      FROM `questions`
    ) + $offset) 
    ORDER BY `categories`.`id`
    LIMIT 24"); //where catId > last row from previous search


  $stmt->execute();
  $result = $stmt->get_result();

  $categories = array();

  while ($question = $result->fetch_array(MYSQLI_ASSOC)) {
    array_push($categories, $question);
  }

  return json_encode($categories);
}



//return the current number of categories in DB
function checkDB()
{
  global $con;
  $stmt = $con->prepare("SELECT count(*) FROM `categories`");
  $stmt->execute();
  $result = $stmt->get_result()->fetch_row()[0];
  echo "$result";
}

// if (isset($_GET['clearQuestions'])) {
//   cleanQuestions();
// }

// if (isset($_GET['clearCategories'])) {
//   cleanCategories();
// }

// function cleanQuestions()
// {
//   global $con;
//   $stmt = $con->prepare("TRUNCATE TABLE `questions`");
//   $stmt->execute();
//   $result = $con->affected_rows;
//   echo "$result";
// }

// function cleanCategories()
// {
//   global $con;
//   $stmt = $con->prepare("TRUNCATE TABLE `categories`");
//   $stmt->execute();
//   $result = $con->affected_rows;
//   echo "$result";
// }
