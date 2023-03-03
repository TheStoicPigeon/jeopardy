import * as levenshtein from "./levenshtein.js";

var answers;
var questions = [...Array(24).keys()];
var score = 0;
var hintsGiven = 0;
var offset = 0;
var categoriesPlayed = 0;

$(document).ready(function() {

  $('.loadingScreen').hide();
  //CALL TO CURL IF DB IS EMPTY
  checkDB(function(result) {
    if (result == 0) {
      GetJService(function(result) {
        $('.loadingScreen').hide();
        $('.board').show();
        $('.console').show();
        $('.game_info').show();
        //INITIAL LOADING OF ANSWERS FROM DB
        LoadCategories(function(result) {
          answers = result;
        })
      });
    } else {
      LoadCategories(function(result) {
        answers = result;
      })
    }
  });



  //HANDLE A CATEGORY CLICK
  $('.question').on('click', function(event) {
    event.preventDefault();
    let id = $(this).data('id');
    $('#guess').show();
    if (questions[id] != null) { //only open modal if this category hasn't been chosen yet
      questions[id] = null; //set this category to null
      hintsGiven = 0;
      $(this).addClass('animate__animated animate__zoomOut');
      $('#question_modal').modal('show');
      $('#guess').focus();
      modalQuestion(id);
    }
  })



  //HANDLE HINT REQUEST
  $('#hint_btn').on('click', function() {
    let qId = $('#guess_btn').data('id');
    let hint = giveHint(qId);
    hintsGiven++;
    $('#guess').val('');
    $('#guess').attr('placeholder', hint);
  })


  //HANDLE SUBMITTING AN ANSWER
  $('#guess_btn').on('click', function(event) {
    event.preventDefault();
    let guess = $('#guess').val();
    let qId = $('#guess_btn').data('id');
    checkAnswer(guess, qId);
  })



  //HANDLE NEW RESET REQUEST
  $('#replay').on('click', function() {
    replayGame();
  })



  //HANDLE NEW GAME REQUEST
  $('#new_game').on('click', function() {
    newGame();
  })



  //WHAT TO DO WHEN A NEW MODAL FIRES
  $('#question_modal').on('show.bs.modal', function() {
    $('#guess').attr('placeholder', ''); //remove any old hints
    $('#guess').val(''); //remove any old guesses
    $('#guess_btn').show();
    $('#hint_btn').show();
    $('#close_btn').hide(); //remove close button -> force user to guess -> show close button when answer given 
  })


}); //-----------end READY--------------



//RETURN THE AMOUNT OF CATEGORIES IN THE DATABASE
function checkDB(callback) {
  let xml = new XMLHttpRequest();
  xml.open("GET", "GetCategories.php?checkDB=true");
  xml.setRequestHeader("content-type", "application/json");
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText);
    }
  }
  xml.send();
}



//HANDLE REPLAYING GAME
function replayGame() {
  score = 0; //reset score
  $('#score').html('');
  questions = [...Array(24).keys()]; //reset questions
  categoriesPlayed -= 6;
  LoadCategories(function(result) {
    answers = result;
  });
}



//HANDLE NEW GAME REQUEST 
function newGame() {
  // score = 0; //reset score
  // $('#score').html('');
  questions = [...Array(24).keys()]; //reset questions
  LoadCategories(function(result) {
    answers = result;
  });
}




//CHECK ANSWER CORRECTNESS
function checkAnswer(guess, id) {
  let answer = decodeHtml(answers[id].answer);
  let airdate = answers[id].airdate;
  airdate = new Date(airdate).toDateString();
  let question = answers[id].question;
  let response;
  $('#guess').hide();

  $('#guess_btn').hide();
  $('#hint_btn').hide();
  $('#close_btn').show();
  $('.modal-content').css('font-size', '2rem');

  if (answer.toLowerCase() === guess.toLowerCase()) {
    response = `Correct! <br><br> <b>${answer}</b> is the answer to <i>${question}</i> which aired on ${airdate}`;
    score += answers[id].value;
    $('#score').html(score);
    $('.modal-body').html(response);

  } else {

    let a = answer.toLowerCase();
    let g = guess.toLowerCase();
    let goodEnough = levenshtein.GetDistance(g, a);

    if (goodEnough) {
      response = `Correct! <br><br> <b>${answer}</b> is the answer to <i>${question}</i> which aired on ${airdate}`;
      score += answers[id].value;
      $('#score').html(score);
      $('.modal-body').html(response);
    } else {
      response = `Incorrect! <br><br> The answer to the question <i>${question}</i> which aired on ${airdate} is <br><span class="incorrect"><b>${answer}</b></span>`;
      score -= answers[id].value;
      $('#score').html(score);
      $('.modal-body').html(response);
    }
  }
}



