<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $title = $this->faker->randomElement([
            'Global Leadership Forum',
            'Executive Summit',
            'International Diplomatic Reception',
            'Corporate Innovation Conference',
            'Private Family Journey',
            'Cultural Delegation Dinner',
        ]);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::random(4),
            'summary' => $this->faker->sentence(14),
            'description' => $this->faker->paragraphs(4, true),
            'cover_image' => null,
            'event_date' => $this->faker->dateTimeBetween('-6 months', '+9 months'),
            'location' => $this->faker->randomElement([
                'Zug, Switzerland',
                'Geneva, Switzerland',
                'Dubai, UAE',
                'Paris, France',
                'London, United Kingdom',
                'New York, USA',
            ]),
            'is_published' => true,
        ];
    }
}
