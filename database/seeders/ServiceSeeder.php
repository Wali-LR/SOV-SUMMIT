<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->data() as $i => $row) {
            $row['position']     = $i;
            $row['is_published'] = true;
            $row['published_at'] = $row['published_at'] ?? now();
            Service::updateOrCreate(['slug' => $row['slug']], $row);
        }
    }

    private function data(): array
    {
        return [
            [
                'slug'     => 'planning-coordination',
                'title'    => 'Planning and coordination for complex programmes',
                'eyebrow'  => 'PLANNING & COORDINATION',
                'summary'  => 'Successful programmes require more than individual suppliers. They require a clear plan, reliable communication, and coordination between every moving part. SOV SUMMIT supports clients from the initial brief through to delivery, helping organise the operational structure behind events, delegations, training programmes, and private experiences.',
                'description' => null,
                'hero_image' => 'assets/img/services/planning.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Initial programme briefing', 'Event concept and operational planning', 'Venue and supplier sourcing',
                    'Accommodation coordination', 'Transportation coordination', 'Private aviation coordination',
                    'Guest and participant management', 'Schedule development', 'Hospitality coordination',
                    'Catering coordination', 'Production coordination', 'Security coordination',
                    'Media coordination', 'On-site coordination', 'Post-event follow-up',
                ],
                'suitable_for_label' => 'Suitable for',
                'suitable_for' => [
                    'Corporate events', 'International conferences', 'Executive programmes', 'Institutional meetings',
                    'Delegations', 'Management training', 'Private events', 'Family trips', 'Multi-destination programmes',
                ],
                'faqs' => null,
                'seo_title' => 'Event Planning & Provider Coordination | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates event planning, suppliers, venues, accommodation, transportation, guest management, schedules, and event delivery.',
                'seo_keywords' => 'event planning, supplier coordination, venue sourcing, guest management, schedules, logistics',
            ],

            [
                'slug'    => 'management-training',
                'title'   => 'Management training designed around your objectives',
                'eyebrow' => 'MANAGEMENT TRAINING',
                'summary' => 'Effective management training should be relevant to the organisation, the participants, and the challenges they face. SOV SUMMIT coordinates bespoke management training programmes that combine learning, professional development, practical exchange, and carefully selected environments.',
                'description' => null,
                'hero_image' => 'assets/img/services/management-training.webp',
                'services_included_label' => 'Programme areas',
                'services_included' => [
                    'Leadership development', 'Executive development', 'Strategic management', 'Team development',
                    'Communication skills', 'Negotiation', 'International business', 'Decision-making',
                    'Organisational development', 'Conflict management', 'Change management', 'Cross-cultural cooperation',
                    'Executive retreats', 'Workshops and seminars', 'Experiential learning programmes',
                ],
                'suitable_for_label' => 'Programme formats',
                'suitable_for' => [
                    'Executive workshops', 'Management seminars', 'Leadership retreats', 'Team-building programmes',
                    'International learning visits', 'Corporate training events', 'Multi-day executive programmes', 'Bespoke institutional programmes',
                ],
                'faqs' => null,
                'seo_title' => 'Management Training Programmes & Executive Learning | SOV SUMMIT',
                'seo_description' => 'Bespoke management training programmes, leadership development, executive learning, team workshops, communication, negotiation, and international training experiences.',
                'seo_keywords' => 'management training, leadership development, executive learning, team workshops, negotiation',
            ],

            [
                'slug'    => 'conference-planning',
                'title'   => 'Conference planning with operational precision',
                'eyebrow' => 'CONFERENCE PLANNING',
                'summary' => 'Conferences bring together people, information, schedules, venues, technology, hospitality, and expectations. SOV SUMMIT coordinates these elements to create a structured and professional event experience.',
                'description' => null,
                'hero_image' => 'assets/img/services/conferences.webp',
                'services_included_label' => 'Conference services',
                'services_included' => [
                    'International conference planning', 'Executive forums', 'Government and institutional meetings',
                    'Corporate conferences', 'Workshops', 'Seminars', 'Venue sourcing', 'Accommodation coordination',
                    'Speaker coordination', 'Guest registration', 'Participant management', 'Technical production',
                    'Audiovisual coordination', 'Catering and hospitality', 'Transportation', 'Security coordination',
                    'Media coverage', 'On-site event management', 'Post-event documentation',
                ],
                'suitable_for_label' => null,
                'suitable_for' => null,
                'faqs' => [
                    ['q' => 'What does conference planning include?',
                     'a' => 'Conference planning can include venue sourcing, programme coordination, speaker management, participant logistics, accommodation, transportation, technical production, catering, security coordination, media coverage, and on-site delivery.'],
                    ['q' => 'Can SOV SUMMIT coordinate international conferences?',
                     'a' => 'Yes. SOV SUMMIT can coordinate the relevant providers and operational requirements for international conferences, subject to the destination, scope, availability, and client brief.'],
                ],
                'seo_title' => 'Conference Planning & Event Management | SOV SUMMIT',
                'seo_description' => 'International conference planning, executive forums, institutional meetings, venue sourcing, speakers, hospitality, technical production, and on-site coordination.',
                'seo_keywords' => 'conference planning, executive forums, institutional meetings, venue sourcing, speakers',
            ],

            [
                'slug'    => 'delegation-management',
                'title'   => 'Delegation management from arrival to departure',
                'eyebrow' => 'DELEGATION MANAGEMENT',
                'summary' => 'Delegations require accurate schedules, appropriate hospitality, reliable transportation, clear communication, and careful coordination between multiple participants and providers. SOV SUMMIT supports the operational planning of government, institutional, corporate, and executive delegations.',
                'description' => null,
                'hero_image' => 'assets/img/services/delegation.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Delegation planning', 'Participant and guest coordination', 'Private aviation coordination',
                    'Airport assistance coordination', 'Accommodation', 'Chauffeur transportation', 'Ground transportation',
                    'Protocol and hospitality', 'Meeting schedules', 'Site visits', 'Venue coordination',
                    'Restaurant reservations', 'Cultural programmes', 'Security coordination', 'Media documentation',
                    'On-site support', 'Departure coordination',
                ],
                'suitable_for_label' => 'Typical delegation requirements',
                'suitable_for' => [
                    'Executive visits', 'Institutional meetings', 'Government programmes', 'Corporate delegations',
                    'Business missions', 'Site inspections', 'International forums', 'Multi-city itineraries', 'VIP hospitality programmes',
                ],
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT manage government and institutional delegations?',
                     'a' => 'SOV SUMMIT coordinates the operational requirements of government, institutional, corporate, and executive delegations, including travel, accommodation, transportation, schedules, hospitality, site visits, and security coordination.'],
                    ['q' => 'What does delegation management include?',
                     'a' => 'Delegation management may include participant coordination, aviation, accommodation, ground transportation, protocol, meeting schedules, site visits, hospitality, security coordination, media documentation, and on-site support.'],
                ],
                'seo_title' => 'Delegation Management & International Coordination | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates government, institutional, corporate, and executive delegations, including travel, accommodation, transportation, protocol, security, and site visits.',
                'seo_keywords' => 'delegation management, protocol, government delegations, executive delegations, hospitality',
            ],

            [
                'slug'    => 'events-productions',
                'title'   => 'Events and productions that connect people',
                'eyebrow' => 'EVENTS & PRODUCTIONS',
                'summary' => 'Every event has its own purpose, audience, atmosphere, and operational requirements. SOV SUMMIT coordinates the providers and details needed to create a coherent and professionally delivered event.',
                'description' => null,
                'hero_image' => 'assets/img/services/events.webp',
                'services_included_label' => 'Event types',
                'services_included' => [
                    'Corporate events', 'Corporate celebrations', 'Concerts', 'Film festivals',
                    'Fashion shows', 'Road shows', 'Private events',
                ],
                'suitable_for_label' => null,
                'suitable_for' => null,
                'faqs' => null,
                'seo_title' => 'Corporate & International Event Organisation | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates corporate events, celebrations, concerts, film festivals, fashion shows, road shows, executive dinners, and private productions.',
                'seo_keywords' => 'corporate events, concerts, film festivals, fashion shows, road shows, private productions',
            ],

            [
                'slug'    => 'security-coordination',
                'title'   => 'Security coordination for people, venues, and programmes',
                'eyebrow' => 'SECURITY COORDINATION',
                'summary' => 'Security requirements should be considered as part of the overall programme from the beginning. SOV SUMMIT coordinates with suitable licensed security providers according to the destination, event type, participant profile, venue, schedule, and operational requirements.',
                'description' => null,
                'hero_image' => 'assets/img/services/security.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Event security coordination', 'Executive and VIP protection coordination', 'Delegation security support',
                    'Venue security coordination', 'Access and guest management', 'Security planning with approved providers',
                    'Travel security coordination', 'Arrival and departure coordination', 'Route and schedule coordination',
                    'On-site communication', 'Coordination with venue management', 'Coordination with local providers', 'Security-related logistics',
                ],
                'suitable_for_label' => 'Suitable for',
                'suitable_for' => [
                    'Executive events', 'Government and institutional delegations', 'Corporate conferences', 'Private functions',
                    'International travel programmes', 'High-profile guests', 'Multi-location events', 'Sensitive meetings',
                ],
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT provide security personnel?',
                     'a' => 'SOV SUMMIT coordinates suitable licensed security providers for events, delegations, travel, and executive programmes. The exact scope depends on local licensing requirements and the assignment.'],
                    ['q' => 'What is security coordination?',
                     'a' => 'Security coordination is the process of identifying and coordinating appropriate security providers, venue requirements, access procedures, schedules, transportation, and communication for a programme.'],
                ],
                'seo_title' => 'Event Security & Executive Protection Coordination | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates suitable licensed security providers for events, delegations, executive programmes, travel, access management, and VIP requirements.',
                'seo_keywords' => 'event security, executive protection, VIP security, delegation security, venue security',
            ],

            [
                'slug'    => 'media-coverage',
                'title'   => 'Capture the moments that matter',
                'eyebrow' => 'MEDIA COVERAGE',
                'summary' => 'Events create important moments, relationships, and messages. Professional documentation helps organisations preserve those moments and communicate their impact after the event has ended. SOV SUMMIT coordinates suitable photographers, videographers, production teams, and media partners according to the purpose and format of the programme.',
                'description' => null,
                'hero_image' => 'assets/img/services/media.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Event photography', 'Conference photography', 'Executive and delegation documentation',
                    'Video production', 'Event highlights', 'Interviews', 'Corporate communications content',
                    'Press and media coordination', 'Social media content', 'Behind-the-scenes documentation',
                    'Venue and programme coverage', 'Edited photo delivery', 'Edited video highlights', 'Post-event content packages',
                ],
                'suitable_for_label' => 'Media coverage for',
                'suitable_for' => [
                    'Conferences', 'Corporate events', 'Executive programmes', 'Delegations', 'Concerts',
                    'Film festivals', 'Fashion shows', 'Road shows', 'Private events', 'Management training programmes',
                ],
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT provide event photography?',
                     'a' => 'SOV SUMMIT coordinates professional photographers, videographers, and media teams for conferences, corporate events, delegations, productions, and private programmes.'],
                    ['q' => 'Can SOV SUMMIT arrange video highlights?',
                     'a' => "Yes. Video production and post-event highlight content can be coordinated with suitable media-production partners according to the programme's objectives."],
                ],
                'seo_title' => 'Event Photography, Video Production & Media Coverage | SOV SUMMIT',
                'seo_description' => 'SOV SUMMIT coordinates photographers, videographers, media teams, event documentation, press support, and post-event visual content.',
                'seo_keywords' => 'event photography, video production, media coverage, event documentation, press coordination',
            ],

            [
                'slug'    => 'travel-experiences',
                'title'   => 'Travel experiences, carefully coordinated',
                'eyebrow' => 'TRAVEL & EXPERIENCES',
                'summary' => 'Travel becomes more complex when several people, destinations, providers, and preferences must be coordinated at the same time. SOV SUMMIT organises the details behind private and executive travel experiences, from transportation and accommodation to restaurants, activities, special occasions, and local support.',
                'description' => null,
                'hero_image' => 'assets/img/services/travel.webp',
                'services_included_label' => 'Services included',
                'services_included' => [
                    'Private aviation coordination', 'Airport transfers', 'Chauffeur transportation', 'Accommodation coordination',
                    'Family travel planning', 'Executive travel', 'Restaurant reservations', 'Cultural experiences',
                    'Activities and excursions', 'Special occasions', 'Multi-city itineraries', 'Ground transportation',
                    'Travel schedules', 'Local provider coordination', 'On-site travel support',
                ],
                'suitable_for_label' => null,
                'suitable_for' => null,
                'faqs' => [
                    ['q' => 'Does SOV SUMMIT arrange private flights?',
                     'a' => "SOV SUMMIT coordinates private aviation requirements through appropriate aviation providers, subject to availability, destination, timing, and the client's requirements."],
                    ['q' => 'Does SOV SUMMIT organise family trips?',
                     'a' => 'Yes. SOV SUMMIT can coordinate family travel, including accommodation, transportation, child-friendly arrangements, restaurants, activities, special occasions, and multi-destination itineraries.'],
                ],
                'seo_title' => 'Private Travel, Aviation & Family Trip Coordination | SOV SUMMIT',
                'seo_description' => 'Private aviation coordination, accommodation, chauffeur transportation, family trips, restaurants, activities, and bespoke travel experiences by SOV SUMMIT.',
                'seo_keywords' => 'private aviation, family trips, chauffeur transportation, luxury travel, executive travel',
            ],
        ];
    }
}
