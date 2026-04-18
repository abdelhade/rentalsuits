<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Suit Rentals ERP</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 RTL -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    <!-- FullCalendar -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary: #2b3a4a;
            --secondary: #e6b325;
            --bg-color: #f4f6f9;
            --glass-bg: rgba(255, 255, 255, 0.85);
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Tajawal', sans-serif;
            background-color: var(--bg-color);
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 0;
            right: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, #1a252f 100%);
            color: white;
            transition: 0.3s;
            z-index: 1000;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: 1px;
            color: var(--secondary);
        }

        .sidebar-menu {
            padding: 15px 0;
            list-style: none;
            margin: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        .sidebar-menu li a:hover, .sidebar-menu li.active a {
            background: rgba(255,255,255,0.1);
            color: var(--secondary);
            border-right: 4px solid var(--secondary);
        }

        .sidebar-menu li a i {
            margin-left: 15px;
            font-size: 1.2rem;
            width: 20px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-right: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: 0.3s;
        }

        /* Top Navbar */
        .topbar {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .btn-glass {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            transition: 0.3s;
        }
        
        .btn-glass:hover {
            background: rgba(255, 255, 255, 0.8);
        }

        /* Card Glassmorphism */
        .card-glass {
            background: var(--glass-bg);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-title {
            font-weight: 800;
            color: var(--primary);
            position: relative;
            padding-bottom: 10px;
        }
        
        .page-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 50px;
            height: 3px;
            background: var(--secondary);
            border-radius: 3px;
        }

        /* Datatable Styling */
        .table-custom {
            border-collapse: separate;
            border-spacing: 0 10px;
            width: 100%;
        }
        .table-custom th {
            border: none;
            background: var(--primary);
            color: white;
            padding: 15px;
            font-weight: 500;
        }
        .table-custom th:first-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        .table-custom th:last-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        .table-custom td {
            background: white;
            border: none;
            padding: 15px;
            vertical-align: middle;
            box-shadow: 0 2px 5px rgba(0,0,0,0.02);
            transition: 0.3s;
        }
        .table-custom tbody tr:hover td {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }
        .table-custom td:first-child { border-top-right-radius: 10px; border-bottom-right-radius: 10px; }
        .table-custom td:last-child { border-top-left-radius: 10px; border-bottom-left-radius: 10px; }
        
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="sidebar-brand">
            <i class="fa-solid fa-user-tie"></i> رينتالز ERP
        </div>
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="/"><i class="fa-solid fa-calendar-days"></i> التقويم والمبيعات</a>
            </li>
            <li class="{{ request()->routeIs('reports.ledger') ? 'active' : '' }}">
                <a href="/reports/ledger"><i class="fa-solid fa-book-open"></i> دفتر التأجير</a>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-users"></i> العملاء والموردين</a>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-boxes-stacked"></i> المجموعات والأصناف</a>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-file-invoice-dollar"></i> السندات (قبض/دفع)</a>
            </li>
            <li>
                <a href="#"><i class="fa-solid fa-sitemap"></i> شجرة الحسابات</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <div>
                <button class="btn btn-light" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
            </div>
            <div>
                <div class="dropdown">
                    <button class="btn btn-glass dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-user-circle"></i> المدير
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">الإعدادات</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#">تسجيل الخروج</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Content -->
        <div class="container-fluid p-4">
            @yield('content')
        </div>
    </main>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.12/dist/sweetalert2.all.min.js"></script>
    
    <script>
        // Setup Ajax CSRF
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Toggle Sidebar
        $('#sidebarToggle').click(function() {
            var sidebar = $('.sidebar');
            var mainContent = $('.main-content');
            if (sidebar.css('right') == '0px') {
                sidebar.css('right', '-260px');
                mainContent.css('margin-right', '0');
            } else {
                sidebar.css('right', '0');
                mainContent.css('margin-right', '260px');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
