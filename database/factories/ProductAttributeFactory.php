<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductAttribute>
 */
class ProductAttributeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductAttribute::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $attributeTypes = [
            'gaming' => [
                ['key' => 'Marca', 'description' => 'Sony'],
                ['key' => 'Modelo do Console', 'description' => 'PlayStation 5'],
                ['key' => 'Versão do Console', 'description' => 'Slim'],
                ['key' => 'SSD Integrado', 'description' => '1TB'],
                ['key' => 'Resolução', 'description' => '4K Ultra HD'],
                ['key' => 'Ray Tracing', 'description' => 'Sim, suporte completo'],
                ['key' => 'HDR', 'description' => 'HDR10'],
                ['key' => 'Conectividade', 'description' => 'Wi-Fi 6, Bluetooth 5.1, Ethernet'],
                ['key' => 'Portas', 'description' => 'USB-A (2x), USB-C (1x), HDMI 2.1'],
                ['key' => 'Dimensões', 'description' => '390 x 104 x 260 mm'],
                ['key' => 'Peso', 'description' => '3.2 kg'],
                ['key' => 'Garantia', 'description' => '12 meses'],
                ['key' => 'Incluso na Caixa', 'description' => 'Console, Controle DualSense, Cabo HDMI, Cabo USB, Manual'],
            ],
            'electronics' => [
                ['key' => 'Marca', 'description' => $this->faker->randomElement(['Samsung', 'LG', 'Sony', 'Panasonic'])],
                ['key' => 'Modelo', 'description' => $this->faker->bothify('??-####')],
                ['key' => 'Tipo de Tela', 'description' => $this->faker->randomElement(['OLED', 'QLED', 'LED', 'LCD'])],
                ['key' => 'Tamanho da Tela', 'description' => $this->faker->randomElement(['32"', '43"', '50"', '55"', '65"', '75"'])],
                ['key' => 'Resolução', 'description' => $this->faker->randomElement(['Full HD', '4K Ultra HD', '8K'])],
                ['key' => 'Smart TV', 'description' => 'Sim'],
                ['key' => 'Sistema Operacional', 'description' => $this->faker->randomElement(['Tizen', 'webOS', 'Android TV', 'Roku TV'])],
                ['key' => 'Conectividade', 'description' => 'Wi-Fi, Bluetooth, Ethernet'],
                ['key' => 'Portas HDMI', 'description' => $this->faker->randomElement(['2', '3', '4'])],
                ['key' => 'Portas USB', 'description' => $this->faker->randomElement(['1', '2', '3'])],
                ['key' => 'Consumo de Energia', 'description' => $this->faker->numberBetween(80, 200) . 'W'],
                ['key' => 'Garantia', 'description' => '12 meses'],
            ],
        ];

        $selectedType = $this->faker->randomElement(['gaming', 'electronics']);
        $attribute = $this->faker->randomElement($attributeTypes[$selectedType]);

        return [
            'product_id' => Product::factory(),
            'key' => $attribute['key'],
            'description' => $attribute['description'],
        ];
    }

    /**
     * Create attributes for a gaming console.
     */
    public function gaming(): static
    {
        return $this->sequence(
            ['key' => 'Marca', 'description' => 'Sony'],
            ['key' => 'Modelo do Console', 'description' => 'PlayStation 5'],
            ['key' => 'Versão do Console', 'description' => 'Slim'],
            ['key' => 'SSD Integrado', 'description' => '1TB'],
            ['key' => 'Resolução', 'description' => '4K Ultra HD'],
            ['key' => 'Ray Tracing', 'description' => 'Sim, suporte completo'],
            ['key' => 'HDR', 'description' => 'HDR10'],
            ['key' => 'Conectividade', 'description' => 'Wi-Fi 6, Bluetooth 5.1, Ethernet'],
            ['key' => 'Portas', 'description' => 'USB-A (2x), USB-C (1x), HDMI 2.1'],
            ['key' => 'Dimensões', 'description' => '390 x 104 x 260 mm'],
            ['key' => 'Peso', 'description' => '3.2 kg'],
            ['key' => 'Garantia', 'description' => '12 meses']
        );
    }

    /**
     * Create attributes for electronics.
     */
    public function electronics(): static
    {
        return $this->sequence(
            ['key' => 'Marca', 'description' => 'Samsung'],
            ['key' => 'Modelo', 'description' => 'UN55TU8000'],
            ['key' => 'Tipo de Tela', 'description' => 'QLED'],
            ['key' => 'Tamanho da Tela', 'description' => '55"'],
            ['key' => 'Resolução', 'description' => '4K Ultra HD'],
            ['key' => 'Smart TV', 'description' => 'Sim'],
            ['key' => 'Sistema Operacional', 'description' => 'Tizen'],
            ['key' => 'Conectividade', 'description' => 'Wi-Fi, Bluetooth, Ethernet'],
            ['key' => 'Portas HDMI', 'description' => '4'],
            ['key' => 'Portas USB', 'description' => '2'],
            ['key' => 'Consumo de Energia', 'description' => '150W'],
            ['key' => 'Garantia', 'description' => '12 meses']
        );
    }
}