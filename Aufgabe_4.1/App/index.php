<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Teilnehmer erstellen</title>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 2em;
    }
    form {
      max-width: 400px;
      display: flex;
      flex-direction: column;
    }
    label {
      margin-top: 1em;
    }
    input, button {
      padding: 0.5em;
      margin-top: 0.2em;
    }
    #toast {
      position: fixed;
      top: 80%;
      right: 10%;
      background: #333;
      color: white;
      padding: 1em 1.5em;
      margin-left: 5%;
      border-radius: 5px;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.5s ease-in-out;
      z-index: 9999;
    }
    #toast.show {
      opacity: 1;
      pointer-events: auto;
    }
  </style>
</head>
<body>

  <h1>Neuen Teilnehmer erstellen</h1>

  <form id="teilnehmerForm">
    <label for="Vorname">Vorname:</label>
    <input type="text" name="Vorname" id="Vorname" required>

    <label for="Nachname">Nachname:</label>
    <input type="text" name="Nachname" id="Nachname" required>

    <label for="Email">E-Mail:</label>
    <input type="email" name="Email" id="Email" required>

    <label for="Telefonnummer">Telefonnummer:</label>
    <input type="text" name="Telefonnummer" id="Telefonnummer" required>

    <label for="Team_ID">Team-ID:</label>
    <input type="number" name="Team_ID" id="Team_ID" required>

    <label for="Adresse_ID">Adresse-ID:</label>
    <input type="number" name="Adresse_ID" id="Adresse_ID" required>

    <button type="submit">Absenden</button>
  </form>

  <div id="toast"></div>

  <script>
    function showToast(message, isError = false) {
      const toast = $('#toast');
      toast.text(message);
      toast.css('background', isError ? '#c0392b' : '#27ae60'); // rot bei Fehler, grün bei Erfolg
      toast.addClass('show');

      setTimeout(() => {
        toast.removeClass('show');
      }, 4000);
    }

    $('#teilnehmerForm').on('submit', function(e) {
      e.preventDefault();

      const data = {
        Vorname: $('#Vorname').val(),
        Nachname: $('#Nachname').val(),
        Email: $('#Email').val(),
        Telefonnummer: $('#Telefonnummer').val(),
        Team_ID: parseInt($('#Team_ID').val()),
        Adresse_ID: parseInt($('#Adresse_ID').val())
      };

      fetch('../index.php/Teilnehmer', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      })
      .then(resp => resp.json())
      .then(result => {
        if (result.success) {
          showToast('Teilnehmer erfolgreich hinzugefügt (ID: ' + result.insert_id + ')');
        } else {
          showToast('Fehler: ' + (result.message || 'Unbekannter Fehler'), true);
        }
      })
      .catch(err => {
        showToast('Fehler beim Senden der Anfrage: ' + err.message, true);
      });
    });
  </script>

</body>
</html>
