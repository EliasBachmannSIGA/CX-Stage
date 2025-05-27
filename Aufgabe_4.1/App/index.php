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
  
  <h1>Neuen Eintrag erstellen</h1>

  <form id="datenForm">
  <label for="tableSelect">Tabelle wählen:</label>
  <select id="tableSelect" required>
    <option value="Teilnehmer" selected>Teilnehmer</option>
    <option value="Zuschauer">Zuschauer</option>
    <option value="Adresse">Adresse</option>
    <option value="Team">Team</option>
    <option value="Turnier">Turnier</option>
    <option value="Wertung">Wertung</option>
  </select>

    <div id="field_Vorname">
      <label for="Vorname">Vorname:</label>
      <input type="text" name="Vorname" id="Vorname">
    </div>

    <div id="field_Nachname">
      <label for="Nachname">Nachname:</label>
      <input type="text" name="Nachname" id="Nachname">
    </div>

    <div id="field_Name">
      <label for="Name">Name:</label>
      <input type="text" name="Name" id="Name">
    </div>

    <div id="field_Nation">
      <label for="Nation">Nation:</label>
      <input type="text" name="Nation" id="Nation">
    </div>

    <div id="field_Trainingsort">
      <label for="Trainingsort">Trainingsort:</label>
      <input type="text" name="Trainingsort" id="Trainingsort">
    </div>

    <div id="field_Email">
      <label for="Email">E-Mail:</label>
      <input type="email" name="Email" id="Email">
    </div>

    <div id="field_Telefonnummer">
      <label for="Telefonnummer">Telefonnummer:</label>
      <input type="text" name="Telefonnummer" id="Telefonnummer">
    </div>

    <div id="field_Team_ID">
      <label for="Team_ID">Team-ID:</label>
      <input type="number" name="Team_ID" id="Team_ID">
    </div>

    <div id="field_Adresse_ID">
      <label for="Adresse_ID">Adresse-ID:</label>
      <input type="number" name="Adresse_ID" id="Adresse_ID">
    </div>

    <div id="field_Strasse">
      <label for="Strasse">Strasse:</label>
      <input type="text" name="Strasse" id="Strasse">
    </div>

    <div id="field_Hausnummer">
      <label for="Hausnummer">Hausnummer:</label>
      <input type="text" name="Hausnummer" id="Hausnummer">
    </div>

    <div id="field_PLZ">
      <label for="PLZ">PLZ:</label>
      <input type="number" name="PLZ" id="PLZ">
    </div>

    <div id="field_Ort">
      <label for="Ort">Ort:</label>
      <input type="text" name="Ort" id="Ort">
    </div>

    <div id="field_Datum">
      <label for="Datum">Datum:</label>
      <input type="date" name="Datum" id="Datum">
    </div>

    <div id="field_Platzierung">
      <label for="Platzierung">Platzierung:</label>
      <input type="number" name="Platzierung" id="Platzierung">
    </div>

    <div id="field_Zeit">
      <label for="Zeit">Zeit:</label>
      <input type="time" name="Zeit" id="Zeit">
    </div>

    <div id="field_Punktzahl">
      <label for="Punktzahl">Punktzahl:</label>
      <input type="text" name="Punktzahl" id="Punktzahl">
    </div>

    <button type="submit">Absenden</button>
  </form>

  <div id="toast"></div>

  <script>
    function isValidEmail(email) {
      const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      return regex.test(email);
    }

    function markValid(id, valid) {
      const input = document.getElementById(id);
      input.style.borderColor = valid ? '#2ecc71' : '#e74c3c';
    }

    // Email live-validieren
    $('#Email').on('input', function () {
      const value = $(this).val();
      markValid('Email', isValidEmail(value));
    });

    // Vorname live-validieren
    $('#Vorname').on('input', function () {
      const valid = $(this).val().length >= 2;
      markValid('Vorname', valid);
    });

    // Nachname live-validieren
    $('#Nachname').on('input', function () {
      const valid = $(this).val().length >= 2;
      markValid('Nachname', valid);
    });

    // Telefonnummer live-validieren (mind. 6 Ziffern)
    $('#Telefonnummer').on('input', function () {
      const valid = /^[\d\s+()-]{6,}$/.test($(this).val());
      markValid('Telefonnummer', valid);
    });

    function showToast(message, isError = false) {
      const toast = $('#toast');
      toast.text(message);
      toast.css('background', isError ? '#c0392b' : '#27ae60'); // rot bei Fehler, grün bei Erfolg
      toast.addClass('show');

      setTimeout(() => {
        toast.removeClass('show');
      }, 4000);
    }

    $('#datenForm').on('submit', function(e) {
      e.preventDefault(); // Muss als Erstes stehen!

      // Validierungen erneut prüfen
      const email = $('#Email').val();
      const vorname = $('#Vorname').val();
      const nachname = $('#Nachname').val();
      const telefon = $('#Telefonnummer').val();

      if (!isValidEmail(email) || vorname.length < 2 || nachname.length < 2 || telefon.length < 6) {
        alert('Bitte fülle alle Felder korrekt aus, bevor du absendest.');
        return;
      }

      const data = {
        Vorname: vorname,
        Nachname: nachname,
        Email: email,
        Telefonnummer: telefon,
        Team_ID: parseInt($('#Team_ID').val()),
        Adresse_ID: parseInt($('#Adresse_ID').val())
      };

      const selectedTable = $('#tableSelect').val();
      
      fetch('../index.php/' + selectedTable, {
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
        if (result.message === 'Teilnehmer existiert bereits') {
          showToast('Fehler: Teilnehmer existiert bereits!', true);
        } else {
          showToast('Fehler: ' + (result.message || 'Unbekannter Fehler'), true);
        }
      }
      })
      .catch(err => {
        showToast('Fehler beim Senden der Anfrage: ' + err.message, true);
      });
    });

    const felderNachTabelle = {
      'Teilnehmer': ['Vorname', 'Nachname', 'Email', 'Telefonnummer', 'Team_ID', 'Adresse_ID'],
      'Zuschauer': ['Vorname', 'Nachname', 'Email', 'Telefonnummer', 'Adresse_ID'],
      'Adresse': ['Strasse', 'Hausnummer', 'PLZ', 'Ort'],
      'Team': ['Name', 'Nation', 'Trainingsort'],
      'Turnier': ['Name', 'Hausnummer', 'Ort', 'Datum'],
      'Wertung': ['Platzierung', 'Zeit', 'Punktzahl']
    };

    function updateFelderSichtbarkeit() {
      const selected = $('#tableSelect').val();
      const sichtbareFelder = felderNachTabelle[selected];

      // Alle Felder ausblenden
      $('[id^="field_"]').hide();

      // Nur relevante Felder einblenden
      sichtbareFelder.forEach(feld => {
        $('#field_' + feld).show();
      });
    }

    // Beim Laden initial aufrufen
    updateFelderSichtbarkeit();

    // Beim Wechsel der Tabelle erneut anpassen
    $('#tableSelect').on('change', updateFelderSichtbarkeit);

  </script>

</body>
</html>
