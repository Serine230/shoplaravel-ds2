# 🛒 ShopLaravel — DS2 Programmation Web 2
> Application E-Commerce complète développée avec **Laravel 11** + **Tailwind CSS**
> ESSEC 2ème année E-Business — 2025–2026

---

## ✨ Fonctionnalités

### Obligatoires ✅
- 🔐 **Authentification complète** : Inscription, Connexion, Déconnexion, Réinitialisation mot de passe, Vérification email
- 📦 **CRUD Produits** : Ajout, modification, suppression avec upload d'images (galerie multiple)
- 📂 **Catalogue** : Affichage public, recherche par mot-clé, filtrage par catégorie, tri (prix, date, popularité, note)
- 🛒 **Panier** : Session-based, ajout/modification/suppression, persistance côté serveur
- 📋 **Commandes** : Checkout complet, historique, statuts (En attente → Validée → Expédiée → Livrée → Annulée)
- ⭐ **Évaluations** : Notation 1–5 étoiles + commentaire par produit

### Bonus ✅ (jusqu'à +3 pts)
| Fonctionnalité | Niveau | Points |
|---|---|---|
| 🎭 Gestion des rôles Admin/User | Niveau 3 | **+3 pts** |
| 💬 Chat entre utilisateurs | Niveau 3 | **+3 pts** |
| 🏷️ Multi-catégories (Many-to-Many) | Niveau 2 | **+2 pts** |
| 🔍 Filtres avancés (Scopes Eloquent) | Niveau 2 | **+1 pt** |
| 🖼️ Upload d'images (Storage) | Niveau 1 | **+1 pt** |
| ❤️ Wishlist | Niveau 1 | **+1 pt** |
| 📄 Pagination personnalisée | Niveau 1 | **+1 pt** |

---

## 🏗️ Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # LoginController, RegisterController, PasswordControllers
│   │   ├── Admin/          # DashboardController, AdminProductController, AdminUserController...
│   │   ├── ProductController.php
│   │   ├── CartController.php      # Panier via Session
│   │   ├── OrderController.php
│   │   ├── ReviewController.php
│   │   ├── WishlistController.php
│   │   └── MessageController.php
│   ├── Middleware/
│   │   └── EnsureUserIsAdmin.php   # Protection routes admin (abort 403)
│   └── Requests/
│       ├── StoreProductRequest.php  # Validation découplée
│       ├── UpdateProductRequest.php
│       └── StoreOrderRequest.php
├── Models/
│   ├── User.php      # isAdmin(), scopes, relationships
│   ├── Product.php   # Scopes: active, featured, priceBetween, search, sortBy
│   ├── Category.php  # Many-to-Many avec Product
│   ├── Order.php     # STATUS_LABELS, canBeCancelled()
│   ├── OrderItem.php
│   ├── Review.php
│   ├── Wishlist.php
│   └── Message.php
database/
├── migrations/       # 4 fichiers de migration propres
├── seeders/
│   └── DatabaseSeeder.php  # 50 produits, 8 catégories, 7+ users avec Faker
resources/views/
├── layouts/
│   ├── app.blade.php      # Layout principal (Tailwind, Alpine.js, Lucide icons)
│   └── admin.blade.php    # Layout admin (sidebar dark, Chart.js)
├── components/
│   └── product-card.blade.php  # Composant réutilisable <x-product-card>
├── auth/              # login, register, reset, verify
├── products/          # index, show, create, edit, mine, _form
├── cart/, orders/, reviews/, profile/, wishlist/, messages/
├── admin/             # dashboard, products, users, orders, categories
└── errors/            # 403, 404
routes/
└── web.php            # Routes séparées: public, auth, admin (avec middleware)
```

---

## 🚀 Installation

### Prérequis
- PHP >= 8.2
- Composer
- MySQL (XAMPP/WAMP) ou SQLite

### Étapes

```bash
# 1. Créer un nouveau projet Laravel (si pas déjà fait)
composer create-project laravel/laravel shop-ds2
cd shop-ds2

# 2. Copier tous les fichiers du projet dans les bons dossiers
# (remplacer app/, database/, resources/, routes/, bootstrap/app.php)

# 3. Configurer la base de données
cp .env.example .env
# Modifier DB_DATABASE, DB_USERNAME, DB_PASSWORD dans .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Exécuter les migrations et les seeders
php artisan migrate --seed

# 6. Créer le lien symbolique pour le stockage des images
php artisan storage:link

# 7. Lancer le serveur
php artisan serve
```

L'application sera accessible sur **http://localhost:8000**

---

## 🔑 Comptes de test

| Rôle | Email | Mot de passe |
|------|-------|-------------|
| **Admin** | admin@shop.com | password |
| **User** | aziz@shop.com | password |
| **User** | serine@shop.com | password |
| **User** | younsi@shop.com | password |
| **User** | amine@shop.com | password |

---

## 🎨 Stack Technique

| Technologie | Usage |
|---|---|
| **Laravel 11** | Framework backend (MVC, Eloquent, Migrations, Blade) |
| **MySQL** | Base de données relationnelle |
| **Tailwind CSS** (CDN) | Design utilitaire, responsive |
| **Alpine.js** | Interactivité légère (dropdowns, toggles) |
| **Lucide Icons** | Icônes SVG |
| **Chart.js** | Graphiques admin (revenus mensuels) |
| **SweetAlert2** | Notifications flash stylées |

---

## 🔒 Sécurité Implémentée

- ✅ **CSRF** — directive `@csrf` sur tous les formulaires
- ✅ **XSS** — utilisation exclusive de `{{ $var }}` (jamais `{!! !!}`)
- ✅ **Injection SQL** — Eloquent ORM + prepared statements
- ✅ **Mass Assignment** — propriété `$fillable` sur tous les modèles
- ✅ **Authorization** — vérification propriétaire produit avant modification
- ✅ **Middleware Admin** — `abort(403)` pour les routes admin
- ✅ **Validation** — FormRequests avec messages en français

---

## 📊 Base de données

```
users ─────────────── products ──── category_product ─── categories
  |                      |
  └── orders ─── order_items
  |
  └── reviews (liées aux products)
  |
  └── wishlists (liées aux products)
  |
  └── messages (sender_id / receiver_id)
```

---

## 👥 Répartition suggérée (équipe)

| Membre | Responsabilité |
|--------|----------------|
| **Aziz** | Architecture, Middleware Admin, Rôles |
| **Serine** | Auth (Login/Register/Reset), Profil |
| **Younsi** | CRUD Produits, Upload images, Categories |
| **Amine** | Panier, Commandes, Checkout |
| **Tous** | Tests, Seeder, README, Rapport |

---

*DS2 — Programmation Web 2 — ESSEC — Aziz Hamrouni et al.*
