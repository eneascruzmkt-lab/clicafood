# ClicaFood

**Cardápio digital com vídeo para restaurantes.**

Plataforma SaaS completa que permite restaurantes criarem cardápios digitais interativos com vídeos dos pratos, Stories estilo Instagram, QR Codes ilimitados e métricas em tempo real.

![Laravel](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Vue.js](https://img.shields.io/badge/Vue.js-3-4FC08D?style=flat-square&logo=vuedotjs&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind-3.4-06B6D4?style=flat-square&logo=tailwindcss&logoColor=white)
![Inertia.js](https://img.shields.io/badge/Inertia.js-1.0-9553E9?style=flat-square)
![Vite](https://img.shields.io/badge/Vite-5-646CFF?style=flat-square&logo=vite&logoColor=white)

---

## Funcionalidades

### Landing Page
- Hero com CTAs e social proof
- Seção "Por que usar" com 6 cards
- Contadores animados de métricas
- Comparativo Antes vs Depois
- Depoimentos em carrossel
- Plano de preços
- FAQ accordion (7 perguntas)
- Botão flutuante WhatsApp
- Visual dark com vermelho/laranja como destaque

### Painel do Restaurante
- **Dashboard** — visualizações, cliques, vídeos assistidos, itens mais vistos
- **Cardápio** — CRUD completo de itens com upload de imagem e vídeo (MP4 ou link externo)
- **Categorias** — CRUD com imagem, ordenação drag & drop, toggle ativo/inativo
- **QR Codes** — geração ilimitada, cores personalizáveis, download PNG/SVG
- **Métricas** — visualizações por dia, itens mais clicados, vídeos mais assistidos, filtro por período
- **Configurações** — logo, cores, endereço, redes sociais, horário de funcionamento
- **Minha Conta** — dados pessoais, troca de senha

### Cardápio Público
- URL pública: `seudominio.com/{slug-do-restaurante}`
- **Stories** no topo com vídeos dos pratos em destaque (fullscreen, estilo Instagram)
- Filtro por categorias
- Cards dos pratos com imagem, preço e botão de play para vídeo
- Modal de vídeo inline
- Design mobile-first e responsivo
- Sem necessidade de login para o cliente final
- Tracking automático de views, cliques e video plays

### Admin Panel
- Dashboard com métricas globais (usuários, restaurantes, views, planos ativos)
- Lista de restaurantes com busca e paginação
- Detalhe do restaurante (dono, itens, categorias, analytics)
- Gestão de planos/assinaturas

### Segurança
- Autenticação com sessão e CSRF token
- Middleware de isolamento entre restaurantes (um dono não acessa dados de outro)
- Middleware admin (apenas admins acessam `/admin`)
- Redirecionamento automático: admin vai para `/admin`, owner vai para `/dashboard`
- Senha nunca exposta na API
- Rate limiting preparado

---

## Stack Tecnológica

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8.2+ / Laravel 11 |
| Frontend | Vue.js 3 |
| Bridge | Inertia.js |
| CSS | Tailwind CSS 3.4 |
| Build | Vite 5 |
| Banco | SQLite (dev) / MySQL / PostgreSQL (prod) |
| QR Code | simplesoftwareio/simple-qrcode |
| Imagem | Intervention Image |
| Vídeo | pbmedia/laravel-ffmpeg |
| Pagamentos | Laravel Cashier (Stripe) |
| Storage | Local / S3 / R2 / DigitalOcean Spaces |
| Tracking | Facebook Pixel + Google Tag Manager (slots preparados) |

---

## Instalação

### Pré-requisitos
- PHP 8.2+
- Composer
- Node.js 18+
- NPM ou Yarn

### Passo a passo

```bash
# 1. Clonar o repositório
git clone https://github.com/eneascruzmkt-lab/clicafood.git
cd clicafood

# 2. Instalar dependências PHP
composer install

# 3. Instalar dependências JS
npm install

# 4. Configurar ambiente
cp .env.example .env
php artisan key:generate

# 5. Configurar banco de dados
# Para SQLite (mais simples):
# Altere DB_CONNECTION=sqlite no .env e comente as outras linhas DB_*
touch database/database.sqlite

# Para MySQL:
# Crie o banco 'clicafood' e configure as credenciais no .env

# 6. Rodar migrations e seed
php artisan migrate --seed

# 7. Criar link do storage
php artisan storage:link

# 8. Iniciar os servidores
php artisan serve        # Terminal 1 — http://localhost:8000
npm run dev              # Terminal 2 — Vite dev server
```

### Acesso

| Conta | Email | Senha |
|-------|-------|-------|
| Admin | `admin@clicafood.com` | `admin123` |
| Demo (restaurante) | `demo@clicafood.com` | `demo1234` |

- **Landing Page:** http://localhost:8000
- **Login:** http://localhost:8000/login
- **Cardápio demo:** http://localhost:8000/burger-station
- **Admin:** http://localhost:8000/admin

---

## Estrutura do Projeto

```
clicafood/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/              # AdminDashboard, AdminRestaurant, AdminPlan
│   │   │   ├── Auth/               # Login, Register, ForgotPassword, ResetPassword
│   │   │   ├── Public/             # MenuController (cardápio público + tracking)
│   │   │   └── Restaurant/         # Dashboard, Category, MenuItem, QrCode, Analytics, Settings, Account
│   │   └── Middleware/
│   │       ├── AdminMiddleware.php
│   │       ├── EnsureHasRestaurant.php
│   │       └── HandleInertiaRequests.php
│   └── Models/                     # User, Restaurant, Category, MenuItem, QrCode, Subscription, Analytic
├── database/
│   ├── migrations/                 # 7 migrations
│   └── seeders/                    # DatabaseSeeder com dados demo
├── resources/
│   ├── css/app.css                 # Tailwind + componentes customizados
│   ├── js/
│   │   ├── app.js                  # Entry point Inertia + Vue
│   │   ├── Layouts/
│   │   │   ├── AppLayout.vue       # Sidebar responsiva (admin/owner dinâmico)
│   │   │   └── AuthLayout.vue      # Layout de login/registro
│   │   └── Pages/
│   │       ├── Admin/              # Dashboard, Restaurants, RestaurantDetail, Plans
│   │       ├── Analytics/          # Index (gráficos, rankings, filtro por período)
│   │       ├── Auth/               # Login, Register, ForgotPassword, ResetPassword
│   │       ├── Categories/         # Index, Form
│   │       ├── Dashboard/          # Index (stats, top items, top videos)
│   │       ├── Landing/            # Index (landing page completa)
│   │       ├── Menu/               # Index, Form (upload vídeo)
│   │       ├── Public/             # Menu (cardápio público com Stories)
│   │       ├── QrCode/             # Index, Form
│   │       └── Settings/           # Edit, Account
│   └── views/
│       └── app.blade.php           # Template raiz (Pixel, GTM, Inertia)
├── routes/web.php                  # Todas as rotas (guest, auth, restaurant, admin, public)
└── config/                         # app, auth, database, filesystems, services, session, etc.
```

---

## Banco de Dados

```
users           — id, name, email, cpf_cnpj, phone, password, role (admin/owner)
restaurants     — id, user_id, name, slug, description, logo, cores, endereço, redes sociais, horários
categories      — id, restaurant_id, name, image, order, active
menu_items      — id, restaurant_id, category_id, name, description, price, image, video_url, featured, available, order, views_count, video_plays_count
qr_codes        — id, restaurant_id, label, style_config (JSON), url
subscriptions   — id, user_id, plan, status, starts_at, ends_at, payment_gateway_id
analytics       — id, restaurant_id, menu_item_id, event_type (view/click/video_play), ip, user_agent, created_at
```

---

## Deploy (Produção)

### Variáveis de ambiente importantes

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=clicafood
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha

# Storage S3/R2
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=
AWS_BUCKET=
FILESYSTEM_DISK=s3

# Stripe
STRIPE_KEY=pk_live_...
STRIPE_SECRET=sk_live_...

# Tracking
FACEBOOK_PIXEL_ID=seu_pixel_id
GTM_CONTAINER_ID=GTM-XXXXXXX

# WhatsApp
WHATSAPP_NUMBER=5511999999999
```

### Build para produção

```bash
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Nginx (exemplo)

```nginx
server {
    listen 80;
    server_name seudominio.com;
    root /var/www/clicafood/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    client_max_body_size 64M;
}
```

---

## Licença

Proprietário. Todos os direitos reservados.
