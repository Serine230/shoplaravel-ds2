<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Admin User ──────────────────────────────────────────
        $admin = User::create([
            'name'              => 'Admin ShopLaravel',
            'email'             => 'admin@shop.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'email_verified_at' => now(),
        ]);

        // ─── Regular Users ───────────────────────────────────────
        $users = [];
        $userData = [
            ['Serine Bouaziz', 'serine@shop.com'],
            ['Younsi Karim',   'younsi@shop.com'],
            ['Amine Trabelsi', 'amine@shop.com'],
            ['Fatma Ben Ali',  'fatma@shop.com'],
            ['Aziz Hamrouni',  'aziz@shop.com'],
            ['Sara Mansouri',  'sara@shop.com'],
            ['Omar Jebali',    'omar@shop.com'],
        ];

        foreach ($userData as [$name, $email]) {
            $users[] = User::create([
                'name'              => $name,
                'email'             => $email,
                'password'          => Hash::make('password'),
                'role'              => 'user',
                'email_verified_at' => now(),
                'phone'             => '+216 ' . rand(20, 99) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                'address'           => $this->randomAddress(),
            ]);
        }

        $allUsers = array_merge([$admin], $users);

        // ─── Categories ──────────────────────────────────────────
        $categoriesData = [
            ['Électronique',  'Smartphones, ordinateurs, accessoires tech'],
            ['Mode',          'Vêtements, chaussures, accessoires de mode'],
            ['Maison & Déco', 'Mobilier, décoration, cuisine'],
            ['Sport & Loisirs','Équipements sportifs, plein air, fitness'],
            ['Beauté',        'Cosmétiques, soins, parfums'],
            ['Livres',        'Romans, BD, manuels, magazines'],
            ['Jeux & Jouets', 'Jeux vidéo, jouets, figurines'],
            ['Alimentation',  'Épicerie fine, bio, compléments'],
        ];

        $categories = [];
        foreach ($categoriesData as [$name, $desc]) {
            $categories[] = Category::create([
                'name'        => $name,
                'slug'        => Str::slug($name),
                'description' => $desc,
                'is_active'   => true,
                'sort_order'  => count($categories),
            ]);
        }

        // ─── Products (50 produits réalistes) ────────────────────
        $products = [];
        $productData = [
            // Électronique
            ['iPhone 15 Pro Max 256GB', 3299.99, 3799.99, 8,   0],
            ['Samsung Galaxy S24 Ultra', 2899.99, null,    12,  0],
            ['MacBook Pro M3 14"',       5999.99, 6499.99, 5,   0],
            ['AirPods Pro 2ème génération', 449.99, 499.99, 20, 0],
            ['iPad Air M2 256GB',        1599.99, null,    7,   0],
            ['Sony PlayStation 5',       1899.99, null,    4,   0],
            ['Dell XPS 15" i7',          4299.99, 4799.99, 6,   0],
            ['Logitech MX Master 3',     199.99,  249.99,  30,  0],
            // Mode
            ['Veste en cuir premium homme', 299.99, 399.99, 15, 1],
            ['Robe de soirée élégante',     189.99, null,   10, 1],
            ['Sneakers Nike Air Max 270',   249.99, 299.99, 25, 1],
            ['Montre Casio G-Shock',        349.99, null,   18, 1],
            ['Sac à main en cuir véritable',499.99, 599.99, 8,  1],
            ['Parfum Chanel N°5 100ml',     289.99, null,   12, 1],
            ['Jean slim Levis 511',         129.99, 159.99, 30, 1],
            // Maison & Déco
            ['Canapé convertible 3 places', 1299.99, 1599.99, 3, 2],
            ['Robot cuiseur Thermomix TM6',  4299.99, null,   2, 2],
            ['Aspirateur robot iRobot Roomba',599.99, 699.99, 6, 2],
            ['Lampe de bureau LED USB',      89.99,  null,    25, 2],
            ['Ensemble vaisselle 24 pièces', 199.99, 259.99, 10, 2],
            ['Tableau décoratif 60x90cm',    149.99, null,   15, 2],
            // Sport
            ['Vélo de route carbone',       1899.99, 2299.99, 4, 3],
            ['Tapis de course pliable',      799.99,  999.99, 5, 3],
            ['Gants de boxe Everlast',        79.99,  null,  20, 3],
            ['Kayak gonflable 2 places',     599.99,  699.99, 3, 3],
            ['Raquette de tennis Wilson',    199.99,  249.99, 12, 3],
            ['Casque vélo Decathlon',         59.99,  null,  30, 3],
            // Beauté
            ['Sérum visage Vitamin C',        89.99,  null,  25, 4],
            ['Palette maquillage Urban Decay',229.99, 269.99, 10, 4],
            ['Huile d\'argan bio 100ml',       49.99,  null,  40, 4],
            ['Coffret parfum homme',          149.99, 199.99, 8, 4],
            // Livres
            ['L\'Étranger – Albert Camus',     24.99, null,  50, 5],
            ['Atomic Habits – James Clear',    39.99, 44.99, 35, 5],
            ['Dune – Frank Herbert',           35.99, null,  40, 5],
            ['Le Petit Prince – Saint-Exupéry',19.99, null,  60, 5],
            ['Deep Work – Cal Newport',        42.99, 49.99, 28, 5],
            // Jeux
            ['Zelda: Breath of the Wild',     79.99, null,   15, 6],
            ['LEGO Technic Ferrari 42143',   349.99, 399.99, 7, 6],
            ['PlayStation 5 Spider-Man 2',    79.99, null,   20, 6],
            ['Monopoly Édition Classique',    59.99, 69.99,  25, 6],
            // Alimentation
            ['Café Arabica premium 1kg',       49.99, null,  30, 7],
            ['Huile d\'olive extra vierge 1L', 29.99, 34.99, 45, 7],
            ['Miel de thym 500g',              39.99, null,  20, 7],
            ['Chocolat noir Valrhona 72%',     24.99, 29.99, 35, 7],
            // Mix bonus
            ['Drone DJI Mini 3',             799.99, 899.99, 5,  0],
            ['Guitare acoustique Yamaha',    399.99,  null,  8,  3],
            ['Cours de photographie en ligne',149.99, 199.99,999, 5],
            ['Table basse scandinave',        499.99,  null, 4,  2],
            ['Crème solaire SPF 50+ 200ml',    29.99,  null, 50, 4],
            ['Kit jardinage complet',          99.99,  119.99,15, 3],
        ];

        foreach ($productData as $i => [$title, $price, $oldPrice, $stock, $catIdx]) {
            $seller = $allUsers[array_rand($allUsers)];
            $p = Product::create([
                'user_id'     => $seller->id,
                'title'       => $title,
                'slug'        => Product::generateUniqueSlug($title),
                'description' => $this->generateDescription($title),
                'price'       => $price,
                'old_price'   => $oldPrice,
                'stock'       => $stock,
                'is_active'   => true,
                'is_featured' => $i < 8, // Les 8 premiers sont en vedette
                'views'       => rand(10, 500),
            ]);

            // Attach primary category + maybe a second
            $p->categories()->attach($categories[$catIdx]->id);
            if (rand(0, 1) && $catIdx > 0) {
                $secondCat = $categories[array_rand($categories)];
                if ($secondCat->id !== $categories[$catIdx]->id) {
                    $p->categories()->attach($secondCat->id);
                }
            }

            $products[] = $p;
        }

        // ─── Reviews ─────────────────────────────────────────────
        $usersForReviews = array_slice($allUsers, 1); // exclude admin
        foreach ($products as $product) {
            $reviewerCount = rand(0, min(5, count($usersForReviews)));
            $reviewers     = array_splice($usersForReviews, 0, 0) + $usersForReviews;
            shuffle($reviewers);

            $used = [];
            for ($i = 0; $i < $reviewerCount; $i++) {
                $reviewer = $reviewers[$i];
                if (in_array($reviewer->id, $used)) continue;
                $used[] = $reviewer->id;

                Review::create([
                    'user_id'    => $reviewer->id,
                    'product_id' => $product->id,
                    'rating'     => rand(3, 5),
                    'title'      => $this->reviewTitles()[array_rand($this->reviewTitles())],
                    'comment'    => $this->reviewComments()[array_rand($this->reviewComments())],
                    'is_approved'=> true,
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }

        // ─── Sample Orders ───────────────────────────────────────
        foreach (array_slice($users, 0, 4) as $user) {
            for ($o = 0; $o < rand(1, 3); $o++) {
                $orderProducts = array_rand($products, rand(1, 3));
                if (!is_array($orderProducts)) $orderProducts = [$orderProducts];

                $items    = [];
                $subtotal = 0;

                foreach ($orderProducts as $idx) {
                    $p   = $products[$idx];
                    $qty = rand(1, 2);
                    $lineTotal = $p->price * $qty;
                    $subtotal += $lineTotal;
                    $items[]  = ['product_id' => $p->id, 'quantity' => $qty, 'price' => $p->price, 'total' => $lineTotal];
                }

                $shipping = $subtotal >= 100 ? 0 : 7;
                $statuses = ['en_attente', 'validee', 'expediee', 'livree', 'annulee'];

                $order = Order::create([
                    'user_id'          => $user->id,
                    'subtotal'         => $subtotal,
                    'shipping'         => $shipping,
                    'total'            => $subtotal + $shipping,
                    'status'           => $statuses[array_rand($statuses)],
                    'payment_method'   => ['cash', 'card', 'virement'][rand(0, 2)],
                    'shipping_address' => $this->randomAddress(),
                    'shipping_city'    => ['Tunis', 'Sfax', 'Sousse', 'Nabeul'][rand(0, 3)],
                    'shipping_phone'   => '+216 ' . rand(20, 99) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                    'created_at'       => now()->subDays(rand(1, 90)),
                ]);

                $order->items()->createMany($items);
            }
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────
    private function generateDescription(string $title): string
    {
        $descriptions = [
            "Découvrez {$title}, un produit de qualité exceptionnelle sélectionné avec soin pour vous offrir la meilleure expérience. Fabriqué avec des matériaux premium, il allie performance et esthétique.",
            "{$title} est le choix idéal pour ceux qui cherchent un rapport qualité-prix imbattable. Testé et approuvé par nos experts, ce produit vous garantit une satisfaction totale.",
            "Profitez de {$title} au meilleur prix. Ce produit haut de gamme est parfait pour un usage quotidien. Livraison rapide et retours facilités.",
        ];
        return $descriptions[array_rand($descriptions)];
    }

    private function randomAddress(): string
    {
        $nums    = [12, 45, 78, 3, 101, 55, 23];
        $streets = ['Avenue Habib Bourguiba', 'Rue de la République', 'Rue Ibn Khaldoun', 'Avenue de France', 'Rue Alain Savary'];
        return $nums[array_rand($nums)] . ', ' . $streets[array_rand($streets)];
    }

    private function reviewTitles(): array
    {
        return ['Excellent produit !', 'Très satisfait', 'Bon rapport qualité/prix', 'Je recommande', 'Produit conforme', 'Très bien', 'Parfait !'];
    }

    private function reviewComments(): array
    {
        return [
            'Produit reçu rapidement, conforme à la description. Je suis très satisfait de mon achat.',
            'Excellent rapport qualité/prix. Je recommande vivement ce vendeur !',
            'Très bon produit, bien emballé. La livraison était rapide. Je recommande.',
            'Qualité au rendez-vous. Le produit correspond parfaitement aux photos et à la description.',
            'Commande passée sans problème, livraison dans les délais. Produit de très bonne qualité.',
        ];
    }
}
