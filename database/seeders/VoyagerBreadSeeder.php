<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\DataRow;
use TCG\Voyager\Models\DataType;
use TCG\Voyager\Models\Menu;
use TCG\Voyager\Models\MenuItem;
use TCG\Voyager\Models\Permission;
use TCG\Voyager\Models\Role;

class VoyagerBreadSeeder extends Seeder
{
    public function run(): void
    {
        $this->setupProducts();
        $this->setupCategories();
        $this->setupBrands();
        $this->setupOrders();
        $this->setupMenuItems();
        $this->generatePermissions();
    }

    // ─────────────────────────────────────────────
    // PRODUITS
    // ─────────────────────────────────────────────
    private function setupProducts(): void
    {
        $type = DataType::updateOrCreate(
            ['slug' => 'products'],
            [
                'name'                  => 'products',
                'display_name_singular' => 'Produit',
                'display_name_plural'   => 'Produits',
                'icon'                  => 'voyager-bag',
                'model_name'            => 'App\\Models\\Product',
                'controller'            => null,
                'generate_permissions'  => 1,
                'description'           => 'Gestion du catalogue produits',
                'server_side'           => 1,
            ]
        );

        $imgDetails  = ['resize' => ['width' => '900', 'height' => null], 'quality' => '85', 'upsize' => true,
                        'thumbnails' => [['name' => 'medium', 'scale' => '50'], ['name' => 'small', 'scale' => '25']]];
        $slugDetails = ['slugify' => ['origin' => 'name', 'forceUpdate' => false]];
        $catRel      = ['relationship' => ['type' => 'belongsTo', 'model' => 'App\\Models\\Category', 'key' => 'id', 'label' => 'name']];
        $brandRel    = ['relationship' => ['type' => 'belongsTo', 'model' => 'App\\Models\\Brand',    'key' => 'id', 'label' => 'name']];
        $numAny      = ['step' => 'any', 'min' => 0];
        $numInt      = ['step' => 1,     'min' => 0];
        $chk         = ['on' => 1, 'off' => 0];

        $rows = [
            ['field' => 'id',                'type' => 'number',          'display_name' => 'ID',                    'required' => 0, 'browse' => 1, 'read' => 0, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 1,  'details' => []],
            ['field' => 'image',             'type' => 'image',           'display_name' => 'Image principale',      'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 2,  'details' => $imgDetails],
            ['field' => 'images',            'type' => 'multiple_images', 'display_name' => 'Galerie d\'images',     'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 3,  'details' => $imgDetails],
            ['field' => 'name',              'type' => 'text',            'display_name' => 'Nom du produit',        'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 4,  'details' => []],
            ['field' => 'slug',              'type' => 'text',            'display_name' => 'Slug URL',              'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 5,  'details' => $slugDetails],
            ['field' => 'sku',               'type' => 'text',            'display_name' => 'Référence SKU',         'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 6,  'details' => []],
            ['field' => 'category_id',       'type' => 'select_dropdown', 'display_name' => 'Catégorie',             'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 7,  'details' => $catRel],
            ['field' => 'brand_id',          'type' => 'select_dropdown', 'display_name' => 'Marque',                'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 8,  'details' => $brandRel],
            ['field' => 'price',             'type' => 'number',          'display_name' => 'Prix (F CFA)',          'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 9,  'details' => $numAny],
            ['field' => 'old_price',         'type' => 'number',          'display_name' => 'Ancien prix',           'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 10, 'details' => $numAny],
            ['field' => 'stock',             'type' => 'number',          'display_name' => 'Stock',                 'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 11, 'details' => $numInt],
            ['field' => 'short_description', 'type' => 'text_area',       'display_name' => 'Résumé',                'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 12, 'details' => []],
            ['field' => 'description',       'type' => 'rich_text_box',   'display_name' => 'Description',           'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 13, 'details' => []],
            ['field' => 'power',             'type' => 'number',          'display_name' => 'Puissance (W)',         'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 14, 'details' => $numAny],
            ['field' => 'warranty',          'type' => 'text',            'display_name' => 'Garantie',              'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 15, 'details' => []],
            ['field' => 'datasheet',         'type' => 'file',            'display_name' => 'Fiche technique (PDF)', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 16, 'details' => []],
            ['field' => 'specs',             'type' => 'rich_text_box',   'display_name' => 'Caractéristiques',      'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 17, 'details' => []],
            ['field' => 'featured',          'type' => 'checkbox',        'display_name' => 'En vedette',            'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 18, 'details' => $chk],
            ['field' => 'active',            'type' => 'checkbox',        'display_name' => 'Actif',                 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 19, 'details' => $chk],
            ['field' => 'created_at',        'type' => 'timestamp',       'display_name' => 'Créé le',               'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 20, 'details' => []],
            ['field' => 'updated_at',        'type' => 'timestamp',       'display_name' => 'Modifié le',            'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 21, 'details' => []],
        ];

        $this->syncDataRows($type, $rows);
    }

    // ─────────────────────────────────────────────
    // CATÉGORIES
    // ─────────────────────────────────────────────
    private function setupCategories(): void
    {
        $type = DataType::updateOrCreate(
            ['slug' => 'categories'],
            [
                'name'                  => 'categories',
                'display_name_singular' => 'Catégorie',
                'display_name_plural'   => 'Catégories',
                'icon'                  => 'voyager-categories',
                'model_name'            => 'App\\Models\\Category',
                'controller'            => null,
                'generate_permissions'  => 1,
                'description'           => 'Catégories de produits solaires',
                'server_side'           => 0,
            ]
        );

        $imgDetails  = ['resize' => ['width' => '600', 'height' => null], 'quality' => '85', 'upsize' => true];
        $slugDetails = ['slugify' => ['origin' => 'name', 'forceUpdate' => false]];
        $numInt      = ['step' => 1, 'min' => 0];
        $chk         = ['on' => 1, 'off' => 0];

        $rows = [
            ['field' => 'id',          'type' => 'number',    'display_name' => 'ID',          'required' => 0, 'browse' => 1, 'read' => 0, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 1,  'details' => []],
            ['field' => 'image',       'type' => 'image',     'display_name' => 'Image',       'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 2,  'details' => $imgDetails],
            ['field' => 'name',        'type' => 'text',      'display_name' => 'Nom',         'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 3,  'details' => []],
            ['field' => 'slug',        'type' => 'text',      'display_name' => 'Slug URL',    'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 4,  'details' => $slugDetails],
            ['field' => 'icon',        'type' => 'text',      'display_name' => 'Icône',       'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 5,  'details' => []],
            ['field' => 'description', 'type' => 'text_area', 'display_name' => 'Description', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 6,  'details' => []],
            ['field' => 'order',       'type' => 'number',    'display_name' => 'Ordre',       'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 7,  'details' => $numInt],
            ['field' => 'active',      'type' => 'checkbox',  'display_name' => 'Active',      'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 8,  'details' => $chk],
            ['field' => 'created_at',  'type' => 'timestamp', 'display_name' => 'Créée le',    'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 9,  'details' => []],
            ['field' => 'updated_at',  'type' => 'timestamp', 'display_name' => 'Modifiée le', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 10, 'details' => []],
        ];

        $this->syncDataRows($type, $rows);
    }

    // ─────────────────────────────────────────────
    // MARQUES
    // ─────────────────────────────────────────────
    private function setupBrands(): void
    {
        $type = DataType::updateOrCreate(
            ['slug' => 'brands'],
            [
                'name'                  => 'brands',
                'display_name_singular' => 'Marque',
                'display_name_plural'   => 'Marques',
                'icon'                  => 'voyager-tag',
                'model_name'            => 'App\\Models\\Brand',
                'controller'            => null,
                'generate_permissions'  => 1,
                'description'           => 'Marques de produits solaires',
                'server_side'           => 0,
            ]
        );

        $imgDetails  = ['resize' => ['width' => '400', 'height' => null], 'quality' => '90', 'upsize' => true];
        $slugDetails = ['slugify' => ['origin' => 'name', 'forceUpdate' => false]];
        $chk         = ['on' => 1, 'off' => 0];

        $rows = [
            ['field' => 'id',         'type' => 'number',    'display_name' => 'ID',       'required' => 0, 'browse' => 1, 'read' => 0, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 1, 'details' => []],
            ['field' => 'logo',       'type' => 'image',     'display_name' => 'Logo',     'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 2, 'details' => $imgDetails],
            ['field' => 'name',       'type' => 'text',      'display_name' => 'Marque',   'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 3, 'details' => []],
            ['field' => 'slug',       'type' => 'text',      'display_name' => 'Slug URL', 'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 4, 'details' => $slugDetails],
            ['field' => 'country',    'type' => 'text',      'display_name' => 'Pays',     'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 5, 'details' => []],
            ['field' => 'featured',   'type' => 'checkbox',  'display_name' => 'Vedette',  'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 1, 'delete' => 0, 'order' => 6, 'details' => $chk],
            ['field' => 'created_at', 'type' => 'timestamp', 'display_name' => 'Créée le', 'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 7, 'details' => []],
            ['field' => 'updated_at', 'type' => 'timestamp', 'display_name' => 'Modifiée', 'required' => 0, 'browse' => 0, 'read' => 0, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 8, 'details' => []],
        ];

        $this->syncDataRows($type, $rows);
    }

    // ─────────────────────────────────────────────
    // COMMANDES
    // ─────────────────────────────────────────────
    private function setupOrders(): void
    {
        $type = DataType::updateOrCreate(
            ['slug' => 'orders'],
            [
                'name'                  => 'orders',
                'display_name_singular' => 'Commande',
                'display_name_plural'   => 'Commandes',
                'icon'                  => 'voyager-list',
                'model_name'            => 'App\\Models\\Order',
                'controller'            => null,
                'generate_permissions'  => 1,
                'description'           => 'Gestion des commandes clients',
                'server_side'           => 1,
            ]
        );

        $statusOpts = ['options' => [
            'pending'    => 'En attente',
            'confirmed'  => 'Confirmée',
            'processing' => 'En traitement',
            'shipped'    => 'Expédiée',
            'delivered'  => 'Livrée',
            'cancelled'  => 'Annulée',
        ]];

        $payOpts = ['options' => [
            'pending'  => 'En attente',
            'paid'     => 'Payée',
            'failed'   => 'Échouée',
            'refunded' => 'Remboursée',
        ]];

        $rows = [
            ['field' => 'id',             'type' => 'number',         'display_name' => 'ID',              'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 1,  'details' => []],
            ['field' => 'order_number',   'type' => 'text',           'display_name' => 'N° Commande',     'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 2,  'details' => []],
            ['field' => 'status',         'type' => 'select_dropdown','display_name' => 'Statut',          'required' => 1, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 0, 'delete' => 0, 'order' => 3,  'details' => $statusOpts],
            ['field' => 'first_name',     'type' => 'text',           'display_name' => 'Prénom',          'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 4,  'details' => []],
            ['field' => 'last_name',      'type' => 'text',           'display_name' => 'Nom',             'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 5,  'details' => []],
            ['field' => 'email',          'type' => 'text',           'display_name' => 'Email',           'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 6,  'details' => []],
            ['field' => 'phone',          'type' => 'text',           'display_name' => 'Téléphone',       'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 7,  'details' => []],
            ['field' => 'address',        'type' => 'text',           'display_name' => 'Adresse',         'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 8,  'details' => []],
            ['field' => 'city',           'type' => 'text',           'display_name' => 'Ville',           'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 9,  'details' => []],
            ['field' => 'postal_code',    'type' => 'text',           'display_name' => 'Code postal',     'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 10, 'details' => []],
            ['field' => 'subtotal',       'type' => 'number',         'display_name' => 'Sous-total',      'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 11, 'details' => []],
            ['field' => 'tax',            'type' => 'number',         'display_name' => 'TVA (18%)',       'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 12, 'details' => []],
            ['field' => 'shipping',       'type' => 'number',         'display_name' => 'Livraison',       'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 13, 'details' => []],
            ['field' => 'total',          'type' => 'number',         'display_name' => 'Total TTC',       'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 14, 'details' => []],
            ['field' => 'payment_method', 'type' => 'text',           'display_name' => 'Mode paiement',  'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 15, 'details' => []],
            ['field' => 'payment_status', 'type' => 'select_dropdown','display_name' => 'Statut paiement','required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 1, 'add' => 0, 'delete' => 0, 'order' => 16, 'details' => $payOpts],
            ['field' => 'notes',          'type' => 'text_area',      'display_name' => 'Notes',          'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 1, 'add' => 0, 'delete' => 0, 'order' => 17, 'details' => []],
            ['field' => 'created_at',     'type' => 'timestamp',      'display_name' => 'Passée le',      'required' => 0, 'browse' => 1, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 18, 'details' => []],
            ['field' => 'updated_at',     'type' => 'timestamp',      'display_name' => 'Mise à jour',    'required' => 0, 'browse' => 0, 'read' => 1, 'edit' => 0, 'add' => 0, 'delete' => 0, 'order' => 19, 'details' => []],
        ];

        $this->syncDataRows($type, $rows);
    }

    // ─────────────────────────────────────────────
    // MENU ADMIN
    // ─────────────────────────────────────────────
    private function setupMenuItems(): void
    {
        $menu = Menu::where('name', 'admin')->first();
        if (! $menu) {
            return;
        }

        $groupItem = MenuItem::firstOrCreate(
            ['menu_id' => $menu->id, 'title' => 'Boutique Solaire'],
            [
                'url'        => '',
                'target'     => '_self',
                'icon_class' => 'voyager-shop',
                'color'      => null,
                'parent_id'  => null,
                'order'      => 10,
            ]
        );

        $subitems = [
            ['title' => 'Produits',   'route' => 'voyager.products.index',   'icon' => 'voyager-bag',        'order' => 11],
            ['title' => 'Catégories', 'route' => 'voyager.categories.index', 'icon' => 'voyager-categories', 'order' => 12],
            ['title' => 'Marques',    'route' => 'voyager.brands.index',     'icon' => 'voyager-tag',        'order' => 13],
            ['title' => 'Commandes',  'route' => 'voyager.orders.index',     'icon' => 'voyager-list',       'order' => 14],
        ];

        foreach ($subitems as $item) {
            MenuItem::updateOrCreate(
                ['menu_id' => $menu->id, 'route' => $item['route']],
                [
                    'title'      => $item['title'],
                    'url'        => '',
                    'target'     => '_self',
                    'icon_class' => $item['icon'],
                    'color'      => null,
                    'parent_id'  => $groupItem->id,
                    'order'      => $item['order'],
                ]
            );
        }

        // Move the default Voyager "Categories" menu item to our group
        MenuItem::where('menu_id', $menu->id)
            ->where('title', 'Categories')
            ->where('route', 'voyager.categories.index')
            ->update(['parent_id' => $groupItem->id, 'title' => 'Catégories', 'icon_class' => 'voyager-categories']);
    }

    // ─────────────────────────────────────────────
    // PERMISSIONS
    // ─────────────────────────────────────────────
    private function generatePermissions(): void
    {
        $tables  = ['products', 'categories', 'brands', 'orders'];
        $actions = ['browse', 'read', 'edit', 'add', 'delete'];
        $adminRole = Role::where('name', 'admin')->first();

        foreach ($tables as $table) {
            foreach ($actions as $action) {
                $key  = $action . '_' . $table;
                $perm = Permission::firstOrCreate(['key' => $key, 'table_name' => $table]);

                if ($adminRole && ! $adminRole->permissions()->where('key', $key)->exists()) {
                    $adminRole->permissions()->attach($perm->id);
                }
            }
        }
    }

    // ─────────────────────────────────────────────
    // HELPER : passe les détails comme tableau PHP
    // ─────────────────────────────────────────────
    private function syncDataRows(DataType $type, array $rows): void
    {
        $fields = array_column($rows, 'field');

        // Supprimer les DataRows qui ne sont plus dans la liste (ex: ancien champ 'specs')
        DataRow::where('data_type_id', $type->id)
            ->whereNotIn('field', $fields)
            ->delete();

        foreach ($rows as $row) {
            // Voyager's setDetailsAttribute calls json_encode() internally.
            // Empty array [] encodes to '[]' which json_decode returns as array → error.
            // Use (object)[] so it encodes to '{}' which decodes to stdClass → OK.
            $details = empty($row['details']) ? (object) [] : $row['details'];

            DataRow::updateOrCreate(
                ['data_type_id' => $type->id, 'field' => $row['field']],
                [
                    'type'         => $row['type'],
                    'display_name' => $row['display_name'],
                    'required'     => $row['required'],
                    'browse'       => $row['browse'],
                    'read'         => $row['read'],
                    'edit'         => $row['edit'],
                    'add'          => $row['add'],
                    'delete'       => $row['delete'],
                    'order'        => $row['order'],
                    'details'      => $details,
                ]
            );
        }
    }
}
