


<footer class=" text-center text-muted py-3 mt-5">
    <div class="container">
        <p><?php echo htmlspecialchars($translations['footer_copyright']); ?></p>
    </div>
</footer>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script>
document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('theme-toggle');
  const html = document.documentElement;
  const currentTheme = localStorage.getItem('theme') || 'light';
  if (currentTheme === 'dark') {
    html.setAttribute('data-bs-theme', 'dark');
    html.classList.add('dark');
    toggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
  } else {
    toggle.innerHTML = '<i class="bi bi-sun-fill"></i>'; 
  }
  toggle.addEventListener('click', function() {
    if (html.classList.contains('dark')) {
      html.removeAttribute('data-bs-theme');
      html.classList.remove('dark');
      localStorage.setItem('theme', 'light');
      toggle.innerHTML = '<i class="bi bi-sun-fill"></i>';
    } else {
      html.setAttribute('data-bs-theme', 'dark');
      html.classList.add('dark');
      localStorage.setItem('theme', 'dark');
      toggle.innerHTML = '<i class="bi bi-moon-fill"></i>';
    }
  });

  // Language switch buttons
  const langArBtn = document.getElementById('lang-ar');
  const langFrBtn = document.getElementById('lang-fr');

  if (langArBtn) {
    langArBtn.addEventListener('click', () => {
      const url = new URL(window.location);
      url.searchParams.set('lang', 'ar');
      window.location = url.toString();
    });
  }

  if (langFrBtn) {
    langFrBtn.addEventListener('click', () => {
      const url = new URL(window.location);
      url.searchParams.set('lang', 'fr');
      window.location = url.toString();
    });
  }

  // Language dropdown menu items
  const langDropdownItems = document.querySelectorAll('.dropdown-item[data-lang]');
  langDropdownItems.forEach(item => {
    item.addEventListener('click', (e) => {
      e.preventDefault();
      const lang = item.getAttribute('data-lang');
      const url = new URL(window.location);
      url.searchParams.set('lang', lang);
      window.location = url.toString();
    });
  });

  // Logout button
  const logoutBtn = document.getElementById('logout-btn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      logoutBtn.style.transform = 'scale(1.2)';
      setTimeout(() => {
        window.location.href = '/pbst_app/auth/logout.php';
      }, 300);
    });
  }
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
