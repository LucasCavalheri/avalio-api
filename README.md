# 🚀 API Laravel - Guia de Instalação Local

Este repositório contém a API desenvolvida em Laravel. Siga os passos abaixo para rodar o projeto localmente em sua máquina.

---

## ✅ Requisitos

Antes de começar, verifique se você tem os seguintes itens instalados:

-   PHP >= 8.1
-   Composer
-   Laravel instalado globalmente (opcional)

---

## ⚙️ Passo a passo para rodar o projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/LucasCavalheri/avalio-api
cd avalio-api
```

### 2. Instalar as dependências

```bash
composer install
```

### 3. Copiar o arquivo .env

```bash
cp .env.example .env
```

### 4. Gerar a chave da aplicação

```bash
php artisan key:generate
```

### 5. Configurar o banco de dados

```bash
php artisan migrate
```

### 6. Iniciar o servidor

```bash
php artisan serve
```

### 7. Acessar a documentação da API

```bash
http://localhost:8000/docs/api
```

## 💳 Configurando o Stripe

### 1. Popular o .env com as variáveis de ambiente do Stripe (pedir para o administrador)

```bash
EXEMPLO:
STRIPE_KEY=pk_test_51N9i72JZ000
STRIPE_SECRET=sk_test_51N9i72JZ000
STRIPE_WEBHOOK_SECRET=whsec_1234567890
STRIPE_BASIC_PRICE_ID=price_1N9i72JZ000
STRIPE_PRO_PRICE_ID=price_1N9i72JZ000
```

### 2. Ouvindo o webhook do Stripe

Abra um novo terminal enquanto o servidor estiver rodando e execute o comando abaixo para ouvir o webhook do Stripe.

```bash
stripe listen --forward-to localhost:8000/api/stripe/webhook
```
