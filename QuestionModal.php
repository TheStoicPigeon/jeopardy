<?php

echo <<<_QUESTION

<div class="modal" id="question_modal" data-backdrop='static' data-keyboard="false">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body">
      </div>
      <div class="modal-footer">
        <div class="answer">
          <input type="text" id="guess" autocomplete="off">
        </div>
        <div class="controls">
          <button type="button" class="btn btn-primary" id="hint_btn" >Hint</button>
          <button type="button" class="btn btn-primary" id="guess_btn" >Guess</button>
          <button type="button" class="btn btn-primary" data-dismiss="modal" id="close_btn" >Close</button>
        </div>
      </div>
    </div>
  </div>
</div>

_QUESTION;
