/**
 * HelpHive — Core JavaScript Application
 * Supports Figma Guest Layer and User Layer States,
 * Navigation Routing, Search & Filtering, Registration Flow, and Modals.
 */

// Sample Data extracted directly from Figma Node 391:10748 and 389:17049
const OPPORTUNITIES_DATA = [
  {
    id: 'coastal-cleanup',
    title: 'Coastal Cleanup Drive',
    org: 'Blue Shores Initiative',
    cause: 'Environment',
    type: 'On-site',
    city: 'Lapu-Lapu City',
    schedule: 'Sat, Oct 31, 2026 • 7:00–10:00 AM PHT (UTC+8)',
    time: 'Oct. 31, 2026 • 7–10 AM',
    duration: 'One day',
    goodFor: 'Groups',
    skills: 'None',
    image: '/assets/images/3f7a0de487a85a9f0d684274dbdcef0b89a3b92d.png',
    fallbackImg: 'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=600&auto=format&fit=crop&q=80',
    description: 'Collect litter along the coast to protect marine life and keep local beaches clean.',
    about: 'Collect litter along the coast to protect marine life and keep local beaches clean. Help keep our shorelines pristine and ensure aquatic biodiversity thrives.',
    tasks: [
      'Collect and sort litter along the assigned shore section.',
      'Work collaboratively with other registered volunteers.',
      'Follow the organizer’s safety and waste disposal instructions.'
    ]
  },
  {
    id: 'environmental-awareness',
    title: 'Environmental Awareness Creator',
    org: 'Green Shift Media',
    cause: 'Environment',
    type: 'Remote',
    city: 'Lapu-Lapu City',
    schedule: 'Sat, Oct 31, 2026 • 9 AM–12 PM PHT (UTC+8)',
    time: 'Oct. 31, 2026 • 9 AM–12 PM',
    duration: 'One day',
    goodFor: 'Individuals',
    skills: 'Communication',
    image: '/assets/images/eab7026a5624b86eb170b2635a52f8d87060bb16.png',
    fallbackImg: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=600&auto=format&fit=crop&q=80',
    description: 'Create a digital infographic or awareness post about recycling, plastic pollution, or environmental conservation.',
    about: 'Design educational materials and infographics that inspire the youth to reduce single-use plastic and adopt sustainable habits.',
    tasks: [
      'Draft digital illustrations or social infographics.',
      'Highlight simple actionable eco-friendly habits.',
      'Submit finalized content for Green Shift Media’s online campaigns.'
    ]
  },
  {
    id: 'animal-shelter-assistant',
    title: 'Animal Shelter Assistant',
    org: 'Paws & Care Foundation',
    cause: 'Animal Welfare',
    type: 'On-site',
    city: 'Cebu City',
    schedule: 'Sat, Oct 24, 2026 • 8 AM–12 PM PHT (UTC+8)',
    time: 'Oct. 24, 2026 • 8 AM–12 PM',
    duration: 'One day',
    goodFor: 'Beginners',
    skills: 'First Aid',
    image: '/assets/images/2209001f5575d5b3199ef6a223b2660545238113.png',
    fallbackImg: 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=600&auto=format&fit=crop&q=80',
    description: 'Help care for rescued animals by preparing food, cleaning their spaces, and giving them attention.',
    about: 'Paws & Care provides temporary shelter and medical care for rescued stray animals before finding them loving adoptive homes.',
    tasks: [
      'Prepare nutritious meal portions for shelter pets.',
      'Assist staff with cleaning and maintaining enclosure spaces.',
      'Spend social time and light exercise with dogs and cats.'
    ]
  },
  {
    id: 'community-care-companion',
    title: 'Community Care Companion',
    org: 'Kind Hearts Community',
    cause: 'Community Support',
    type: 'On-site',
    city: 'Mandaue City',
    schedule: 'Sat, Nov 7, 2026 • 8 AM–12 PM PHT (UTC+8)',
    time: 'Nov. 7, 2026 • 8 AM–12 PM',
    duration: 'One day',
    goodFor: 'Beginners',
    skills: 'Communication',
    image: '/assets/images/eabb17339acffde4c1d32b654fbd849359a5cbc5.png',
    fallbackImg: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=600&auto=format&fit=crop&q=80',
    description: 'Support older adults through friendly conversations, simple activities, and community outreach.',
    about: 'Brighten the day of seniors and elderly community members through warm companionship, storytelling, and recreation.',
    tasks: [
      'Engage in conversations and listen to senior stories.',
      'Facilitate gentle board games and crafts.',
      'Assist staff in organizing a wholesome morning tea.'
    ]
  },
  {
    id: 'weekend-reading-buddies',
    title: 'Weekend Reading Buddies',
    org: 'Bright Futures Foundation',
    cause: 'Education',
    type: 'On-site',
    city: 'Cebu City',
    schedule: 'Every Sat, Oct 24, 2026 • 7:00–10:00 AM PHT (UTC+8)',
    time: 'Oct. 24, 2026 • 7–10 AM',
    duration: 'Ongoing',
    goodFor: 'Students',
    skills: 'Teaching',
    image: '/assets/images/27e4ecfbf0890dfccab39241ba7e09523dcc1795.png',
    fallbackImg: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&auto=format&fit=crop&q=80',
    description: 'Help children build reading skills through fun learning activities and one-on-one tutoring.',
    about: 'Empower elementary school learners by providing patient, fun, and encouraging reading sessions on Saturday mornings.',
    tasks: [
      'Pair up with a student for shared reading time.',
      'Review vocabulary words and comprehension games.',
      'Encourage young readers with positive feedback.'
    ]
  }
];

