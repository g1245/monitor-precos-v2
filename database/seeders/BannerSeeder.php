<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Banner Promocional 1',
            'desktop_image' => null,
            'mobile_image' => null,
            'link' => '#',
            'order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Banner Promocional 2',
            'desktop_image' => null,
            'mobile_image' => null,
            'link' => '#',
            'order' => 2,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Banner Promocional 3',
            'desktop_image' => null,
            'mobile_image' => null,
            'link' => '#',
            'order' => 3,
            'is_active' => true,
        ]);
    }
}
