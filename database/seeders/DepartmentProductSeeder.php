<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create root departments
        $electronics = Department::factory()->root()->create([
            'name' => 'Eletrônicos',
        ]);
        $clothing = Department::factory()->root()->create([
            'name' => 'Roupas',
        ]);
        $home = Department::factory()->root()->create([
            'name' => 'Casa e Jardim',
        ]);

        // Create child departments
        $smartphones = Department::factory()->withParent($electronics->id)->create([
            'name' => 'Smartphones',
        ]);
        $laptops = Department::factory()->withParent($electronics->id)->create([
            'name' => 'Laptops',
        ]);
        
        $mens = Department::factory()->withParent($clothing->id)->create([
            'name' => 'Masculino',
        ]);
        $womens = Department::factory()->withParent($clothing->id)->create([
            'name' => 'Feminino',
        ]);
        
        $furniture = Department::factory()->withParent($home->id)->create([
            'name' => 'Móveis',
        ]);
        $decoration = Department::factory()->withParent($home->id)->create([
            'name' => 'Decoração',
        ]);

        // Create products
        $products = Product::factory(20)->create();

        // Attach products to departments randomly
        foreach ($products as $product) {
            $departments = collect([$electronics, $clothing, $home, $smartphones, $laptops, $mens, $womens, $furniture, $decoration])
                ->random(rand(1, 3)); // Each product belongs to 1-3 departments
            
            $product->departments()->attach($departments->pluck('id')->toArray());
        }
    }
}