// App State
let currentLayer = 'guest';
let selectedOpportunity = OPPORTUNITIES_DATA[0];
let savedOpportunities = new Set(['coastal-cleanup', 'weekend-reading-buddies']);
let myApplications = [
  {
    oppId: 'animal-shelter-assistant',
    title: 'Animal Shelter Assistant',
    org: 'Paws & Care Foundation',
    cause: 'Animal Welfare',
    location: 'Cebu City',
    date: 'Sat, Oct 24, 2026',
    status: 'Approved',
    statusNote: "You're confirmed!"
  },
  {
    oppId: 'coastal-cleanup',
    title: 'Coastal Cleanup Drive',
    org: 'Blue Shores Initiative',
    cause: 'Environment',
    location: 'Lapu-Lapu City',
    date: 'Sat, Oct 31, 2026',
    status: 'Pending Review',
    statusNote: 'Waiting for the organization to review it'
  }
];

let previousView = 'home';

// Initialize
document.addEventListener('DOMContentLoaded', () => {
  if (window.lucide) {
    lucide.createIcons();
  }
  renderOpportunitiesList();
  renderApplicationsList();
  renderSavedList();

  // Close dropdowns on outside click
  document.addEventListener('click', (e) => {
    if (!e.target.closest('.profile-trigger-wrapper')) {
      const dropdown = document.getElementById('profile-dropdown');
      if (dropdown) dropdown.classList.remove('show');
    }

    if (!e.target.closest('.filter-dropdown')) {
      document.querySelectorAll('.filter-panel').forEach(p => p.classList.remove('show'));
    }
  });

  // Handle ESC key for modals
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeModal('login-modal');
      closeModal('signup-modal');
    }
  });
});

/* ========================================================
   LAYER SWITCHER (Guest vs User)
   ======================================================== */
function switchLayer(layer) {
  currentLayer = layer;
  document.body.dataset.mode = layer;

  const btnGuest = document.getElementById('btn-layer-guest');
  const btnUser = document.getElementById('btn-layer-user');

  if (layer === 'guest') {
    btnGuest.classList.add('active');
    btnUser.classList.remove('active');
    showToast('Switched to Figma Guest Layer');
  } else {
    btnUser.classList.add('active');
    btnGuest.classList.remove('active');
    showToast('Switched to Figma User Layer (Logged in as John Smith)');
  }

  if (window.lucide) {
    lucide.createIcons();
  }
}

