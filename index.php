<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  <title>Final Jeopardy</title>
  <!-- Bootstrap core JavaScript-->
  <link href="index.css" rel="stylesheet">
  <link href="bootstrap.min.css" rel="stylesheet">
  <link rel="icon" type="image/svg+xml" href="favicon.svg">
  <link rel="icon" type="image/png" href="favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
  <script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
  <script src="bootstrap.min.js"></script>
  <script type="module" src="index.js"></script>
</head>

<body>

  <?php
  include_once('QuestionModal.php');
  ?>
  <div class="loadingScreen">

    <div class="loading_top">
      <img src="will.svg" id="main_logo" />
    </div>
    <div class="loading_bottom">
      <div class="loading_images">
        <div class="loading">
          <h1>LOADING</h1>
          <span class="animate__animated animate__bounce animate__delay-0.5s animate__slow animate__infinite">&nbsp;<img src="dot.svg" /></span>
          <span class="animate__animated animate__bounce animate__delay-1s animate__infinite">&nbsp;<img src="dot.svg" /></span>
          <span class="animate__animated animate__bounce animate__delay-1.5s animate__fast animate__infinite">&nbsp;<img src="dot.svg" /></span>
        </div>
        <h4>We'll be right with you</h4>
      </div>
    </div>
  </div>


  <div class="board">
    <div class="grid-item category">topic</div>
    <div class="grid-item category">topic</div>
    <div class="grid-item category">topic</div>
    <div class="grid-item category">topic</div>
    <div class="grid-item category">topic</div>
    <div class="grid-item category">topic</div>

    <div class="grid-item question" data-id="0">
      <h2 class="choice">200</h2>
    </div>
    <div class="grid-item question" data-id="4">
      <h2 class="choice">200</h2>
    </div>
    <div class="grid-item question" data-id="8">
      <h2 class="choice">200</h2>
    </div>
    <div class="grid-item question" data-id="12">
      <h2 class="choice">200</h2>
    </div>
    <div class="grid-item question" data-id="16">
      <h2 class="choice">200</h2>
    </div>
    <div class="grid-item question" data-id="20">
      <h2 class="choice">200</h2>
    </div>

    <div class="grid-item question" data-id="1">
      <h2 class="choice">300</h2>
    </div>
    <div class="grid-item question" data-id="5">
      <h2 class="choice">300</h2>
    </div>
    <div class="grid-item question" data-id="9">
      <h2 class="choice">300</h2>
    </div>
    <div class="grid-item question" data-id="13">
      <h2 class="choice">300</h2>
    </div>
    <div class="grid-item question" data-id="17">
      <h2 class="choice">300</h2>
    </div>
    <div class="grid-item question" data-id="21">
      <h2 class="choice">300</h2>
    </div>

    <div class="grid-item question" data-id="2">
      <h2 class="choice">400</h2>
    </div>
    <div class="grid-item question" data-id="6">
      <h2 class="choice">400</h2>
    </div>
    <div class="grid-item question" data-id="10">
      <h2 class="choice">400</h2>
    </div>
    <div class="grid-item question" data-id="14">
      <h2 class="choice">400</h2>
    </div>
    <div class="grid-item question" data-id="18">
      <h2 class="choice">400</h2>
    </div>
    <div class="grid-item question" data-id="22">
      <h2 class="choice">400</h2>
    </div>

    <div class="grid-item question" data-id="3">
      <h2 class="choice">500</h2>
    </div>
    <div class="grid-item question" data-id="7">
      <h2 class="choice">500</h2>
    </div>
    <div class="grid-item question" data-id="11">
      <h2 class="choice">500</h2>
    </div>
    <div class="grid-item question" data-id="15">
      <h2 class="choice">500</h2>
    </div>
    <div class="grid-item question" data-id="19">
      <h2 class="choice">500</h2>
    </div>
    <div class="grid-item question" data-id="23">
      <h2 class="choice">500</h2>
    </div>


  </div>
  <div class="hint"></div>
  <div class="console">
    <img src="person.svg" id="person" />
  </div>

  <div class="game_info">
    <div class="score_display">
      <h2 id="h2_score">Score:&nbsp;<span id="score"></span></h2>
      <button type="submit" class="game_btn" id="replay">Replay<img src="replay.svg" alt="replay" /></button>
      <button type="submit" class="game_btn" id="new_game">Next<img src="next.svg" alt="next game" /></button>
    </div>
  </div>
  </div>

</body>

</html>
