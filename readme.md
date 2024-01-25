# Newsletter Talk

## Script simples feito em PHP para ler a Newsletter do @filipedeschamps
### Requisitos:
- Ter a assinatura da Newsletter do Filipe Deschamps
- Ter o PHP instalado localmente
- Ter o composer instalado

### Para usar este app:

#### 1. Crie uma senha segura para apps de terceiros nas configurações da sua conta do Google.

#### 2. No console do Google Cloud, crie uma credencial de acesso, isso gerará um arquivo JSON. Baixe o JSON, renomeie para "key.json" e copie para a raiz deste projeto. Em seguida, ative na sua conta do Google Cloud o serviço "Text To Speech".

#### 3. Crie o arquivo .env na raiz deste projeto com as chaves contidas em .env-example. No EMAIL use seu gmail que tenha a assinatura da Newsletter do Filipe Deschamps, na PASSWORD você coloca a senha gerada no passo 1.

#### 4. Rode o comando composer install e para rodar o projeto use 'php src/index.php'