/* ========================================================
   ROUTING & VIEW MANAGEMENT
   ======================================================== */
function navigateTo(viewId) {
  const currentActive = document.querySelector('.page-view.active');
  if (currentActive) {
    previousView = currentActive.id.replace('view-', '');
  }

  document.querySelectorAll('.page-view').forEach(view => {
    view.classList.remove('active');
  });

  const targetView = document.getElementById(`view-${viewId}`);
  if (targetView) {
    targetView.classList.add('active');
  }

  // Update Nav Links
  document.querySelectorAll('.nav-item').forEach(link => link.classList.remove('active'));
  const activeLink = document.getElementById(`nav-link-${viewId}`);
  if (activeLink) {
    activeLink.classList.add('active');
  }

  window.scrollTo({ top: 0, behavior: 'smooth' });

  if (window.lucide) {
    lucide.createIcons();
  }
}

function historyBack() {
  navigateTo(previousView || 'home');
}

/* ========================================================
   OPPORTUNITY RENDERING & DETAILS
   ======================================================== */
function renderOpportunitiesList(data = OPPORTUNITIES_DATA) {
  const container = document.getElementById('opportunities-container');
  const countBadge = document.getElementById('results-count-badge');
  if (!container) return;

  if (countBadge) {
    countBadge.textContent = `${data.length} Opportunities`;
  }

  if (data.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 48px; background: #fff; border: 1px solid var(--slate-border); border-radius: var(--radius-lg);">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">No matching opportunities found</h3>
        <p style="color: var(--slate-muted); margin-bottom: 20px;">Try clearing filters or searching for different keywords.</p>
        <button class="btn btn-ghost" onclick="resetAllFilters()">Reset All Filters</button>
      </div>
    `;
    return;
  }

  container.innerHTML = data.map(opp => {
    const isSaved = savedOpportunities.has(opp.id);
    return `
      <article class="opportunity-card" onclick="viewOpportunityDetails('${opp.id}')">
        <div class="opp-thumbnail">
          <img src="${opp.image}" alt="${opp.title}" onerror="this.src='${opp.fallbackImg}'">
        </div>
        <div class="opp-content">
          <div class="opp-header-row">
            <div>
              <h3 class="opp-title">${opp.title}</h3>
              <span class="opp-org">${opp.org}</span>
            </div>
            <button type="button" class="bookmark-btn ${isSaved ? 'saved' : ''}" 
                    title="${isSaved ? 'Remove Bookmark' : 'Save Opportunity'}"
                    onclick="toggleSaveOpportunity(event, '${opp.id}')">
              <i data-lucide="bookmark" ${isSaved ? 'style="fill: var(--primary-yellow);"' : ''}></i>
            </button>
          </div>

          <p class="opp-desc">${opp.description}</p>

          <div class="opp-tags-row">
            <span class="tag-badge cause">${opp.cause}</span>
            <span class="tag-badge">${opp.type}</span>
            <span class="tag-meta">
              <i data-lucide="map-pin"></i> ${opp.city}
            </span>
            <span class="tag-meta">
              <i data-lucide="calendar"></i> ${opp.schedule}
            </span>
            <span class="tag-badge">Good for ${opp.goodFor.toLowerCase()}</span>
            <span class="tag-badge">${opp.duration}</span>
          </div>
        </div>
      </article>
    `;
  }).join('');

  if (window.lucide) {
    lucide.createIcons();
  }
}

function viewOpportunityDetails(oppId) {
  const opp = OPPORTUNITIES_DATA.find(o => o.id === oppId);
  if (!opp) return;

  selectedOpportunity = opp;
  const content = document.getElementById('opportunity-detail-content');
  if (!content) return;

  content.innerHTML = `
    <div class="detail-hero-banner">
      <img src="${opp.image}" alt="${opp.title}" onerror="this.src='${opp.fallbackImg}'">
    </div>
    <div class="detail-body">
      <div class="detail-header-section">
        <span class="tag-badge cause" style="margin-bottom: 12px; display: inline-block;">${opp.cause}</span>
        <h1 class="detail-title">${opp.title}</h1>
        <p class="detail-org-name">${opp.org}</p>
        <p class="opp-desc" style="font-size: 16px;">${opp.description}</p>
      </div>

      <!-- Quick Info Bar (Figma Node 308:42930) -->
      <div class="quick-info-grid">
        <div class="info-box">
          <div class="label">Time</div>
          <div class="val">${opp.time}</div>
        </div>
        <div class="info-box">
          <div class="label">Duration</div>
          <div class="val">${opp.duration}</div>
        </div>
        <div class="info-box">
          <div class="label">Skills needed</div>
          <div class="val">${opp.skills}</div>
        </div>
        <div class="info-box">
          <div class="label">Suitable for</div>
          <div class="val">${opp.goodFor}</div>
        </div>
      </div>

      <h3 class="detail-section-title">About this opportunity</h3>
      <p class="detail-text">${opp.about}</p>

      <h3 class="detail-section-title">What you'll do</h3>
      <ul class="bullet-checklist">
        ${opp.tasks.map(task => `
          <li>
            <i data-lucide="check" class="checklist-icon"></i>
            <span>${task}</span>
          </li>
        `).join('')}
      </ul>

      <!-- After the activity Notice (Figma TC_HH_011) -->
      <div class="after-activity-notice">
        <h4>After the activity</h4>
        <p>Your attendance and volunteer hours will be reviewed by the organization before they appear as verified hours in your HelpHive account.</p>
      </div>

      <!-- Action Button -->
      <div class="detail-cta-wrap">
        <button type="button" class="btn btn-cause btn-full" style="padding: 16px; font-size: 18px;" onclick="startVolunteerRegistration('${opp.id}')">
          Register Now
        </button>
      </div>
    </div>
  `;

  navigateTo('details');
}

/* ========================================================
   VOLUNTEER REGISTRATION FLOW
   ======================================================== */
function startVolunteerRegistration(oppId) {
  // If guest, prompt login or let them register smoothly
  const opp = OPPORTUNITIES_DATA.find(o => o.id === oppId);
  if (opp) {
    selectedOpportunity = opp;
    document.getElementById('reg-form-opp-title').textContent = opp.title;
    document.getElementById('reg-form-opp-org').textContent = `${opp.org} • ${opp.time}`;
  }
  navigateTo('volunteer-reg');
}

function handleRegistrationSubmit(e) {
  e.preventDefault();

  const opp = selectedOpportunity;
  // Automatically switch to User layer if in guest mode
  if (currentLayer === 'guest') {
    switchLayer('user');
  }

  // Add to applications
  myApplications.unshift({
    oppId: opp.id,
    title: opp.title,
    org: opp.org,
    cause: opp.cause,
    location: opp.city,
    date: opp.time,
    status: 'Pending Review',
    statusNote: 'Please wait while the organization reviews your registration.'
  });

  document.getElementById('confirm-opp-name').innerHTML = `
    Your registration for <strong>${opp.title}</strong> has been submitted successfully.
  `;

  renderApplicationsList();
  navigateTo('registration-confirmed');
  showToast('Registration submitted successfully!');
}

function handleOrgRegisterSubmit(e) {
  e.preventDefault();
  showToast('Organization Registration Submitted! Our team will review your application.');
  setTimeout(() => {
    navigateTo('home');
  }, 1200);
}

/* ========================================================
   APPLICATIONS & SAVED OPPORTUNITIES
   ======================================================== */
function renderApplicationsList() {
  const container = document.getElementById('applications-container');
  if (!container) return;

  container.innerHTML = myApplications.map(app => `
    <div class="application-card">
      <div class="app-meta">
        <span class="tag-badge cause" style="margin-bottom: 8px; display: inline-block;">${app.cause}</span>
        <h3>${app.title}</h3>
        <p>${app.org} • ${app.location} • ${app.date}</p>
        <p style="font-size: 13px; color: #a16207; margin-top: 4px;">${app.statusNote}</p>
      </div>
      <div class="app-side">
        <span class="status-badge ${app.status.toLowerCase().includes('pending') ? 'pending' : 'approved'}">
          ${app.status}
        </span>
        <button type="button" class="btn btn-ghost" onclick="viewOpportunityDetails('${app.oppId}')">
          View Opportunity
        </button>
      </div>
    </div>
  `).join('');

  if (window.lucide) {
    lucide.createIcons();
  }
}

function toggleSaveOpportunity(e, oppId) {
  e.stopPropagation();
  if (savedOpportunities.has(oppId)) {
    savedOpportunities.delete(oppId);
    showToast('Removed from Saved Opportunities');
  } else {
    savedOpportunities.add(oppId);
    showToast('Saved to your bookmarks!');
  }
  renderOpportunitiesList(getFilteredOpportunities());
  renderSavedList();
}

function renderSavedList() {
  const container = document.getElementById('saved-opportunities-container');
  if (!container) return;

  const savedData = OPPORTUNITIES_DATA.filter(o => savedOpportunities.has(o.id));
  if (savedData.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 48px; background: #fff; border: 1px solid var(--slate-border); border-radius: var(--radius-lg);">
        <p style="color: var(--slate-muted); font-size: 16px;">You haven't saved any opportunities yet.</p>
        <button class="btn btn-primary" style="margin-top: 16px;" onclick="navigateTo('search')">Browse Opportunities</button>
      </div>
    `;
    return;
  }

  container.innerHTML = savedData.map(opp => `
    <article class="opportunity-card" onclick="viewOpportunityDetails('${opp.id}')">
      <div class="opp-thumbnail">
        <img src="${opp.image}" alt="${opp.title}" onerror="this.src='${opp.fallbackImg}'">
      </div>
      <div class="opp-content">
        <div class="opp-header-row">
          <div>
            <h3 class="opp-title">${opp.title}</h3>
            <span class="opp-org">${opp.org}</span>
          </div>
          <button type="button" class="bookmark-btn saved" onclick="toggleSaveOpportunity(event, '${opp.id}')">
            <i data-lucide="bookmark" style="fill: var(--primary-yellow);"></i>
          </button>
        </div>
        <p class="opp-desc">${opp.description}</p>
        <div class="opp-tags-row">
          <span class="tag-badge cause">${opp.cause}</span>
          <span class="tag-badge">${opp.type}</span>
          <span class="tag-meta"><i data-lucide="map-pin"></i> ${opp.city}</span>
          <span class="tag-meta"><i data-lucide="calendar"></i> ${opp.schedule}</span>
        </div>
      </div>
    </article>
  `).join('');

  if (window.lucide) {
    lucide.createIcons();
  }
}

