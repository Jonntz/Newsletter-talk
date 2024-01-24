<?php

    use Google\Cloud\TextToSpeech\V1\SynthesisInput;
    use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
    use Google\Cloud\TextToSpeech\V1\AudioConfig;
    use Google\Cloud\TextToSpeech\V1\AudioEncoding;
    use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
    use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;

    require 'vendor/autoload.php';
    

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
    $dotenv->load();

    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $email = getenv('EMAIL');
    $password = getenv('PASSWORD');   

    // Conectar à caixa de entrada
    $mailbox = imap_open($hostname, $email, $password) or die('Não foi possível conectar: ' . imap_last_error());

    // Endereço do remetente
    $remetente = 'newsletter@filipedeschamps.com.br';

    // Procurar por emails não lidos do remetente específico
    $emails = imap_search($mailbox, 'UNSEEN FROM "' . $remetente . '"');

    $textToSpeechClient = new TextToSpeechClient([
            'credentials' => __DIR__ . '/key.json'
        ]);
     

    if($emails){
        foreach ($emails as $email){ 
            $maxTextLength = 5000;
          
            $body = imap_fetchbody($mailbox, $email, 1) . '<br>';
            $text = imap_qprint($body);

            $truncatedText = (strlen($text) > $maxTextLength) ? substr($text, 0, $maxTextLength) : $text;

            $input = (new SynthesisInput())
                ->setText($truncatedText);

            // Create a VoiceSelectionParams object
            $voice = (new VoiceSelectionParams())
                ->setLanguageCode("pt-BR"); // Replace with the actual voice name

            // Create an AudioConfig object
            $audioConfig = (new AudioConfig())
                ->setAudioEncoding(AudioEncoding::LINEAR16);

            // Create a SynthesizeSpeechRequest object
            $request = (new SynthesizeSpeechRequest())
                ->setInput($input)
                ->setVoice($voice)
                ->setAudioConfig($audioConfig);

            // Call the synthesizeSpeech method with the correct request object
            $response = $textToSpeechClient->synthesizeSpeech($request);
            file_put_contents('news.mp3', $response->getAudioContent());

            $caminhoArquivo = __DIR__ . '/news.mp3';
            $comando = "vlc \"$caminhoArquivo\"";

            shell_exec($comando);
            shell_exec('del news.mp3');
        }
    } else {
        echo 'Nenhum email não lido do remetente encontrado.';
        
    }

    // Fechar a conexão com a caixa de correio
    imap_close($mailbox);

