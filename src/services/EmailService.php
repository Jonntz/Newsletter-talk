<?php
    namespace src\services;
    
    require __DIR__ .'/../../vendor/autoload.php';

    class EmailService{

        private $hostname;
        private $email;
        private $password;

        public function __construct($hostname, $email, $password){
            $this->hostname = $hostname;
            $this->email = $email;
            $this->password = $password;
        }

        public function getEmails() {
            $result = [];
            // Conectar à caixa de entrada
            $mailbox = imap_open($this->hostname, $this->email, $this->password) or die('Não foi possível conectar: ' . imap_last_error());

            // Endereço do remetente
            $remetente = 'newsletter@filipedeschamps.com.br';

            // Procurar por emails não lidos do remetente específico
            $emails = imap_search($mailbox, 'UNSEEN FROM "' . $remetente . '"');

            if($emails){
                foreach ($emails as $email){ 
                    $maxTextLength = 5000;
                  
                    $body = imap_fetchbody($mailbox, $email, 1) . '<br>';
                    $textWithLinks = imap_qprint($body);

                    $textWithoutLinks = preg_replace('/\b(?:https?|ftp):\/\/\S+\b/', '', $textWithLinks);
                    $text = preg_replace('/[^\pL\s\d\-_.,!?]/u', '', $textWithoutLinks);

        
                    $truncatedText = (strlen($text) > $maxTextLength) ? substr($text, 0, $maxTextLength) : $text;
    
                    $result[] = $truncatedText;

                    // Fechar a conexão com a caixa de correio
                    imap_close($mailbox);  

                    return $result;
                }
            } else {
                // Fechar a conexão com a caixa de correio
                imap_close($mailbox);  
                return ['Nenhum email não lido do remetente encontrado.'];
            }
        
            
        }
    }