/* ========================================================
   SEARCH & FILTERS
   ======================================================== */
function getFilteredOpportunities() {
  const keyword = (document.getElementById('search-keyword')?.value || '').toLowerCase().trim();
  const checkedLocs = Array.from(document.querySelectorAll('input[name="locType"]:checked')).map(cb => cb.value);
  const checkedCauses = Array.from(document.querySelectorAll('input[name="causeArea"]:checked')).map(cb => cb.value);
  const checkedGoodFor = Array.from(document.querySelectorAll('input[name="goodFor"]:checked')).map(cb => cb.value);
  const checkedDuration = Array.from(document.querySelectorAll('input[name="duration"]:checked')).map(cb => cb.value);

  return OPPORTUNITIES_DATA.filter(opp => {
    // Keyword match
    if (keyword) {
      const match = opp.title.toLowerCase().includes(keyword) ||
                    opp.org.toLowerCase().includes(keyword) ||
                    opp.description.toLowerCase().includes(keyword) ||
                    opp.skills.toLowerCase().includes(keyword) ||
                    opp.cause.toLowerCase().includes(keyword);
      if (!match) return false;
    }

    // Filter checks
    if (checkedLocs.length > 0 && !checkedLocs.includes(opp.type)) return false;
    if (checkedCauses.length > 0 && !checkedCauses.includes(opp.cause)) return false;
    if (checkedGoodFor.length > 0 && !checkedGoodFor.includes(opp.goodFor)) return false;
    if (checkedDuration.length > 0 && !checkedDuration.includes(opp.duration)) return false;

    return true;
  });
}

