<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Clients</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css.css">
</head>
<body>
    <header>
        <h1>Sélectionnez un client</h1>
    </header>

    <main>
        <section class="container">
            <?php
            $output = shell_exec('/var/www/html/venv/bin/python3 /var/www/html/script1.py 2>&1');
            $clients = json_decode($output, true);

            if (!empty($clients)) {
                echo '<form>';
                echo '<label for="client">Choisissez un client :</label>';
                echo '<select name="client" id="client" onchange="submitForm()" class="form-control">';
                echo '<option value="">Sélectionnez un client</option>';

                foreach ($clients as $client) {
                    $id_cl = htmlspecialchars($client['id']);
                    $output2 = shell_exec('/var/www/html/venv/bin/python3 /var/www/html/script2.py ' . escapeshellarg($id_cl) . ' 2>&1');

                    echo '<option value="' . htmlspecialchars($output2) . '" data-info=\'' . htmlspecialchars($client['external_id']) . '\'>' .
                        "Nom: " . htmlspecialchars($client['nom']) . ' | ' .
                        "Adresse: " . htmlspecialchars($client['adresse']) . ' | ' .
                        "Email: " . htmlspecialchars($client['email']) .
                        '</option>';
                }
                echo '</select>';
                echo '</form>';
            } else {
                echo '<p>Aucun client trouvé.</p>';
            }
            ?>
        </section>

        <section>
            <div class="modal fade" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header ">
                            <h5 class="modal-title" id="clientModalLabel">Client Sélectionné</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" id="modalBody">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 - Adcosoft</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        function submitForm() {
            const clientSelect = document.getElementById("client");
            const selectedOption = clientSelect.options[clientSelect.selectedIndex];
            const selectedValue = clientSelect.value;
            const dataInfo = selectedOption.getAttribute("data-info");

            console.log("External id: ", dataInfo);
            const jsonData = JSON.parse(selectedValue);

            let modalContent = '<h5>Informations du client : ';
            const regex = /^[0-9]{3}[A-Za-z0-9]{3}$/;

            if (!regex.test(dataInfo)) {
                modalContent += `<span style="color: red;">External ID est incorrect.</span></h5>`;
            } else {
                modalContent += `<span style="color: green;">External ID est correct.</span></h5>`;
            }

            if (jsonData.length === 0) {
                modalContent += `<p style="color: orange;">Aucune facture disponible.</p>`;
            } else {
                jsonData.forEach(item => {
                    modalContent += `
                        <p>
                            Numéro: ${item.numero}<br>
                            Date: ${item.date}<br>
                            Intitulé: ${item.intitule}<br>
                            Montant TTC: ${item.montant_ttc}
                        </p>
                        <hr>
                    `;
                });
            }

            document.getElementById("modalBody").innerHTML = modalContent;
            $('#clientModal').modal('show');
        }
    </script>
</body>
</html>