//CREATE A HINT
function giveHint(id) {
  let answer = decodeHtml(answers[id].answer);
  let hint = Array();
  for (let i = 0; i < answer.length; i++) {
    if (i <= hintsGiven) {
      hint.push(answer[i]);
    }
    else {
      switch (answer[i]) {
        case ' ':
          hint.push(' ');
          break;
        case '-':
          hint.push('-');
          break;
        case '&':
          hint.push('&');
          break;
        default:
          hint.push('*');
          break;
      }
    }
  }
  return hint.join('');
}



//DISPLAY QUESTION IN MODAL
function modalQuestion(id) {
  const regex = /[^a-zA-Z'" ]/g;
  let question = answers[id].question;
  let answer = decodeHtml(answers[id].answer);

  if (answer.indexOf("(") !== -1) { //some answers have an optional answer in ( )'s . . . I opted for simplicity just to remove it
    answer = answer.slice(0, answer.indexOf("(") - 1);
    answers[id].answer = answer;
  }

  let outline = [];

  for (let char of answer) {
    switch (char) {
      case ' ':
        outline.push(' ');
        break;
      case '-':
        outline.push('-');
        break;
      case '&':
        outline.push('&');
        break;
      default:
        outline.push('*');
        break;
    }
  }
  $('#guess').attr('placeholder', outline.join(''));
  $('.modal-body').html(question);
  $('#guess_btn').data('id', id);
}



//GET DATA FROM IMPORT QUESTIONS 
//THIS IS THE WAITING SCREEN
function GetJService(callback) {
  offset += 70; //Used in ImportQuestions.php so we don't ask for any of the same categories
  $('.board').hide();
  $('.console').hide();
  $('.game_info').hide();
  $('.loadingScreen').show();

  categoriesPlayed = 0; //reset are categories counter
  let xml = new XMLHttpRequest();
  xml.open('GET', `ImportQuestions.php?getData=${offset}`, true);
  xml.setRequestHeader('Content-type', 'application/x-www-form-urlenconded');
  xml.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      callback(this.responseText)
    }
  }
  xml.send();
}

//JS quick fix to PHP html_entity_decode
//https://stackoverflow.com/questions/7394748/whats-the-right-way-to-decode-a-string-that-has-special-html-entities-in-it?lq=1
function decodeHtml(html) {
  return $('<div>').html(html).text();
}



//FETCH QUESTIONS FROM DATABASE
function LoadCategories(callback) {
  categoriesPlayed += 6;
  checkDB(function(currentTotal) {
    if (currentTotal <= categoriesPlayed) {
      GetJService(function(result) {
        $('.loadingScreen').hide();
        $('.board').show();
        $('.console').show();
        $('.game_info').show();
        offset += 70;
      });
    }
    else {
      let xml = new XMLHttpRequest();
      xml.open('GET', `GetCategories.php?reset=${categoriesPlayed - 6}`);
      xml.setRequestHeader('Content-type', 'application/json');
      xml.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          let data = JSON.parse(this.responseText);
          displayCategories(data);
          callback(data);
        }
      }
      xml.send();
    }
  })
}



//DISPLAY FETCHED DATA TO THE SCREEN
function displayCategories(data) {

  $('.category').each(function(i) {
    $(this).addClass('animate__animated animate__flipInX');
    $(this).html(data[i * 4].title);
  })
  let time = 0;
  $('.question').each(function() {

    $(this).removeClass('animate__animated animate__zoomOut');
    $(this).addClass(`animate__animated animate__flipInX animate_delay-${time += 0.5}s`);
  })
}
