<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Product;
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
                'Fones de Ouvido', 'Dispositivos Inteligentes', 'Acessórios',
            ],
            'Roupas' => [
                'Masculino', 'Feminino', 'Infantil', 'Esportiva', 'Calçados',
                'Acessórios de Moda', 'Roupas Íntimas', 'Moda Praia',
            ],
            'Casa e Jardim' => [
                'Móveis', 'Decoração', 'Jardinagem', 'Iluminação', 'Utensílios Domésticos',
                'Eletrodomésticos', 'Ferramentas', 'Organização',
            ],
            'Beleza e Saúde' => [
                'Maquiagem', 'Cuidados com a Pele', 'Perfumes', 'Cabelos', 'Higiene Pessoal',
                'Suplementos Alimentares', 'Medicamentos', 'Equipamentos de Saúde',
            ],
            'Alimentos e Bebidas' => [
                'Mercearia', 'Bebidas Alcoólicas', 'Bebidas Não Alcoólicas', 'Alimentos Orgânicos', 'Doces e Chocolates',
                'Padaria', 'Congelados', 'Importados',
            ],
            'Automotivo' => [
                'Acessórios para Carros', 'Peças Automotivas', 'Som Automotivo', 'Pneus', 'Óleos e Fluidos',
                'Ferramentas Automotivas', 'Motos', 'Carros',
            ],
            'Brinquedos e Jogos' => [
                'Brinquedos Infantis', 'Jogos de Tabuleiro', 'Jogos Eletrônicos', 'Brinquedos Educativos', 'Pelúcias',
                'Brinquedos para Exterior', 'Quebra-Cabeças', 'Bonecos e Figuras de Ação',
            ],
            'Livros' => [
                'Literatura', 'Livros Técnicos', 'Infantil', 'Autoajuda', 'Biografias',
                'Livros Digitais', 'Histórias em Quadrinhos', 'Importados',
            ],
            'Informática' => [
                'Hardware', 'Software', 'Periféricos', 'Redes', 'Armazenamento',
                'Componentes', 'Cabos e Adaptadores', 'Suprimentos',
            ],
            'Pets' => [
                'Alimentos para Cães', 'Alimentos para Gatos', 'Acessórios para Pets', 'Saúde Animal', 'Brinquedos para Pets',
                'Camas e Casinhas', 'Higiene Pet', 'Aquarismo',
            ],
            'Moda Infantil' => [
                'Bebês', 'Crianças', 'Adolescentes', 'Calçados Infantis', 'Acessórios Infantis',
                'Fantasias', 'Roupas de Inverno', 'Roupas de Verão',
            ],
            'Áudio e Vídeo' => [
                'Home Theater', 'Caixas de Som', 'Amplificadores', 'Projetores', 'Players',
                'Acessórios de Áudio', 'Gravadores', 'Microfones',
            ],
            'Escritório' => [
                'Móveis para Escritório', 'Materiais de Escritório', 'Papelaria', 'Organização', 'Impressoras',
                'Calculadoras', 'Cadeiras de Escritório', 'Suprimentos',
            ],
            'Instrumentos Musicais' => [
                'Guitarras e Baixos', 'Teclados', 'Percussão', 'Sopro', 'Cordas',
                'Amplificadores', 'Acessórios Musicais', 'Equipamentos de DJ',
            ],
            'Viagem' => [
                'Malas', 'Mochilas', 'Acessórios de Viagem', 'Equipamentos de Camping', 'Mapas e Guias',
                'Câmeras de Viagem', 'Adaptadores', 'Necessaires',
            ],
            'Joias e Relógios' => [
                'Anéis', 'Colares', 'Brincos', 'Pulseiras', 'Relógios Masculinos',
                'Relógios Femininos', 'Relógios Esportivos', 'Acessórios',
            ],
            'Games' => [
                'Consoles', 'Jogos', 'Acessórios para Consoles', 'Games para PC', 'Periféricos Gamer',
                'Cadeiras Gamer', 'Realidade Virtual', 'Notebooks Gamer',
            ],
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
        }
    }
}
