<?php
declare(strict_types=1);

$opportunitiesList = $opportunities ?? [];
$keyword = $_GET['keyword'] ?? '';
$location = $_GET['location'] ?? '';
$selectedCause = $_GET['cause'] ?? '';
$selectedDistance = $_GET['distance'] ?? '';
$dateFrom = $_GET['date_from'] ?? '';
$dateTo = $_GET['date_to'] ?? '';

$page = (int) ($currentPage ?? 1);
$totalP = (int) ($totalPages ?? 1);
$totalNum = (int) ($totalCount ?? count($opportunitiesList));
$savedList = $savedOpportunityIds ?? [];

$queryParams = $_GET;
$buildPageUrl = function(int $p) use ($queryParams): string {
    $params = $queryParams;
    $params['page'] = $p;
    return '/opportunities?' . http_build_query($params);
};
?>
<!-- 2. SEARCH OPPORTUNITIES VIEW (Figma 308:40548) -->
<section id="view-search" class="page-view active" style="display: block;">
  <div class="section-container">
    <!-- Search Header Bar -->
    <form action="/opportunities" method="GET" class="search-top-bar" id="search-filter-form" onsubmit="handleSearchSubmit(event)">
      <div class="search-input-field keyword">
        <i data-lucide="search" class="field-icon"></i>
        <input type="text" name="keyword" id="search-keyword" value="<?= e($keyword) ?>" placeholder="Search by keyword, skill, or interest" oninput="applyFilters()">
      </div>
      
      <!-- Location with PH Cities Dropdown (Default is Any) -->
      <div class="search-input-field location filter-dropdown" id="location-field-wrapper">
        <i data-lucide="map-pin" class="field-icon"></i>
        <input type="text" name="location" id="search-location" value="<?= !empty($location) ? e($location) : 'Any' ?>" placeholder="Any City / Province" autocomplete="off" onfocus="showLocationDropdown()" onclick="showLocationDropdown()" oninput="handleLocationInput()">
        <button type="button" class="clear-field-btn" onclick="clearLocation()"><i data-lucide="x"></i></button>
        <div class="filter-panel location-dropdown-panel" id="location-dropdown-panel">
          <div class="panel-header">Select City / Province (Philippines)</div>
          <div class="location-options-list" id="location-options-list">
            <button type="button" class="location-opt <?= (empty($location) || strtolower($location) === 'any') ? 'active' : '' ?>" onclick="selectLocation('Any')"><strong>Any City / Province (All)</strong></button>
            
            <div class="location-group-title">Metro Manila (NCR)</div>
            <button type="button" class="location-opt" onclick="selectLocation('Manila')">Manila</button>
            <button type="button" class="location-opt" onclick="selectLocation('Quezon City')">Quezon City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Makati City')">Makati City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Taguig City')">Taguig City (BGC)</button>
            <button type="button" class="location-opt" onclick="selectLocation('Pasig City')">Pasig City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Mandaluyong City')">Mandaluyong City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Pasay City')">Pasay City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Parañaque City')">Parañaque City</button>
            
            <div class="location-group-title">Cebu &amp; Central Visayas</div>
            <button type="button" class="location-opt" onclick="selectLocation('Cebu City')">Cebu City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Lapu-Lapu City')">Lapu-Lapu City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Mandaue City')">Mandaue City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Talisay City')">Talisay City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Olango Island')">Olango Island, Cebu</button>
            <button type="button" class="location-opt" onclick="selectLocation('Dumaguete City')">Dumaguete City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Tagbilaran City')">Tagbilaran City</button>

            <div class="location-group-title">Western Visayas</div>
            <button type="button" class="location-opt" onclick="selectLocation('Iloilo City')">Iloilo City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Bacolod City')">Bacolod City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Roxas City')">Roxas City</button>

            <div class="location-group-title">Mindanao</div>
            <button type="button" class="location-opt" onclick="selectLocation('Davao City')">Davao City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Cagayan de Oro')">Cagayan de Oro</button>
            <button type="button" class="location-opt" onclick="selectLocation('General Santos City')">General Santos City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Zamboanga City')">Zamboanga City</button>

            <div class="location-group-title">Luzon Provinces</div>
            <button type="button" class="location-opt" onclick="selectLocation('Baguio City')">Baguio City (Benguet)</button>
            <button type="button" class="location-opt" onclick="selectLocation('Angeles City')">Angeles City (Pampanga)</button>
            <button type="button" class="location-opt" onclick="selectLocation('Antipolo')">Antipolo (Rizal)</button>
            <button type="button" class="location-opt" onclick="selectLocation('Calamba')">Calamba (Laguna)</button>
            <button type="button" class="location-opt" onclick="selectLocation('Batangas City')">Batangas City</button>
            <button type="button" class="location-opt" onclick="selectLocation('Naga City')">Naga City</button>
          </div>
        </div>
      </div>

      <!-- Distance Dropdown UI -->
      <div class="search-input-field distance filter-dropdown" id="distance-field-wrapper">
        <button type="button" class="distance-trigger-btn" id="distance-trigger-btn" onclick="toggleDropdown('dropdown-distance'); event.stopPropagation();">
          <span id="distance-label"><?= !empty($selectedDistance) ? e($selectedDistance === 'province' ? 'Within my province' : 'Within ' . $selectedDistance . ' km') : 'Distance' ?></span>
          <i data-lucide="chevron-down" class="dropdown-chevron"></i>
        </button>
        <input type="hidden" name="distance" id="filter-distance" value="<?= e($selectedDistance) ?>">
        <div class="filter-panel distance-panel" id="dropdown-distance">
          <div class="panel-header">Distance Radius</div>
          <div class="distance-options-list">
            <button type="button" class="distance-opt <?= empty($selectedDistance) ? 'active' : '' ?>" onclick="selectDistance('', 'Distance')">Any Distance</button>
            <button type="button" class="distance-opt <?= $selectedDistance === '5' ? 'active' : '' ?>" onclick="selectDistance('5', 'Within 5 km')">Within 5 km</button>
            <button type="button" class="distance-opt <?= $selectedDistance === '10' ? 'active' : '' ?>" onclick="selectDistance('10', 'Within 10 km')">Within 10 km</button>
            <button type="button" class="distance-opt <?= $selectedDistance === '15' ? 'active' : '' ?>" onclick="selectDistance('15', 'Within 15 km')">Within 15 km</button>
            <button type="button" class="distance-opt <?= $selectedDistance === '25' ? 'active' : '' ?>" onclick="selectDistance('25', 'Within 25 km')">Within 25 km</button>
            <button type="button" class="distance-opt <?= $selectedDistance === '50' ? 'active' : '' ?>" onclick="selectDistance('50', 'Within 50 km')">Within 50 km</button>
            <button type="button" class="distance-opt <?= $selectedDistance === 'province' ? 'active' : '' ?>" onclick="selectDistance('province', 'Within my province')">Within my province</button>
          </div>
        </div>
      </div>

      <!-- Date Range Filter -->
      <div class="search-input-field date-filter" style="display: flex; align-items: center; gap: 6px; padding: 0 14px;">
        <i data-lucide="calendar" class="field-icon"></i>
        <input type="date" name="date_from" id="search-date-from" value="<?= e($dateFrom) ?>" placeholder="From" title="Start date from" onchange="applyFilters()" style="border: none; outline: none; background: transparent; font-size: 13px; color: var(--slate-text);">
        <span style="color: var(--slate-muted); font-size: 12px;">to</span>
        <input type="date" name="date_to" id="search-date-to" value="<?= e($dateTo) ?>" placeholder="To" title="Start date to" onchange="applyFilters()" style="border: none; outline: none; background: transparent; font-size: 13px; color: var(--slate-text);">
      </div>

      <button type="submit" class="btn btn-primary search-action-btn" id="search-action-btn" onclick="handleSearchSubmit(event)">
        Search
      </button>
    </form>

    <!-- Filter Row with Dropdowns -->
    <div class="filters-row">
      <div class="filter-dropdown">
        <button type="button" class="filter-chip" onclick="toggleDropdown('dropdown-location-type')">
          Location Type <i data-lucide="chevron-down"></i>
        </button>
        <div class="filter-panel" id="dropdown-location-type">
          <div class="panel-header">Location Type</div>
          <label><input type="checkbox" name="locType" value="On-site" checked onchange="if(typeof applyFilters==='function') applyFilters()"> On-site</label>
          <label><input type="checkbox" name="locType" value="Remote" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Remote</label>
          <div class="panel-actions">
            <button type="button" onclick="resetFilters('locType')">Reset</button>
            <button type="button" onclick="selectAll('locType')">Select All</button>
          </div>
        </div>
      </div>

      <div class="filter-dropdown">
        <button type="button" class="filter-chip" onclick="toggleDropdown('dropdown-causes')">
          Cause Areas <i data-lucide="chevron-down"></i>
        </button>
        <div class="filter-panel" id="dropdown-causes">
          <div class="panel-header">Cause Areas</div>
          <label><input type="checkbox" name="causeArea" value="Environment" <?= ($selectedCause === 'Environment' || !$selectedCause) ? 'checked' : '' ?> onchange="if(typeof applyFilters==='function') applyFilters()"> Environment</label>
          <label><input type="checkbox" name="causeArea" value="Education" <?= ($selectedCause === 'Education' || !$selectedCause) ? 'checked' : '' ?> onchange="if(typeof applyFilters==='function') applyFilters()"> Education</label>
          <label><input type="checkbox" name="causeArea" value="Community Support" <?= ($selectedCause === 'Community Support' || !$selectedCause) ? 'checked' : '' ?> onchange="if(typeof applyFilters==='function') applyFilters()"> Community Support</label>
          <label><input type="checkbox" name="causeArea" value="Animal Welfare" <?= ($selectedCause === 'Animal Welfare' || !$selectedCause) ? 'checked' : '' ?> onchange="if(typeof applyFilters==='function') applyFilters()"> Animal Welfare</label>
          <div class="panel-actions">
            <button type="button" onclick="resetFilters('causeArea')">Reset</button>
            <button type="button" onclick="selectAll('causeArea')">Select All</button>
          </div>
        </div>
      </div>

      <div class="filter-dropdown">
        <button type="button" class="filter-chip" onclick="toggleDropdown('dropdown-goodfor')">
          Good For <i data-lucide="chevron-down"></i>
        </button>
        <div class="filter-panel" id="dropdown-goodfor">
          <div class="panel-header">Good For</div>
          <label><input type="checkbox" name="goodFor" value="Students" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Students</label>
          <label><input type="checkbox" name="goodFor" value="Beginners" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Beginners</label>
          <label><input type="checkbox" name="goodFor" value="Groups" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Groups</label>
          <label><input type="checkbox" name="goodFor" value="Individuals" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Individuals</label>
          <div class="panel-actions">
            <button type="button" onclick="resetFilters('goodFor')">Reset</button>
            <button type="button" onclick="selectAll('goodFor')">Select All</button>
          </div>
        </div>
      </div>

      <div class="filter-dropdown">
        <button type="button" class="filter-chip" onclick="toggleDropdown('dropdown-duration')">
          Duration <i data-lucide="chevron-down"></i>
        </button>
        <div class="filter-panel" id="dropdown-duration">
          <div class="panel-header">Duration</div>
          <label><input type="checkbox" name="duration" value="One day" checked onchange="if(typeof applyFilters==='function') applyFilters()"> One day</label>
          <label><input type="checkbox" name="duration" value="A few days" checked onchange="if(typeof applyFilters==='function') applyFilters()"> A few days</label>
          <label><input type="checkbox" name="duration" value="Ongoing" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Ongoing</label>
          <div class="panel-actions">
            <button type="button" onclick="resetFilters('duration')">Reset</button>
            <button type="button" onclick="selectAll('duration')">Select All</button>
          </div>
        </div>
      </div>

      <div class="filter-dropdown">
        <button type="button" class="filter-chip" onclick="toggleDropdown('dropdown-skills')">
          Skills <i data-lucide="chevron-down"></i>
        </button>
        <div class="filter-panel" id="dropdown-skills">
          <div class="panel-header">Skills</div>
          <label><input type="checkbox" name="skills" value="Teaching" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Teaching</label>
          <label><input type="checkbox" name="skills" value="Communication" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Communication</label>
          <label><input type="checkbox" name="skills" value="First Aid" checked onchange="if(typeof applyFilters==='function') applyFilters()"> First Aid</label>
          <label><input type="checkbox" name="skills" value="Gardening" checked onchange="if(typeof applyFilters==='function') applyFilters()"> Gardening</label>
          <div class="panel-actions">
            <button type="button" onclick="resetFilters('skills')">Reset</button>
            <button type="button" onclick="selectAll('skills')">Select All</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Result Badge & Counter + Reset All Filters -->
    <div class="results-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
      <div class="results-badge" id="results-count-badge">
        <?= $totalNum . ' ' . ($totalNum === 1 ? 'Opportunity' : 'Opportunities') ?>
      </div>
      <button type="button" class="btn btn-ghost btn-sm btn-reset-all" id="btn-reset-all-filters" onclick="resetAllFilters()" style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; font-size: 13px; border-radius: var(--radius-full); cursor: pointer; border: 1px solid var(--slate-border); background: #ffffff;">
        <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i> Reset All Filters
      </button>
    </div>

    <!-- Opportunity Cards List -->
    <div class="opportunities-list" id="opportunities-container">
      <?php if (!empty($opportunitiesList)): ?>
        <?php foreach ($opportunitiesList as $opp): ?>
          <?php $isSaved = in_array((int) $opp['id'], $savedList, true); ?>
          <article class="opportunity-card" onclick="window.location.href='/opportunities/<?= (int) $opp['id'] ?>'">
            <div class="opp-thumbnail">
              <img src="<?= e($opp['image_url'] ?? asset('assets/images/3f7a0de487a85a9f0d684274dbdcef0b89a3b92d.png')) ?>" alt="<?= e($opp['title']) ?>" onerror="this.src='https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=600&auto=format&fit=crop&q=80'">
            </div>
            <div class="opp-content">
              <div class="opp-header-row">
                <div>
                  <h3 class="opp-title"><?= e($opp['title']) ?></h3>
                  <span class="opp-org"><?= e($opp['org_name'] ?? 'HelpHive Partner') ?></span>
                </div>
                <button type="button" class="bookmark-btn <?= $isSaved ? 'saved' : '' ?>" 
                        title="<?= $isSaved ? 'Remove Bookmark' : 'Save Opportunity' ?>" 
                        onclick="event.stopPropagation(); toggleSaveOpportunity(event, '<?= (int) $opp['id'] ?>')">
                  <i data-lucide="bookmark" <?= $isSaved ? 'style="fill: var(--primary-yellow);"' : '' ?>></i>
                </button>
              </div>

              <p class="opp-desc"><?= e($opp['description']) ?></p>

              <div class="opp-tags-row">
                <span class="tag-badge cause"><?= e($opp['cause_area']) ?></span>
                <span class="tag-badge"><?= e($opp['location_type'] ?? 'On-site') ?></span>
                <span class="tag-meta">
                  <i data-lucide="map-pin"></i> <?= e($opp['location']) ?>
                </span>
                <span class="tag-meta">
                  <i data-lucide="calendar"></i> <?= e(date('M d, Y', strtotime($opp['start_at'] ?? 'now'))) ?>
                </span>
                <span class="tag-badge">Capacity: <?= (int) ($opp['capacity'] ?? 0) ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="empty-state" style="text-align: center; color: var(--slate-muted); padding: 48px 16px; background: #fff; border: 1px solid var(--slate-border); border-radius: var(--radius-lg); width: 100%;">
          <h3 style="font-size: 18px; font-weight: 600; margin-bottom: 8px;">No volunteering opportunities found</h3>
          <p style="margin-bottom: 16px;">Try adjusting your search criteria, dates, or clearing filters.</p>
        </div>
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrap" id="pagination-controls">
      <?php if ($page > 1): ?>
        <a href="<?= e($buildPageUrl($page - 1)) ?>" class="pagination-btn" id="prev-page" title="Previous Page"><i data-lucide="chevron-left"></i></a>
      <?php else: ?>
        <button type="button" class="pagination-btn disabled" id="prev-page" title="Previous Page"><i data-lucide="chevron-left"></i></button>
      <?php endif; ?>

      <?php for ($p = 1; $p <= $totalP; $p++): ?>
        <a href="<?= e($buildPageUrl($p)) ?>" class="pagination-btn <?= $p === $page ? 'active' : '' ?>" style="text-decoration: none;"><?= $p ?></a>
      <?php endfor; ?>

      <?php if ($page < $totalP): ?>
        <a href="<?= e($buildPageUrl($page + 1)) ?>" class="pagination-btn" id="next-page" title="Next Page"><i data-lucide="chevron-right"></i></a>
      <?php else: ?>
        <button type="button" class="pagination-btn disabled" id="next-page" title="Next Page"><i data-lucide="chevron-right"></i></button>
      <?php endif; ?>
    </div>
  </div>

  <!-- OPPORTUNITY DETAILS MODAL (Figma Node 308:42930 / 381:11170) -->
  <div class="modal-backdrop" id="opportunity-modal" onclick="handleBackdropClick(event, 'opportunity-modal')">
    <div class="modal-card modal-card-opportunity" id="modal-opportunity-card">
      <button type="button" class="modal-close-btn" onclick="closeModal('opportunity-modal')" aria-label="Close modal">
        <i data-lucide="x"></i>
      </button>
      <div id="opportunity-detail-content">
        <!-- Dynamically injected when an opportunity is clicked -->
      </div>
    </div>
  </div>

  <script>
    window.SERVER_OPPORTUNITIES = <?= json_encode($opportunitiesList) ?>;
    window.SAVED_OPPORTUNITY_IDS = <?= json_encode($savedList) ?>;
    window.SERVER_PAGE = <?= (int) $page ?>;
    window.TOTAL_PAGES = <?= (int) $totalP ?>;
  </script>
</section>
