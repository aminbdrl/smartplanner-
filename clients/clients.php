<?php $activePage = 'clients'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Clients · SmartWills</title>
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/sidebar.css">
    <link rel="stylesheet" href="../assets/css/topbar.css">
    <link rel="stylesheet" href="../assets/css/components.css">
    <link rel="stylesheet" href="../assets/css/clients.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <?php include '../layouts/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../layouts/topbar.php'; ?>
        <div class="content">
            <div class="page-header">
                <h1><i class="fas fa-users"></i> Client Management</h1>
                <button class="btn-total" onclick="alert('Total clients: 42')">
                    <i class="fas fa-user-friends"></i>
                    <span class="num">42</span>
                    <span class="label">Total</span>
                </button>
                <!-- Add Client id -->
                <button class="btn-primary" id="addClientBtn" style="margin-left:auto;"><i class="fas fa-plus"></i> Add Client</button>
            </div>
            <div class="toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" placeholder="Search by name / phone" id="searchInput"></div>
                <div class="filter-group">
                    <select id="filterRisk"><option value="all">All Risks</option><option value="low">Low</option><option value="moderate">Moderate</option><option value="high">High</option></select>
                    <select id="filterStatus"><option value="all">All Status</option><option value="active">Active</option><option value="done">Completed</option><option value="pending">Pending</option></select>
                </div>
            </div>
            <div class="table-wrapper">
                <div class="table-scroll">
                    <table class="client-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Contact</th>
                                <th>Risk</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th>Risk Assessment</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="clientTableBody"></tbody>
                    </table>
                </div>
            </div>
            <footer>© 2026 smartwillsplanner.com</footer>
        </div>
    </div>
</div>



<script src="../assets/js/global.js"></script>
<script src="../assets/js/clients.js"></script>
</body>
</html>