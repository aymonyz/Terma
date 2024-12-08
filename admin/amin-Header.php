
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #020304;
            font-family: 'Tajawal', sans-serif;
        }

        .navbar {
            background-color: #020304;
            color: #fff;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
        }

        .navbar a:hover {
            color: #ffc107;
        }

        .navbar .username {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .logout-btn {
            background-color: #dc3545;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: #fff;
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }

        .main-content {
            padding: 30px;
        }

        .btn-success,
        .btn-warning,
        .btn-delete {
            border-radius: 5px;
            font-size: 0.9rem;
        }

        .btn-delete {
            background-color: #dc3545;
            color: #fff;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .btn-warning:hover {
            background-color: #ffc107;
        }

        table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        table thead th {
            background-color: #343a40;
            color: #fff;
            text-align: center;
        }

        table tbody tr {
            background-color: #fff;
            transition: transform 0.2s ease;
        }

        table tbody tr:hover {
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table td,
        table th {
            vertical-align: middle;
            text-align: center;
        }

        .modal-header {
            background-color: #007bff;
            color: #fff;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>

    <!-- Navbar -->
    <div class="navbar">
        <div class="d-flex align-items-center">
            <a href="admin_dashboard.php" class="d-flex align-items-center">
                <i class="fas fa-home me-2"></i> لوحة القيادة
            </a>
            <a href="admin_user.php" class="d-flex align-items-center">
                <i class="fas fa-users me-2"></i> إدارة المستخدمين
            </a>
            <a href="manage_requests.php" class="d-flex align-items-center">
                <i class="fas fa-tasks me-2"></i>   التصنيفات
            </a>
            <a href="../index.php" class="d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> العودة
            </a>
            
        </div>
        <div class="d-flex align-items-center">
            <span class="username"><i class="fas fa-user-circle me-2"></i> مرحباً، المدير</span>
            <a href="?logout=true" class="logout-btn ms-3"><i class="fas fa-sign-out-alt me-2"></i> تسجيل الخروج</a>
        </div>
    </div>



    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

