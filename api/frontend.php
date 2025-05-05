<!DOCTYPE html>
<html>
<head>
    <title>FORM FOR API</title>
</head>
<body>
    <h2>Adresse eingeben</h2>
    <form id="apiForm">
        <input type="text" id="prename" placeholder="Vorname">
        <input type="text" id="lastname" placeholder="Nachname">
        <input type="number" id="id" placeholder="ID">
        <button type="submit">Speichern</button> <!-- POST -->
    </form>

    <button onclick="getData()">Daten anzeigen</button> <!-- GET -->
    <button onclick="updateData()">Daten ändern</button> <!-- PUT -->
    <button onclick="deleteData()">Daten löschen</button> <!-- DELETE -->

    <pre id="output"></pre>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $("#apiForm").on("submit", function(e) {
            e.preventDefault();
            const data = {
                prename: $("#prename").val(),
                lastname: $("#lastname").val(),
                id: $("#id").val()
            };

            $.post("backend.php", data, function(response) {
                $(".result").html(JSON.stringify(response, null, 2));
                console.log(response);
            }, "json");
        });

        function getData() {
            $.get("backend.php", function(data) {
                $(".result").html(JSON.stringify(data, null, 2));
                console.log(data);
            }, "json");
        }

        function updateData() {
            const data = {
                prename: $("#prename").val(),
                lastname: $("#lastname").val(),
                id: $("#id").val()
            };

            $.ajax({
                type: "PUT",
                url: "backend.php",
                data: data,
                success: function(response) {
                    $(".result").html(JSON.stringify(response, null, 2));
                    console.log(response);
                }
            });
        }

        function deleteData() {
            const data = { id: $("#id").val() };

            $.ajax({
                type: "DELETE",
                url: "backend.php",
                data: data,
                success: function(response) {
                    $(".result").html("Gelöscht: " + JSON.stringify(response));
                    console.log(response);
                }
            });
        }
    </script>
</body>
</html>