function applyFilters() {
  const filtered = getFilteredOpportunities();
  renderOpportunitiesList(filtered);
}

function clearLocation() {
  const loc = document.getElementById('search-location');
  if (loc) loc.value = '';
}

function toggleDropdown(panelId) {
  const panel = document.getElementById(panelId);
  if (!panel) return;
  const isShown = panel.classList.contains('show');
  document.querySelectorAll('.filter-panel').forEach(p => p.classList.remove('show'));
  if (!isShown) {
    panel.classList.add('show');
  }
}

function resetFilters(groupName) {
  document.querySelectorAll(`input[name="${groupName}"]`).forEach(cb => cb.checked = false);
  applyFilters();
}

function selectAll(groupName) {
  document.querySelectorAll(`input[name="${groupName}"]`).forEach(cb => cb.checked = true);
  applyFilters();
}

function resetAllFilters() {
  const kw = document.getElementById('search-keyword');
  if (kw) kw.value = '';
  document.querySelectorAll('.filter-panel input[type="checkbox"]').forEach(cb => cb.checked = true);
  applyFilters();
}

function filterCauseAndSearch(causeName) {
  document.querySelectorAll('input[name="causeArea"]').forEach(cb => {
    cb.checked = (cb.value === causeName);
  });
  navigateTo('search');
  applyFilters();
}

