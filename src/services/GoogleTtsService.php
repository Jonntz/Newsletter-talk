<?php
    namespace src\services;

    use Google\Cloud\TextToSpeech\V1\AudioConfig;
    use Google\Cloud\TextToSpeech\V1\AudioEncoding;
    use Google\Cloud\TextToSpeech\V1\SynthesisInput;
    use Google\Cloud\TextToSpeech\V1\SynthesizeSpeechRequest;
    use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;

    require __DIR__ .'/../../vendor/autoload.php';

    class GoogleTtsService {
        private $truncatedText;
        private $textToSpeechClient;

        public function __construct($truncatedText, $textToSpeechClient) {
            $this->truncatedText = $truncatedText;
            $this->textToSpeechClient = $textToSpeechClient;
        }

        public function runTextToSpeech() {
            $input = (new SynthesisInput())
                ->setText($this->truncatedText);

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
            $response = $this->textToSpeechClient->synthesizeSpeech($request);
            file_put_contents('news.mp3', $response->getAudioContent());

            $caminhoArquivo = __DIR__ . '/../../news.mp3';

            $comando = "vlc \"$caminhoArquivo\"";

            shell_exec($comando);
            shell_exec('del news.mp3');
        }
    }