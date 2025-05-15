<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    public function run()
    {
        $sliders = [
            [
                'title' => 'Welcome to Auto Parts Hub',
                'subtitle' => 'Your One-Stop Shop for Quality Auto Parts',
                'image' => 'slider1.jpg',
                'button_text' => 'Shop Now',
                'link' => '/shop',
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'title' => 'Quality Parts Guaranteed',
                'subtitle' => 'All Parts are Genuine and Quality Tested',
                'image' => 'slider2.jpg',
                'button_text' => 'Learn More',
                'link' => '/about',
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'title' => 'Special Offers',
                'subtitle' => 'Get Great Deals on Auto Parts',
                'image' => 'slider3.jpg',
                'button_text' => 'View Offers',
                'link' => '/shop',
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
} 