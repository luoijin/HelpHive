<?php
declare(strict_types=1);
?>
<!-- ================= FOOTER ================= -->
<footer class="main-footer">
  <div class="footer-container">
    <!-- Col 1: Logo & Tagline -->
    <div class="footer-col brand-col">
      <div class="footer-brand">
        <span class="footer-brand-name"><span class="logo-help">Help</span><span class="logo-hive">Hive</span></span>
      </div>
      <p class="footer-tagline">Small Acts, Lasting Impact.</p>
    </div>

    <!-- Col 2: Get Involved -->
    <div class="footer-col">
      <h4 class="footer-col-title">Get Involved</h4>
      <ul class="footer-nav-list">
        <li><a href="/opportunities">Search Opportunities</a></li>
        <li><a href="/register?role=organization">Register Organization</a></li>
      </ul>
    </div>

    <!-- Col 3: Support -->
    <div class="footer-col">
      <h4 class="footer-col-title">Support</h4>
      <ul class="footer-nav-list">
        <li><a href="/#about" onclick="if(window.location.pathname !== '/') { window.location.href = '/#about'; }">About HelpHive</a></li>
        <li><a href="/#faqs" onclick="if(window.location.pathname !== '/') { window.location.href = '/#faqs'; }">FAQs</a></li>
        <li><a href="#" onclick="alert('Contact HelpHive Support:\nEmail: support@helphive.org\nPhone: +63 32 123 4567\nLocation: Cebu City, Philippines'); return false;">Contact Us</a></li>
      </ul>
    </div>
  </div>

  <!-- Footer Bottom Bar -->
  <div class="footer-bottom-bar">
    <div class="footer-bottom-container">
      <p class="copyright">&copy; <?= date('Y') ?> HelpHive. All rights reserved.</p>
      <div class="legal-links">
        <a href="#" onclick="alert('HelpHive Terms of Use'); return false;">Terms</a>
        <span class="divider">|</span>
        <a href="#" onclick="alert('HelpHive Privacy Policy'); return false;">Privacy Policy</a>
      </div>
    </div>
  </div>
</footer>
