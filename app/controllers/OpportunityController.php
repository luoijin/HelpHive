<?php
declare(strict_types=1);

final class OpportunityController
{
    /** GET /opportunities — Search & Browse */
    public function index(): void
    {
        $keyword = $_GET['keyword'] ?? null;
        $cause = $_GET['cause'] ?? null;
        $location = $_GET['location'] ?? null;

        $opportunities = [];
        try {
            $model = new OpportunityModel(Database::connection());
            $opportunities = $model->allPublished($keyword, $cause, $location);
        } catch (\PDOException $e) {
            error_log('Database connection error in OpportunityController@index: ' . $e->getMessage());
        }

        view('opportunities/index', [
            'title' => 'Search Opportunities — HelpHive',
            'opportunities' => $opportunities,
        ]);
    }

    /** GET /opportunities/{id} — Show details */
    public function show(int $id): void
    {
        $opp = null;
        try {
            $model = new OpportunityModel(Database::connection());
            $opp = $model->find($id);
        } catch (\PDOException $e) {
            error_log('Database connection error in OpportunityController@show: ' . $e->getMessage());
        }

        if (!$opp) {
            $fallbackList = [
                1 => [
                    'id' => 1,
                    'title' => 'Coastal Cleanup Drive',
                    'org_name' => 'Blue Shores Initiative',
                    'cause_area' => 'Environment',
                    'location' => 'Lapu-Lapu City, Cebu',
                    'start_at' => '2026-11-20 07:00:00',
                    'end_at' => '2026-11-20 10:00:00',
                    'capacity' => 50,
                    'good_for' => 'Groups',
                    'description' => 'Collect litter along the coast to protect marine life and keep local beaches clean. Help keep our shorelines pristine and ensure aquatic biodiversity thrives.',
                    'requirements' => 'Wear comfortable clothing, sunscreen, and bring water. Gloves and collection bags will be provided.'
                ],
                2 => [
                    'id' => 2,
                    'title' => 'Environmental Awareness Creator',
                    'org_name' => 'Green Shift Media',
                    'cause_area' => 'Environment',
                    'location' => 'Lapu-Lapu City, Cebu',
                    'start_at' => '2026-11-21 09:00:00',
                    'end_at' => '2026-11-21 12:00:00',
                    'capacity' => 15,
                    'good_for' => 'Individuals',
                    'description' => 'Create digital infographics or awareness posts about recycling, plastic pollution, and environmental conservation.',
                    'requirements' => 'Proficiency in Canva or Adobe Express, stable internet connection.'
                ],
                3 => [
                    'id' => 3,
                    'title' => 'Animal Shelter Assistant',
                    'org_name' => 'Paws & Care Foundation',
                    'cause_area' => 'Animal Welfare',
                    'location' => 'Cebu City, Cebu',
                    'start_at' => '2026-11-22 08:00:00',
                    'end_at' => '2026-11-22 12:00:00',
                    'capacity' => 20,
                    'good_for' => 'Beginners',
                    'description' => 'Help care for rescued animals by preparing food, cleaning their spaces, and giving them attention and love.',
                    'requirements' => 'Comfortable around cats and dogs, wear closed shoes.'
                ],
                4 => [
                    'id' => 4,
                    'title' => 'Mangrove Tree Planting',
                    'org_name' => 'Blue Shores Initiative',
                    'cause_area' => 'Environment',
                    'location' => 'Olango Island, Cebu',
                    'start_at' => '2026-11-28 07:30:00',
                    'end_at' => '2026-11-28 11:30:00',
                    'capacity' => 40,
                    'good_for' => 'Groups & Individuals',
                    'description' => 'Plant mangrove seedlings along the intertidal zone to restore natural coastal protection barriers.',
                    'requirements' => 'Ready to walk in mud and shallow water, sunscreen recommended.'
                ],
                5 => [
                    'id' => 5,
                    'title' => 'Weekend Reading Buddies',
                    'org_name' => 'Bright Futures Foundation',
                    'cause_area' => 'Education',
                    'location' => 'Cebu City, Cebu',
                    'start_at' => '2026-11-29 08:30:00',
                    'end_at' => '2026-11-29 11:30:00',
                    'capacity' => 12,
                    'good_for' => 'Students',
                    'description' => 'Help children build reading comprehension and confidence through engaging storytelling sessions.',
                    'requirements' => 'Patience and enthusiasm for teaching elementary kids.'
                ],
                6 => [
                    'id' => 6,
                    'title' => 'Manila Bay Shoreline Restoration',
                    'org_name' => 'Blue Shores Initiative',
                    'cause_area' => 'Environment',
                    'location' => 'Manila',
                    'start_at' => '2026-12-05 07:00:00',
                    'end_at' => '2026-12-05 11:00:00',
                    'capacity' => 60,
                    'good_for' => 'Groups & Individuals',
                    'description' => 'Assist in shoreline cleanups and sorting collected recyclable debris along Roxas Boulevard.',
                    'requirements' => 'Comfortable shoes, hydration bottle, sun protection.'
                ],
                7 => [
                    'id' => 7,
                    'title' => 'Youth Digital Literacy Mentors',
                    'org_name' => 'Green Shift Media',
                    'cause_area' => 'Education',
                    'location' => 'Quezon City',
                    'start_at' => '2026-12-06 09:00:00',
                    'end_at' => '2026-12-06 13:00:00',
                    'capacity' => 25,
                    'good_for' => 'Students & Beginners',
                    'description' => 'Facilitate introductory digital skills and safe internet usage workshops for community youth.',
                    'requirements' => 'Basic computer literacy and enthusiasm for sharing knowledge.'
                ],
                8 => [
                    'id' => 8,
                    'title' => 'Davao Community Food Hub & Relief',
                    'org_name' => 'Paws & Care Foundation',
                    'cause_area' => 'Community Support',
                    'location' => 'Davao City',
                    'start_at' => '2026-12-12 08:00:00',
                    'end_at' => '2026-12-12 12:00:00',
                    'capacity' => 30,
                    'good_for' => 'Groups & Beginners',
                    'description' => 'Help organize community food pantry packs and assist in relief meal preparation for local families.',
                    'requirements' => 'Closed shoes, comfortable clothes, team spirit.'
                ]
            ];
            $opp = $fallbackList[$id] ?? null;
        }

        if (!$opp) {
            http_response_code(404);
            view('opportunities/index', [
                'title' => 'Opportunity Not Found — HelpHive',
                'opportunities' => [],
            ]);
            return;
        }

        view('opportunities/show', [
            'title' => $opp['title'] . ' — HelpHive',
            'opportunity' => $opp,
        ]);
    }
}
