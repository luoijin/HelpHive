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
        $distance = $_GET['distance'] ?? null;
        $dateFrom = $_GET['date_from'] ?? null;
        $dateTo = $_GET['date_to'] ?? null;

        $page = max(1, (int) ($_GET['page'] ?? 1));
        $perPage = 6;
        $total = 0;
        $totalPages = 1;
        $opportunities = [];

        try {
            $model = new OpportunityModel(Database::connection());
            $total = $model->countPublished($keyword, $cause, $location, $dateFrom, $dateTo);
            $totalPages = max(1, (int) ceil($total / $perPage));
            $page = min($page, $totalPages);
            $offset = ($page - 1) * $perPage;
            $opportunities = $model->allPublished($keyword, $cause, $location, $dateFrom, $dateTo, $perPage, $offset);
        } catch (\PDOException $e) {
            error_log('Database connection error in OpportunityController@index: ' . $e->getMessage());
        }

        $user = Auth::user();
        $savedIds = [];
        if ($user !== null) {
            try {
                $savedModel = new SavedOpportunityModel(Database::connection());
                $savedIds = $savedModel->savedIdsForUser((int) $user['id']);
            } catch (\PDOException $e) {
                error_log('Database error loading saved opportunities: ' . $e->getMessage());
            }
        }

        view('opportunities/index', [
            'title' => 'Search Opportunities — HelpHive',
            'opportunities' => $opportunities,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalCount' => $total,
            'perPage' => $perPage,
            'savedOpportunityIds' => $savedIds,
        ]);
    }

    /** GET /opportunities/{id} — Show details */
    public function show(int $id): void
    {
        $opp = null;
        try {
            $model = new OpportunityModel(Database::connection());
            $opp = $model->findPublished($id);
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

        $user = Auth::user();
        $isSaved = false;
        if ($user !== null) {
            try {
                $savedModel = new SavedOpportunityModel(Database::connection());
                $isSaved = $savedModel->isSaved((int) $user['id'], $id);
            } catch (\PDOException $e) {
                error_log('Database error checking saved opportunity: ' . $e->getMessage());
            }
        }

        view('opportunities/show', [
            'title' => $opp['title'] . ' — HelpHive',
            'opportunity' => $opp,
            'isSaved' => $isSaved,
        ]);
    }
}
