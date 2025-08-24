<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Attribute;
use App\Models\Product;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Создание категорий
        $category1 = Category::create([
            'name' => 'Плитка для пола',
            'slug' => Str::slug('Плитка для пола'),
            'description' => 'Высококачественная плитка для напольного покрытия.',
            'is_active' => true,
        ]);
        $category2 = Category::create([
            'name' => 'Плитка для стен',
            'slug' => Str::slug('Плитка для стен'),
            'description' => 'Элегантная плитка для облицовки стен.',
            'is_active' => true,
        ]);

        $category3 = Category::create([
            'name' => 'Сантехника',
            'slug' => Str::slug('Сантехника'),
            'description' => 'Широкий выбор сантехники для ванных комнат.',
            'is_active' => true,
        ]);

        $category_baths = Category::create([
            'name' => 'Ванны',
            'slug' => Str::slug('Ванны'),
            'description' => 'Различные типы ванн для вашей ванной комнаты.',
            'is_active' => true,
            'parent_id' => $category3->id,
        ]);

        $category_sinks = Category::create([
            'name' => 'Раковины',
            'slug' => Str::slug('Раковины'),
            'description' => 'Стильные и функциональные раковины.',
            'is_active' => true,
            'parent_id' => $category3->id,
        ]);

        $category_toilets = Category::create([
            'name' => 'Унитазы',
            'slug' => Str::slug('Унитазы'),
            'description' => 'Современные унитазы для любой ванной.',
            'is_active' => true,
            'parent_id' => $category3->id,
        ]);

        $category_faucets = Category::create([
            'name' => 'Смесители',
            'slug' => Str::slug('Смесители'),
            'description' => 'Качественные смесители для ванной и кухни.',
            'is_active' => true,
            'parent_id' => $category3->id,
        ]);

        $category4 = Category::create([
            'name' => 'Клей для плитки',
            'slug' => Str::slug('Клей для плитки'),
            'description' => 'Надежный клей для любых видов плитки.',
            'is_active' => true,
        ]);

        $category5 = Category::create([
            'name' => 'Затирка для плитки',
            'slug' => Str::slug('Затирка для плитки'),
            'description' => 'Качественная затирка для долговечного шва.',
            'is_active' => true,
        ]);

        // Создание брендов
        $brand1 = Brand::create([
            'name' => 'Kerama Marazzi',
            'slug' => Str::slug('Kerama Marazzi'),
            'country' => 'Россия',
            'is_active' => true,
        ]);
        $brand2 = Brand::create([
            'name' => 'Atlas Concorde',
            'slug' => Str::slug('Atlas Concorde'),
            'country' => 'Италия',
            'is_active' => true,
        ]);
        $brand3 = Brand::create([
            'name' => 'Grohe',
            'slug' => Str::slug('Grohe'),
            'country' => 'Германия',
            'is_active' => true,
        ]);
        $brand4 = Brand::create([
            'name' => 'Cersanit',
            'slug' => Str::slug('Cersanit'),
            'country' => 'Польша',
            'is_active' => true,
        ]);
        $brand5 = Brand::create([
            'name' => 'Litokol',
            'slug' => Str::slug('Litokol'),
            'country' => 'Италия',
            'is_active' => true,
        ]);

        // Создание цветов
        $color1 = Color::create([
            'name' => 'Белый',
            'hex' => '#FFFFFF',
        ]);
        $color2 = Color::create([
            'name' => 'Серый',
            'hex' => '#808080',
        ]);
        $color3 = Color::create([
            'name' => 'Бежевый',
            'hex' => '#F5F5DC',
        ]);
        $color4 = Color::create([
            'name' => 'Хром',
            'hex' => '#E4E5E6',
        ]);

        // Создание атрибутов
        $attribute_thickness = Attribute::create([
            'name' => 'Толщина',
            'slug' => Str::slug('Толщина'),
            'type' => 'number',
        ]);
        $attribute_material = Attribute::create([
            'name' => 'Материал',
            'slug' => Str::slug('Материал'),
            'type' => 'string',
        ]);
        $attribute_waterproof = Attribute::create([
            'name' => 'Влагостойкость',
            'slug' => Str::slug('Влагостойкость'),
            'type' => 'boolean',
        ]);
        $attribute_collection = Attribute::create([
            'name' => 'Коллекция',
            'slug' => Str::slug('Коллекция'),
            'type' => 'string',
        ]);
        $attribute_description_full = Attribute::create([
            'name' => 'Полное описание',
            'slug' => Str::slug('Полное описание'),
            'type' => 'text',
        ]);
        $attribute_volume = Attribute::create([
            'name' => 'Объем',
            'slug' => Str::slug('Объем'),
            'type' => 'number',
        ]);
        $attribute_weight = Attribute::create([
            'name' => 'Вес',
            'slug' => Str::slug('Вес'),
            'type' => 'number',
        ]);
        $attribute_installation_type = Attribute::create([
            'name' => 'Тип установки',
            'slug' => Str::slug('Тип установки'),
            'type' => 'string',
        ]);
        $attribute_shape = Attribute::create([
            'name' => 'Форма',
            'slug' => Str::slug('Форма'),
            'type' => 'string',
        ]);
        $attribute_application = Attribute::create([
            'name' => 'Применение',
            'slug' => Str::slug('Применение'),
            'type' => 'string',
        ]);
        $attribute_drying_time = Attribute::create([
            'name' => 'Время высыхания',
            'slug' => Str::slug('Время высыхания'),
            'type' => 'string',
        ]);
        $attribute_pack_weight = Attribute::create([
            'name' => 'Вес упаковки',
            'slug' => Str::slug('Вес упаковки'),
            'type' => 'number',
        ]);
        $attribute_min_temp = Attribute::create([
            'name' => 'Мин. температура эксплуатации',
            'slug' => Str::slug('Мин. температура эксплуатации'),
            'type' => 'number',
        ]);
        $attribute_max_temp = Attribute::create([
            'name' => 'Макс. температура эксплуатации',
            'slug' => Str::slug('Макс. температура эксплуатации'),
            'type' => 'number',
        ]);
        $attribute_consumption = Attribute::create([
            'name' => 'Расход',
            'slug' => Str::slug('Расход'),
            'type' => 'string',
        ]);

        // Создание продуктов
        $product1 = Product::create([
            'article' => 'KM001',
            'name' => 'Плитка Лофт Белая',
            'slug' => Str::slug('Плитка Лофт Белая'),
            'price' => 1250.00,
            'unit' => 'кв.м',
            'product_code' => 'P001',
            'description' => 'Современная плитка в стиле лофт.',
            'category_id' => $category1->id,
            'color_id' => $color1->id,
            'brand_id' => $brand1->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Матовая',
            'pattern' => 'Однотонный',
            'country' => 'Россия',
            'collection' => 'Лофт',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product1->id,
            'attribute_id' => $attribute_thickness->id,
            'number_value' => 10.5,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product1->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Керамогранит',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product1->id,
            'attribute_id' => $attribute_waterproof->id,
            'boolean_value' => true,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product1->id,
            'attribute_id' => $attribute_description_full->id,
            'text_value' => 'Полное описание плитки Лофт Белая: Идеально подходит для современных интерьеров. Легко укладывается и проста в уходе.',
        ]);

        $product2 = Product::create([
            'article' => 'AC002',
            'name' => 'Плитка Элегант Серая',
            'slug' => Str::slug('Плитка Элегант Серая'),
            'price' => 2500.00,
            'unit' => 'кв.м',
            'product_code' => 'P002',
            'description' => 'Изысканная плитка для классических интерьеров.',
            'category_id' => $category2->id,
            'color_id' => $color2->id,
            'brand_id' => $brand2->id,
            'is_published' => true,
            'is_sale' => true,
            'texture' => 'Глянцевая',
            'pattern' => 'Мрамор',
            'country' => 'Италия',
            'collection' => 'Элегант',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product2->id,
            'attribute_id' => $attribute_thickness->id,
            'number_value' => 8.0,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product2->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Керамика',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product2->id,
            'attribute_id' => $attribute_waterproof->id,
            'boolean_value' => false,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product2->id,
            'attribute_id' => $attribute_description_full->id,
            'text_value' => 'Полное описание плитки Элегант Серая: Создаст атмосферу роскоши в любом помещении. Долговечна и устойчива к износу.',
        ]);

        $product3 = Product::create([
            'article' => 'KM003',
            'name' => 'Плитка Теплый Беж',
            'slug' => Str::slug('Плитка Теплый Беж'),
            'price' => 1500.00,
            'unit' => 'кв.м',
            'product_code' => 'P003',
            'description' => 'Уютная плитка в теплых тонах.',
            'category_id' => $category1->id,
            'color_id' => $color3->id,
            'brand_id' => $brand1->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Матовая',
            'pattern' => 'Под дерево',
            'country' => 'Россия',
            'collection' => 'Природа',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product3->id,
            'attribute_id' => $attribute_thickness->id,
            'number_value' => 9.0,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product3->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Керамогранит',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product3->id,
            'attribute_id' => $attribute_waterproof->id,
            'boolean_value' => true,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product3->id,
            'attribute_id' => $attribute_description_full->id,
            'text_value' => 'Полное описание плитки Теплый Беж: Имитация дерева создает ощущение тепла и комфорта. Подходит для любого стиля.',
        ]);

        $product4 = Product::create([
            'article' => 'CSN001',
            'name' => 'Ванна акриловая Cersanit',
            'slug' => Str::slug('Ванна акриловая Cersanit'),
            'price' => 15000.00,
            'unit' => 'шт',
            'product_code' => 'S001',
            'description' => 'Комфортная акриловая ванна для вашей ванной комнаты.',
            'category_id' => $category_baths->id,
            'color_id' => $color1->id,
            'brand_id' => $brand4->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Гладкая',
            'pattern' => 'Нет',
            'country' => 'Польша',
            'collection' => 'Nano',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product4->id,
            'attribute_id' => $attribute_volume->id,
            'number_value' => 200,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product4->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Акрил',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product4->id,
            'attribute_id' => $attribute_installation_type->id,
            'string_value' => 'Пристенная',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product4->id,
            'attribute_id' => $attribute_shape->id,
            'string_value' => 'Прямоугольная',
        ]);

        $product5 = Product::create([
            'article' => 'GRH002',
            'name' => 'Смеситель для раковины Grohe',
            'slug' => Str::slug('Смеситель для раковины Grohe'),
            'price' => 7500.00,
            'unit' => 'шт',
            'product_code' => 'S002',
            'description' => 'Надежный и стильный смеситель для умывальника.',
            'category_id' => $category_faucets->id,
            'color_id' => $color4->id,
            'brand_id' => $brand3->id,
            'is_published' => true,
            'is_sale' => true,
            'texture' => 'Глянцевая',
            'pattern' => 'Нет',
            'country' => 'Германия',
            'collection' => 'Eurosmart',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product5->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Латунь',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product5->id,
            'attribute_id' => $attribute_installation_type->id,
            'string_value' => 'На раковину',
        ]);

        $product8 = Product::create([
            'article' => 'CSN003',
            'name' => 'Раковина Cersanit President',
            'slug' => Str::slug('Раковина Cersanit President'),
            'price' => 3500.00,
            'unit' => 'шт',
            'product_code' => 'S003',
            'description' => 'Элегантная керамическая раковина для любой ванной комнаты.',
            'category_id' => $category_sinks->id,
            'color_id' => $color1->id,
            'brand_id' => $brand4->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Гладкая',
            'pattern' => 'Нет',
            'country' => 'Польша',
            'collection' => 'President',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product8->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Керамика',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product8->id,
            'attribute_id' => $attribute_installation_type->id,
            'string_value' => 'Подвесная',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product8->id,
            'attribute_id' => $attribute_shape->id,
            'string_value' => 'Овальная',
        ]);

        $product9 = Product::create([
            'article' => 'CSN004',
            'name' => 'Унитаз-компакт Cersanit Delfi',
            'slug' => Str::slug('Унитаз-компакт Cersanit Delfi'),
            'price' => 8000.00,
            'unit' => 'шт',
            'product_code' => 'S004',
            'description' => 'Компактный унитаз с горизонтальным выпуском.',
            'category_id' => $category_toilets->id,
            'color_id' => $color1->id,
            'brand_id' => $brand4->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Гладкая',
            'pattern' => 'Нет',
            'country' => 'Польша',
            'collection' => 'Delfi',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product9->id,
            'attribute_id' => $attribute_material->id,
            'string_value' => 'Керамика',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product9->id,
            'attribute_id' => $attribute_installation_type->id,
            'string_value' => 'Напольный',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product9->id,
            'attribute_id' => $attribute_waterproof->id,
            'boolean_value' => false,
        ]);

        $product6 = Product::create([
            'article' => 'LIT001',
            'name' => 'Клей для плитки Litokol Litoflex K80',
            'slug' => Str::slug('Клей для плитки Litokol Litoflex K80'),
            'price' => 1200.00,
            'unit' => 'упак.',
            'product_code' => 'M001',
            'description' => 'Высокоэластичный клей для керамогранита и крупноформатной плитки.',
            'category_id' => $category4->id,
            'color_id' => $color1->id,
            'brand_id' => $brand5->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Порошок',
            'pattern' => 'Нет',
            'country' => 'Италия',
            'collection' => 'Litoflex',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product6->id,
            'attribute_id' => $attribute_pack_weight->id,
            'number_value' => 25,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product6->id,
            'attribute_id' => $attribute_drying_time->id,
            'string_value' => '24 часа',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product6->id,
            'attribute_id' => $attribute_min_temp->id,
            'number_value' => -30,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product6->id,
            'attribute_id' => $attribute_max_temp->id,
            'number_value' => 70,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product6->id,
            'attribute_id' => $attribute_consumption->id,
            'string_value' => '2-5 кг/м2',
        ]);

        $product7 = Product::create([
            'article' => 'LIT002',
            'name' => 'Затирка для плитки Litokol Starlike Evo',
            'slug' => Str::slug('Затирка для плитки Litokol Starlike Evo'),
            'price' => 800.00,
            'unit' => 'кг',
            'product_code' => 'M002',
            'description' => 'Эпоксидная затирка для швов с высокой стойкостью к агрессивным средам.',
            'category_id' => $category5->id,
            'color_id' => $color2->id,
            'brand_id' => $brand5->id,
            'is_published' => true,
            'is_sale' => false,
            'texture' => 'Гладкая',
            'pattern' => 'Нет',
            'country' => 'Италия',
            'collection' => 'Starlike',
        ]);

        ProductAttributeValue::create([
            'product_id' => $product7->id,
            'attribute_id' => $attribute_pack_weight->id,
            'number_value' => 5,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product7->id,
            'attribute_id' => $attribute_drying_time->id,
            'string_value' => '24 часа (пешие нагрузки)',
        ]);
        ProductAttributeValue::create([
            'product_id' => $product7->id,
            'attribute_id' => $attribute_waterproof->id,
            'boolean_value' => true,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product7->id,
            'attribute_id' => $attribute_min_temp->id,
            'number_value' => -20,
        ]);
        ProductAttributeValue::create([
            'product_id' => $product7->id,
            'attribute_id' => $attribute_max_temp->id,
            'number_value' => 100,
        ]);
    }
}
