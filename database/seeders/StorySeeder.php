<?php

namespace Database\Seeders;

use App\Models\Story;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class StorySeeder extends Seeder
{
    public function run(): void
    {
        Story::updateOrCreate(
            ['slug' => 'the-vanni-kingdom'],
            [
                'title' => 'The Vanni Kingdom',
                'category' => 'HISTORY',
                'excerpt' => 'Discover the rich history of the Vanni chieftains and their independent rule over the northern mainland prior to colonial expansion.',
                'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1600&q=80',
                'is_published' => true,
                'is_featured' => true,
                'order' => 1,
                'reading_time_minutes' => 6,
                'blocks' => [
                    [
                        'type' => 'intro',
                        'text' => 'The history of the Vanni is one of fierce independence and resilience. Situated between the Jaffna Kingdom to the north and the Sinhalese kingdoms to the south, the Vanni chieftains—known as Vanniyars—maintained a unique autonomy that shaped the culture of the region.',
                    ],
                    [
                        'type' => 'image',
                        'url' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&q=80',
                        'caption' => 'Artistic interpretation of the Vanni landscape at Vanniyan Restaurant.'
                    ],
                    [
                        'type' => 'text',
                        'text' => 'For centuries, these dense forests and dry zone plains were ruled by chieftains who built tanks (reservoirs) to sustain agriculture and defended their borders against both local kings and later colonial powers including the Portuguese, Dutch, and British.'
                    ],
                    [
                        'type' => 'pull_quote',
                        'quote' => 'The Vanni remained one of the last bastions of resistance on the island, its deep jungles offering natural fortifications against foreign rule.',
                        'source' => 'Historical Archives'
                    ],
                    [
                        'type' => 'historical_context',
                        'text' => 'The last ruling chieftain, Pandara Vanniyan, led a significant rebellion against the British in 1803. Though ultimately defeated, his legacy remains a powerful symbol of resistance in Northern Sri Lanka.'
                    ],
                    [
                        'type' => 'interpretation',
                        'text' => 'The artwork featured in the restaurant is a contemporary interpretation inspired by the strength and resilience of the Vanni people. It does not depict a specific historical moment, but rather the enduring spirit of the region.'
                    ]
                ],
                'sources' => [
                    [
                        'title' => 'History of Sri Lanka',
                        'author' => 'K. M. de Silva',
                        'year' => '1981',
                        'url' => null
                    ],
                    [
                        'title' => 'The Vanni and the Vanniyars',
                        'author' => 'C. S. Navaratnam',
                        'year' => '1960',
                        'url' => null
                    ]
                ]
            ]
        );

        Story::updateOrCreate(
            ['slug' => 'traditional-vanni-food'],
            [
                'title' => 'Traditional Vanni Food',
                'category' => 'FOOD & TRADITION',
                'excerpt' => 'Explore the unique culinary techniques, spices, and ingredients that define the distinctive taste of the Vanni region.',
                'image' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=800&q=80',
                'is_published' => true,
                'is_featured' => false,
                'order' => 2,
                'reading_time_minutes' => 4,
                'blocks' => [
                    [
                        'type' => 'intro',
                        'text' => 'The cuisine of the Vanni is shaped by its geography. With its dry climate and reliance on seasonal rains, the food here reflects a deep connection to the earth.'
                    ],
                    [
                        'type' => 'cultural_context',
                        'text' => 'Sun-drying ingredients for preservation is a common practice. Spices like coriander, cumin, and dried red chilies are roasted and ground to create the distinctive fiery curries that the region is known for.'
                    ]
                ]
            ]
        );

        Story::firstOrCreate(
            ['slug' => 'vanni-agriculture'],
            [
                'title' => 'Vanni Agriculture',
                'category' => 'LAND & AGRICULTURE',
                'excerpt' => 'A look into the historical and modern agricultural practices that sustain the people of the Vanni mainland.',
                'image' => 'https://images.unsplash.com/photo-1625246333195-78d9c38ad449?w=800&q=80',
                'is_published' => true,
                'is_featured' => false,
                'order' => 3,
            ]
        );

        Story::firstOrCreate(
            ['slug' => 'nallur-kingdom'],
            [
                'title' => 'Nallur Kingdom',
                'category' => 'HISTORY',
                'excerpt' => 'The historical connections between the Vanni chieftains and the powerful Aryacakravarti dynasty of Jaffna.',
                'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=800&q=80',
                'is_published' => true,
                'is_featured' => false,
                'order' => 4,
            ]
        );

        Story::firstOrCreate(
            ['slug' => 'traditional-market'],
            [
                'title' => 'Traditional Market',
                'category' => 'DAILY LIFE',
                'excerpt' => 'The bustling markets of the north, where fresh produce, spices, and community life come together.',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=800&q=80',
                'is_published' => true,
                'is_featured' => false,
                'order' => 5,
            ]
        );
    }
}
