<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dados do Google Sheets</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Dados da Planilha do Google Sheets</h1>

        <?php
$apiKey = ''; // Sua chave da API
$spreadsheetId = ''; // ID da planilha
$range = 'Datos!A1:D10'; // Nome da aba "Datos"

// URL para buscar os dados
$url = "https://sheets.googleapis.com/v4/spreadsheets/$spreadsheetId/values/$range?key=$apiKey";


        // Obter os dados da planilha
        $response = @file_get_contents($url);
        
        if ($response === FALSE) {
            echo "<div class='alert alert-danger'>Erro ao acessar a API: " . htmlspecialchars($http_response_header[0]) . "</div>";
            var_dump($http_response_header); // Adicione isso para ver os cabeçalhos de resposta
            exit;
        }

        $data = json_decode($response, true);

        // Exibir os dados em uma tabela
        if (isset($data['values'])) {
            echo '<table class="table table-bordered mt-3">';
            echo '<thead><tr>';

            // Cabeçalhos da tabela
            foreach ($data['values'][0] as $header) {
                echo "<th>$header</th>";
            }
            echo '</tr></thead>';
            echo '<tbody>';

            // Dados da tabela
            for ($i = 1; $i < count($data['values']); $i++) {
                echo '<tr>';
                foreach ($data['values'][$i] as $cell) {
                    echo "<td>$cell</td>";
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "<div class='alert alert-danger'>Erro ao obter dados da planilha: " . htmlspecialchars($data['error']['message']) . "</div>";
        }
        ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
