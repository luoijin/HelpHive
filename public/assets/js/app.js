/**
 * HelpHive — Core JavaScript Application
 * Supports Figma Guest Layer and User Layer States,
 * Navigation Routing, Search & Filtering, Registration Flow, and Modals.
 */

// Comprehensive Opportunities Data matching Figma Node 391:10748, 389:17049, and Database Seed
const OPPORTUNITIES_DATA = [
  {
    id: 1,
    slug: 'coastal-cleanup',
    title: 'Coastal Cleanup Drive',
    org: 'Blue Shores Initiative',
    cause: 'Environment',
    type: 'On-site',
    city: 'Lapu-Lapu City, Cebu',
    schedule: 'Sat, Nov 20, 2026 • 7:00–10:00 AM PHT (UTC+8)',
    time: 'Nov 20, 2026 • 7–10 AM',
    duration: 'One day',
    goodFor: 'Groups',
    skills: 'None',
    capacity: 50,
    image: '/assets/images/3f7a0de487a85a9f0d684274dbdcef0b89a3b92d.png',
    fallbackImg: 'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=600&auto=format&fit=crop&q=80',
    description: 'Collect litter along the coast to protect marine life and keep local beaches clean.',
    about: 'Collect litter along the coast to protect marine life and keep local beaches clean. Help keep our shorelines pristine and ensure aquatic biodiversity thrives.',
    requirements: 'Wear comfortable clothes, bring drinking water and hat. Trash bags and gloves provided.',
    tasks: [
      'Collect and sort litter along the assigned shore section.',
      'Work collaboratively with other registered volunteers.',
      'Follow the organizer’s safety and waste disposal instructions.'
    ]
  },
  {
    id: 2,
    slug: 'environmental-awareness',
    title: 'Environmental Awareness Creator',
    org: 'Green Shift Media',
    cause: 'Environment',
    type: 'Remote',
    city: 'Lapu-Lapu City, Cebu',
    schedule: 'Sat, Nov 21, 2026 • 9:00 AM–12:00 PM PHT (UTC+8)',
    time: 'Nov 21, 2026 • 9 AM–12 PM',
    duration: 'One day',
    goodFor: 'Individuals',
    skills: 'Communication',
    capacity: 15,
    image: '/assets/images/eab7026a5624b86eb170b2635a52f8d87060bb16.png',
    fallbackImg: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=600&auto=format&fit=crop&q=80',
    description: 'Create a digital infographic or awareness post about recycling, plastic pollution, or environmental conservation.',
    about: 'Design educational materials and infographics that inspire the youth to reduce single-use plastic and adopt sustainable habits.',
    requirements: 'Proficiency in Canva or Adobe Express, stable internet connection.',
    tasks: [
      'Draft digital illustrations or social infographics.',
      'Highlight simple actionable eco-friendly habits.',
      'Submit finalized content for Green Shift Media’s online campaigns.'
    ]
  },
  {
    id: 3,
    slug: 'animal-shelter-assistant',
    title: 'Animal Shelter Assistant',
    org: 'Paws & Care Foundation',
    cause: 'Animal Welfare',
    type: 'On-site',
    city: 'Cebu City, Cebu',
    schedule: 'Sat, Nov 22, 2026 • 8:00 AM–12:00 PM PHT (UTC+8)',
    time: 'Nov 22, 2026 • 8 AM–12 PM',
    duration: 'One day',
    goodFor: 'Beginners',
    skills: 'First Aid',
    capacity: 20,
    image: '/assets/images/2209001f5575d5b3199ef6a223b2660545238113.png',
    fallbackImg: 'https://images.unsplash.com/photo-1548767797-d8c844163c4c?w=600&auto=format&fit=crop&q=80',
    description: 'Help care for rescued animals by preparing food, cleaning their spaces, and giving them attention and love.',
    about: 'Paws & Care provides temporary shelter and medical care for rescued stray animals before finding them loving adoptive homes.',
    requirements: 'Comfortable around cats and dogs, wear closed shoes.',
    tasks: [
      'Prepare nutritious meal portions for shelter pets.',
      'Assist staff with cleaning and maintaining enclosure spaces.',
      'Spend social time and light exercise with dogs and cats.'
    ]
  },
  {
    id: 4,
    slug: 'mangrove-tree-planting',
    title: 'Mangrove Tree Planting',
    org: 'Blue Shores Initiative',
    cause: 'Environment',
    type: 'On-site',
    city: 'Olango Island, Cebu',
    schedule: 'Sat, Nov 28, 2026 • 7:30–11:30 AM PHT (UTC+8)',
    time: 'Nov 28, 2026 • 7:30–11:30 AM',
    duration: 'One day',
    goodFor: 'Groups & Individuals',
    skills: 'Gardening',
    capacity: 40,
    image: '/assets/images/bcaa29c6960f82756e4e156fec5481a5ed10c521.png',
    fallbackImg: 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?w=600&auto=format&fit=crop&q=80',
    description: 'Plant mangrove seedlings along the intertidal zone to restore natural coastal protection barriers.',
    about: 'Mangrove forests provide vital protection against storm surges and sustain diverse coastal ecosystems. Join us for a hands-on restoration day.',
    requirements: 'Ready to walk in mud and shallow water, sunscreen and hydration recommended.',
    tasks: [
      'Transport and prepare mangrove saplings.',
      'Plant saplings along designated tidal plots.',
      'Collect non-biodegradable debris around planting zones.'
    ]
  },
  {
    id: 5,
    slug: 'weekend-reading-buddies',
    title: 'Weekend Reading Buddies',
    org: 'Bright Futures Foundation',
    cause: 'Education',
    type: 'On-site',
    city: 'Cebu City, Cebu',
    schedule: 'Sat, Nov 29, 2026 • 8:30–11:30 AM PHT (UTC+8)',
    time: 'Nov 29, 2026 • 8:30–11:30 AM',
    duration: 'Ongoing',
    goodFor: 'Students',
    skills: 'Teaching',
    capacity: 12,
    image: '/assets/images/27e4ecfbf0890dfccab39241ba7e09523dcc1795.png',
    fallbackImg: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&auto=format&fit=crop&q=80',
    description: 'Help children build reading skills through fun learning activities and one-on-one tutoring.',
    about: 'Empower elementary school learners by providing patient, fun, and encouraging reading sessions on Saturday mornings.',
    requirements: 'Patience and enthusiasm for teaching elementary kids.',
    tasks: [
      'Pair up with a student for shared reading time.',
      'Review vocabulary words and comprehension games.',
      'Encourage young readers with positive feedback.'
    ]
  },
  {
    id: 6,
    slug: 'manila-bay-shoreline',
    title: 'Manila Bay Shoreline Restoration',
    org: 'Blue Shores Initiative',
    cause: 'Environment',
    type: 'On-site',
    city: 'Manila',
    schedule: 'Sat, Dec 5, 2026 • 7:00–11:00 AM PHT (UTC+8)',
    time: 'Dec 5, 2026 • 7–11 AM',
    duration: 'One day',
    goodFor: 'Groups & Individuals',
    skills: 'None',
    capacity: 60,
    image: '/assets/images/3f7a0de487a85a9f0d684274dbdcef0b89a3b92d.png',
    fallbackImg: 'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=600&auto=format&fit=crop&q=80',
    description: 'Assist in shoreline cleanups and sorting collected recyclable debris along Roxas Boulevard.',
    about: 'Join local community volunteers to clear shoreline debris and preserve Manila Bay coastal waters for future generations.',
    requirements: 'Comfortable shoes, hydration bottle, sun protection.',
    tasks: [
      'Collect and segregate shoreline plastics and drift waste.',
      'Weigh and document sorted recyclable materials.',
      'Assist local environmental guides with cleanup staging.'
    ]
  },
  {
    id: 7,
    slug: 'youth-digital-literacy',
    title: 'Youth Digital Literacy Mentors',
    org: 'Green Shift Media',
    cause: 'Education',
    type: 'On-site',
    city: 'Quezon City',
    schedule: 'Sat, Dec 6, 2026 • 9:00 AM–1:00 PM PHT (UTC+8)',
    time: 'Dec 6, 2026 • 9 AM–1 PM',
    duration: 'One day',
    goodFor: 'Students & Beginners',
    skills: 'Communication',
    capacity: 25,
    image: '/assets/images/fa9a09b328e3ff6d275f4933939d59d043244a18.png',
    fallbackImg: 'https://images.unsplash.com/photo-1509062522246-3755977927d7?w=600&auto=format&fit=crop&q=80',
    description: 'Facilitate introductory digital skills and safe internet usage workshops for community youth.',
    about: 'Help young learners discover digital tools, basic programming concepts, and internet safety skills.',
    requirements: 'Basic computer literacy and enthusiasm for sharing knowledge.',
    tasks: [
      'Guide small groups through computer basics exercises.',
      'Help students create their first digital presentation.',
      'Answer questions and foster an encouraging environment.'
    ]
  },
  {
    id: 8,
    slug: 'davao-food-hub',
    title: 'Davao Community Food Hub & Relief',
    org: 'Paws & Care Foundation',
    cause: 'Community Support',
    type: 'On-site',
    city: 'Davao City',
    schedule: 'Sat, Dec 12, 2026 • 8:00 AM–12:00 PM PHT (UTC+8)',
    time: 'Dec 12, 2026 • 8 AM–12 PM',
    duration: 'One day',
    goodFor: 'Groups & Beginners',
    skills: 'First Aid',
    capacity: 30,
    image: '/assets/images/eabb17339acffde4c1d32b654fbd849359a5cbc5.png',
    fallbackImg: 'https://images.unsplash.com/photo-1593113598332-cd288d649433?w=600&auto=format&fit=crop&q=80',
    description: 'Help organize community food pantry packs and assist in relief meal preparation for local families.',
    about: 'Support local families through food security programs and relief pack distribution across community barangays.',
    requirements: 'Closed shoes, comfortable clothes, team spirit.',
    tasks: [
      'Pack food hampers and sort nutritional essentials.',
      'Assist in distribution check-in for registered recipients.',
      'Help maintain clean and organized staging counters.'
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

    if (!e.target.closest('#location-field-wrapper')) {
      const locPanel = document.getElementById('location-dropdown-panel');
      if (locPanel) locPanel.classList.remove('show');
    }

    if (!e.target.closest('#distance-field-wrapper')) {
      const distPanel = document.getElementById('dropdown-distance');
      if (distPanel) distPanel.classList.remove('show');
    }

    if (!e.target.closest('.custom-role-dropdown')) {
      document.querySelectorAll('.custom-role-dropdown').forEach(d => d.classList.remove('open'));
    }

    if (!e.target.closest('.filter-dropdown')) {
      document.querySelectorAll('.filter-panel:not(#location-dropdown-panel):not(#dropdown-distance)').forEach(p => p.classList.remove('show'));
    }
  });

  // Handle ESC key for modals
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeModal('opportunity-modal');
      closeModal('login-modal');
      closeModal('signup-modal');
      const locPanel = document.getElementById('location-dropdown-panel');
      if (locPanel) locPanel.classList.remove('show');
      const distPanel = document.getElementById('dropdown-distance');
      if (distPanel) distPanel.classList.remove('show');
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
function normalizeOpportunity(raw) {
  if (!raw) return null;
  const id = raw.id;
  const title = raw.title || 'Community Service Opportunity';
  const org = raw.org || raw.org_name || 'HelpHive Partner';
  const cause = raw.cause || raw.cause_area || 'Community Support';
  const type = raw.type || raw.location_type || 'On-site';
  const city = raw.city || raw.location || 'Cebu City, Philippines';
  const schedule = raw.schedule || (raw.start_at ? new Date(raw.start_at).toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' }) : 'Scheduled Event');
  const time = raw.time || (raw.start_at ? new Date(raw.start_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) : 'Upcoming');
  const duration = raw.duration || 'One day';
  const goodFor = raw.goodFor || raw.good_for || 'Individuals & Groups';
  const skills = raw.skills || 'General Volunteering';
  const capacity = raw.capacity || 25;
  const image = raw.image || raw.image_url || '/assets/images/3f7a0de487a85a9f0d684274dbdcef0b89a3b92d.png';
  const fallbackImg = raw.fallbackImg || 'https://images.unsplash.com/photo-1618477461853-cf6ed80faba5?w=600&auto=format&fit=crop&q=80';
  const description = raw.description || 'Join this meaningful opportunity to support local community initiatives.';
  const about = raw.about || raw.description || 'HelpHive connects volunteers with organizations doing vital community work.';
  const requirements = raw.requirements || 'Please bring comfortable attire and personal water bottle.';
  const tasks = raw.tasks || [
    'Participate actively in scheduled community service activities.',
    'Work collaboratively with other registered volunteers.',
    'Follow the organizer’s safety and waste disposal instructions.'
  ];

  return {
    id,
    slug: raw.slug || ('opp-' + id),
    title,
    org,
    cause,
    type,
    city,
    schedule,
    time,
    duration,
    goodFor,
    skills,
    capacity,
    image,
    fallbackImg,
    description,
    about,
    requirements,
    tasks
  };
}

function getAllOpportunities() {
  const map = new Map();
  // 1. Add static normalized opportunities
  OPPORTUNITIES_DATA.forEach(o => {
    const norm = normalizeOpportunity(o);
    map.set(String(norm.id), norm);
  });
  // 2. Add or override with server opportunities if present
  if (window.SERVER_OPPORTUNITIES && Array.isArray(window.SERVER_OPPORTUNITIES) && window.SERVER_OPPORTUNITIES.length > 0) {
    window.SERVER_OPPORTUNITIES.forEach(so => {
      const norm = normalizeOpportunity(so);
      map.set(String(norm.id), norm);
    });
  }
  return Array.from(map.values());
}

function getOpportunityById(oppId) {
  if (!oppId) return null;
  const strId = String(oppId).toLowerCase();
  const all = getAllOpportunities();
  return all.find(o => String(o.id).toLowerCase() === strId || (o.slug && o.slug.toLowerCase() === strId)) || null;
}

function renderOpportunitiesList(data) {
  const container = document.getElementById('opportunities-container');
  const countBadge = document.getElementById('results-count-badge');
  if (!container) return;

  const dataset = data !== undefined ? data : getAllOpportunities();

  if (countBadge) {
    countBadge.textContent = `${dataset.length} Opportunities`;
  }

  if (dataset.length === 0) {
    container.innerHTML = `
      <div style="text-align: center; padding: 48px; background: #fff; border: 1px solid var(--slate-border); border-radius: var(--radius-lg);">
        <h3 style="font-size: 20px; font-weight: 700; margin-bottom: 8px;">No matching opportunities found</h3>
        <p style="color: var(--slate-muted); margin-bottom: 20px;">Try clearing filters or searching for different keywords or locations.</p>
        <button class="btn btn-ghost" onclick="resetAllFilters()">Reset All Filters</button>
      </div>
    `;
    return;
  }

  container.innerHTML = dataset.map(opp => {
    const isSaved = savedOpportunities.has(opp.id) || savedOpportunities.has(opp.slug);
    return `
      <article class="opportunity-card" onclick="window.location.href='/opportunities/${opp.id}'">
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
            <span class="tag-badge">Capacity: ${opp.capacity}</span>
            <span class="tag-badge">Good for ${opp.goodFor.toLowerCase()}</span>
          </div>
        </div>
      </article>
    `;
  }).join('');

  if (window.lucide) {
    lucide.createIcons();
  }
}

function renderOpportunityDetailHtml(opp) {
  const isUser = document.body && document.body.getAttribute('data-mode') === 'user';
  const tasksList = (opp.tasks && opp.tasks.length > 0) ? opp.tasks : [
    'Participate actively in scheduled community service activities.',
    'Work collaboratively with other registered volunteers.',
    'Follow the organizer’s safety and waste disposal instructions.'
  ];

  return `
    <div class="detail-hero-banner">
      <img src="${opp.image}" alt="${opp.title}" onerror="this.src='${opp.fallbackImg}'">
    </div>
    <div class="detail-body">
      <div class="detail-header-section">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; gap: 12px;">
          <span class="tag-badge cause">${opp.cause}</span>
          <a href="/opportunities/${opp.id}" class="view-full-page-link" title="Open full detail page">
            <i data-lucide="external-link"></i> Full Page View
          </a>
        </div>
        <h1 class="detail-title">${opp.title}</h1>
        <p class="detail-org-name">${opp.org}</p>
        <p class="opp-desc" style="font-size: 16px;">${opp.description}</p>
      </div>

      <!-- Quick Info Bar (Figma Node 308:42930) -->
      <div class="quick-info-grid">
        <div class="info-box">
          <div class="label">Date & Time</div>
          <div class="val">${opp.time || opp.schedule}</div>
        </div>
        <div class="info-box">
          <div class="label">Location</div>
          <div class="val">${opp.city}</div>
        </div>
        <div class="info-box">
          <div class="label">Available Slots</div>
          <div class="val">${opp.capacity} volunteers</div>
        </div>
        <div class="info-box">
          <div class="label">Suitable for</div>
          <div class="val">${opp.goodFor}</div>
        </div>
      </div>

      <h3 class="detail-section-title">About this opportunity</h3>
      <p class="detail-text">${opp.about || opp.description}</p>

      ${opp.requirements ? `
        <h3 class="detail-section-title">Requirements & What to Bring</h3>
        <p class="detail-text">${opp.requirements}</p>
      ` : ''}

      <h3 class="detail-section-title">What you'll do</h3>
      <ul class="bullet-checklist">
        ${tasksList.map(task => `
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
        ${isUser ? `
          <form action="/opportunities/${opp.id}/register" method="POST">
            <button type="submit" class="btn btn-cause btn-full" style="padding: 16px; font-size: 18px;">
              Confirm Registration Now
            </button>
          </form>
        ` : `
          <button type="button" class="btn btn-cause btn-full" style="padding: 16px; font-size: 18px;" onclick="closeModal('opportunity-modal'); openModal('login-modal');">
            Log In to Register
          </button>
        `}
      </div>
    </div>
  `;
}

function openOpportunityDetails(oppId) {
  const opp = getOpportunityById(oppId);
  const targetId = opp ? opp.id : oppId;
  window.location.href = `/opportunities/${targetId}`;
}

function viewOpportunityDetails(oppId) {
  openOpportunityDetails(oppId);
}

/* ========================================================
   VOLUNTEER REGISTRATION FLOW
   ======================================================== */
function startVolunteerRegistration(oppId) {
  const isUser = document.body && document.body.getAttribute('data-mode') === 'user';
  if (!isUser) {
    if (typeof openModal === 'function') {
      openModal('login-modal');
    }
    showToast('Please log in to register for opportunities.');
    return;
  }
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
  const isUser = document.body && document.body.getAttribute('data-mode') === 'user';
  if (!isUser) {
    if (typeof openModal === 'function') {
      openModal('login-modal');
    }
    showToast('Please log in to save opportunities to your bookmarks.');
    return;
  }
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
   SEARCH & FILTERS (Philippine Cities, Proximity & Distance)
   ======================================================== */
const PH_REGIONS = {
  cebu: {
    province: 'Cebu',
    cities: ['cebu city', 'lapu-lapu city', 'mandaue city', 'talisay city', 'olango island'],
    distances: {
      'lapu-lapu city': { 'mandaue city': 5, 'cebu city': 10, 'olango island': 8, 'talisay city': 18 },
      'mandaue city': { 'lapu-lapu city': 5, 'cebu city': 6, 'talisay city': 14, 'olango island': 13 },
      'cebu city': { 'mandaue city': 6, 'lapu-lapu city': 10, 'talisay city': 8, 'olango island': 18 },
      'olango island': { 'lapu-lapu city': 8, 'mandaue city': 13, 'cebu city': 18, 'talisay city': 25 }
    }
  },
  ncr: {
    province: 'Metro Manila (NCR)',
    cities: ['manila', 'quezon city', 'makati city', 'taguig city', 'pasig city', 'mandaluyong city', 'pasay city', 'parañaque city'],
    distances: {
      'manila': { 'makati city': 8, 'pasay city': 6, 'quezon city': 12, 'mandaluyong city': 9, 'taguig city': 12, 'pasig city': 14 },
      'quezon city': { 'manila': 12, 'pasig city': 10, 'mandaluyong city': 10, 'makati city': 15, 'taguig city': 18 },
      'makati city': { 'taguig city': 4, 'pasay city': 5, 'mandaluyong city': 4, 'manila': 8, 'pasig city': 8, 'quezon city': 15 },
      'taguig city': { 'makati city': 4, 'pasig city': 6, 'pasay city': 7, 'mandaluyong city': 7, 'manila': 12, 'quezon city': 18 },
      'pasig city': { 'mandaluyong city': 4, 'taguig city': 6, 'makati city': 8, 'quezon city': 10, 'manila': 14 }
    }
  },
  davao: {
    province: 'Davao del Sur',
    cities: ['davao city'],
    distances: {}
  }
};

function checkDistanceMatch(oppCity, selectedLoc, distanceVal) {
  if (!distanceVal) return true; // Any Distance
  const normOpp = (oppCity || '').toLowerCase();
  const normLoc = (selectedLoc || '').toLowerCase();
  const isAny = !normLoc || normLoc === 'any' || normLoc === 'any city / province' || normLoc === 'all';

  // When location is Any and distance is selected
  if (isAny) {
    if (distanceVal === 'province' || distanceVal === '' || parseFloat(distanceVal) >= 15) {
      return true;
    }
    // For smaller distances, check against major city clusters
    return checkDistanceMatch(oppCity, 'cebu city', distanceVal) || checkDistanceMatch(oppCity, 'manila', distanceVal);
  }

  const baseLoc = normLoc;

  // Province-level filter
  if (distanceVal === 'province') {
    for (const reg of Object.values(PH_REGIONS)) {
      const matchBase = reg.cities.some(c => baseLoc.includes(c)) || baseLoc.includes(reg.province.toLowerCase());
      if (matchBase) {
        return reg.cities.some(c => normOpp.includes(c)) || normOpp.includes(reg.province.toLowerCase());
      }
    }
    return normOpp.includes(baseLoc);
  }

  const radiusKm = parseFloat(distanceVal);
  if (isNaN(radiusKm)) return true;

  // Exact same city matches within any non-zero radius
  for (const reg of Object.values(PH_REGIONS)) {
    for (const city of reg.cities) {
      if (baseLoc.includes(city) && normOpp.includes(city)) {
        return true;
      }
    }
    // Check known matrix distance
    for (const [fromCity, toMap] of Object.entries(reg.distances)) {
      if (baseLoc.includes(fromCity)) {
        for (const [toCity, dist] of Object.entries(toMap)) {
          if (normOpp.includes(toCity) && dist <= radiusKm) {
            return true;
          }
        }
      }
    }
  }

  // Fallback: if radius is 25km or 50km and same province cluster
  if (radiusKm >= 25) {
    for (const reg of Object.values(PH_REGIONS)) {
      if (reg.cities.some(c => baseLoc.includes(c)) && reg.cities.some(c => normOpp.includes(c))) {
        return true;
      }
    }
  }

  return false;
}

function getFilteredOpportunities() {
  const kwEl = document.getElementById('search-keyword');
  const keyword = (kwEl ? kwEl.value : '').toLowerCase().trim();

  const locEl = document.getElementById('search-location');
  const rawLoc = (locEl ? locEl.value : '').trim();
  const location = rawLoc.toLowerCase();
  const isAnyLocation = !location || location === 'any' || location === 'any city / province' || location === 'all';

  const distEl = document.getElementById('filter-distance');
  const distance = distEl ? distEl.value : '';

  const checkedLocs = Array.from(document.querySelectorAll('input[name="locType"]:checked')).map(cb => cb.value);
  const checkedCauses = Array.from(document.querySelectorAll('input[name="causeArea"]:checked')).map(cb => cb.value);
  const checkedGoodFor = Array.from(document.querySelectorAll('input[name="goodFor"]:checked')).map(cb => cb.value);
  const checkedDuration = Array.from(document.querySelectorAll('input[name="duration"]:checked')).map(cb => cb.value);
  const checkedSkills = Array.from(document.querySelectorAll('input[name="skills"]:checked')).map(cb => cb.value);

  const dataset = getAllOpportunities();

  return dataset.filter(opp => {
    // 1. Keyword match
    if (keyword) {
      const match = (opp.title && opp.title.toLowerCase().includes(keyword)) ||
                    (opp.org && opp.org.toLowerCase().includes(keyword)) ||
                    (opp.description && opp.description.toLowerCase().includes(keyword)) ||
                    (opp.skills && opp.skills.toLowerCase().includes(keyword)) ||
                    (opp.cause && opp.cause.toLowerCase().includes(keyword)) ||
                    (opp.city && opp.city.toLowerCase().includes(keyword));
      if (!match) return false;
    }

    // 2. City / Province match (Default is Any)
    if (!isAnyLocation && !distance) {
      const locMatch = opp.city.toLowerCase().includes(location);
      if (!locMatch) return false;
    }

    // 3. Distance match
    if (distance) {
      if (!checkDistanceMatch(opp.city, isAnyLocation ? '' : location, distance)) {
        return false;
      }
    }

    // 4. Location Type (On-site / Remote)
    if (checkedLocs.length > 0 && !checkedLocs.includes(opp.type)) return false;

    // 5. Cause Areas
    if (checkedCauses.length > 0) {
      const causeMatch = checkedCauses.some(c => opp.cause.toLowerCase().includes(c.toLowerCase()));
      if (!causeMatch) return false;
    }

    // 6. Good For
    if (checkedGoodFor.length > 0) {
      const goodMatch = checkedGoodFor.some(g => opp.goodFor.toLowerCase().includes(g.toLowerCase()));
      if (!goodMatch) return false;
    }

    // 7. Duration
    if (checkedDuration.length > 0) {
      const durMatch = checkedDuration.some(d => opp.duration.toLowerCase().includes(d.toLowerCase()));
      if (!durMatch) return false;
    }

    // 8. Skills
    if (checkedSkills.length > 0) {
      const oppSkill = (opp.skills || '').toLowerCase();
      const skillMatch = oppSkill === 'none' || checkedSkills.some(s => oppSkill.includes(s.toLowerCase()));
      if (!skillMatch) return false;
    }

    return true;
  });
}

function applyFilters() {
  const filtered = getFilteredOpportunities();
  renderOpportunitiesList(filtered);
}

function handleSearchSubmit(e) {
  if (e) {
    e.preventDefault();
    e.stopPropagation();
  }
  // Close any open dropdowns
  hideLocationDropdown();
  const distPanel = document.getElementById('dropdown-distance');
  if (distPanel) distPanel.classList.remove('show');
  document.querySelectorAll('.filter-panel').forEach(p => p.classList.remove('show'));

  // Run filters
  applyFilters();

  // Update browser URL query string without page reload
  const params = new URLSearchParams();
  const kw = document.getElementById('search-keyword')?.value.trim();
  const rawLoc = document.getElementById('search-location')?.value.trim();
  const dist = document.getElementById('filter-distance')?.value;

  if (kw) params.set('keyword', kw);
  if (rawLoc && rawLoc.toLowerCase() !== 'any' && rawLoc.toLowerCase() !== 'any city / province' && rawLoc.toLowerCase() !== 'all') {
    params.set('location', rawLoc);
  }
  if (dist) params.set('distance', dist);

  const queryString = params.toString() ? ('?' + params.toString()) : '';
  window.history.replaceState({}, '', '/opportunities' + queryString);

  // Smooth scroll to results container
  const container = document.getElementById('opportunities-container');
  if (container) {
    const yOffset = -120;
    const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
    window.scrollTo({ top: Math.max(0, y), behavior: 'smooth' });
  }
}

/* Location (City/Province Philippines) Handlers */
function showLocationDropdown() {
  const panel = document.getElementById('location-dropdown-panel');
  if (panel) panel.classList.add('show');
}

function hideLocationDropdown() {
  const panel = document.getElementById('location-dropdown-panel');
  if (panel) panel.classList.remove('show');
}

function selectLocation(val) {
  const input = document.getElementById('search-location');
  const cleanVal = (!val || val.toLowerCase() === 'any' || val.toLowerCase().includes('all')) ? 'Any' : val;
  if (input) input.value = cleanVal;
  hideLocationDropdown();

  // Update active state on location options
  document.querySelectorAll('.location-opt').forEach(btn => {
    if ((cleanVal === 'Any' && (btn.textContent.includes('Any City') || btn.textContent.includes('All Cities'))) ||
        (cleanVal !== 'Any' && btn.textContent.trim().toLowerCase() === cleanVal.toLowerCase())) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  applyFilters();
}

function clearLocation() {
  const input = document.getElementById('search-location');
  if (input) input.value = 'Any';
  const list = document.getElementById('location-options-list');
  if (list) {
    list.querySelectorAll('.location-opt, .location-group-title').forEach(el => el.style.display = '');
  }
  document.querySelectorAll('.location-opt').forEach(btn => {
    if (btn.textContent.includes('Any City') || btn.textContent.includes('All Cities')) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });
  applyFilters();
}

function handleLocationInput() {
  const input = document.getElementById('search-location');
  const query = (input?.value || '').toLowerCase().trim();
  const panel = document.getElementById('location-dropdown-panel');
  if (panel) panel.classList.add('show');

  const list = document.getElementById('location-options-list');
  if (list) {
    const opts = list.querySelectorAll('.location-opt');
    opts.forEach(opt => {
      const txt = opt.textContent.toLowerCase();
      if (!query || query === 'any' || txt.includes(query)) {
        opt.style.display = '';
      } else {
        opt.style.display = 'none';
      }
    });
  }

  applyFilters();
}

/* Distance Filter Handlers */
function selectDistance(val, label) {
  const input = document.getElementById('filter-distance');
  const lbl = document.getElementById('distance-label');
  if (input) input.value = val;
  if (lbl) lbl.textContent = label;

  document.querySelectorAll('.distance-opt').forEach(btn => {
    if ((val === '' && btn.textContent.includes('Any Distance')) ||
        (val !== '' && btn.getAttribute('onclick')?.includes(`'${val}'`))) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  const panel = document.getElementById('dropdown-distance');
  if (panel) panel.classList.remove('show');

  applyFilters();
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

  const loc = document.getElementById('search-location');
  if (loc) loc.value = 'Any';

  const dist = document.getElementById('filter-distance');
  if (dist) dist.value = '';

  const distLbl = document.getElementById('distance-label');
  if (distLbl) distLbl.textContent = 'Distance';

  document.querySelectorAll('.distance-opt').forEach(btn => {
    if (btn.textContent.includes('Any Distance')) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  document.querySelectorAll('.location-opt').forEach(btn => {
    if (btn.textContent.includes('Any City') || btn.textContent.includes('All Cities')) {
      btn.classList.add('active');
    } else {
      btn.classList.remove('active');
    }
  });

  document.querySelectorAll('.filter-panel input[type="checkbox"]').forEach(cb => {
    cb.checked = true;
  });

  const list = document.getElementById('location-options-list');
  if (list) {
    list.querySelectorAll('.location-opt, .location-group-title').forEach(el => el.style.display = '');
  }

  hideLocationDropdown();
  const distPanel = document.getElementById('dropdown-distance');
  if (distPanel) distPanel.classList.remove('show');
  document.querySelectorAll('.filter-panel').forEach(p => p.classList.remove('show'));

  applyFilters();
  window.history.replaceState({}, '', '/opportunities');
}

function filterCauseAndSearch(causeName) {
  document.querySelectorAll('input[name="causeArea"]').forEach(cb => {
    cb.checked = (cb.value === causeName);
  });
  navigateTo('search');
  applyFilters();
}

/* ========================================================
   ROLE SELECTOR DROPDOWN ("I want to join as")
   ======================================================== */
function toggleRoleDropdown(dropdownId) {
  const el = document.getElementById(dropdownId);
  if (!el) return;
  const isOpen = el.classList.contains('open');
  document.querySelectorAll('.custom-role-dropdown').forEach(d => d.classList.remove('open'));
  if (!isOpen) {
    el.classList.add('open');
  }
}

function selectRole(dropdownId, roleVal, roleTitle, roleDesc, icon) {
  const container = document.getElementById(dropdownId);
  if (!container) return;

  const prefix = dropdownId.replace('-dropdown', '');
  const input = document.getElementById(`${prefix}-input`);
  const titleEl = document.getElementById(`${prefix}-title`);
  const descEl = document.getElementById(`${prefix}-desc`);
  const iconEl = document.getElementById(`${prefix}-icon`);

  if (input) input.value = roleVal;
  if (titleEl) titleEl.textContent = roleTitle;
  if (descEl) descEl.textContent = roleDesc;
  if (iconEl) {
    iconEl.innerHTML = `<i data-lucide="${icon}"></i>`;
  }

  container.querySelectorAll('.role-dropdown-option').forEach(opt => {
    if (opt.getAttribute('data-role') === roleVal) {
      opt.classList.add('selected');
      opt.setAttribute('aria-selected', 'true');
    } else {
      opt.classList.remove('selected');
      opt.setAttribute('aria-selected', 'false');
    }
  });

  container.classList.remove('open');
  if (window.lucide) {
    lucide.createIcons();
  }
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
   MODALS (Login / Signup / Opportunity Details)
   ======================================================== */
function openModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) {
    modal.classList.add('active');
    if (window.lucide) {
      lucide.createIcons();
    }
  }
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
