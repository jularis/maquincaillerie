<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Categories
        $cats = [
            ['name' => 'Panneaux Solaires',    'slug' => 'panneaux-solaires',    'icon' => '☀️',  'order' => 1],
            ['name' => 'Onduleurs',             'slug' => 'onduleurs',             'icon' => '⚡',  'order' => 2],
            ['name' => 'Batteries',             'slug' => 'batteries',             'icon' => '🔋',  'order' => 3],
            ['name' => 'Kits Solaires',         'slug' => 'kits-solaires',         'icon' => '🏠',  'order' => 4],
            ['name' => 'Bornes de Recharge',    'slug' => 'bornes-recharge',       'icon' => '🔌',  'order' => 5],
            ['name' => 'Électricité',           'slug' => 'electricite',           'icon' => '💡',  'order' => 6],
            ['name' => 'Systèmes de Fixation',  'slug' => 'systemes-fixation',     'icon' => '🔧',  'order' => 7],
            ['name' => 'Domotique & Outillage', 'slug' => 'domotique-outillage',   'icon' => '🏡',  'order' => 8],
        ];
        foreach ($cats as $cat) {
            \App\Models\Category::create(array_merge($cat, ['active' => true]));
        }

        // Brands
        $brands = [
            ['name' => 'JA Solar',      'slug' => 'ja-solar',      'country' => 'CN', 'featured' => true],
            ['name' => 'Victron Energy', 'slug' => 'victron-energy', 'country' => 'NL', 'featured' => true],
            ['name' => 'SMA',           'slug' => 'sma',            'country' => 'DE', 'featured' => true],
            ['name' => 'Fronius',       'slug' => 'fronius',        'country' => 'AT', 'featured' => true],
            ['name' => 'Enphase',       'slug' => 'enphase',        'country' => 'US', 'featured' => true],
            ['name' => 'Pylontech',     'slug' => 'pylontech',      'country' => 'CN', 'featured' => true],
            ['name' => 'Growatt',       'slug' => 'growatt',        'country' => 'CN', 'featured' => false],
            ['name' => 'Huawei',        'slug' => 'huawei',         'country' => 'CN', 'featured' => false],
        ];
        foreach ($brands as $brand) {
            \App\Models\Brand::create($brand);
        }

        // Products
        $products = [
            // Panneaux Solaires (cat 1) — prix en FCFA (1 EUR ≈ 655 FCFA)
            ['category_id'=>1,'brand_id'=>1,'name'=>'JA Solar 400W Monocristallin','slug'=>'ja-solar-400w-mono','sku'=>'JAS-400M','short_description'=>'Panneau solaire haute performance 400W monocristallin, idéal pour installations résidentielles.','price'=>124000,'old_price'=>150000,'stock'=>45,'power'=>400,'warranty'=>'25 ans','featured'=>true,'specs'=>['Puissance'=>'400W','Technologie'=>'Monocristallin','Dimensions'=>'1722×1134×30mm','Poids'=>'20.7kg','Rendement'=>'20.8%']],
            ['category_id'=>1,'brand_id'=>1,'name'=>'JA Solar 450W Bifacial','slug'=>'ja-solar-450w-bifacial','sku'=>'JAS-450B','short_description'=>'Panneau bifacial 450W avec production renforcée des deux côtés.','price'=>163000,'old_price'=>null,'stock'=>30,'power'=>450,'warranty'=>'25 ans','featured'=>false,'specs'=>['Puissance'=>'450W','Technologie'=>'Bifacial PERC','Rendement'=>'21.5%']],
            ['category_id'=>1,'brand_id'=>1,'name'=>'JA Solar 550W Half-Cell','slug'=>'ja-solar-550w-half-cell','sku'=>'JAS-550H','short_description'=>'Panneau haute puissance 550W Half-Cell pour grandes installations.','price'=>196000,'old_price'=>229000,'stock'=>20,'power'=>550,'warranty'=>'25 ans','featured'=>true,'specs'=>['Puissance'=>'550W','Technologie'=>'Half-Cell PERC','Rendement'=>'21.3%']],

            // Onduleurs (cat 2)
            ['category_id'=>2,'brand_id'=>3,'name'=>'SMA Sunny Boy 3.0kW','slug'=>'sma-sunny-boy-3kw','sku'=>'SMA-SB3','short_description'=>'Onduleur monophasé 3kW de référence, compatible avec tous types de panneaux.','price'=>583000,'old_price'=>688000,'stock'=>12,'power'=>3000,'warranty'=>'5 ans','featured'=>true,'specs'=>['Puissance'=>'3000W','Phases'=>'Monophasé','Rendement'=>'97.2%','MPP'=>'1']],
            ['category_id'=>2,'brand_id'=>4,'name'=>'Fronius Primo 5.0kW','slug'=>'fronius-primo-5kw','sku'=>'FRO-P5','short_description'=>'Onduleur monophasé 5kW avec monitoring intégré.','price'=>845000,'old_price'=>null,'stock'=>8,'power'=>5000,'warranty'=>'5 ans','featured'=>false,'specs'=>['Puissance'=>'5000W','Phases'=>'Monophasé','Rendement'=>'97.9%']],
            ['category_id'=>2,'brand_id'=>3,'name'=>'SMA Sunny Tripower 10kW','slug'=>'sma-sunny-tripower-10kw','sku'=>'SMA-STP10','short_description'=>'Onduleur triphasé 10kW pour grandes installations.','price'=>1631000,'old_price'=>1893000,'stock'=>5,'power'=>10000,'warranty'=>'5 ans','featured'=>true,'specs'=>['Puissance'=>'10kW','Phases'=>'Triphasé','Rendement'=>'98.4%']],

            // Batteries (cat 3)
            ['category_id'=>3,'brand_id'=>6,'name'=>'Pylontech US2000C 2.4kWh','slug'=>'pylontech-us2000c','sku'=>'PYL-US2000C','short_description'=>'Batterie LiFePO4 2.4kWh, compatible avec la plupart des onduleurs hybrides.','price'=>518000,'old_price'=>583000,'stock'=>25,'power'=>null,'warranty'=>'10 ans','featured'=>true,'specs'=>['Capacité'=>'2.4kWh','Technologie'=>'LiFePO4','Cycles'=>'>6000','DoD'=>'95%']],
            ['category_id'=>3,'brand_id'=>2,'name'=>'Victron Lithium 12V 200Ah','slug'=>'victron-lithium-12v-200ah','sku'=>'VIC-LI200','short_description'=>'Batterie lithium 12V 200Ah avec BMS intégré Victron.','price'=>1239000,'old_price'=>null,'stock'=>10,'power'=>null,'warranty'=>'8 ans','featured'=>false,'specs'=>['Tension'=>'12V','Capacité'=>'200Ah','Technologie'=>'LiFePO4','Poids'=>'24kg']],
            ['category_id'=>3,'brand_id'=>6,'name'=>'Pylontech Force H2 10kWh','slug'=>'pylontech-force-h2-10kwh','sku'=>'PYL-FH2','short_description'=>'Pack batterie haute capacité 10kWh pour autonomie maximale.','price'=>3269000,'old_price'=>3597000,'stock'=>6,'power'=>null,'warranty'=>'10 ans','featured'=>true,'specs'=>['Capacité'=>'10kWh','Technologie'=>'LiFePO4','Cycles'=>'>6000']],

            // Kits Solaires (cat 4)
            ['category_id'=>4,'brand_id'=>1,'name'=>'Kit Solaire 3kWc Autoconsommation','slug'=>'kit-solaire-3kwc','sku'=>'KIT-3KWC','short_description'=>'Kit complet 3kWc : 8 panneaux 400W + onduleur + câblage. Prêt à installer.','price'=>2286000,'old_price'=>2614000,'stock'=>15,'power'=>3200,'warranty'=>'10 ans','featured'=>true,'specs'=>['Puissance'=>'3.2kWc','Panneaux'=>'8x 400W','Onduleur'=>'3kW','Production'=>'~3500 kWh/an']],
            ['category_id'=>4,'brand_id'=>1,'name'=>'Kit Solaire 6kWc + Batterie 5kWh','slug'=>'kit-solaire-6kwc-batterie','sku'=>'KIT-6KWC-BAT','short_description'=>'Kit complet avec stockage : 14 panneaux 450W + onduleur hybride + batterie 5kWh.','price'=>5888000,'old_price'=>6871000,'stock'=>7,'power'=>6300,'warranty'=>'10 ans','featured'=>true,'specs'=>['Puissance'=>'6.3kWc','Panneaux'=>'14x 450W','Stockage'=>'5kWh','Production'=>'~7000 kWh/an']],

            // Bornes de Recharge (cat 5)
            ['category_id'=>5,'brand_id'=>5,'name'=>'Enphase EV Charger 7.4kW','slug'=>'enphase-ev-charger-7kw','sku'=>'ENP-EV74','short_description'=>'Borne de recharge véhicule électrique 7.4kW monophasée, compatible toutes marques.','price'=>649000,'old_price'=>780000,'stock'=>18,'power'=>7400,'warranty'=>'3 ans','featured'=>true,'specs'=>['Puissance'=>'7.4kW','Phases'=>'Monophasée','Connecteur'=>'Type 2','WiFi'=>'Oui']],
            ['category_id'=>5,'brand_id'=>2,'name'=>'Victron EV Charging Station 22kW','slug'=>'victron-ev-22kw','sku'=>'VIC-EV22','short_description'=>'Borne de recharge rapide 22kW triphasée avec gestion intelligente.','price'=>1632000,'old_price'=>null,'stock'=>5,'power'=>22000,'warranty'=>'3 ans','featured'=>false,'specs'=>['Puissance'=>'22kW','Phases'=>'Triphasée','Connecteur'=>'Type 2','GreenPower'=>'Oui']],

            // Fixation (cat 7)
            ['category_id'=>7,'brand_id'=>null,'name'=>'Structure Fixation Toit Tuiles 4 panneaux','slug'=>'structure-toit-tuiles-4p','sku'=>'FIX-TUIL4','short_description'=>'Kit de fixation aluminium pour 4 panneaux sur toit tuiles, charge maximale garantie.','price'=>124000,'old_price'=>144000,'stock'=>40,'power'=>null,'warranty'=>'20 ans','featured'=>false,'specs'=>['Panneaux'=>'4','Matériau'=>'Aluminium 6005T5','Inclinaison'=>'15-60°']],
            ['category_id'=>7,'brand_id'=>null,'name'=>'Structure Fixation Toit Plat 6 panneaux','slug'=>'structure-toit-plat-6p','sku'=>'FIX-PLAT6','short_description'=>'Système de fixation pour toit plat, 6 panneaux, angle réglable.','price'=>229000,'old_price'=>null,'stock'=>22,'power'=>null,'warranty'=>'20 ans','featured'=>false,'specs'=>['Panneaux'=>'6','Angle'=>'10-15°','Matériau'=>'Aluminium']],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create(array_merge($product, [
                'active' => true,
                'views'  => rand(10, 500),
                'description' => $product['short_description'] . ' Notre équipe d\'experts est disponible pour vous accompagner dans votre projet d\'installation solaire. Nous proposons également un service de pose par des installateurs certifiés RGE.',
                'images' => null,
            ]));
        }
    }
}
