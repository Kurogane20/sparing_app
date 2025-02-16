<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">
  <link rel="icon" href="{{ asset('pngegg.png') }}" type="image/x-icon">
  <script src="{{asset ('assets/plugins/chart.js/Chart.min.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom@0.7.7"></script>
  <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
  {{-- <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script> --}}
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('home') }}" class="nav-link">Home</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">Logout</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Navbar Search -->
      {{-- <li class="nav-item">
        <a class="nav-link" data-widget="navbar-search" href="#" role="button">
          <i class="fas fa-search"></i>
        </a>
        <div class="navbar-search-block">
          <form class="form-inline">
            <div class="input-group input-group-sm">
              <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                  <i class="fas fa-search"></i>
                </button>
                <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                  <i class="fas fa-times"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
      </li> --}}

      <!-- Messages Dropdown Menu -->
      
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
          <a class="nav-link" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="far fa-bell"></i>
              <span id="notificationBadge" class="badge badge-warning navbar-badge">0</span>
          </a>
          <div id="notificationMenu" class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-header">0 Notifications</span>
              <div id="notificationItems"></div>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
          </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('home') }}" class="brand-link">
      <img src="{{ asset('assets/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ $title ?? "SPARING APP" }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-1 pb-1 mb-1 d-flex">
        {{-- <div class="image">
          <img src="{{ asset('assets/dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div> --}}
        <div class="info">
          <a href="#" class="d-block">Welcome, {{ auth()->user()->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item ">
            <a href="home" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard                
              </p>
            </a>            
          </li>
          <li class="nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <a href="{{ route('profile') }}" class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
              <i class="nav-icon fas fa-building"></i>
              <p>
                Data Perusahaan
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
          @if (Auth::user()->role == 'admin')
          <li class="nav-item {{ request()->routeIs('users.index') ? 'active' : '' }}">
            <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user"></i>
              <p>
                User Management
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
          @endif
          <li class="nav-item {{ request()->routeIs('view-data.index') ? 'active' : '' }}">
            <a href="{{ route('view-data.index') }}" class="nav-link {{ request()->routeIs('view-data.index') ? 'active' : '' }}">
              <i class="nav-icon fas fa-print"></i>
              <p>
                Monitoring Data
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  @yield('content')

  <!-- /.content-wrapper -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2025 <a href="#">Mitra Mutiara</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->


<!-- jQuery -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
{{-- <script src="{{ asset('assets/dist/js/demo.js') }}"></script> --}}
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script>
    const pusher = new Pusher('7f90a15862d5a61ba889', {
        cluster: 'ap1',
        encrypted: true,
        forceTLS: true,

    });

    const channel = pusher.subscribe('data-channel');
    console.log('Pusher initialized and channel subscribed');
    const notificationBadge = document.getElementById('notificationBadge');
    const notificationItems = document.getElementById('notificationItems');
    const notificationHeader = document.querySelector('#notificationMenu .dropdown-header');

    let notificationCount = 0;

    channel.bind('data.exceeded', function(data) {
      console.log('Event received:', data);
      console.log('Data exceeded threshold:', data);
        // Update notification count
        notificationCount++;
        notificationBadge.textContent = notificationCount;
        notificationHeader.textContent = `${notificationCount} Notifications`;

        // Create new notification item
        const notificationItem = document.createElement('a');
        notificationItem.href = '#';
        notificationItem.className = 'dropdown-item';
        notificationItem.innerHTML = `
            <div class="media">
                <div class="media-body">
                    <h3 class="dropdown-item-title">
                        Data Exceeded Threshold
                        <span class="float-right text-sm text-danger"><i class="fas fa-exclamation-circle"></i></span>
                    </h3>
                    <p class="text-sm">${data.message}</p>
                    <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> Just now</p>
                </div>
            </div>
        `;

        // Add the new notification to the top of the list
        notificationItems.insertBefore(notificationItem, notificationItems.firstChild);

        // Show alert (optional)
        alert(data.message);
    });

    // Clear notifications when "See All Notifications" is clicked
    document.querySelector('.dropdown-footer').addEventListener('click', function(e) {
        e.preventDefault();
        notificationCount = 0;
        notificationBadge.textContent = '0';
        notificationHeader.textContent = '0 Notifications';
        notificationItems.innerHTML = ''; // Clear all notifications
    });
</script>
</body>
</html>
