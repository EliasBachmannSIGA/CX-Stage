<?php
// Variablen definieren
$name = $email = $message = "";
$showData = false;

// Wenn das Formular abgeschickt wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST["name"];
  $email = $_POST["email"];
  $message = $_POST["message"];
  $showData = true;
}
?>
<html>
<body>

<h2>Kontaktformular</h2>

<form method="post" action="">
  <label for="name">Name:</label><br>
  <input type="text" id="name" name="name" required><br><br>

  <label for="email">E-Mail:</label><br>
  <input type="email" id="email" name="email" required><br><br>

  <label for="message">Nachricht:</label><br>
  <textarea id="message" name="message" rows="4" cols="40" required></textarea><br><br>

  <input type="submit" value="Absenden">
</form>

<h2>Letzte Eingabe</h2>

<?php if ($showData): 
  echo 'Name: ', $name;
  echo '<br><br>';
  echo 'Email: ', $email;
  echo '<br><br>';
  echo 'Nachricht: ', nl2br($message);
 endif; ?>

</body>
</html>
