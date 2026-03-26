</div><!-- /.d-flex (sidebar + main-content wrapper) -->

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<!-- AOS -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  // Init AOS
  AOS.init({ duration: 600, once: true });

  // Dark mode toggle
  const themeToggle = document.getElementById('themeToggle');
  if (themeToggle) {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') { document.body.classList.add('dark-mode'); themeToggle.innerHTML = '<i class="bi bi-sun-fill me-1"></i> Light Mode'; }
    themeToggle.addEventListener('click', () => {
      document.body.classList.toggle('dark-mode');
      const isDark = document.body.classList.contains('dark-mode');
      localStorage.setItem('theme', isDark ? 'dark' : 'light');
      themeToggle.innerHTML = isDark ? '<i class="bi bi-sun-fill me-1"></i> Light Mode' : '<i class="bi bi-moon-fill me-1"></i> Dark Mode';
    });
  }

  // Mobile sidebar toggle
  const sidebarToggle = document.getElementById('sidebarToggle');
  const sidebar = document.querySelector('.sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', () => sidebar.classList.toggle('open'));
  }
</script>
</body>
</html>
