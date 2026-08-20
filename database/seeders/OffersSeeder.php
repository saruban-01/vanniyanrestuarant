<?php

namespace Database\Seeders;

use App\Models\LoyaltyConfig;
use App\Models\Offer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OffersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Offers
        $offers = [
            [
                'title' => 'Weekend Family Biryani Feast',
                'description' => 'Enjoy our signature Mutton Biryani for the whole family at a special price. Includes raita, moju, and 4 complimentary soft drinks.',
                'image_url' => 'https://images.unsplash.com/photo-1589302168068-964664d93cb0?auto=format&fit=crop&q=80&w=800',
                'type' => 'bundle',
                'price_or_discount' => 'Rs. 6,500',
                'valid_from' => now()->subDays(2),
                'valid_until' => now()->addDays(30),
                'is_dine_in' => true,
                'is_takeaway' => true,
                'is_featured' => true,
                'is_published' => true,
                'terms' => 'Valid on weekends only. Cannot be combined with other offers.',
            ],
            [
                'title' => 'Complimentary Dessert',
                'description' => 'Get a complimentary Watalappan when you order any two main courses.',
                'image_url' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?auto=format&fit=crop&q=80&w=800',
                'type' => 'free_item',
                'price_or_discount' => 'Free Dessert',
                'valid_from' => now()->subDays(5),
                'valid_until' => now()->addDays(14),
                'is_dine_in' => true,
                'is_takeaway' => false,
                'is_featured' => false,
                'is_published' => true,
                'terms' => 'Dine-in only. One complimentary dessert per table.',
            ],
            [
                'title' => 'Upcoming Holiday Special',
                'description' => 'Celebrate the upcoming holiday with 20% off all Kothu Roti orders.',
                'image_url' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?auto=format&fit=crop&q=80&w=800',
                'type' => 'discount',
                'price_or_discount' => '20% OFF',
                'valid_from' => now()->addDays(5), // Scheduled
                'valid_until' => now()->addDays(10),
                'is_dine_in' => true,
                'is_takeaway' => true,
                'is_featured' => false,
                'is_published' => true,
                'terms' => 'Cannot be combined with other offers.',
            ],
            [
                'title' => 'Expired Lunch Offer',
                'description' => 'Quick lunch special.',
                'image_url' => 'https://images.unsplash.com/photo-1544148103-0773bf10d330?auto=format&fit=crop&q=80&w=800',
                'type' => 'discount',
                'price_or_discount' => '10% OFF',
                'valid_from' => now()->subDays(20),
                'valid_until' => now()->subDays(10), // Expired
                'is_dine_in' => true,
                'is_takeaway' => true,
                'is_featured' => false,
                'is_published' => true,
                'terms' => 'Expired offer.',
            ],
        ];

        foreach ($offers as $offer) {
            $offer['slug'] = Str::slug($offer['title']);
            Offer::updateOrCreate(['slug' => $offer['slug']], $offer);
        }

        // 2. Seed Loyalty Config
        LoyaltyConfig::updateOrCreate(
            ['id' => 1],
            [
            'heading' => 'COLLECT VISITS. ENJOY REWARDS.',
            'description' => 'Ask our team for a Vanniyan Loyalty Card during your next visit and collect eligible visits on your physical card.',
            'card_image_url' => 'https://images.unsplash.com/photo-1589302168068-964664d93cb0?auto=format&fit=crop&q=80&w=800', // Placeholder
            'visit_5_title' => 'FREE DRINK',
            'visit_5_reward' => 'Receive a free drink on your 5th eligible visit.',
            'visit_10_title' => 'RS. 1,000 FOOD COUPON',
            'visit_10_reward' => 'Receive a Rs. 1,000 food coupon on your 10th eligible visit.',
            'how_it_works' => [
                ['title' => 'GET YOUR CARD', 'description' => 'Ask our team for your Vanniyan Loyalty Card.'],
                ['title' => 'COLLECT YOUR VISITS', 'description' => 'Bring your physical card and record eligible visits.'],
                ['title' => 'ENJOY YOUR REWARDS', 'description' => 'Reach your 5th and 10th visits to receive the listed rewards.'],
            ],
            'terms' => [
                'One bill = one visit.',
                'Valid for eligible dine-in bills only.',
                'Cannot be combined with another offer, where applicable.',
                'Management decision is final.',
            ],
            'cta_text' => 'VIEW MENU',
            'is_visible' => true,
        ]);
    }
}
