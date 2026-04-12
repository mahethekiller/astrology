<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ZodiacSign;

class ZodiacSignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $signs = [
            ['name' => 'Aries', 'slug' => 'aries', 'icon' => 'frontend/images/aries.png'],
            ['name' => 'Taurus', 'slug' => 'taurus', 'icon' => 'frontend/images/taurus.png'],
            ['name' => 'Gemini', 'slug' => 'gemini', 'icon' => 'frontend/images/gemini.png'],
            ['name' => 'Cancer', 'slug' => 'cancer', 'icon' => 'frontend/images/cancer.png'],
            ['name' => 'Leo', 'slug' => 'leo', 'icon' => 'frontend/images/leo.png'],
            ['name' => 'Virgo', 'slug' => 'virgo', 'icon' => 'frontend/images/virgo.png'],
            ['name' => 'Libra', 'slug' => 'libra', 'icon' => 'frontend/images/libra.png'],
            ['name' => 'Scorpio', 'slug' => 'scorpio', 'icon' => 'frontend/images/scorpio.png'],
            ['name' => 'Sagittarius', 'slug' => 'sagittarius', 'icon' => 'frontend/images/sagittarius.png'],
            ['name' => 'Capricorn', 'slug' => 'capricorn', 'icon' => 'frontend/images/capricorn.png'],
            ['name' => 'Aquarius', 'slug' => 'aquarius', 'icon' => 'frontend/images/aquarius.png'],
            ['name' => 'Pisces', 'slug' => 'pisces', 'icon' => 'frontend/images/pisces.png'],
        ];

        foreach ($signs as $index => $sign) {
            ZodiacSign::updateOrCreate(
                ['slug' => $sign['slug']],
                [
                    'name' => $sign['name'],
                    'icon' => $sign['icon'],
                    'is_active' => true,
                    'sort_order' => $index + 1,
                ]
            );
        }
    }
}
