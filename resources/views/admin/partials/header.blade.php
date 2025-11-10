<style>
  /* inherit global font from layout */
  body {
    margin: 0;
    font-family: inherit;
    background: #f8fafc;
  }

  header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    background: #fff;
    padding: 12px 24px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    border-radius: 0 0 16px 16px;
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
    flex-wrap: nowrap;
  }

  .header-left {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1 1 auto;
    min-width: 0;
  }

  .mobile-sidebar-toggle {
    display: none;
    width: 40px;
    height: 40px;
    border-radius: 12px;
    border: 1px solid rgba(37, 99, 235, 0.15);
    background: #f1f5f9;
    color: #1f2937;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease, transform 0.2s ease;
  }

  .mobile-sidebar-toggle i {
    font-size: 20px;
    line-height: 1;
  }

  .mobile-sidebar-toggle:hover,
  .mobile-sidebar-toggle:focus-visible {
    background: #e2e8f0;
    outline: none;
    transform: translateY(-1px);
  }

  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #555;
    flex-wrap: nowrap;
    min-width: 0;
    flex: 1 1 auto;
    line-height: 1.3;
  }

  .breadcrumb-item {
    display: inline-block;
    max-width: clamp(120px, 45vw, 220px);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
  }

  .breadcrumb-item:first-of-type {
    max-width: clamp(100px, 30vw, 160px);
  }

  .breadcrumb-item a {
    color: inherit;
    text-decoration: none;
    display: inline-block;
    max-width: 100%;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .breadcrumb-item.active {
    font-weight: 600;
    color: #2563eb;
  }

  .breadcrumb-separator {
    white-space: nowrap;
    color: rgba(85, 85, 85, 0.75);
  }

  /* Right - Info */
  .info {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 14px;
    color: #555;
    flex: 0 0 auto;
    margin-left: auto;
    flex-wrap: nowrap;
  }

  .status {
    display: flex;
    align-items: center;
    gap: 6px;
    color: #16a34a;
    font-weight: 500;
  }

  .status-dot {
    width: 8px;
    height: 8px;
    background: #16a34a;
    border-radius: 50%;
  }

  /* User */
  .user {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    position: relative;
  }

  .user-avatar {
    width: 36px;
    height: 36px;
    background: #3b82f6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
  }

  .user-info {
    display: flex;
    flex-direction: column;
    font-size: 13px;
    line-height: 1.2;
  }

  .user-info .role {
    font-size: 12px;
    color: #777;
  }

  /* Dropdown */
  .dropdown {
    display: none;
    position: absolute;
    top: 48px;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    overflow: hidden;
    z-index: 1001;
  }

  .dropdown a {
    display: block;
    padding: 10px 16px;
    font-size: 14px;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
  }

  .dropdown a:hover {
    background: #f1f5f9;
  }

  .dropdown a.logout {
    color: #ef4444;
    font-weight: 500;
  }

  .dropdown.show {
    display: block;
    animation: fadeIn 0.2s ease-in-out;
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
  }

  /* Responsive Design */
  @media (max-width: 992px) {
    .mobile-sidebar-toggle {
      display: inline-flex;
    }
  }

  @media (max-width: 992px) {
    header {
      padding: 10px 16px;
      gap: 12px;
    }

    .header-left {
      gap: 10px;
    }

    .info {
      margin-left: auto;
      gap: 16px;
    }

    .date-time {
      display: none;
    }
  }

  @media (max-width: 640px) {
    .info {
      gap: 10px;
    }

    .info .status {
      display: none;
    }

    .breadcrumb {
      display: none;
    }
  }

  @media (max-width: 480px) {
    .user-info {
      display: none;
    }

    .user {
      gap: 4px;
    }
  }

  @media (max-width: 375px) {
    header {
      padding: 8px 12px;
    }

    .mobile-sidebar-toggle {
      width: 36px;
      height: 36px;
    }

    .breadcrumb .active {
      max-width: clamp(100px, 40vw, 160px);
    }
  }
</style>

<header>
  <div class="header-left">
    <button class="mobile-sidebar-toggle" type="button" aria-label="Toggle sidebar" data-sidebar-toggle>
      <i class="bx bx-menu"></i>
    </button>
    <!-- Breadcrumb -->
    <div class="breadcrumb">
      <span>🏠</span>
        @php
          $breadcrumbData = isset($breadcrumb) && is_array($breadcrumb) ? $breadcrumb : generate_breadcrumb();
          // Compute first name and initial for user display
          $fullName = trim(auth()->user()->name ?? 'Admin');
          $parts = preg_split('/\s+/', $fullName);
          $firstName = $parts[0] ?? $fullName;
          $firstInitial = strtoupper(substr($firstName, 0, 1) ?: 'A');

          // Map role values to nicer display labels
          $rawRole = auth()->user()->role ?? 'admin';
          $roleKey = strtolower(trim($rawRole));
          if ($roleKey === 'admin' || $roleKey === 'administrator') {
            $displayRole = 'Administrator';
          } elseif (in_array($roleKey, ['super admin', 'super_admin', 'superadmin', 'super-admin'], true)) {
            $displayRole = 'Super Admin';
          } else {
            // Fallback: make it readable (replace underscores/dashes and title-case)
            $displayRole = ucwords(str_replace(['_', '-'], ' ', $roleKey));
          }
        @endphp
      @foreach($breadcrumbData as $item)
        @if($loop->first)
          <span class="breadcrumb-item"><a href="{{ $item['url'] ?? '#' }}">{{ $item['title'] }}</a></span>
        @else
          <span class="breadcrumb-separator">/</span>
          @if($loop->last)
            <span class="breadcrumb-item active" aria-current="page">{{ $item['title'] }}</span>
          @else
            <span class="breadcrumb-item">
              @if(isset($item['url']))
                <a href="{{ $item['url'] }}">{{ $item['title'] }}</a>
              @else
                {{ $item['title'] }}
              @endif
            </span>
          @endif
        @endif
      @endforeach
    </div>
  </div>

  <!-- Right Info -->
  <div class="info">
    <div class="date-time">
      <span id="date"></span> | <span id="clock"></span>
    </div>
    <div class="status">
      <div class="status-dot"></div>
      Online
    </div>
    <div class="user" id="userMenu">
      <div class="user-avatar">{{ $firstInitial }}</div>
      <div class="user-info">
        <span>{{ $firstName }}</span>
  <span class="role">{{ $displayRole }}</span>
      </div>

      <!-- Dropdown -->
      <div class="dropdown" id="dropdownMenu">
        <a href="#">👤 Profile</a>
        <a href="#" class="logout" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">🚪 Logout</a>
      </div>
    </div>
  </div>
</header>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('backoffice.logout') }}" method="POST" style="display: none;">
  @csrf
</form>

<script>
  // Update date & clock
  function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
    document.getElementById("date").textContent = now.toLocaleDateString('en-US', options);
    document.getElementById("clock").textContent = now.toLocaleTimeString();
  }
  setInterval(updateDateTime, 1000);
  updateDateTime();

  // Dropdown toggle
  const userMenu = document.getElementById("userMenu");
  const dropdownMenu = document.getElementById("dropdownMenu");

  userMenu.addEventListener("click", () => {
    dropdownMenu.classList.toggle("show");
  });

  // Klik di luar dropdown untuk menutup
  document.addEventListener("click", (e) => {
    if (!userMenu.contains(e.target)) {
      dropdownMenu.classList.remove("show");
    }
  });

  // Enhanced sticky header effect
  window.addEventListener('scroll', function() {
    const header = document.querySelector('header');
    if (window.scrollY > 0) {
      header.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
      header.style.borderRadius = '0';
    } else {
      header.style.boxShadow = '0 2px 6px rgba(0,0,0,0.08)';
      header.style.borderRadius = '0 0 16px 16px';
    }
  });
</script>