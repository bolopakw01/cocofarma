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
    background: #fff;
    padding: 12px 24px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    border-radius: 0 0 16px 16px;
    position: sticky;
    top: 0;
    z-index: 1000;
    transition: all 0.3s ease;
  }

  /* Left - Breadcrumb */
  .breadcrumb {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 14px;
    color: #555;
  }

  .breadcrumb span.active {
    font-weight: 600;
    color: #2563eb;
  }

  /* Right - Info */
  .info {
    display: flex;
    align-items: center;
    gap: 20px;
    font-size: 14px;
    color: #555;
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
  @media (max-width: 768px) {
    header {
      padding: 8px 16px;
      flex-wrap: wrap;
      gap: 10px;
    }
    
    .breadcrumb {
      font-size: 12px;
      order: 3;
      width: 100%;
      margin-top: 8px;
    }
    
    .info {
      gap: 12px;
      font-size: 12px;
    }
    
    .date-time {
      display: none;
    }
  }

  @media (max-width: 480px) {
    .info .status {
      display: none;
    }
  }
</style>

<header>
  <!-- Breadcrumb -->
  <div class="breadcrumb">
    <span>🏠</span>
    @php
      $breadcrumbData = isset($breadcrumb) && is_array($breadcrumb) ? $breadcrumb : generate_breadcrumb();
    @endphp
    @foreach($breadcrumbData as $item)
      @if($loop->first)
        <span><a href="{{ $item['url'] ?? '#' }}" style="text-decoration: none; color: inherit;">{{ $item['title'] }}</a></span>
      @else
        <span>/</span>
        @if($loop->last)
          <span class="active">{{ $item['title'] }}</span>
        @else
          <span>
            @if(isset($item['url']))
              <a href="{{ $item['url'] }}" style="text-decoration: none; color: inherit;">{{ $item['title'] }}</a>
            @else
              {{ $item['title'] }}
            @endif
          </span>
        @endif
      @endif
    @endforeach
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
      <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}</div>
      <div class="user-info">
        <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        <span class="role">{{ auth()->user()->role ?? 'admin' }}</span>
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