# 🌟 API Avalio

Uma API completa para uma plataforma de avaliações e reviews de negócios, com sistema de assinaturas e notificações! 🚀

---

## 📋 Sobre o Projeto

O **Avalio** é uma API para uma plataforma de avaliações e reviews de negócios, que permite que empresas gerenciem sua presença online e interajam com o feedback de seus clientes. Desenvolvida com tecnologias modernas, a plataforma oferece uma experiência completa:

-   **Autenticação e Segurança**: Utiliza **Laravel Sanctum** para autenticação segura via tokens e proteção de rotas
-   **Pagamentos e Assinaturas**: Integração com **Stripe** para gerenciamento de planos e pagamentos recorrentes
-   **Upload de Imagens**: Usa **Amazon S3** para armazenamento seguro e eficiente de logos e imagens de capa dos negócios
-   **Sistema de Notificações**: Notificações em tempo real via app, além de integrações com **Twilio** para envio de mensagens via WhatsApp e SMS
-   **Comunicação por Email**: Utiliza **Laravel Mail** com **Mailtrap** para envios seguros de emails transacionais
-   **Sistema de Filas**: Implementa **Laravel Queue** para processamento assíncrono de tarefas pesadas
-   **Webhooks**: Integração em tempo real com eventos do Stripe para gestão de assinaturas
-   **Documentação**: API totalmente documentada e disponível através do endpoint `/docs/api`

O diferencial do Avalio está na sua simplicidade: donos de negócios precisam apenas compartilhar o link do seu negócio com seus clientes. A partir daí, qualquer pessoa pode deixar sua avaliação de forma rápida e descomplicada, sem necessidade de criar uma conta. Os clientes podem:

-   Avaliar com 1 a 5 estrelas
-   Deixar comentários detalhados (opcional)
-   Escolher se identificar pelo nome ou permanecer anônimo
-   Ver todas as avaliações anteriores do negócio
-   Acompanhar respostas dos proprietários (disponível no plano pro)

É uma solução completa para conectar empresas e clientes através de avaliações, fornecendo insights valiosos para melhorias e construindo uma reputação online sólida!

## ✨ Funcionalidades

Aqui estão as principais funcionalidades da API:

-   **👤 Gerenciamento de Usuários**

    -   Cadastro com nome, email, celular e senha
    -   Login seguro com email e senha
    -   Recuperação de senha com envio de email seguro
    -   Atualização de perfil (nome, email, celular e senha)
    -   Exclusão de conta

-   **🏢 Gerenciamento de Negócios**

    -   Cadastro completo com nome, descrição, telefone, email e endereço
    -   Atualização, exclusão e listagem de negócios
    -   Upload de imagens de logo e capa
    -   Limites de negócios baseados no plano de assinatura (1 para básico, 3 para pro)

-   **⭐ Gerenciamento de Reviews**

    -   Criação de reviews com nome, nota e comentário
    -   Listagem de todas as reviews por negócio
    -   Aprovação automática para plano básico
    -   Moderação de reviews (exclusivo plano pro)
    -   Respostas a reviews (exclusivo plano pro)

-   **🔔 Sistema de Notificações**

    -   Notificações em tempo real via app
    -   Notificações via WhatsApp (exclusivo plano pro)
    -   Listagem de todas as notificações
    -   Marcação de notificações como lidas/não lidas
    -   Exclusão de notificações
    -   Contagem de notificações não lidas

-   **💳 Integração com Stripe**

    -   Assinatura de planos (basic e pro)
    -   Gerenciamento de assinaturas (iniciar, trocar planos e cancelar)
    -   Visualização do plano atual
    -   Histórico completo de assinaturas
    -   Histórico de pagamentos e faturas
    -   Webhooks para eventos do Stripe

-   **🔐 Autenticação e Segurança**

    -   Middleware de autenticação (`auth:sanctum`) para proteger rotas
    -   Middleware de verificação de assinatura ativa
    -   Limitação de requisições em rotas sensíveis

---

## 🖥️ Tecnologias Utilizadas

Este projeto foi desenvolvido com as seguintes tecnologias:

-   **PHP** como linguagem principal
-   **Laravel** como framework para construção da API
-   **Laravel Cashier** para integração com Stripe
-   **Stripe** para gerenciamento de pagamentos e assinaturas
-   **Sanctum** para autenticação baseada em tokens
-   **Amazon S3** para armazenamento de imagens
-   **Twilio** para envio de notificações via WhatsApp e SMS
-   **Laravel Mail** para envio de emails transacionais

## 📦 Pré-requisitos

Para rodar este projeto, você precisa ter instalado:

-   PHP 8.2 ou superior
-   Composer
-   MySQL ou PostgreSQL
-   Conta no Stripe (para processamento de pagamentos)
-   Conta na AWS com acesso ao S3 (para armazenamento de imagens)
-   Conta no Twilio (para envio de mensagens)
-   Conta no Mailtrap (para testes de envio de email)

## 🚀 Configuração do Ambiente

1. Clone o repositório
2. Instale as dependências:

```bash
composer install
```

3. Configure o arquivo `.env` com suas credenciais:

```bash
cp .env.example .env
```

```env
# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Mail (Mailtrap para ambiente de desenvolvimento)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=seu_usuario_mailtrap
MAIL_PASSWORD=sua_senha_mailtrap
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=seu_email
MAIL_FROM_NAME="${APP_NAME}"

# Stripe
STRIPE_KEY=sua_stripe_key
STRIPE_SECRET=seu_stripe_secret
STRIPE_WEBHOOK_SECRET=seu_webhook_secret
STRIPE_BASIC_PRICE_ID=preco_plano_basico
STRIPE_PRO_PRICE_ID=preco_plano_pro

# AWS S3
AWS_ACCESS_KEY_ID=sua_access_key
AWS_SECRET_ACCESS_KEY=sua_secret_key
AWS_DEFAULT_REGION=sua_regiao
AWS_BUCKET=seu_bucket
AWS_USE_PATH_STYLE_ENDPOINT=true_ou_false

# Twilio
TWILIO_SID=seu_account_sid
TWILIO_AUTH_TOKEN=seu_auth_token
TWILIO_WHATSAPP_FROM=seu_numero_whatsapp_do_twilio
```

> **Nota**: Para ambiente de desenvolvimento, utilizamos o Mailtrap para testar o envio de emails de forma segura, sem risco de enviar emails reais acidentalmente.

4. Gere a chave da aplicação:

```bash
php artisan key:generate
```

5. Abra outro terminal e inicie a escuta da fila do Laravel:

```bash
php artisan queue:listen
```

6. Abra outro terminal e inicie a escuta de webhooks do Stripe (para ambiente de desenvolvimento):

```bash
stripe listen --forward-to http://localhost:8000/api/stripe/webhook
```

7. Execute as migrações:

```bash
php artisan migrate
```

8. Inicie o servidor:

```bash
php artisan serve
```

## 📝 Documentação da API

A documentação completa da API está disponível em `/docs/api` quando o servidor está rodando. Exemplo:

```bash
http://localhost:8000/docs/api
```

## 🔒 Planos e Funcionalidades

### Plano Básico

-   1 negócio ativo
-   Aprovação automática de reviews
-   Visualização de reviews
-   Sem acesso à moderação de reviews
-   Sem permissão para responder reviews
-   Sem acesso ao sistema de notificações

### Plano Pro

-   Até 3 negócios ativos
-   Moderação completa de reviews
-   Resposta a reviews
-   Notificações via app e WhatsApp
-   Todas as funcionalidades do plano básico

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
