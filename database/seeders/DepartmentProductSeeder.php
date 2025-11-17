<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create root departments (20 categories)
        $rootDepartments = [
            'Eletrônicos' => [
                'Smartphones', 'Laptops', 'Tablets', 'Smart TVs', 'Câmeras', 
                'Fones de Ouvido', 'Dispositivos Inteligentes', 'Acessórios'
            ],
            'Roupas' => [
                'Masculino', 'Feminino', 'Infantil', 'Esportiva', 'Calçados', 
                'Acessórios de Moda', 'Roupas Íntimas', 'Moda Praia'
            ],
            'Casa e Jardim' => [
                'Móveis', 'Decoração', 'Jardinagem', 'Iluminação', 'Utensílios Domésticos', 
                'Eletrodomésticos', 'Ferramentas', 'Organização'
            ],
            'Beleza e Saúde' => [
                'Maquiagem', 'Cuidados com a Pele', 'Perfumes', 'Cabelos', 'Higiene Pessoal', 
                'Suplementos Alimentares', 'Medicamentos', 'Equipamentos de Saúde'
            ],
            'Alimentos e Bebidas' => [
                'Mercearia', 'Bebidas Alcoólicas', 'Bebidas Não Alcoólicas', 'Alimentos Orgânicos', 'Doces e Chocolates', 
                'Padaria', 'Congelados', 'Importados'
            ],
            'Automotivo' => [
                'Acessórios para Carros', 'Peças Automotivas', 'Som Automotivo', 'Pneus', 'Óleos e Fluidos', 
                'Ferramentas Automotivas', 'Motos', 'Carros'
            ],
            'Brinquedos e Jogos' => [
                'Brinquedos Infantis', 'Jogos de Tabuleiro', 'Jogos Eletrônicos', 'Brinquedos Educativos', 'Pelúcias', 
                'Brinquedos para Exterior', 'Quebra-Cabeças', 'Bonecos e Figuras de Ação'
            ],
            'Livros' => [
                'Literatura', 'Livros Técnicos', 'Infantil', 'Autoajuda', 'Biografias', 
                'Livros Digitais', 'Histórias em Quadrinhos', 'Importados'
            ],
            'Informática' => [
                'Hardware', 'Software', 'Periféricos', 'Redes', 'Armazenamento', 
                'Componentes', 'Cabos e Adaptadores', 'Suprimentos'
            ],
            'Pets' => [
                'Alimentos para Cães', 'Alimentos para Gatos', 'Acessórios para Pets', 'Saúde Animal', 'Brinquedos para Pets', 
                'Camas e Casinhas', 'Higiene Pet', 'Aquarismo'
            ],
            'Moda Infantil' => [
                'Bebês', 'Crianças', 'Adolescentes', 'Calçados Infantis', 'Acessórios Infantis', 
                'Fantasias', 'Roupas de Inverno', 'Roupas de Verão'
            ],
            'Áudio e Vídeo' => [
                'Home Theater', 'Caixas de Som', 'Amplificadores', 'Projetores', 'Players', 
                'Acessórios de Áudio', 'Gravadores', 'Microfones'
            ],
            'Escritório' => [
                'Móveis para Escritório', 'Materiais de Escritório', 'Papelaria', 'Organização', 'Impressoras', 
                'Calculadoras', 'Cadeiras de Escritório', 'Suprimentos'
            ],
            'Instrumentos Musicais' => [
                'Guitarras e Baixos', 'Teclados', 'Percussão', 'Sopro', 'Cordas', 
                'Amplificadores', 'Acessórios Musicais', 'Equipamentos de DJ'
            ],
            'Viagem' => [
                'Malas', 'Mochilas', 'Acessórios de Viagem', 'Equipamentos de Camping', 'Mapas e Guias', 
                'Câmeras de Viagem', 'Adaptadores', 'Necessaires'
            ],
            'Joias e Relógios' => [
                'Anéis', 'Colares', 'Brincos', 'Pulseiras', 'Relógios Masculinos', 
                'Relógios Femininos', 'Relógios Esportivos', 'Acessórios'
            ],
            'Games' => [
                'Consoles', 'Jogos', 'Acessórios para Consoles', 'Games para PC', 'Periféricos Gamer', 
                'Cadeiras Gamer', 'Realidade Virtual', 'Notebooks Gamer'
            ]
        ];

        // Arrays to store all departments
        $allDepartments = [];
        $rootDepartmentsCollection = [];
        $childDepartmentsCollection = [];

        // Create all departments
        foreach ($rootDepartments as $rootName => $children) {
            // Create root department
            $rootDept = Department::factory()->root()->create([
                'name' => $rootName,
            ]);
            
            $rootDepartmentsCollection[] = $rootDept;
            $allDepartments[] = $rootDept;
            
            // Create child departments
            foreach ($children as $childName) {
                $childDept = Department::factory()->withParent($rootDept->id)->create([
                    'name' => $childName,
                ]);
                
                $childDepartmentsCollection[] = $childDept;
                $allDepartments[] = $childDept;
            }
        }

        // Create 200 products
        $products = Product::factory(200)->create();

        // Attach products to departments randomly
        foreach ($products as $product) {
            // Each product belongs to 1-5 departments randomly selected from all departments
            $selectedDepts = collect($allDepartments)->random(rand(1, 5));
            
            $product->departments()->attach($selectedDepts->pluck('id')->toArray());
            
            // Add product attributes based on department
            $this->addProductAttributes($product, $selectedDepts);
        }
    }

    /**
     * Add attributes to a product based on its departments.
     */
    private function addProductAttributes(Product $product, $departments): void
    {
        $departmentNames = $departments->pluck('name')->map(fn($name) => strtolower($name))->toArray();
        
        // Check if product is in gaming/electronics departments
        if (collect($departmentNames)->contains(fn($name) => str_contains($name, 'game') || str_contains($name, 'console'))) {
            // Gaming console attributes
            $gamingAttributes = [
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
            ];
            
            foreach ($gamingAttributes as $attr) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'key' => $attr['key'],
                    'description' => $attr['description'],
                ]);
            }
        } elseif (collect($departmentNames)->contains(fn($name) => str_contains($name, 'eletrônico') || str_contains($name, 'tv') || str_contains($name, 'smartphone'))) {
            // Electronics attributes
            $electronicsAttributes = [
                ['key' => 'Marca', 'description' => fake()->randomElement(['Samsung', 'LG', 'Sony', 'Panasonic'])],
                ['key' => 'Modelo', 'description' => fake()->bothify('??-####')],
                ['key' => 'Tipo de Tela', 'description' => fake()->randomElement(['OLED', 'QLED', 'LED', 'LCD'])],
                ['key' => 'Tamanho da Tela', 'description' => fake()->randomElement(['32"', '43"', '50"', '55"', '65"', '75"'])],
                ['key' => 'Resolução', 'description' => fake()->randomElement(['Full HD', '4K Ultra HD', '8K'])],
                ['key' => 'Smart TV', 'description' => 'Sim'],
                ['key' => 'Sistema Operacional', 'description' => fake()->randomElement(['Tizen', 'webOS', 'Android TV', 'Roku TV'])],
                ['key' => 'Conectividade', 'description' => 'Wi-Fi, Bluetooth, Ethernet'],
                ['key' => 'Garantia', 'description' => '12 meses'],
            ];
            
            foreach ($electronicsAttributes as $attr) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'key' => $attr['key'],
                    'description' => $attr['description'],
                ]);
            }
        } else {
            // Generic attributes for other products
            $genericAttributes = [
                ['key' => 'Marca', 'description' => fake()->company()],
                ['key' => 'Material', 'description' => fake()->randomElement(['Algodão', 'Poliéster', 'Plástico', 'Metal', 'Madeira'])],
                ['key' => 'Cor', 'description' => fake()->colorName()],
                ['key' => 'Dimensões', 'description' => fake()->numberBetween(10, 100) . ' x ' . fake()->numberBetween(10, 100) . ' x ' . fake()->numberBetween(5, 50) . ' cm'],
                ['key' => 'Peso', 'description' => fake()->randomFloat(2, 0.1, 10) . ' kg'],
                ['key' => 'Garantia', 'description' => fake()->randomElement(['6 meses', '12 meses', '24 meses'])],
            ];
            
            // Add only 3-4 attributes for generic products
            $selectedAttributes = collect($genericAttributes)->random(rand(3, 4));
            foreach ($selectedAttributes as $attr) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'key' => $attr['key'],
                    'description' => $attr['description'],
                ]);
            }
        }
    }
}