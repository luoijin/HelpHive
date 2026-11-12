<?php
declare(strict_types=1);

$opportunitiesList = $opportunities ?? [];
$keyword = $_GET['keyword'] ?? '';
$location = $_GET['location'] ?? 'Cebu, Philippines';
$selectedCause = $_GET['cause'] ?? '';
?>
<!-- 2. SEARCH OPPORTUNITIES VIEW (Figma 308:40548) -->
<section id="view-search" class="page-view active" style="display: block;">
  <div class="section-container">
    <!-- Search Header Bar -->
    <form action="/opportunities" method="GET" class="search-top-bar" id="search-filter-form">
      <div class="search-input-field keyword">
        <i data-lucide="search" class="field-icon"></i>
        <input type="text" name="keyword" id="search-keyword" value="<?= e($keyword) ?>" placeholder="Search by keyword, skill, or interest" oninput="if(typeof applyFilters === 'function') applyFilters()">
      </div>
      <div class="search-input-field location">
        <i data-lucide="map-pin" class="field-icon"></i>
        <input type="text" name="location" id="search-location" value="<?= e($location) ?>" placeholder="Location">
        <button type="button" class="clear-field-btn" onclick="clearLocation()"><i data-lucide="x"></i></button>
      </div>
      <div class="search-input-field distance">
        <select name="distance" id="filter-distance">
          <option value="">Distance</option>
          <option value="5">5 km</option>
          <option value="10">10 km</option>
          <option value="15">15 km</option>
          <option value="25">25 km</option>
          <option value="50">50 km</option>
          <option value="province">Within my province</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary search-action-btn">
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

    <!-- Result Badge & Counter -->
    <div class="results-header">
      <div class="results-badge" id="results-count-badge">
        <?= count($opportunitiesList) > 0 ? count($opportunitiesList) . ' Opportunities' : '5 Opportunities' ?>
      </div>
    </div>

    <!-- Opportunity Cards List -->
    <div class="opportunities-list" id="opportunities-container">
      <?php if (!empty($opportunitiesList)): ?>
        <?php foreach ($opportunitiesList as $opp): ?>
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
                <button type="button" class="bookmark-btn" onclick="event.stopPropagation(); toggleSaveOpportunity(event, '<?= (int) $opp['id'] ?>')">
                  <i data-lucide="bookmark"></i>
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
        <!-- Handled dynamically by app.js fallback or initial render -->
      <?php endif; ?>
    </div>

    <!-- Pagination -->
    <div class="pagination-wrap">
      <button type="button" class="pagination-btn disabled" id="prev-page"><i data-lucide="chevron-left"></i></button>
      <button type="button" class="pagination-btn active">1</button>
      <button type="button" class="pagination-btn">2</button>
      <button type="button" class="pagination-btn">3</button>
      <button type="button" class="pagination-btn">4</button>
      <button type="button" class="pagination-btn">5</button>
      <button type="button" class="pagination-btn" id="next-page"><i data-lucide="chevron-right"></i></button>
    </div>
  </div>
</section>
