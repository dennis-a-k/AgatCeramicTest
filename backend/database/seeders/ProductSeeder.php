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
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('ru_RU');

        // Создание категорий
        $categories = [];
        $category_names = ['Керамогранит', 'Плитка', 'Мозаика', 'Клинкер', 'Ступени', 'Затирка', 'Клей', 'Сантехника'];
        foreach ($category_names as $name) {
            $categories[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Описание категории ' . $name,
                'is_active' => true,
            ]);
        }

        // Подкатегории для сантехники
        $sanitary_subs = [];
        $sub_names = ['Ванны', 'Смесители', 'Унитазы', 'Инсталляции', 'Душевые кабины'];
        foreach ($sub_names as $name) {
            $sanitary_subs[$name] = Category::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => 'Описание подкатегории ' . $name,
                'is_active' => true,
                'parent_id' => $categories['Сантехника']->id,
            ]);
        }

        // Создание брендов (204)
        $brands = [];
        $countries = ['Россия', 'Италия', 'Германия', 'Польша', 'Испания', 'Турция', 'Китай', 'Бельгия', 'Франция', 'Швеция'];
        $lat = range('A', 'Z');
        $cyr = ['А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'];
        $num = range('0', '9');
        $all_chars = array_merge($lat, $cyr, $num);
        $brand_names = [];
        foreach ($all_chars as $char) {
            for ($j = 1; $j <= 3; $j++) {
                if (in_array($char, $lat)) {
                    $name = $char . 'Brand' . $j;
                } elseif (in_array($char, $cyr)) {
                    $name = $char . 'Бренд' . $j;
                } else {
                    $name = $char . 'Brand' . $j;
                }
                $brand_names[] = $name;
            }
        }
        shuffle($brand_names);
        for ($i = 0; $i < 204; $i++) {
            $name = $brand_names[$i];
            $country = $faker->randomElement($countries);
            $brands[] = Brand::create([
                'name' => $name,
                'slug' => Str::slug($name . $i),
                'country' => $country,
                'is_active' => true,
            ]);
        }

        // Создание цветов (36)
        $colors = [];
        $color_names = ['Белый', 'Черный', 'Серый', 'Красный', 'Синий', 'Зеленый', 'Желтый', 'Оранжевый', 'Фиолетовый', 'Розовый', 'Бежевый', 'Коричневый', 'Голубой', 'Бирюзовый', 'Лиловый', 'Малиновый', 'Изумрудный', 'Золотой', 'Серебряный', 'Бронзовый', 'Перламутровый', 'Металлик', 'Гранитный', 'Мраморный', 'Дубовый', 'Бук', 'Орех', 'Клен', 'Ясень', 'Вишня', 'Эбен', 'Тик', 'Бамбук', 'Ротанг', 'Лен'];
        foreach ($color_names as $name) {
            $hex = '#' . strtoupper(dechex(rand(0, 16777215)));
            $colors[] = Color::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'hex' => $hex,
            ]);
        }

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
            'slug' => 'vlagostoikost',
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
        $attribute_sizes = Attribute::create([
            'name' => 'Размеры',
            'slug' => Str::slug('Размеры'),
            'type' => 'string',
        ]);
        $attribute_used_as_glue = Attribute::create([
            'name' => 'Используется в качестве клея',
            'slug' => Str::slug('Используется в качестве клея'),
            'type' => 'boolean',
        ]);
        $attribute_seam_width = Attribute::create([
            'name' => 'Ширина шва',
            'slug' => 'sirina-sva',
            'type' => 'string',
        ]);
        $attribute_type = Attribute::create([
            'name' => 'Тип',
            'slug' => Str::slug('Тип'),
            'type' => 'string',
        ]);

        // Генерация товаров
        $product_counter = 1;

        // Керамогранит (150)
        $this->createProductsForCategory($categories['Керамогранит'], $brands, $colors, $faker, 150, 'кв.м', 'KG', 'Керамогранит', $attribute_sizes, $attribute_thickness, $attribute_material, $attribute_waterproof, $attribute_description_full, $product_counter);

        // Плитка (150)
        $this->createProductsForCategory($categories['Плитка'], $brands, $colors, $faker, 150, 'кв.м', 'PL', 'Керамика', $attribute_sizes, $attribute_thickness, $attribute_material, $attribute_waterproof, $attribute_description_full, $product_counter);

        // Мозаика (150)
        $this->createProductsForCategory($categories['Мозаика'], $brands, $colors, $faker, 150, 'кв.м', 'MZ', 'Мозаика', $attribute_sizes, $attribute_thickness, $attribute_material, $attribute_waterproof, $attribute_description_full, $product_counter);

        // Клинкер (150)
        $this->createProductsForCategory($categories['Клинкер'], $brands, $colors, $faker, 150, 'кв.м', 'KL', 'Клинкер', $attribute_sizes, $attribute_thickness, $attribute_material, $attribute_waterproof, $attribute_description_full, $product_counter);

        // Ступени (150)
        $this->createProductsForCategory($categories['Ступени'], $brands, $colors, $faker, 150, 'кв.м', 'ST', 'Керамогранит', $attribute_sizes, $attribute_thickness, $attribute_material, $attribute_waterproof, $attribute_description_full, $product_counter);

        // Затирка (150)
        $this->createGroutProducts($categories['Затирка'], $brands, $colors, $faker, 150, $attribute_weight, $attribute_used_as_glue, $attribute_seam_width, $attribute_description_full, $product_counter);

        // Клей (150)
        $this->createGlueProducts($categories['Клей'], $brands, $colors, $faker, 150, $attribute_weight, $attribute_description_full, $product_counter);

        // Сантехника (150, распределено по подкатегориям)
        $this->createSanitaryProducts($sanitary_subs, $brands, $colors, $faker, 30, $attribute_type, $attribute_description_full, $product_counter);
    }

    private function createProductsForCategory($category, $brands, $colors, $faker, $count, $unit, $prefix, $material, $attribute_sizes, $attribute_thickness, $attribute_material, $attribute_waterproof, $attribute_description_full, &$product_counter)
    {
        for ($i = 0; $i < $count; $i++) {
            $brand = $faker->randomElement($brands);
            $color = $faker->randomElement($colors);
            $name = $faker->sentence(3);
            $article = $prefix . str_pad($product_counter, 4, '0', STR_PAD_LEFT);
            $product = Product::create([
                'article' => $article,
                'name' => $name,
                'slug' => Str::slug($name . $product_counter),
                'price' => $faker->numberBetween(1000, 10000),
                'unit' => $unit,
                'product_code' => $prefix . $product_counter,
                'description' => $faker->paragraph,
                'category_id' => $category->id,
                'color_id' => $color->id,
                'brand_id' => $brand->id,
                'is_published' => $faker->boolean(80),
                'is_sale' => $faker->boolean(20),
                'texture' => $faker->randomElement(['Матовая', 'Глянцевая', 'Полуматовая']),
                'pattern' => $faker->randomElement(['Однотонный', 'Мрамор', 'Под дерево', 'Абстрактный']),
                'country' => $brand->country,
                'collection' => $faker->word,
            ]);

            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_sizes->id,
                'string_value' => $faker->randomElement(['600x600', '800x800', '1200x600', '300x300']),
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_thickness->id,
                'number_value' => $faker->numberBetween(8, 12),
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_material->id,
                'string_value' => $material,
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_waterproof->id,
                'boolean_value' => true,
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_description_full->id,
                'text_value' => $faker->paragraph(3),
            ]);

            $product_counter++;
        }
    }

    private function createGroutProducts($category, $brands, $colors, $faker, $count, $attribute_weight, $attribute_used_as_glue, $attribute_seam_width, $attribute_description_full, &$product_counter)
    {
        for ($i = 0; $i < $count; $i++) {
            $brand = $faker->randomElement($brands);
            $color = $faker->randomElement($colors);
            $name = $faker->sentence(3);
            $article = 'ZT' . str_pad($product_counter, 4, '0', STR_PAD_LEFT);
            $product = Product::create([
                'article' => $article,
                'name' => $name,
                'slug' => Str::slug($name . $product_counter),
                'price' => $faker->numberBetween(500, 2000),
                'unit' => 'кг',
                'product_code' => 'ZT' . $product_counter,
                'description' => $faker->paragraph,
                'category_id' => $category->id,
                'color_id' => $color->id,
                'brand_id' => $brand->id,
                'is_published' => $faker->boolean(80),
                'is_sale' => $faker->boolean(20),
                'texture' => 'Гладкая',
                'pattern' => 'Нет',
                'country' => $brand->country,
                'collection' => $faker->word,
            ]);

            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_weight->id,
                'number_value' => $faker->numberBetween(1, 25),
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_used_as_glue->id,
                'boolean_value' => $faker->boolean,
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_seam_width->id,
                'string_value' => $faker->randomElement(['1-3 мм', '2-5 мм', '3-8 мм']),
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_description_full->id,
                'text_value' => $faker->paragraph(3),
            ]);

            $product_counter++;
        }
    }

    private function createGlueProducts($category, $brands, $colors, $faker, $count, $attribute_weight, $attribute_description_full, &$product_counter)
    {
        for ($i = 0; $i < $count; $i++) {
            $brand = $faker->randomElement($brands);
            $color = $faker->randomElement($colors);
            $name = $faker->sentence(3);
            $article = 'KL' . str_pad($product_counter, 4, '0', STR_PAD_LEFT);
            $product = Product::create([
                'article' => $article,
                'name' => $name,
                'slug' => Str::slug($name . $product_counter),
                'price' => $faker->numberBetween(800, 3000),
                'unit' => 'упак.',
                'product_code' => 'KL' . $product_counter,
                'description' => $faker->paragraph,
                'category_id' => $category->id,
                'color_id' => $color->id,
                'brand_id' => $brand->id,
                'is_published' => $faker->boolean(80),
                'is_sale' => $faker->boolean(20),
                'texture' => 'Порошок',
                'pattern' => 'Нет',
                'country' => $brand->country,
                'collection' => $faker->word,
            ]);

            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_weight->id,
                'number_value' => $faker->numberBetween(5, 25),
            ]);
            ProductAttributeValue::create([
                'product_id' => $product->id,
                'attribute_id' => $attribute_description_full->id,
                'text_value' => $faker->paragraph(3),
            ]);

            $product_counter++;
        }
    }

    private function createSanitaryProducts($sanitary_subs, $brands, $colors, $faker, $count_per_sub, $attribute_type, $attribute_description_full, &$product_counter)
    {
        foreach ($sanitary_subs as $sub_name => $sub_category) {
            for ($i = 0; $i < $count_per_sub; $i++) {
                $brand = $faker->randomElement($brands);
                $color = $faker->randomElement($colors);
                $name = $faker->sentence(3);
                $article = 'SN' . str_pad($product_counter, 4, '0', STR_PAD_LEFT);
                $product = Product::create([
                    'article' => $article,
                    'name' => $name,
                    'slug' => Str::slug($name . $product_counter),
                    'price' => $faker->numberBetween(5000, 50000),
                    'unit' => 'шт',
                    'product_code' => 'SN' . $product_counter,
                    'description' => $faker->paragraph,
                    'category_id' => $sub_category->id,
                    'color_id' => $color->id,
                    'brand_id' => $brand->id,
                    'is_published' => $faker->boolean(80),
                    'is_sale' => $faker->boolean(20),
                    'texture' => 'Гладкая',
                    'pattern' => 'Нет',
                    'country' => $brand->country,
                    'collection' => $faker->word,
                ]);

                $type = '';
                switch ($sub_name) {
                    case 'Ванны':
                        $type = $faker->randomElement(['Акриловая', 'Чугунная', 'Стальная']);
                        break;
                    case 'Смесители':
                        $type = $faker->randomElement(['Однорычажный', 'Двухвентильный', 'Термостатический']);
                        break;
                    case 'Унитазы':
                        $type = $faker->randomElement(['Компакт', 'Подвесной', 'Напольный']);
                        break;
                    case 'Инсталляции':
                        $type = $faker->randomElement(['Блочная', 'Рамная']);
                        break;
                    case 'Душевые кабины':
                        $type = $faker->randomElement(['Закрытая', 'Открытая', 'Угловая']);
                        break;
                }

                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute_type->id,
                    'string_value' => $type,
                ]);
                ProductAttributeValue::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attribute_description_full->id,
                    'text_value' => $faker->paragraph(3),
                ]);

                $product_counter++;
            }
        }
    }
}
