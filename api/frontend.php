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

    <script>
        const output = document.getElementById("output");

        document.getElementById("apiForm").addEventListener("submit", function(e) {
            e.preventDefault();
            const data = {
                prename: document.getElementById("prename").value,
                lastname: document.getElementById("lastname").value,
                id: document.getElementById("id").value
            };

            fetch("backend.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(data)
            })
            .then(res => res.json())
            .then(res => output.textContent = JSON.stringify(res, null, 2))
            .catch(err => console.error("Fehler:", err));
        });

        function getData() {
            fetch("backend.php")
                .then(res => res.json())
                .then(res => output.textContent = JSON.stringify(res, null, 2))
                .catch(err => console.error("Fehler:", err));
        }

        function updateData() {
            const data = {
                prename: document.getElementById("prename").value,
                lastname: document.getElementById("lastname").value,
                id: document.getElementById("id").value
            };

            fetch("backend.php", {
                method: "PUT",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(data)
            })
            .then(res => res.json())
            .then(res => output.textContent = JSON.stringify(res, null, 2))
            .catch(err => console.error("Fehler:", err));
        }

        function deleteData() {
            const data = { id: document.getElementById("id").value };

            fetch("backend.php", {
                method: "DELETE",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: new URLSearchParams(data)
            })
            .then(res => res.json())
            .then(res => output.textContent = `Gelöscht: ${JSON.stringify(res)}`)
            .catch(err => console.error("Fehler:", err));
        }
    </script>
</body>
</html>
