<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $samples = [
            [
                'title' => 'Sovereign Executive Forum',
                'summary' => 'A closed-door forum for family-office principals, C-level executives, and institutional partners exploring cross-border coordination.',
                'description' => "The Sovereign Executive Forum brings together a curated group of principals, institutional leaders, and long-term operators for two days of structured conversations on cross-border programme delivery.\n\nProgramming spans keynote sessions, moderated round tables, and off-record briefings from operators active in aviation, security, and hospitality. Attendance is strictly by invitation.",
                'event_date' => now()->addMonths(2)->setTime(9, 0),
                'location' => 'Zug, Switzerland',
            ],
            [
                'title' => 'Alpine Delegation Dinner',
                'summary' => 'A private hosted dinner for an incoming diplomatic delegation, coordinated end-to-end from airport arrival through evening programme.',
                'description' => "An intimate hosted dinner staged in a private Alpine venue for a visiting diplomatic delegation. SOV SUMMIT coordinated arrival logistics, security escort, hospitality, protocol, translation, and evening entertainment as a single accountable programme.\n\nGuests included principals from three foreign missions and a small circle of Swiss institutional hosts.",
                'event_date' => now()->addMonths(1)->setTime(19, 30),
                'location' => 'Andermatt, Switzerland',
            ],
            [
                'title' => 'International Media Coordination Programme',
                'summary' => 'End-to-end press coordination for an international launch weekend across three destinations and two continents.',
                'description' => "A three-city programme coordinating a press pool of forty accredited journalists across launches in Zug, Milan, and Dubai. Deliverables spanned press credentialing, hotel and transfer logistics, on-site media room production, and same-day content distribution.\n\nDelivered in partnership with the client's in-house communications team and three regional production partners.",
                'event_date' => now()->addMonths(-1)->setTime(18, 0),
                'location' => 'Zug · Milan · Dubai',
            ],
            [
                'title' => 'Private Family Journey — Mediterranean',
                'summary' => 'A discreet ten-day multi-destination itinerary for a private family, coordinated across private aviation, yachting, and cultural programming.',
                'description' => "A ten-day private itinerary spanning three Mediterranean destinations, delivered for a single family. The programme integrated private aviation, port-to-port yacht coordination, private cultural access, chef-led hospitality, and unobtrusive security throughout.\n\nDelivered with full discretion; no public communications were issued before, during, or after the programme.",
                'event_date' => now()->addMonths(-3)->setTime(10, 0),
                'location' => 'Mediterranean',
            ],
        ];

        foreach ($samples as $s) {
            Event::updateOrCreate(
                ['title' => $s['title']],
                array_merge($s, ['is_published' => true]),
            );
        }
    }
}
