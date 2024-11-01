<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Captura os dados do formulário
    $name = $_POST['nome'];
    $email = $_POST['descricao'];
    $image = $_FILES['image'];

    // Verifica se a imagem foi enviada sem erros
    if ($image['error'] === UPLOAD_ERR_OK) {
        // Faz o upload da imagem para um serviço de hospedagem (por exemplo, Imgur)
        $uploadResponse = uploadImageToImgur($image['tmp_name']);
        
        if ($uploadResponse) {
            $imageUrl = $uploadResponse['link']; // URL da imagem hospedada
        } else {
            echo "Erro ao fazer upload da imagem.";
            exit;
        }
    } else {
        echo "Erro ao enviar a imagem.";
        exit;
    }

    // Cria um array com os dados
    $data = [
        'name' => $name,
        'email' => $email,
        'image' => $imageUrl // Armazena a URL da imagem
    ];

    // Converte o array em JSON
    $jsonData = json_encode($data);

    // URL do Webhook do Make
    $webhookUrl = "";

    // Inicializa uma nova sessão cURL
    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($jsonData)
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Executa a requisição
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Logando os dados que estão sendo enviados
    file_put_contents('log.txt', $jsonData); // Salva os dados enviados em um arquivo de log
    file_put_contents('response.txt', $response); // Salva a resposta em um arquivo de log
    file_put_contents('debug_log.txt', "HTTP Code: $httpCode\nResponse: $response\nJSON Data: $jsonData\n", FILE_APPEND);

    // Verifica se houve algum erro
    if (curl_errno($ch)) {
        echo 'Erro: ' . curl_error($ch);
    } else {
        echo 'Dados enviados com sucesso!';
         header("Location: sucesso.php"); 
    }

    // Fecha a sessão cURL
    curl_close($ch);
} else {
    echo 'Método de requisição inválido.';
}

// Função para fazer o upload da imagem para Imgur
function uploadImageToImgur($imagePath) {
    $clientId = ""; // Coloque aqui seu Client ID do Imgur
    $url = "https://api.imgur.com/3/image";

    // Inicializa a sessão cURL para o upload da imagem
    $ch = curl_init($url);
    $data = [
        'image' => base64_encode(file_get_contents($imagePath)),
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Client-ID $clientId",
        "Content-Type: application/json" // Certifique-se de que o tipo de conteúdo está correto
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // Converte os dados para JSON

    // Executa a requisição
    $response = json_decode(curl_exec($ch), true);
    
    // Fecha a sessão cURL
    curl_close($ch);
    
    // Verifica se houve erro na requisição
    if (isset($response['success']) && $response['success']) {
        return $response['data']; // Retorna os dados da imagem
    } else {
        // Logue o erro para análise
        echo 'Erro ao fazer upload: ' . ($response['data']['error'] ?? 'Erro desconhecido');
        return null;
    }
}
?>
