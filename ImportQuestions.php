<?php

require_once("connect.php");


//updates DB with up to 50 different categories each with 4 questions ranging from 200 - 500 dollars in value
if (isset($_GET['getData'])) {

  $offset = $_GET['getData']; //tell jservice API to grab us categories starting from offset >> eliminates getting the same data

  $topics = array(); //topics will hold an array of 4 questions (values 200 - 500) for a given category

  global $con;
  $con->begin_transaction();
  $con->query("DELETE FROM `questions`");
  $con->query("DELETE FROM `categories`");

  try {
    $topics = CategoryFinder($topics, $offset);

    for ($i = 0; $i < count($topics); $i++) {

      $topic = json_decode($topics[$i]);

      $category = $topic[0]->category;
      $catId = InsertCategory($category);
      foreach ($topic as $q) {

        $question = $q->question;
        $answer = $q->answer;
        $value = $q->value;
        $airdate = date_create($q->airdate)->format('Y-m-d H:i:s');
        InsertQuestion($question, $answer, $value, $airdate, $catId);
      }
    }
    $con->commit();
    $con->close();
    echo "success";
  } catch (mysqli_sql_exception $exception) {
    $con->rollback();
    echo "Error retrieving data";
  }
}


function CategoryFinder($topics, $offset)
{
  $url = "http://jservice.io/api/categories?count=70&offset=$offset";
  $curlObj = curl_init($url);
  curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($curlObj);
  $categories = json_decode($data);


  //inspect each category to see if it has a clue count of at least 8
  foreach ($categories as $category) {

    //loop through till we get 50 topics with 4 different questions with values 100 -> 500 (100 values were hard to come by)
    if (count($topics) < 50) {
      if ($category->clues_count < 5) {
        continue;
      } else {

        //get the clues for that category and loop through them
        $catId = $category->id;
        $questions = GetQuestions($catId);
        if ($questions) {
          array_push($topics, $questions);
        }
      }
    }
  }
  return $topics;
}


function GetQuestions($catId)
{
  $values = [200, 300, 400, 500];
  $questBank = array();

  $url = "http://jservice.io/api/clues?category=$catId";
  $curlObj = curl_init($url);
  curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($curlObj);

  $result = json_decode($data);
  foreach ($result as $item) {
    if (count($values) == 0) {
      return json_encode($questBank);
    }
    if (in_array($item->value, $values)) {
      $value = $item->value;

      $index = array_search($value, $values);

      $category = $item->category->title;
      $question = $item->question;
      $answer = $item->answer;
      $value = $item->value;
      $airdate = date_create($item->airdate)->format('Y-m-d H:i:s');
      $question = ["category" => $category, "question" => $question, "answer" => $answer, "value" => $value, "airdate" => $airdate];
      array_push($questBank, $question);

      unset($values[$index]);
    }
  }
  return false;
}


function InsertCategory(string $category): int
{
  global $con;
  $stmt = $con->prepare("INSERT INTO `categories` (`title`, `datecreated`) VALUES (?, NOW())");
  $stmt->bind_param('s', $category);
  if ($stmt->execute()) {
    return $con->insert_id;
  }
}

function InsertQuestion(string $question, string $answer, int $value, string $airDate, int $catId)
{
  global $con;
  $q = sanitizeSQL($question);
  $a = sanitizeSQL($answer);
  $stmt = $con->prepare("INSERT INTO `questions` (`question`, `answer`, `value`, `airdate`, `catId`, `datecreated`) VALUES (?, ?, ?, ?,?, NOW())");
  $stmt->bind_param('ssisi', $q, $a, $value, $airDate, $catId);
  $stmt->execute();
  return $con->affected_rows;
}
