var loadMessages,
    messageCounter = 0;

function sendMessage() {
  var message = "Ich habe eine lange Reise von Requests hinter mir!";
  var timestamp = Math.floor(Date.now() / 1000);


  resetStatusMessages();

  $.get("logger.php", {
    message: message,
    timestamp: timestamp
  })
    .success(function() {
      msg('Nachricht wurde angelegt.');
    })
    .fail(function() {
      error('Nachricht konnte nicht gespeichert werden.');
    });
}

function startShowingMessages() {
  loadMessages = setInterval(function () {
    $.get("getLoggerHTML.php", function (response) {
        resetStatusMessages();

      if(response.more === 0) {
        stopShowingMessages();
      }

      if(messageCounter > 5) {
        $('.entry').empty();
        messageCounter = 0
      }

      var entry =
        '<div class="entry" style="display:none;">' +
        '<p>' +
        response.message.time +
        ', ' + response.message.from +
        ': ' + response.message.message +
        '</p>' +
        '</div>';

      $(entry).appendTo('body .message-container').fadeIn('slow');
      messageCounter ++;
    }, "json")
      .fail(function() {
        stopShowingMessages();
        error('Es konnten keine Nachrichten gefunden werden.');
      });
  }, 1000);
}

function stopShowingMessages() {
  clearInterval(loadMessages);
}

function restart() {
  stopShowingMessages();

  $.get("restart.php", function () {
    clearMessages();
  });
}

function clearMessages() {
  $('.entry').empty();
  msg('Alle Nachrichten wurden erfolgreich gelöscht.');
}

//window.onload = startShowingMessages;