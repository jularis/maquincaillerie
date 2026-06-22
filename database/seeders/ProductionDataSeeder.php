<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductionDataSeeder extends Seeder
{
    private array $imageCache = [];

    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        Product::truncate();
        Brand::truncate();
        Category::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        Storage::disk('public')->makeDirectory('products');

        $this->command->info('Création des catégories...');
        $cats = $this->createCategories();

        $this->command->info('Création des marques...');
        $brands = $this->createBrands();

        $this->command->info('Création des produits + téléchargement des images...');
        $this->createProducts($cats, $brands);

        $this->command->info('✅ ' . Product::count() . ' produits créés.');
    }

    // -------------------------------------------------------------------------
    // Categories
    // -------------------------------------------------------------------------
    private function createCategories(): array
    {
        $defs = [
            ['name' => 'Kits Solaires',      'slug' => 'kits-solaires',     'icon' => '⚡',  'order' => 1],
            ['name' => 'Panneaux Solaires',  'slug' => 'panneaux-solaires', 'icon' => '☀️', 'order' => 2],
            ['name' => 'Batteries Solaires', 'slug' => 'batteries',         'icon' => '🔋', 'order' => 3],
            ['name' => 'Onduleurs Solaires', 'slug' => 'onduleurs',         'icon' => '⚙️', 'order' => 4],
            ['name' => 'Accessoires',        'slug' => 'accessoires',       'icon' => '🛠️','order' => 5],
            ['name' => 'Bornes de Recharge', 'slug' => 'bornes-recharge',   'icon' => '🔌', 'order' => 6],
        ];

        $map = [];
        foreach ($defs as $d) {
            $cat = Category::create([
                'name'        => $d['name'],
                'slug'        => $d['slug'],
                'icon'        => $d['icon'],
                'description' => '',
                'image'       => null,
                'parent_id'   => null,
                'order'       => $d['order'],
                'active'      => true,
            ]);
            $map[$d['slug']] = $cat->id;
        }

        return $map;
    }

    // -------------------------------------------------------------------------
    // Brands
    // -------------------------------------------------------------------------
    private function createBrands(): array
    {
        $defs = [
            ['name' => 'BSM',            'slug' => 'bsm',            'country' => 'CN'],
            ['name' => 'GSB',            'slug' => 'gsb',            'country' => 'CN'],
            ['name' => 'Victron Energy', 'slug' => 'victron-energy', 'country' => 'NL'],
            ['name' => 'LUX Power',      'slug' => 'lux-power',      'country' => 'CN'],
            ['name' => 'Ecobox',         'slug' => 'ecobox',         'country' => 'CN'],
            ['name' => 'IVEM',           'slug' => 'ivem',           'country' => 'CN'],
            ['name' => 'HY Solar',       'slug' => 'hy-solar',       'country' => 'CN'],
        ];

        $map = [];
        foreach ($defs as $d) {
            $brand = Brand::create([
                'name'     => $d['name'],
                'slug'     => $d['slug'],
                'logo'     => null,
                'country'  => $d['country'],
                'featured' => false,
            ]);
            $map[$d['slug']] = $brand->id;
        }

        return $map;
    }

    // -------------------------------------------------------------------------
    // Image downloader
    // -------------------------------------------------------------------------
    private function img(string $url, string $slug): ?string
    {
        if (array_key_exists($url, $this->imageCache)) {
            return $this->imageCache[$url];
        }

        try {
            $ctx = stream_context_create([
                'http' => [
                    'timeout'       => 20,
                    'user_agent'    => 'Mozilla/5.0 (compatible; Laravel)',
                    'ignore_errors' => true,
                ],
                'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);

            $data = @file_get_contents($url, false, $ctx);

            if (!$data || strlen($data) < 500) {
                $this->command->warn("  âš  Ã‰chec image: " . basename($url));
                return $this->imageCache[$url] = null;
            }

            $ext  = strtolower(pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION)) ?: 'jpg';
            $path = "products/{$slug}.{$ext}";
            Storage::disk('public')->put($path, $data);
            $this->command->line("  âœ“ {$path}");
            return $this->imageCache[$url] = $path;
        } catch (\Exception $e) {
            $this->command->warn("  âœ— Exception: " . $e->getMessage());
            return $this->imageCache[$url] = null;
        }
    }

    // -------------------------------------------------------------------------
    // Products
    // -------------------------------------------------------------------------
    private function createProducts(array $cats, array $brands): void
    {
        foreach ($this->productList($cats, $brands) as $data) {
            $this->command->line("â†’ {$data['name']}");

            $img = isset($data['image_url']) ? $this->img($data['image_url'], $data['slug']) : null;

            Product::create([
                'category_id'       => $data['category_id'],
                'brand_id'          => $data['brand_id'] ?? null,
                'name'              => $data['name'],
                'slug'              => $data['slug'],
                'sku'               => $data['sku'],
                'short_description' => $data['short_description'] ?? '',
                'description'       => $data['description'] ?? '',
                'price'             => $data['price'],
                'old_price'         => $data['old_price'] ?? null,
                'stock'             => $data['stock'] ?? 10,
                'image'             => $img,
                'images'            => [],
                'specs'             => $data['specs'] ?? [],
                'power'             => $data['power'] ?? null,
                'warranty'          => $data['warranty'] ?? '12 mois',
                'featured'          => $data['featured'] ?? false,
                'active'            => true,
                'views'             => 0,
            ]);
        }
    }

    private function productList(array $cats, array $brands): array
    {
        return array_merge(
            $this->kits($cats, $brands),
            $this->panneaux($cats, $brands),
            $this->batteries($cats, $brands),
            $this->onduleurs($cats, $brands),
            $this->accessoires($cats, $brands),
            $this->bornesRecharge($cats, $brands),
        );
    }

    // =========================================================================
    // PANNEAUX SOLAIRES
    // =========================================================================
    private function panneaux(array $cats, array $brands): array
    {
        $c = $cats['panneaux-solaires'];
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Panneau solaire Bifacial Double verre 720W',
                'slug'              => 'panneau-solaire-bifacial-double-verre-720w',
                'sku'               => 'PAN-BIF-720W',
                'short_description' => 'Panneau bifacial double verre haute performance 720Wc, idÃ©al pour installations Ã  haut rendement.',
                'description'       => 'Panneau solaire bifacial double verre 720W. La technologie bifaciale permet de capter la lumiÃ¨re des deux cÃ´tÃ©s pour un rendement optimal. Verre renforcÃ© anti-reflet, rÃ©sistant aux conditions climatiques extrÃªmes.',
                'price'             => 95000,
                'power'             => null,
                'warranty'          => '25 ans',
                'stock'             => 15,
                'featured'          => true,
                'specs'             => ['Puissance' => '720 Wc', 'Type' => 'Bifacial Double verre', 'Technologie' => 'Monocristallin', 'Dimensions' => '2278 Ã— 1134 Ã— 35 mm'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/Hae05751055ed44968b54d17d38c9ac2cx.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Panneau solaire TOPCON Bifacial 610W',
                'slug'              => 'panneau-solaire-topcon-bifacial-610w',
                'sku'               => 'PAN-TOP-610W',
                'short_description' => 'Panneau TOPCON bifacial 610W, technologie N-type pour un rendement supÃ©rieur jusqu\'Ã  22%.',
                'description'       => 'Panneau solaire TOPCON Bifacial 610W. La technologie TOPCON (Tunnel Oxide Passivated Contact) offre un rendement exceptionnel. CertifiÃ© pour les environnements difficiles.',
                'price'             => 85000,
                'power'             => null,
                'warranty'          => '25 ans',
                'stock'             => 20,
                'featured'          => true,
                'specs'             => ['Puissance' => '610 Wc', 'Type' => 'TOPCON Bifacial', 'Technologie' => 'N-type', 'Rendement' => 'jusqu\'Ã  22%'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/Hf2e114e6fa3242ceb920662f9ec1ffe6K.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['gsb'],
                'name'              => 'Panneau solaire mono 550Wc GSB',
                'slug'              => 'panneau-solaire-mono-550wc-gsb',
                'sku'               => 'PAN-GSB-550W',
                'short_description' => 'Panneau monocristallin 550Wc GSB, half-cell haute efficacitÃ© pour systÃ¨mes rÃ©sidentiels et commerciaux.',
                'description'       => 'Panneau solaire monocristallin 550Wc GSB. Technologie half-cell pour une production maximale mÃªme en cas d\'ombrage partiel. Cadre en aluminium anodisÃ© rÃ©sistant Ã  la corrosion.',
                'price'             => 75000,
                'power'             => null,
                'warranty'          => '25 ans',
                'stock'             => 25,
                'featured'          => false,
                'specs'             => ['Puissance' => '550 Wc', 'Type' => 'Monocristallin Half-Cell', 'Marque' => 'GSB', 'Garantie produit' => '12 ans'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/02/solar-panel.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Panneau solaire mono 450Wc',
                'slug'              => 'panneau-solaire-mono-450wc',
                'sku'               => 'PAN-MONO-450W',
                'short_description' => 'Panneau monocristallin 450Wc, excellent rapport qualitÃ©-prix pour installations rÃ©sidentielles.',
                'description'       => 'Panneau solaire monocristallin 450Wc. Solution fiable et Ã©conomique pour vos installations solaires. Compatible avec tous les rÃ©gulateurs MPPT et onduleurs hybrides.',
                'price'             => 70000,
                'power'             => null,
                'warranty'          => '25 ans',
                'stock'             => 30,
                'featured'          => false,
                'specs'             => ['Puissance' => '450 Wc', 'Type' => 'Monocristallin', 'Tension Voc' => '49,5 V', 'Courant Isc' => '11,5 A'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/9e9217126724e64a9c1ab3cf471c9919_medium-1.jpg',
            ],
        ];
    }

    // =========================================================================
    // BATTERIES SOLAIRES
    // =========================================================================
    private function batteries(array $cats, array $brands): array
    {
        $c = $cats['batteries'];
        return [
            [
                'category_id'       => $c,
                'brand_id'          => $brands['bsm'],
                'name'              => 'Batterie Lithium BSM 24V 208AH',
                'slug'              => 'batterie-lithium-bsm-24v-208ah',
                'sku'               => 'BAT-BSM-24V-208',
                'short_description' => 'Batterie lithium LiFePO4 BSM 24V 208Ah, technologie haute performance pour systÃ¨mes solaires autonomes.',
                'description'       => 'Batterie Lithium BSM 24V 208AH. Technologie LiFePO4 (lithium fer phosphate) pour une longue durÃ©e de vie de plus de 4000 cycles. SystÃ¨me BMS intÃ©grÃ© pour une protection optimale.',
                'price'             => 600000,
                'power'             => null,
                'warranty'          => '3 ans',
                'stock'             => 8,
                'featured'          => true,
                'specs'             => ['Tension' => '24 V', 'CapacitÃ©' => '208 Ah', 'Ã‰nergie' => '4,99 kWh', 'Cycles' => '> 4000', 'Technologie' => 'LiFePO4'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/H460ca4063b2d471faf49f8fdd599f20fE.png',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['bsm'],
                'name'              => 'Batterie Lithium BSM 48V 280AH',
                'slug'              => 'batterie-lithium-bsm-48v-280ah',
                'sku'               => 'BAT-BSM-48V-280',
                'short_description' => 'Batterie lithium BSM 48V 280Ah â€” 13,4 kWh de stockage pour installations solaires haute puissance.',
                'description'       => 'Batterie Lithium BSM 48V 280AH. Grande capacitÃ© de 280Ah en 48V offrant 13,44 kWh de stockage. IdÃ©ale pour les installations commerciales et les maisons avec forte consommation.',
                'price'             => 1350000,
                'power'             => null,
                'warranty'          => '3 ans',
                'stock'             => 5,
                'featured'          => true,
                'specs'             => ['Tension' => '48 V', 'CapacitÃ©' => '280 Ah', 'Ã‰nergie' => '13,44 kWh', 'Cycles' => '> 4000', 'Technologie' => 'LiFePO4'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/H13c6c3d0938e4933ac6fde5caa75caf2w.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['bsm'],
                'name'              => 'Batterie Lithium BSM 48V 200AH',
                'slug'              => 'batterie-lithium-bsm-48v-200ah',
                'sku'               => 'BAT-BSM-48V-200',
                'short_description' => 'Batterie lithium BSM 48V 200Ah â€” 9,6 kWh de stockage pour autonomie rÃ©sidentielle complÃ¨te.',
                'description'       => 'Batterie Lithium BSM 48V 200AH. 9,6 kWh de stockage en configuration 48V. Compatible avec les principaux onduleurs hybrides du marchÃ©.',
                'price'             => 950000,
                'power'             => null,
                'warranty'          => '3 ans',
                'stock'             => 7,
                'featured'          => false,
                'specs'             => ['Tension' => '48 V', 'CapacitÃ©' => '200 Ah', 'Ã‰nergie' => '9,6 kWh', 'Cycles' => '> 4000', 'Technologie' => 'LiFePO4'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/H2b7f4d8239ec4ddbb5dd229dc6aabd33R.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Batterie lithium 48V 100Ah 6000 cycles',
                'slug'              => 'batterie-lithium-48v-100ah-6000-cycles',
                'sku'               => 'BAT-LI-48V-100',
                'short_description' => 'Batterie lithium 48V 100Ah, 6000 cycles garantis â€” longÃ©vitÃ© exceptionnelle pour usage intensif.',
                'description'       => 'Batterie lithium 48V 100Ah avec 6000 cycles de charge/dÃ©charge. Profondeur de dÃ©charge jusqu\'Ã  95%. BMS intÃ©grÃ© avec protection contre la surcharge, la surdÃ©charge et les courts-circuits.',
                'price'             => 600000,
                'power'             => null,
                'warranty'          => '5 ans',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Tension' => '48 V', 'CapacitÃ©' => '100 Ah', 'Ã‰nergie' => '4,8 kWh', 'Cycles' => '6000', 'DoD' => '95%'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/08/FLA48200-1.png',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['lux-power'],
                'name'              => 'Module de Batterie Lithium LUX-Y 48V 100Ah',
                'slug'              => 'module-batterie-lithium-lux-y-48v-100ah',
                'sku'               => 'BAT-LUX-48V-100',
                'short_description' => 'Module batterie lithium LUX-Y 48V 100Ah haute tension, empilable jusqu\'Ã  16 modules en parallÃ¨le.',
                'description'       => 'Module de Batterie Lithium LUX-Y 48V 100Ah. SystÃ¨me haute tension empilable. Chaque module peut Ãªtre associÃ© Ã  d\'autres pour augmenter la capacitÃ©. Communication BMS via CAN Bus.',
                'price'             => 700000,
                'power'             => null,
                'warranty'          => '3 ans',
                'stock'             => 8,
                'featured'          => false,
                'specs'             => ['Tension' => '48 V', 'CapacitÃ©' => '100 Ah', 'Type' => 'Haute Tension', 'Communication' => 'CAN Bus', 'Empilable' => 'jusqu\'Ã  16 modules'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/11/Lux-Y-48100HG01-1.png',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['lux-power'],
                'name'              => 'ContrÃ´leur de Module de Batterie LUX 48V 100Ah',
                'slug'              => 'controleur-module-batterie-lux-48v-100ah',
                'sku'               => 'BAT-LUX-CTRL',
                'short_description' => 'ContrÃ´leur BMS LUX pour module batterie 48V, gestion intelligente de la charge et dÃ©charge.',
                'description'       => 'ContrÃ´leur de Module de Batterie LUX 48V 100Ah. SystÃ¨me de gestion de batterie (BMS) intelligent pour l\'ensemble des modules LUX-Y. Protection complÃ¨te et monitoring en temps rÃ©el.',
                'price'             => 550000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 5,
                'featured'          => false,
                'specs'             => ['Tension' => '48 V', 'Type' => 'BMS ContrÃ´leur', 'CompatibilitÃ©' => 'Modules LUX-Y'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/11/LUX-Y-48100HG01-2-qw9bv6r2et33i58rbav97vi7j71dh7rofycemqigzs.png',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['lux-power'],
                'name'              => 'Rack de rangement pour Module de Batterie LUX-Y',
                'slug'              => 'rack-rangement-module-batterie-lux-y',
                'sku'               => 'BAT-LUX-RACK',
                'short_description' => 'Rack de rangement pour modules batterie LUX-Y, support robuste pour empilage sÃ©curisÃ©.',
                'description'       => 'Rack de rangement pour Module de Batterie LUX-Y. Structure mÃ©tallique robuste permettant le montage et l\'empilage des modules de batteries LUX-Y en toute sÃ©curitÃ©.',
                'price'             => 450000,
                'warranty'          => '2 ans',
                'stock'             => 5,
                'featured'          => false,
                'specs'             => ['Type' => 'Rack mÃ©tallique', 'CompatibilitÃ©' => 'Modules LUX-Y', 'CapacitÃ©' => 'Jusqu\'Ã  8 modules'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/11/LUX-Y-48100HG01-2-qw9bv6r2et33i58rbav97vi7j71dh7rofycemqigzs.png',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Batterie lithium FLA 48V 500Ah 6000 cycles',
                'slug'              => 'batterie-lithium-fla-48v-500ah-6000-cycles',
                'sku'               => 'BAT-FLA-48V-500',
                'short_description' => 'Batterie lithium FLA 48V 500Ah â€” 24 kWh de stockage pour installations industrielles et commerciales.',
                'description'       => 'Batterie lithium FLA 48V 500Ah avec 6000 cycles garantis. CapacitÃ© de 24 kWh idÃ©ale pour les grandes installations solaires commerciales et industrielles.',
                'price'             => 2100000,
                'power'             => null,
                'warranty'          => '5 ans',
                'stock'             => 3,
                'featured'          => true,
                'specs'             => ['Tension' => '48 V', 'CapacitÃ©' => '500 Ah', 'Ã‰nergie' => '24 kWh', 'Cycles' => '6000'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/11/OIP-15.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['gsb'],
                'name'              => 'Batterie solaire 12V 200Ah GSB',
                'slug'              => 'batterie-solaire-12v-200ah-gsb',
                'sku'               => 'BAT-GSB-12V-200',
                'short_description' => 'Batterie solaire stationnaire GSB 12V 200Ah, technologie AGM sans entretien.',
                'description'       => 'Batterie solaire GSB 12V 200Ah. Technologie AGM (Absorbed Glass Mat) sans entretien. IdÃ©ale pour les systÃ¨mes solaires rÃ©sidentiels et les installations hors rÃ©seau.',
                'price'             => 155000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 15,
                'featured'          => false,
                'specs'             => ['Tension' => '12 V', 'CapacitÃ©' => '200 Ah', 'Technologie' => 'AGM', 'Cycles' => '> 500'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2023/11/IMG-20231202-WA0062.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['gsb'],
                'name'              => 'Batterie solaire 100Ah 12V GSB',
                'slug'              => 'batterie-solaire-100ah-12v-gsb',
                'sku'               => 'BAT-GSB-12V-100',
                'short_description' => 'Batterie solaire AGM GSB 12V 100Ah, solution Ã©conomique pour petites installations solaires.',
                'description'       => 'Batterie solaire GSB 12V 100Ah. Technologie AGM sans entretien. Solution idÃ©ale pour les petits systÃ¨mes solaires, Ã©clairages et Ã©quipements autonomes.',
                'price'             => 100000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 20,
                'featured'          => false,
                'specs'             => ['Tension' => '12 V', 'CapacitÃ©' => '100 Ah', 'Technologie' => 'AGM'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2023/11/GSB-100.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Batterie solaire GEL 150Ah 12V',
                'slug'              => 'batterie-solaire-gel-150ah-12v',
                'sku'               => 'BAT-GEL-12V-150',
                'short_description' => 'Batterie solaire GEL 12V 150Ah, technologie gel pour une durÃ©e de vie prolongÃ©e.',
                'description'       => 'Batterie solaire GEL 12V 150Ah. La technologie GEL offre une rÃ©sistance accrue aux dÃ©charges profondes et une durÃ©e de vie supÃ©rieure aux batteries AGM classiques.',
                'price'             => 120000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 12,
                'featured'          => false,
                'specs'             => ['Tension' => '12 V', 'CapacitÃ©' => '150 Ah', 'Technologie' => 'GEL'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/06/411d8181f018ac70b5f4df3c620e591f_medium-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Batterie solaire GEL 200Ah 12V',
                'slug'              => 'batterie-solaire-gel-200ah-12v',
                'sku'               => 'BAT-GEL-12V-200',
                'short_description' => 'Batterie solaire GEL 12V 200Ah, grande capacitÃ© pour autonomie maximale.',
                'description'       => 'Batterie solaire GEL 12V 200Ah. IdÃ©ale pour les installations nÃ©cessitant une grande autonomie. Compatible avec tous les rÃ©gulateurs solaires.',
                'price'             => 155000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Tension' => '12 V', 'CapacitÃ©' => '200 Ah', 'Technologie' => 'GEL'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2021/12/Hd9af00e917be423f8f483d38d65cb9edT-300x300.jpg',
            ],
        ];
    }

    // =========================================================================
    // ONDULEURS SOLAIRES
    // =========================================================================
    private function onduleurs(array $cats, array $brands): array
    {
        $c = $cats['onduleurs'];
        return [
            [
                'category_id'       => $c,
                'brand_id'          => $brands['bsm'],
                'name'              => 'Onduleur hybride BSM 48V 11KW',
                'slug'              => 'onduleur-hybride-bsm-48v-11kw',
                'sku'               => 'OND-BSM-48V-11K',
                'short_description' => 'Onduleur hybride BSM 11KW double MPPT, onde sinusoÃ¯dale pure pour installations solaires haute puissance.',
                'description'       => 'Onduleur hybride BSM 48V 11KW. Double MPPT pour optimiser la capture d\'Ã©nergie provenant de diffÃ©rentes chaÃ®nes de panneaux. Supporte les batteries lithium et AGM. Onde sinusoÃ¯dale pure avec rendement optimal.',
                'price'             => 690000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 5,
                'featured'          => true,
                'specs'             => ['Puissance' => '11 kW', 'Tension batterie' => '48 V', 'Type MPPT' => 'Double MPPT', 'Sortie' => 'Onde sinusoÃ¯dale pure', 'Batteries' => 'Lithium / AGM'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/H26d7a6ddcbb746999a27f2cbfaf1bdfeS-600x600.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['bsm'],
                'name'              => 'Onduleur hybride BSM 48V 5,5KW',
                'slug'              => 'onduleur-hybride-bsm-48v-5-5kw',
                'sku'               => 'OND-BSM-48V-5K5',
                'short_description' => 'Onduleur/chargeur multifonction BSM 5,5KW, combine onduleur solaire, chargeur et rÃ©gulateur en un.',
                'description'       => 'Onduleur hybride BSM 48V 5,5KW. Multifonction: onduleur, chargeur solaire et chargeur de batterie dans un design compact. Ã‰cran LCD pour rÃ©glages personnalisables. Alimentation fiable et continue.',
                'price'             => 320000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 8,
                'featured'          => true,
                'specs'             => ['Puissance' => '5,5 kW', 'Tension batterie' => '48 V', 'Type' => 'Hybride multifonction', 'Affichage' => 'LCD'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/H969bff4032d64f5d8756679498b64cecH-600x600.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['bsm'],
                'name'              => 'Onduleur hybride BSM 24V 3,5KW',
                'slug'              => 'onduleur-hybride-bsm-24v-3-5kw',
                'sku'               => 'OND-BSM-24V-3K5',
                'short_description' => 'Onduleur hybride BSM 3,5KW 24V, solution compacte pour petites installations solaires rÃ©sidentielles.',
                'description'       => 'Onduleur hybride BSM 24V 3,5KW. Combine onduleur, chargeur solaire et chargeur de batterie. Ã‰cran LCD convivial. IdÃ©al pour les habitations avec une consommation modÃ©rÃ©e.',
                'price'             => 250000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Puissance' => '3,5 kW', 'Tension batterie' => '24 V', 'Type' => 'Hybride multifonction', 'Affichage' => 'LCD'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/12/Hdce68e4685c74518896a8d9dad3989dfO-600x600.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['hy-solar'],
                'name'              => 'Onduleur rÃ©seau HY-50KW-HT',
                'slug'              => 'onduleur-reseau-hy-50kw-ht',
                'sku'               => 'OND-HY-50KW',
                'short_description' => 'Onduleur rÃ©seau triphasÃ© HY 50KW haute tension, pour installations solaires industrielles et commerciales.',
                'description'       => 'Onduleur rÃ©seau HY-50KW-HT. Puissance de 50kW pour les grandes installations commerciales et industrielles. Technologie haute tension, triphasÃ©, compatible rÃ©seau.',
                'price'             => 3200000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 2,
                'featured'          => false,
                'specs'             => ['Puissance' => '50 kW', 'Phase' => 'TriphasÃ©', 'Type' => 'RÃ©seau (grid-tied)', 'Tension' => 'Haute tension'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/07/OIP-14.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['victron-energy'],
                'name'              => 'Convertisseur chargeur Multiplus II Victron 3KVA 48V 35A',
                'slug'              => 'convertisseur-chargeur-multiplus-ii-victron-3kva-48v-35a',
                'sku'               => 'OND-VIC-MP2-3K',
                'short_description' => 'Victron MultiPlus II 3KVA 48V â€” onduleur/chargeur bidirectionnel avec assistant de rÃ©seau intÃ©grÃ©.',
                'description'       => 'Convertisseur chargeur Multiplus II Victron 3KVA 48V 35A. Le MultiPlus II combine un onduleur/chargeur bidirectionnel avec un assistant de rÃ©seau. Transfert instantanÃ©, compatible VE.Bus, surveillance via Cerbo GX.',
                'price'             => 900000,
                'power'             => null,
                'warranty'          => '5 ans',
                'stock'             => 4,
                'featured'          => true,
                'specs'             => ['Puissance' => '3 kVA', 'Tension batterie' => '48 V', 'Courant charge' => '35 A', 'Interface' => 'VE.Bus', 'Marque' => 'Victron Energy'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/07/multiplus-ii-front_prd2-1.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['ivem'],
                'name'              => 'Onduleur hybride IVEM 8KW',
                'slug'              => 'onduleur-hybride-ivem-8kw',
                'sku'               => 'OND-IVEM-8KW',
                'short_description' => 'Onduleur hybride IVEM 8KW, solution polyvalente pour installations solaires rÃ©sidentielles et commerciales.',
                'description'       => 'Onduleur hybride IVEM 8KW. Puissance de 8KW avec fonction hybride (solaire + rÃ©seau + batterie). Compatible batteries lithium et AGM/GEL.',
                'price'             => 480000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 6,
                'featured'          => false,
                'specs'             => ['Puissance' => '8 kW', 'Type' => 'Hybride', 'Marque' => 'IVEM'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/telecharger-3.jpg',
            ],
        ];
    }

    // =========================================================================
    // (méthode régulateurs supprimée — catégorie retirée)
    // =========================================================================
    private function _obsoleteRegulateursDontCall(): array
    {
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'RÃ©gulateur solaire MPPT 30A',
                'slug'              => 'regulateur-solaire-mppt-30a',
                'sku'               => 'REG-MPPT-30A',
                'short_description' => 'RÃ©gulateur MPPT 30A, rendement jusqu\'Ã  98%, compatible 12V/24V, idÃ©al pour petites installations.',
                'description'       => 'RÃ©gulateur solaire MPPT 30A. Technologie Maximum Power Point Tracking pour un rendement optimal jusqu\'Ã  98%. Compatible 12V et 24V. IdÃ©al pour installations rÃ©sidentielles de petite taille.',
                'price'             => 45000,
                'old_price'         => 55000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 20,
                'featured'          => false,
                'specs'             => ['Courant' => '30 A', 'Technologie' => 'MPPT', 'Tensions' => '12V / 24V', 'Rendement' => 'â‰¥ 98%'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/03/WhatsApp-Image-2024-09-16-a-14.01.40_5a4a4090.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'RÃ©gulateur solaire MPPT 60A',
                'slug'              => 'regulateur-solaire-mppt-60a',
                'sku'               => 'REG-MPPT-60A',
                'short_description' => 'RÃ©gulateur MPPT 60A, technologie avancÃ©e pour optimiser la production solaire jusqu\'Ã  30% vs PWM.',
                'description'       => 'RÃ©gulateur solaire MPPT 60A. Rendement maximum jusqu\'Ã  98%. Compatible 12V, 24V et 48V. Augmente la production solaire jusqu\'Ã  30% par rapport aux rÃ©gulateurs PWM classiques.',
                'price'             => 110000,
                'old_price'         => 125000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 15,
                'featured'          => false,
                'specs'             => ['Courant' => '60 A', 'Technologie' => 'MPPT', 'Tensions' => '12V / 24V / 48V', 'Rendement' => 'â‰¥ 98%'],
                'image_url'         => $imgUrl,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'RÃ©gulateur solaire MPPT 100A',
                'slug'              => 'regulateur-solaire-mppt-100a',
                'sku'               => 'REG-MPPT-100A',
                'short_description' => 'RÃ©gulateur MPPT 100A haute performance pour installations solaires moyennes et grandes.',
                'description'       => 'RÃ©gulateur solaire MPPT 100A. Haute performance pour les installations solaires de moyenne et grande envergure. Ã‰cran LCD, protection contre surcharge et surtension.',
                'price'             => 160000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Courant' => '100 A', 'Technologie' => 'MPPT', 'Tensions' => '12V / 24V / 48V', 'Affichage' => 'LCD'],
                'image_url'         => $imgUrl,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'RÃ©gulateur solaire MPPT 120A',
                'slug'              => 'regulateur-solaire-mppt-120a',
                'sku'               => 'REG-MPPT-120A',
                'short_description' => 'RÃ©gulateur MPPT 120A, solution professionnelle pour installations solaires importantes.',
                'description'       => 'RÃ©gulateur solaire MPPT 120A. CapacitÃ© de 120A pour les grandes installations solaires professionnelles. Rendement maximal, protection intÃ©grÃ©e et communication RS485.',
                'price'             => 175000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 8,
                'featured'          => false,
                'specs'             => ['Courant' => '120 A', 'Technologie' => 'MPPT', 'Tensions' => '12V / 24V / 48V', 'Communication' => 'RS485'],
                'image_url'         => $imgUrl,
            ],
        ];
    }

    // =========================================================================
    // KITS SOLAIRES
    // =========================================================================
    private function kits(array $cats, array $brands): array
    {
        $c = $cats['kits-solaires'];
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire ACCESS',
                'slug'              => 'kit-solaire-access',
                'sku'               => 'KIT-ACCESS',
                'short_description' => 'Kit solaire clÃ© en main 2 millions FCFA, pour habitations avec consommation de base (Ã©clairage, TV, ventilateur).',
                'description'       => 'Kit Solaire ACCESS. Solution d\'accÃ¨s Ã  l\'Ã©nergie solaire pour les habitations avec une consommation de base. Comprend panneaux, rÃ©gulateur, batteries et onduleur. Installation incluse Ã  Abidjan.',
                'price'             => 2000000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 5,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '1-2 kWc', 'Usage' => 'Ã‰clairage, TV, ventilateur', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-02-a-15.48.43_a1467f4f.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire FREE',
                'slug'              => 'kit-solaire-free',
                'sku'               => 'KIT-FREE',
                'short_description' => 'Kit solaire FREE 3,7 millions FCFA, pour s\'affranchir des coupures avec autonomie confortable.',
                'description'       => 'Kit Solaire FREE. Pour une autonomie Ã©nergÃ©tique confortable. Ã‰quipement complet pour ne plus subir les coupures de courant au quotidien.',
                'price'             => 3700000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 5,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '2-3 kWc', 'Usage' => 'Climatiseur 1CV + Ã©lectromÃ©nager', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-02-a-15.48.44_8537bd03.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire ECO',
                'slug'              => 'kit-solaire-eco',
                'sku'               => 'KIT-ECO',
                'short_description' => 'Kit solaire ECO 4,3 millions FCFA, solution Ã©conomique complÃ¨te pour villa ou appartement.',
                'description'       => 'Kit Solaire ECO. Solution complÃ¨te et Ã©conomique pour villas et appartements. Comprend tout le nÃ©cessaire pour une autonomie solaire quotidienne.',
                'price'             => 4300000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 4,
                'featured'          => true,
                'specs'             => ['Puissance PV' => '3 kWc', 'Usage' => 'Villa / Appartement', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-02-a-15.48.43_468ef33a.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire ECO FRESH',
                'slug'              => 'kit-solaire-eco-fresh',
                'sku'               => 'KIT-ECO-FRESH',
                'short_description' => 'Kit solaire ECO FRESH 6 millions FCFA, avec rÃ©frigÃ©ration solaire incluse pour confort maximal.',
                'description'       => 'Kit Solaire ECO FRESH. Solution complÃ¨te incluant rÃ©frigÃ©ration solaire. IdÃ©al pour les familles souhaitant allier confort et indÃ©pendance Ã©nergÃ©tique.',
                'price'             => 6000000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 4,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '4 kWc', 'Inclus' => 'RÃ©frigÃ©ration solaire', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-02-a-15.48.43_ac9a9102.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire CONFORT',
                'slug'              => 'kit-solaire-confort',
                'sku'               => 'KIT-CONFORT',
                'short_description' => 'Kit solaire CONFORT 8,5 millions FCFA, pour duplex avec climatisation et tous Ã©lectromÃ©nagers.',
                'description'       => 'Kit Solaire CONFORT. Pour duplex et grandes maisons avec climatisation et Ã©lectromÃ©nager complet. Autonomie Ã©nergÃ©tique totale.',
                'price'             => 8500000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 3,
                'featured'          => true,
                'specs'             => ['Puissance PV' => '6 kWc', 'Usage' => 'Duplex avec climatisation', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/03/WhatsApp-Image-2025-09-03-a-08.15.50_ceec8ca8.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire BRONZE',
                'slug'              => 'kit-solaire-bronze',
                'sku'               => 'KIT-BRONZE',
                'short_description' => 'Kit solaire BRONZE 9 millions FCFA, pour duplex rÃ©sidence triphasÃ© 30A avec splits et gros appareils.',
                'description'       => 'Kit Solaire BRONZE. Cible les duplex avec compteur triphasÃ© 30A. GÃ¨re splits, machines Ã  laver, fers Ã  repasser, Ã©lectromÃ©nager et Ã©clairage. Consommation journaliÃ¨re infÃ©rieure Ã  40 kWh.',
                'price'             => 9000000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 3,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '7 kWc', 'Phase' => 'TriphasÃ© 30A', 'Consommation max' => '40 kWh/jour', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2023/07/WhatsApp-Image-2025-08-28-a-15.55.26_f18b96cd.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire SYLVER',
                'slug'              => 'kit-solaire-sylver',
                'sku'               => 'KIT-SYLVER',
                'short_description' => 'Kit solaire SYLVER 11,5 millions FCFA, autonomie totale pour grandes villas avec forte consommation.',
                'description'       => 'Kit Solaire SYLVER. Autonomie totale pour grandes villas avec forte consommation Ã©nergÃ©tique. Solution haut de gamme pour une indÃ©pendance Ã©nergÃ©tique complÃ¨te.',
                'price'             => 11500000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 3,
                'featured'          => true,
                'specs'             => ['Puissance PV' => '8 kWc', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-08-28-a-15.55.22_55834f1f.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire GOLD',
                'slug'              => 'kit-solaire-gold',
                'sku'               => 'KIT-GOLD',
                'short_description' => 'Kit solaire GOLD 14,7 millions FCFA, solution premium pour rÃ©sidences haut de gamme.',
                'description'       => 'Kit Solaire GOLD. Solution premium pour rÃ©sidences haut de gamme avec forte consommation. Ã‰quipements de haute qualitÃ© et performances maximales.',
                'price'             => 14700000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 2,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '10 kWc', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-02-a-15.58.34_539720a2.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire PLATINIUM',
                'slug'              => 'kit-solaire-platinium',
                'sku'               => 'KIT-PLATINIUM',
                'short_description' => 'Kit solaire PLATINIUM 16,9 millions FCFA, pour grandes propriÃ©tÃ©s et petites entreprises.',
                'description'       => 'Kit Solaire PLATINIUM. ConÃ§u pour les grandes propriÃ©tÃ©s et les petites entreprises. Puissance et fiabilitÃ© au service de vos besoins Ã©nergÃ©tiques professionnels.',
                'price'             => 16900000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 2,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '12 kWc', 'Usage' => 'Grandes propriÃ©tÃ©s / PME', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-02-a-15.48.44_6ee06f83.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire SAPHIRE',
                'slug'              => 'kit-solaire-saphire',
                'sku'               => 'KIT-SAPHIRE',
                'short_description' => 'Kit solaire SAPHIRE 18 millions FCFA, solution industrielle pour entreprises Ã  forte consommation.',
                'description'       => 'Kit Solaire SAPHIRE. Solution industrielle et commerciale pour entreprises Ã  forte consommation. Puissance maximale avec Ã©quipements de grade professionnel.',
                'price'             => 18000000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 2,
                'featured'          => true,
                'specs'             => ['Puissance PV' => '15 kWc', 'Usage' => 'Commercial / Industriel', 'Installation' => 'Incluse Ã  Abidjan'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/08/WhatsApp-Image-2025-09-03-a-09.35.39_26879063.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire TITANIUM',
                'slug'              => 'kit-solaire-titanium',
                'sku'               => 'KIT-TITANIUM',
                'short_description' => 'Kit solaire TITANIUM 21,5 millions FCFA, puissance maximale pour usines et grandes entreprises.',
                'description'       => 'Kit Solaire TITANIUM. Puissance maximale pour usines et grandes entreprises. Rendement exceptionnel avec Ã©quipements de classe industrielle.',
                'price'             => 21500000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 2,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '20 kWc', 'Usage' => 'Industriel / Usines', 'Installation' => 'Sur devis'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2024/08/WhatsApp-Image-2025-09-03-a-07.07.20_14b939a1.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire DIAMOND',
                'slug'              => 'kit-solaire-diamond',
                'sku'               => 'KIT-DIAMOND',
                'short_description' => 'Kit solaire DIAMOND 33 millions FCFA, solution clÃ© en main pour industries et grandes entreprises.',
                'description'       => 'Kit Solaire DIAMOND. La solution haut de gamme pour les industries et grandes entreprises. Autonomie totale avec le meilleur des Ã©quipements solaires disponibles sur le marchÃ©.',
                'price'             => 33000000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 1,
                'featured'          => true,
                'specs'             => ['Puissance PV' => '30 kWc', 'Usage' => 'Grandes entreprises / Industries', 'Installation' => 'Sur devis'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/08/WhatsApp-Image-2025-08-28-a-15.55.26_6eeab6e2.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Kit Solaire BUSINESS',
                'slug'              => 'kit-solaire-business',
                'sku'               => 'KIT-BUSINESS',
                'short_description' => 'Kit solaire BUSINESS 75 millions FCFA, solution industrielle trÃ¨s haute puissance pour grandes installations.',
                'description'       => 'Kit Solaire BUSINESS. Solution industrielle trÃ¨s haute puissance. Pour les grandes installations commerciales et industrielles nÃ©cessitant une autonomie Ã©nergÃ©tique totale.',
                'price'             => 75000000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 1,
                'featured'          => false,
                'specs'             => ['Puissance PV' => '50 kWc', 'Usage' => 'TrÃ¨s grandes entreprises', 'Installation' => 'Sur devis'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2025/06/WhatsApp-Image-2025-09-02-a-15.59.28_add793b6.jpg',
            ],
        ];
    }

    // =========================================================================
    // Ã‰CLAIRAGES
    // =========================================================================
    private function eclairages(array $cats, array $brands): array
    {
        $c = $cats['eclairages'];
        $lampadaireImg   = 'https://maquincaillerie-solaire.com/wp-content/uploads/2023/09/OIP-5.jpg';
        $projecteurImg   = 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/WhatsApp-Image-2022-01-03-at-23.44.24-1-300x300.jpeg';
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Lampadaire solaire 100W',
                'slug'              => 'lampadaire-solaire-100w',
                'sku'               => 'ECL-LAMP-100W',
                'short_description' => 'Lampadaire solaire autonome 100W, Ã©clairage public et privÃ© sans raccordement Ã©lectrique.',
                'description'       => 'Lampadaire solaire 100W. Ã‰clairage autonome pour espaces publics, parkings et allÃ©es privÃ©es. Panneau solaire intÃ©grÃ©, batterie lithium, dÃ©tecteur de mouvement.',
                'price'             => 60000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 20,
                'featured'          => false,
                'specs'             => ['Puissance' => '100 W', 'Autonomie' => '8-12h', 'Batterie' => 'Lithium intÃ©grÃ©e'],
                'image_url'         => $lampadaireImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Lampadaire solaire 200W',
                'slug'              => 'lampadaire-solaire-200w',
                'sku'               => 'ECL-LAMP-200W',
                'short_description' => 'Lampadaire solaire 200W haute luminositÃ© pour grandes surfaces et voies publiques.',
                'description'       => 'Lampadaire solaire 200W. Haute luminositÃ© pour l\'Ã©clairage de grandes surfaces, voies publiques et parkings. Technologie LED efficace et longue durÃ©e de vie.',
                'price'             => 75000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 15,
                'featured'          => false,
                'specs'             => ['Puissance' => '200 W', 'Autonomie' => '8-12h', 'Type LED' => 'Haute efficacitÃ©'],
                'image_url'         => $lampadaireImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Lampadaire solaire 300W',
                'slug'              => 'lampadaire-solaire-300w',
                'sku'               => 'ECL-LAMP-300W',
                'short_description' => 'Lampadaire solaire 300W pour Ã©clairage de grandes voies et espaces industriels.',
                'description'       => 'Lampadaire solaire 300W. Puissance d\'Ã©clairage Ã©levÃ©e pour les grandes voies, zones industrielles et commerciales. RÃ©sistant aux intempÃ©ries IP65.',
                'price'             => 85000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Puissance' => '300 W', 'Protection' => 'IP65', 'Autonomie' => '10-14h'],
                'image_url'         => $lampadaireImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Lampadaire solaire 400W',
                'slug'              => 'lampadaire-solaire-400w',
                'sku'               => 'ECL-LAMP-400W',
                'short_description' => 'Lampadaire solaire 400W trÃ¨s haute puissance pour Ã©clairage industriel et grands espaces.',
                'description'       => 'Lampadaire solaire 400W. Solution d\'Ã©clairage trÃ¨s haute puissance pour les grandes infrastructures. Panneau solaire haute performance, batterie lithium grande capacitÃ©.',
                'price'             => 100000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 8,
                'featured'          => true,
                'specs'             => ['Puissance' => '400 W', 'Protection' => 'IP65', 'Autonomie' => '10-14h'],
                'image_url'         => $lampadaireImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Lampadaire solaire D2 80W',
                'slug'              => 'lampadaire-solaire-d2-80w',
                'sku'               => 'ECL-D2-80W',
                'short_description' => 'Lampadaire solaire design D2 80W, esthÃ©tique moderne pour environnements rÃ©sidentiels et jardins.',
                'description'       => 'Lampadaire solaire D2 80W. Design moderne et Ã©lÃ©gant pour les environnements rÃ©sidentiels, jardins et allÃ©es privÃ©es. Batterie intÃ©grÃ©e, installation facile sans cÃ¢blage.',
                'price'             => 200000,
                'power'             => null,
                'warranty'          => '2 ans',
                'stock'             => 6,
                'featured'          => false,
                'specs'             => ['Puissance' => '80 W', 'Design' => 'SÃ©rie D2', 'Usage' => 'RÃ©sidentiel / Jardin'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/10/317cbff3240b5aed79bfe5e7c933d4ae_medium-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['ecobox'],
                'name'              => 'Projecteur solaire Ecobox 100W',
                'slug'              => 'projecteur-solaire-ecobox-100w',
                'sku'               => 'ECL-ECO-PROJ-100',
                'short_description' => 'Projecteur solaire Ecobox 100W, Ã©clairage de faÃ§ades, jardins et parkings sans raccordement.',
                'description'       => 'Projecteur solaire Ecobox 100W. IdÃ©al pour l\'Ã©clairage de faÃ§ades, jardins, parkings et zones de sÃ©curitÃ©. Installation simple sans cÃ¢blage Ã©lectrique.',
                'price'             => 55000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 15,
                'featured'          => false,
                'specs'             => ['Puissance' => '100 W', 'Marque' => 'Ecobox', 'Protection' => 'IP65'],
                'image_url'         => $projecteurImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => $brands['ecobox'],
                'name'              => 'Projecteur solaire Ecobox 200W',
                'slug'              => 'projecteur-solaire-ecobox-200w',
                'sku'               => 'ECL-ECO-PROJ-200',
                'short_description' => 'Projecteur solaire Ecobox 200W haute luminositÃ© pour Ã©clairage extÃ©rieur de grande surface.',
                'description'       => 'Projecteur solaire Ecobox 200W. Grande puissance d\'Ã©clairage pour les espaces extÃ©rieurs Ã©tendus. Panneau solaire intÃ©grÃ©, dÃ©tecteur de mouvement en option.',
                'price'             => 75000,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Puissance' => '200 W', 'Marque' => 'Ecobox', 'Protection' => 'IP65'],
                'image_url'         => $projecteurImg,
            ],
        ];
    }

    // =========================================================================
    // ACCESSOIRES DE POSE
    // =========================================================================
    private function accessoires(array $cats, array $brands): array
    {
        $c = $cats['accessoires'];
        $cableImg = 'https://maquincaillerie-solaire.com/wp-content/uploads/2020/08/cable-solaire-2x4mm2-1mm.jpg';
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Disjoncteur DC 125A',
                'slug'              => 'disjoncteur-dc-125a',
                'sku'               => 'ACC-DISJ-DC-125',
                'short_description' => 'Disjoncteur DC 125A pour protection des circuits solaires haute puissance.',
                'description'       => 'Disjoncteur DC 125A. Protection indispensable pour les circuits courant continu (DC) dans les installations solaires de grande puissance. Coupure sÃ»re et fiable.',
                'price'             => 40000,
                'warranty'          => '1 an',
                'stock'             => 20,
                'featured'          => false,
                'specs'             => ['Courant' => '125 A', 'Type' => 'DC (Courant Continu)', 'Usage' => 'Protection circuits solaires'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/03/H3d4b0352586348378d86c99ceb5fcdcbx-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Disjoncteur DC 63A',
                'slug'              => 'disjoncteur-dc-63a',
                'sku'               => 'ACC-DISJ-DC-63',
                'short_description' => 'Disjoncteur DC 63A pour protection des installations solaires rÃ©sidentielles.',
                'description'       => 'Disjoncteur DC 63A. Protection des circuits courant continu pour installations solaires rÃ©sidentielles et semi-professionnelles.',
                'price'             => 20000,
                'warranty'          => '1 an',
                'stock'             => 25,
                'featured'          => false,
                'specs'             => ['Courant' => '63 A', 'Type' => 'DC (Courant Continu)'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/BB1-63-2Pdc-mcb-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Parafoudre DC PhotovoltaÃ¯que',
                'slug'              => 'parafoudre-dc-photovoltaique',
                'sku'               => 'ACC-PARAFOUDRE-DC',
                'short_description' => 'Parafoudre DC photovoltaÃ¯que, protection contre la foudre et les surtensions pour panneaux solaires.',
                'description'       => 'Parafoudre DC PhotovoltaÃ¯que. Protection indispensable contre les surtensions dues Ã  la foudre pour vos installations solaires. Ã‰vite les dommages coÃ»teux sur les Ã©quipements.',
                'price'             => 25000,
                'warranty'          => '1 an',
                'stock'             => 15,
                'featured'          => false,
                'specs'             => ['Type' => 'Parafoudre DC', 'Usage' => 'PhotovoltaÃ¯que', 'Protection' => 'Type 2'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/03/Hab048a8b428e4e2e882b0ee4c7ba903bq-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Sac Ã  outil installation solaire PV',
                'slug'              => 'sac-outil-installation-solaire-pv',
                'sku'               => 'ACC-SAC-OUTIL',
                'short_description' => 'Sac Ã  outils complet pour techniciens installateurs de systÃ¨mes photovoltaÃ¯ques.',
                'description'       => 'Sac Ã  outil installation solaire PV. Kit complet pour les techniciens installateurs. Contient tous les outils nÃ©cessaires pour une installation solaire professionnelle.',
                'price'             => 45000,
                'warranty'          => '6 mois',
                'stock'             => 10,
                'featured'          => false,
                'specs'             => ['Contenu' => 'Outils PV complets', 'Usage' => 'Techniciens installateurs'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2020/08/Sac-%C3%A0-outil-installation-PV-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'CÃ¢ble solaire 2Ã—6mmÂ² (vendu au mÃ¨tre)',
                'slug'              => 'cable-solaire-2x6mm2-au-metre',
                'sku'               => 'ACC-CABLE-6MM2',
                'short_description' => 'CÃ¢ble solaire bipolaire 2Ã—6mmÂ², rÃ©sistant aux UV et aux intempÃ©ries, vendu au mÃ¨tre.',
                'description'       => 'CÃ¢ble solaire 2Ã—6mmÂ² vendu au mÃ¨tre. CÃ¢ble bipolaire spÃ©cialement conÃ§u pour les installations photovoltaÃ¯ques. RÃ©sistant aux UV, aux intempÃ©ries et aux hautes tempÃ©ratures. CertifiÃ© TÃœV.',
                'price'             => 2500,
                'warranty'          => '2 ans',
                'stock'             => 500,
                'featured'          => false,
                'specs'             => ['Section' => '2Ã—6 mmÂ²', 'Type' => 'CÃ¢ble solaire bipolaire', 'CertifiÃ©' => 'TÃœV', 'Vendu' => 'Au mÃ¨tre'],
                'image_url'         => $cableImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'CÃ¢ble solaire 2Ã—4mmÂ² (vendu au mÃ¨tre)',
                'slug'              => 'cable-solaire-2x4mm2-au-metre',
                'sku'               => 'ACC-CABLE-4MM2',
                'short_description' => 'CÃ¢ble solaire bipolaire 2Ã—4mmÂ², idÃ©al pour connexions entre panneaux et rÃ©gulateurs.',
                'description'       => 'CÃ¢ble solaire 2Ã—4mmÂ² vendu au mÃ¨tre. Pour les connexions entre panneaux solaires et rÃ©gulateurs de charge. RÃ©sistant aux UV et certifiÃ© pour usage extÃ©rieur.',
                'price'             => 2200,
                'warranty'          => '2 ans',
                'stock'             => 500,
                'featured'          => false,
                'specs'             => ['Section' => '2Ã—4 mmÂ²', 'Type' => 'CÃ¢ble solaire bipolaire', 'Vendu' => 'Au mÃ¨tre'],
                'image_url'         => $cableImg,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Connecteurs MC4 MÃ¢le et Femelle en 3T',
                'slug'              => 'connecteurs-mc4-male-femelle-3t',
                'sku'               => 'ACC-MC4-3T',
                'short_description' => 'Connecteurs MC4 triple (3T) pour mise en parallÃ¨le de panneaux solaires, lot mÃ¢le + femelle.',
                'description'       => 'Connecteurs MC4 MÃ¢le et Femelle en 3T. Permet de connecter 3 cÃ¢bles solaires ensemble (mise en parallÃ¨le ou en sÃ©rie). Ã‰tanches IP67, rÃ©sistants aux UV.',
                'price'             => 10000,
                'warranty'          => '1 an',
                'stock'             => 30,
                'featured'          => false,
                'specs'             => ['Type' => 'MC4 Triple (3T)', 'Protection' => 'IP67', 'Contenu' => '1 mÃ¢le + 1 femelle'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2020/08/Connecteur-MC4-3T-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Connecteurs MC4 MÃ¢le et Femelle en T',
                'slug'              => 'connecteurs-mc4-male-femelle-t',
                'sku'               => 'ACC-MC4-T',
                'short_description' => 'Connecteurs MC4 en T pour branchement de 2 panneaux solaires en parallÃ¨le.',
                'description'       => 'Connecteurs MC4 MÃ¢le et Femelle en T. Permet de connecter 2 cÃ¢bles solaires ensemble. Ã‰tanches IP67, faciles Ã  clipser. Indispensables pour les installations photovoltaÃ¯ques.',
                'price'             => 6500,
                'warranty'          => '1 an',
                'stock'             => 40,
                'featured'          => false,
                'specs'             => ['Type' => 'MC4 en T', 'Protection' => 'IP67', 'Contenu' => '1 mÃ¢le + 1 femelle'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2020/08/Connecteur-MC4-2T-300x300.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Connecteurs MC4 MÃ¢le et Femelle',
                'slug'              => 'connecteurs-mc4-male-femelle',
                'sku'               => 'ACC-MC4',
                'short_description' => 'Connecteurs MC4 standard mÃ¢le et femelle, pour toutes connexions entre panneaux solaires.',
                'description'       => 'Connecteurs MC4 MÃ¢le et Femelle standard. Connexion rapide et sÃ©curisÃ©e pour cÃ¢bles solaires 4-6mmÂ². Ã‰tanches IP67, rÃ©sistants aux UV et aux hautes tempÃ©ratures.',
                'price'             => 2000,
                'warranty'          => '1 an',
                'stock'             => 100,
                'featured'          => false,
                'specs'             => ['Type' => 'MC4 Standard', 'Protection' => 'IP67', 'Contenu' => '1 mÃ¢le + 1 femelle'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2020/08/Connecteur-MC4-m%C3%A2le-et-femelle-300x300.jpg',
            ],
        ];
    }

    // =========================================================================
    // CHAUFFE-EAU SOLAIRES
    // =========================================================================
    private function chauffeEau(array $cats, array $brands): array
    {
        $c   = $cats['chauffe-eau'];
        $img = 'https://maquincaillerie-solaire.com/wp-content/uploads/2020/10/H5121c073576b44cfbb46447c5f32cbce3.png';
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Chauffe-eau solaire 150 litres compact Ã  pression',
                'slug'              => 'chauffe-eau-solaire-150-litres-compact-pression',
                'sku'               => 'CE-SOL-150L',
                'short_description' => 'Chauffe-eau solaire compact thermosiphon 150L Ã  pression, garantie panneau 10 ans, cuve 5 ans.',
                'description'       => 'Chauffe-eau solaire 150 litres compact Ã  pression. Technologie thermosiphon : ne nÃ©cessite ni pompe de circulation ni rÃ©gulation complexe. Panneau solaire thermique haute performance. Garantie 10 ans sur le panneau, 5 ans sur la cuve.',
                'price'             => 580000,
                'warranty'          => '10 ans panneau / 5 ans cuve',
                'stock'             => 5,
                'featured'          => true,
                'specs'             => ['CapacitÃ©' => '150 litres', 'Type' => 'Compact thermosiphon', 'Pression' => 'Ã€ pression', 'Garantie panneau' => '10 ans', 'Garantie cuve' => '5 ans'],
                'image_url'         => $img,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Chauffe-eau solaire 200 litres compact Ã  pression',
                'slug'              => 'chauffe-eau-solaire-200-litres-compact-pression',
                'sku'               => 'CE-SOL-200L',
                'short_description' => 'Chauffe-eau solaire compact thermosiphon 200L Ã  pression, capacitÃ© familiale avec garanties Ã©tendues.',
                'description'       => 'Chauffe-eau solaire 200 litres compact Ã  pression. Grande capacitÃ© pour familles nombreuses. Technologie thermosiphon sans pompe ni rÃ©gulation. Garantie 10 ans sur le panneau thermique, 5 ans sur la cuve.',
                'price'             => 650000,
                'warranty'          => '10 ans panneau / 5 ans cuve',
                'stock'             => 5,
                'featured'          => false,
                'specs'             => ['CapacitÃ©' => '200 litres', 'Type' => 'Compact thermosiphon', 'Pression' => 'Ã€ pression', 'Garantie panneau' => '10 ans', 'Garantie cuve' => '5 ans'],
                'image_url'         => $img,
            ],
        ];
    }

    // =========================================================================
    // RÃ‰CEPTEURS SOLAIRES
    // =========================================================================
    private function recepteurs(array $cats, array $brands): array
    {
        $c   = $cats['recepteurs'];
        $img = 'https://maquincaillerie-solaire.com/wp-content/uploads/2021/02/FB_IMG_1620590268040-300x300.jpg';
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Ventilateur Solaire Rechargeable 18"',
                'slug'              => 'ventilateur-solaire-rechargeable-18-pouces',
                'sku'               => 'REC-VENT-18P',
                'short_description' => 'Ventilateur solaire rechargeable 18", fonctionnement hybride solaire ou rÃ©seau 220V, port USB intÃ©grÃ©.',
                'description'       => 'Ventilateur Solaire Rechargeable 18". Fonctionnement hybride : solaire ou rÃ©seau Ã©lectrique 220V. Port USB intÃ©grÃ© pour recharge de tÃ©lÃ©phones. Veilleuse nocturne intÃ©grÃ©e. Panneau solaire 7,5W optionnel.',
                'price'             => 48500,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 15,
                'featured'          => false,
                'specs'             => ['DiamÃ¨tre' => '18 pouces', 'Alimentation' => 'Solaire + 220V', 'Port USB' => 'Oui', 'Veilleuse' => 'Oui', 'Panneau optionnel' => '7,5W'],
                'image_url'         => $img,
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Ventilateur Solaire Rechargeable 16"',
                'slug'              => 'ventilateur-solaire-rechargeable-16-pouces',
                'sku'               => 'REC-VENT-16P',
                'short_description' => 'Ventilateur solaire rechargeable 16", compact et Ã©conomique, fonctionne sur solaire ou secteur 220V.',
                'description'       => 'Ventilateur Solaire Rechargeable 16". Version compacte du ventilateur solaire. Fonctionnement hybride solaire ou rÃ©seau 220V. Port USB pour recharge de tÃ©lÃ©phones. IdÃ©al pour chambres et bureaux.',
                'price'             => 38500,
                'power'             => null,
                'warranty'          => '1 an',
                'stock'             => 20,
                'featured'          => false,
                'specs'             => ['DiamÃ¨tre' => '16 pouces', 'Alimentation' => 'Solaire + 220V', 'Port USB' => 'Oui'],
                'image_url'         => $img,
            ],
        ];
    }

    // =========================================================================
    // BORNES DE RECHARGE
    // =========================================================================
    private function bornesRecharge(array $cats, array $brands): array
    {
        $c = $cats['bornes-recharge'];
        return [
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Borne de recharge AC 7,4 kW monophasée',
                'slug'              => 'borne-recharge-ac-7-4kw-mono',
                'sku'               => 'BRN-AC-7K4-MONO',
                'short_description' => 'Borne de recharge AC 7,4 kW monophasée pour véhicule électrique, compatible toutes marques.',
                'description'       => 'Borne de recharge AC 7,4 kW monophasée. Compatible avec tous les véhicules électriques et hybrides rechargeables. Prise Type 2, câble de 5 m inclus. Idéale pour usage résidentiel.',
                'price'             => 280000,
                'warranty'          => '2 ans',
                'stock'             => 8,
                'featured'          => true,
                'specs'             => ['Puissance' => '7,4 kW', 'Phase' => 'Monophasé', 'Prise' => 'Type 2', 'Câble' => '5 m', 'Protection' => 'IP54'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/borne-recharge.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Borne de recharge AC 22 kW triphasée',
                'slug'              => 'borne-recharge-ac-22kw-tri',
                'sku'               => 'BRN-AC-22K-TRI',
                'short_description' => 'Borne de recharge AC 22 kW triphasée pour charge rapide de véhicules électriques.',
                'description'       => 'Borne de recharge AC 22 kW triphasée. Charge jusqu\'à 3 fois plus vite qu\'une borne 7,4 kW. Compatible véhicules acceptant la charge triphasée. Prise Type 2 universelle.',
                'price'             => 480000,
                'warranty'          => '2 ans',
                'stock'             => 5,
                'featured'          => true,
                'specs'             => ['Puissance' => '22 kW', 'Phase' => 'Triphasé', 'Prise' => 'Type 2', 'Protection' => 'IP54'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/borne-recharge-22kw.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Borne de recharge DC 50 kW rapide',
                'slug'              => 'borne-recharge-dc-50kw-rapide',
                'sku'               => 'BRN-DC-50K',
                'short_description' => 'Borne de recharge DC 50 kW ultra-rapide, pour stations de recharge publiques et commerciales.',
                'description'       => 'Borne de recharge DC 50 kW rapide. Charge en courant continu pour une recharge ultra-rapide (20 à 80% en 30 min). Idéale pour stations publiques, parkings commerciaux et flottes professionnelles.',
                'price'             => 3500000,
                'warranty'          => '2 ans',
                'stock'             => 2,
                'featured'          => false,
                'specs'             => ['Puissance' => '50 kW', 'Type' => 'DC rapide', 'Prises' => 'CCS + CHAdeMO', 'Protection' => 'IP55'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/borne-dc-50kw.jpg',
            ],
            [
                'category_id'       => $c,
                'brand_id'          => null,
                'name'              => 'Câble recharge Type 2 vers Type 2 — 7 m',
                'slug'              => 'cable-recharge-type2-type2-7m',
                'sku'               => 'BRN-CABLE-T2-7M',
                'short_description' => 'Câble de recharge Type 2 vers Type 2, 7 m, 32A, pour bornes et véhicules électriques.',
                'description'       => 'Câble de recharge véhicule électrique Type 2 vers Type 2. Longueur 7 m, intensité max 32A. Compatible avec toutes les bornes de recharge de type 2 et les véhicules acceptant la prise Type 2.',
                'price'             => 45000,
                'warranty'          => '1 an',
                'stock'             => 20,
                'featured'          => false,
                'specs'             => ['Connecteurs' => 'Type 2 / Type 2', 'Longueur' => '7 m', 'Intensité' => '32 A'],
                'image_url'         => 'https://maquincaillerie-solaire.com/wp-content/uploads/2022/01/cable-type2.jpg',
            ],
        ];
    }
}
