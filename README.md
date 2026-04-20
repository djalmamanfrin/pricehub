# 📊 PriceHub — Comparador de Preços via WhatsApp

Sistema para consulta de preços de produtos de supermercados via WhatsApp, com foco em comparação inteligente, construção de audiência e futura monetização com anúncios para empresas.

🏷️ Nomes para apresentar o projeto:
- Código: PriceHub
- Usuário: Preço no Zap
- Comercial: PriceHub Ads

---

## 🚀 Objetivo do Projeto

- Permitir que usuários consultem preços via WhatsApp
- Comparar preços entre mercados
- Criar uma base de dados colaborativa
- Evoluir para uma plataforma de anúncios (PriceHub Ads)

---

## 🧠 Estrutura do Projeto
### Tabelas principais
- products → produtos cadastrados
- markets → mercados
- offers → preços observados
- conversations → estado da conversa (M2M)

## 🔄 Fluxo do Sistema
```
Usuário (WhatsApp)
   ↓
n8n (Webhook)
   ↓
Backend (Laravel)
   ↓
PostgreSQL
   ↓
Resposta ao usuário
```
### 💬 Exemplo de uso
- Usuário: `coca 2l`
- Resposta:
```
Coca-Cola 2L:
- Muffato: R$ 8,99
- Condor: R$ 9,49
```

### 🤖 Fluxo M2M
Estados possíveis
- INITIAL
- SEARCHING
- AMBIGUOUS
- AWAITING_SELECTION
- RESOLVED

Exemplo de contexto salvo:
```json
{
    "step": {
        "step": "AWAITING_SELECTION",
        "options": [
            {
                "id": 1,
                "name": "Coca-Cola 2L"
            },
            {
                "id": 2,
                "name": "Coca-Cola 600ml"
            }
        ]
    }
}
```
### 🔍 Busca de produtos
```
SELECT *
FROM products
WHERE normalized_name ILIKE '%coca%'
LIMIT 5;
```
### 📦 Integração com WhatsApp
- n8n (webhook)
- API externa (Zenvia, Twilio ou oficial)

### 💰 Monetização (futuro)
- Destaque de ofertas patrocinadas 
- Ranking de produtos promovidos 
- Painel para mercados 
- Publicidade segmentada

## 🧱 Stack Tecnológica

- PHP 8.2+
- Laravel 12
- PostgreSQL
- Node.js 22+
- n8n (integração com WhatsApp)

---

## 📋 Requisitos

- PHP 8.2 ou superior → `php -v`
- PostgreSQL → `psql --version`
- Composer → `composer --version`
- Node.js 22+ → `node -v`
- Git → `git -v`

---

## Como rodar o projeto baixado

- Duplicar o arquivo ".env.example" e renomear para ".env".

- Para a funcionalidade enviar e-mail funcionar, necessário alterar as credenciais do servidor de envio de e-mail no arquivo .env.
- Utilizar o servidor fake durante o desenvolvimento: [Acessar envio gratuito de e-mail](https://mailtrap.io)
- Utilizar o servidor Iagente no ambiente de produção: [Acessar envio gratuito de e-mail](https://login.iagente.com.br)
```
MAIL_MAILER=smtp
MAIL_SCHEME=null
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=nome-do-usuario-na-mailtrap
MAIL_PASSWORD=senha-do-usuario-na-mailtrap
MAIL_FROM_ADDRESS="colocar-email-remetente@meu-dominio.com.br"
MAIL_FROM_NAME="${APP_NAME}"
```

Instalar as dependências do PHP.
```
composer install
```

Instalar as dependências do Node.js.
```
npm install
```

Gerar a chave no arquivo .env.
```
php artisan key:generate
```

Executar as migrations para criar as tabelas e as colunas.
```
php artisan migrate
```

Executar as seeders para cadastrar os dados de teste.
```
php artisan db:seed
```

Rodar o projeto local.
```
composer run dev
```

Acessar a página criada com Laravel.
```
http://127.0.0.1:8000
```

Limpar o cache quando a tela ficar em branco.
```
php artisan route:clear
php artisan view:clear
php artisan config:clear
php artisan cache:clear
php artisan optimize:clear
```
