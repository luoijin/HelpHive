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
