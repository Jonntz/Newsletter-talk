<?php

    use Google\Cloud\TextToSpeech\V1\Client\TextToSpeechClient;
    use src\services\EmailService;
    use src\services\GoogleTtsService;

    require __DIR__ . '/../vendor/autoload.php';
    require __DIR__ . '/services/EmailService.php';
    require __DIR__ . '/services/GoogleTtsService.php';

    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__ . '/..//');
    $dotenv->load();

    $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
    $email = getenv('EMAIL');
    $password = getenv('PASSWORD'); 
    $textToSpeechClient = new TextToSpeechClient([
        'credentials' => __DIR__ . '/../key.json'
    ]);

    $truncatedText = new EmailService($hostname, $email, $password);
    $texts = $truncatedText->getEmails();
    
    $news;
    foreach ($texts as $text) {
        // echo $text;
        $news = $text;
    }
    
    $googleTTS = new GoogleTtsService($news, $textToSpeechClient);
    $googleTTS->runTextToSpeech();
    

    


