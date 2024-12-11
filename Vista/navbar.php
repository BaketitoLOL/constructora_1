<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: #fff;
            position: fixed;
            height: 100%;
            padding-top: 20px;
            transition: all 0.3s ease;
        }
        .sidebar.collapsed {
            width: 80px;
        }
        .sidebar h4 {
            text-align: center;
        }
        .sidebar.collapsed h4 {
            display: none;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 15px;
            transition: all 0.3s ease;
        }
        .sidebar a i {
            margin-right: 10px;
        }
        .sidebar.collapsed a i {
            margin-right: 0;
        }
        .sidebar.collapsed a span {
            display: none;
        }
        .sidebar a:hover {
            background-color: #495057;
            border-radius: 5px;
        }
        .sidebar .submenu {
            display: none;
            padding-left: 20px;
        }
        .sidebar .submenu a {
            padding: 5px 15px;
        }
        .sidebar a.active + .submenu {
            display: block;
        }
        .content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
            transition: all 0.3s ease;
        }
        .content.collapsed {
            margin-left: 80px;
        }
        .toggle-btn {
            position: absolute;
            top: 15px;
            left: 260px;
            background-color: #343a40;
            border: none;
            color: #fff;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .sidebar.collapsed + .content .toggle-btn {
            left: 90px;
        }
        #google_translate_element {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <h4>Menú</h4>
        <div id="google_translate_element"></div> <!-- Google Translate Widget -->
        <a href="admin_dashboard.php"><i class="fas fa-home"></i> <span>Inicio</span></a>
        <a href="#" class="toggle-submenu"><i class="fas fa-user-circle"></i> <span>Clientes</span></a>
        <div class="submenu">
            <a href="clientes.php"><i class="fas fa-users"></i> <span>Gestionar Clientes</span></a>
        </div>
        <a href="#" class="toggle-submenu"><i class="fas fa-briefcase"></i> <span>Sucursales</span></a>
        <div class="submenu">
            <a href="sucursales.php"><i class="fas fa-building"></i> <span>Gestionar Sucursales</span></a>
        </div>
        <a href="#" class="toggle-submenu"><i class="fas fa-users-cog"></i> <span>Empleados</span></a>
        <div class="submenu">
            <a href="empleados.php"><i class="fas fa-id-card"></i> <span>Gestionar Empleados</span></a>
        </div>
        <a href="#" class="toggle-submenu"><i class="fas fa-file-invoice-dollar"></i> <span>Presupuestos</span></a>
        <div class="submenu">
            <a href="presupuesto.php"><i class="fas fa-money-check-alt"></i> <span>Gestionar Presupuestos</span></a>
        </div>
        <a href="#" class="toggle-submenu"><i class="fas fa-wrench"></i> <span>Servicios</span></a>
        <div class="submenu">
            <a href="servicios.php"><i class="fas fa-cogs"></i> <span>Gestionar Servicios</span></a>
        </div>
        <a href="#" class="toggle-submenu"><i class="fas fa-money-bill"></i> <span>Nóminas</span></a>
        <div class="submenu">
            <a href="nomina.php"><i class="fas fa-coins"></i> <span>Gestionar Nóminas</span></a>
        </div>
        <a href="#" class="toggle-submenu"><i class="fas fa-hard-hat"></i> <span>Obras</span></a>
        <div class="submenu">
            <a href="obras.php"><i class="fas fa-clipboard-list"></i> <span>Gestionar Obras</span></a>
        </div>
    </div>

    <!-- Botón de Toggle -->
    <button class="toggle-btn" id="toggleBtn">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Google Translate API -->
    <script>
        function googleTranslateElementInit() {
            new google.translate.TranslateElement(
                {pageLanguage: 'es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE},
                'google_translate_element'
            );
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const submenuToggles = document.querySelectorAll('.toggle-submenu');

        // Toggle Sidebar
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });

        // Toggle Submenus
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                toggle.classList.toggle('active');
            });
        });
    </script>
</body>
