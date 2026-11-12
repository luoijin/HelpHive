<?php
declare(strict_types=1);

$savedList = $savedOpportunities ?? [];
?>
<!-- 7. SAVED OPPORTUNITIES VIEW (Figma 381:9517) -->
<section id="view-saved" class="page-view active" style="display: block;">
  <div class="section-container">
    <div class="section-heading">
      <h2 class="section-title">Saved Opportunities</h2>
      <p class="section-subtitle">Activities you have bookmarked for later review and participation.</p>
    </div>

    <div class="opportunities-list" id="saved-opportunities-container">
      <?php if (!empty($savedList)): ?>
        <?php foreach ($savedList as $opp): ?>
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
                <button type="button" class="bookmark-btn saved" onclick="event.stopPropagation(); toggleSaveOpportunity(event, '<?= (int) $opp['id'] ?>')">
                  <i data-lucide="bookmark" style="fill: var(--primary-yellow);"></i>
                </button>
              </div>
              <p class="opp-desc"><?= e($opp['description']) ?></p>
              <div class="opp-tags-row">
                <span class="tag-badge cause"><?= e($opp['cause_area']) ?></span>
                <span class="tag-badge"><?= e($opp['location_type'] ?? 'On-site') ?></span>
                <span class="tag-meta"><i data-lucide="map-pin"></i> <?= e($opp['location']) ?></span>
                <span class="tag-meta"><i data-lucide="calendar"></i> <?= e(date('M d, Y', strtotime($opp['start_at'] ?? 'now'))) ?></span>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <div style="text-align: center; padding: 48px; background: #fff; border: 1px solid var(--slate-border); border-radius: var(--radius-lg); width: 100%;">
          <i data-lucide="bookmark" style="width: 48px; height: 48px; stroke: var(--slate-muted); margin-bottom: 16px;"></i>
          <p style="color: var(--slate-muted); font-size: 16px; margin-bottom: 16px;">You haven't saved any opportunities yet.</p>
          <a href="/opportunities" class="btn btn-primary" style="text-decoration: none;">Browse Opportunities</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>