/* ========================================================
   PROFILE MENU
   ======================================================== */
function toggleProfileMenu() {
  const dropdown = document.getElementById('profile-dropdown');
  const btn = document.getElementById('profile-dropdown-btn');
  if (dropdown) dropdown.classList.toggle('show');
  if (btn) btn.classList.toggle('open');
}

/* ========================================================
   MODALS (Login / Signup)
   ======================================================== */
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.add('active');
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.classList.remove('active');
}

function handleBackdropClick(e, modalId) {
  if (e.target.id === modalId) {
    closeModal(modalId);
  }
}

function switchModal(currentId, targetId) {
  closeModal(currentId);
  openModal(targetId);
}

function mockSocialLogin(provider) {
  showToast(`Logged in successfully via ${provider}!`);
  closeModal('login-modal');
  closeModal('signup-modal');
  switchLayer('user');
}

function handleLoginSubmit(e) {
  e.preventDefault();
  closeModal('login-modal');
  switchLayer('user');
  showToast('Logged in successfully as John Smith!');
}

function handleSignupSubmit(e) {
  e.preventDefault();
  closeModal('signup-modal');
  switchLayer('user');
  showToast('Account created successfully! Welcome to HelpHive.');
}

function openContactModal() {
  alert('Contact HelpHive Support:\nEmail: support@helphive.org\nPhone: +63 32 123 4567\nLocation: Cebu City, Philippines');
}

/* ========================================================
   FAQ ACCORDION
   ======================================================== */
function toggleFaq(btn) {
  const item = btn.closest('.faq-item');
  if (item) {
    item.classList.toggle('active');
  }
}

/* ========================================================
   TOAST NOTIFICATION
   ======================================================== */
function showToast(msg) {
  const toast = document.getElementById('toast');
  const text = document.getElementById('toast-text');
  if (!toast || !text) return;

  text.textContent = msg;
  toast.classList.add('show');
  setTimeout(() => {
    toast.classList.remove('show');
  }, 3500);
}
