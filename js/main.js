// ============================================
//   Amazing HR — Main JavaScript
//   amazinghr.org
// ============================================

document.addEventListener('DOMContentLoaded', function () {

  // ---- Navbar scroll shadow ----
  const navbar = document.querySelector('.ahr-navbar');
  if (navbar) {
    window.addEventListener('scroll', function () {
      navbar.classList.toggle('scrolled', window.scrollY > 60);
    });
  }

  // ---- Active nav link based on current page ----
  const page = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.navbar-nav .nav-link').forEach(link => {
    const href = link.getAttribute('href');
    if (href === page || (page === '' && href === 'index.html')) {
      link.classList.add('active');
    }
  });

  // ---- Fade-in on scroll (Intersection Observer) ----
  const fadeEls = document.querySelectorAll('.fade-in');
  if (fadeEls.length) {
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (e.isIntersecting) {
          e.target.classList.add('visible');
          obs.unobserve(e.target);
        }
      });
    }, { threshold: 0.12 });
    fadeEls.forEach(el => obs.observe(el));
  }

  // ---- Counter animation ----
  const counters = document.querySelectorAll('.counter');
  if (counters.length) {
    const cObs = new IntersectionObserver(entries => {
      entries.forEach(e => {
        if (!e.isIntersecting) return;
        const el     = e.target;
        const target = parseInt(el.dataset.target, 10);
        const suffix = el.dataset.suffix || '';
        const dur    = 1600;
        const step   = target / (dur / 16);
        let cur = 0;
        const timer = setInterval(() => {
          cur += step;
          if (cur >= target) { cur = target; clearInterval(timer); }
          el.textContent = Math.floor(cur) + suffix;
        }, 16);
        cObs.unobserve(el);
      });
    }, { threshold: 0.5 });
    counters.forEach(c => cObs.observe(c));
  }

  // ---- Contact form — PHP mailer submission ----
  const form = document.getElementById('contactForm');
  if (form) {
    form.addEventListener('submit', async function (e) {
      e.preventDefault();
      const btn = form.querySelector('[type="submit"]');
      btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Sending…';
      btn.disabled = true;

      const data = new FormData(form);
      try {
        const res = await fetch('send_mail.php', {
          method: 'POST',
          body: data
        });
        const json = await res.json();
        if (json.success) {
          form.style.display = 'none';
          const msg = document.getElementById('formSuccess');
          if (msg) msg.style.display = 'block';
        } else {
          btn.innerHTML = 'Send Message &nbsp;<i class="bi bi-send-fill"></i>';
          btn.disabled = false;
          alert('Something went wrong. Please try again or email us directly at dhyan@amazing-hr.com');
        }
      } catch {
        btn.innerHTML = 'Send Message &nbsp;<i class="bi bi-send-fill"></i>';
        btn.disabled = false;
        alert('Network error. Please check your connection and try again.');
      }
    });
  }

  // ---- Smooth scroll for anchor links ----
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
      const target = document.querySelector(this.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

});
