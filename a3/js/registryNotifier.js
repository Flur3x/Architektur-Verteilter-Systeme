function notifyRegistry() {
  var serverName = $('#server-name').val();
  var ip = $('#ip').val();

  resetStatusMessages();

  $.get("notifyRegistry.php", {
    name: serverName,
    ip: ip
  })
    .success(function() {
      msg('Server wurde erfolgreich angemeldet. Viel Spaß!');
    })
    .fail(function() {
      error('Es ist leider etwas schief gelaufen.');
    });
}