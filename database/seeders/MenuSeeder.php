<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Header Menu
        $header = \App\Models\Menu::create([
            'name' => 'Header Menu',
            'slug' => 'header',
        ]);

        $headerItems = [
            ['title' => 'Chat with Astrologer', 'route' => 'astrologer.index', 'type' => 'route', 'order' => 1],
            ['title' => 'Talk to Astrologer', 'route' => 'astrologer.index', 'type' => 'route', 'order' => 2],
            ['title' => 'Free Kundli', 'route' => 'kundli.index', 'type' => 'route', 'order' => 3],
            ['title' => 'Kundli Matching', 'route' => 'kundli.matching', 'type' => 'route', 'order' => 4],
            ['title' => 'Horoscopes', 'route' => 'horoscope.daily', 'type' => 'route', 'order' => 5],
            ['title' => 'Blogs', 'url' => '/blog', 'type' => 'url', 'order' => 6],
        ];

        foreach ($headerItems as $item) {
            $header->items()->create($item);
        }

        // 2. Footer Quick Links 1
        $footer1 = \App\Models\Menu::create([
            'name' => 'Footer Quick Links 1',
            'slug' => 'footer-quick-links-1',
        ]);

        $footer1Items = [
            ['title' => 'Chat with Astrologer', 'url' => '#', 'type' => 'url', 'order' => 1],
            ['title' => 'Tarot Readers', 'url' => '#', 'type' => 'url', 'order' => 2],
            ['title' => 'Vastu Experts', 'url' => '#', 'type' => 'url', 'order' => 3],
            ['title' => 'Love Astrologer', 'url' => '#', 'type' => 'url', 'order' => 4],
            ['title' => 'Financial Astrologer', 'url' => '#', 'type' => 'url', 'order' => 5],
            ['title' => 'Marriage Astrologer', 'url' => '#', 'type' => 'url', 'order' => 6],
            ['title' => 'Horoscope 2026', 'url' => '#', 'type' => 'url', 'order' => 7],
        ];

        foreach ($footer1Items as $item) {
            $footer1->items()->create($item);
        }

        // 3. Footer Quick Links 2
        $footer2 = \App\Models\Menu::create([
            'name' => 'Footer Quick Links 2',
            'slug' => 'footer-quick-links-2',
        ]);

        $footer2Items = [
            ['title' => 'About Us', 'url' => '#', 'type' => 'url', 'order' => 1],
            ['title' => 'Contact Us', 'url' => '#', 'type' => 'url', 'order' => 2],
            ['title' => 'Astrologer Registration', 'url' => '#', 'type' => 'url', 'order' => 3],
            ['title' => 'Career', 'url' => '#', 'type' => 'url', 'order' => 4],
            ['title' => 'Site Map', 'url' => '#', 'type' => 'url', 'order' => 5],
            ['title' => 'Karma & Destiny', 'url' => '#', 'type' => 'url', 'order' => 6],
            ['title' => 'Media Coverage', 'url' => '#', 'type' => 'url', 'order' => 7],
        ];

        foreach ($footer2Items as $item) {
            $footer2->items()->create($item);
        }
    }
}
