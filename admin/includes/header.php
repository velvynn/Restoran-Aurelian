<?php
// admin/includes/header.php
if (!isset($page_title)) {
    $page_title = 'Admin Panel';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #0b3b2e;
            --secondary-color: #0f5132;
            --accent-color: #d4af37;
            --light-color: #f8f9fa;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        
        /* ===================== SIDEBAR STYLING UNTUK SEMUA MENU ===================== */
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            min-height: 100vh;
            position: fixed;
            width: 280px;
            box-shadow: 2px 0 20px rgba(0,0,0,0.15);
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-header h3 {
            margin: 15px 0 5px;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .sidebar-header p {
            margin: 5px 0;
            color: rgba(255,255,255,0.9);
            font-size: 0.9rem;
        }
        
        .sidebar-header small {
            display: inline-block;
            background: var(--accent-color);
            color: var(--primary-color);
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-top: 8px;
        }
        
        /* Menu Container */
        .sidebar-menu {
            list-style: none;
            padding: 20px 10px;
            margin: 0;
            flex: 1;
            overflow-y: auto;
        }
        
        .sidebar-menu li {
            margin: 8px 0;
        }
        
        /* Menu Item Styling - SEMUA MENU SAMA */
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            color: rgba(255, 255, 255, 0.85);
            text-decoration: none;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 12px;
            margin: 0 10px;
            border: 1px solid transparent;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        /* Left Border untuk active */
        .sidebar-menu li a::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--accent-color);
            transform: scaleY(0);
            transition: transform 0.3s ease;
            transform-origin: bottom;
        }
        
        .sidebar-menu li.active a::before {
            transform: scaleY(1);
        }
        
        /* Hover Effect */
        .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(8px);
            border-color: rgba(212, 175, 55, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        /* Active Menu Styling */
        .sidebar-menu li.active a {
            background: linear-gradient(135deg, rgba(212, 175, 55, 0.15), rgba(11, 59, 46, 0.3));
            border-color: rgba(212, 175, 55, 0.5);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.2);
            color: white;
        }
        
        /* Icon Container */
        .menu-icon-wrapper {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu li a:hover .menu-icon-wrapper,
        .sidebar-menu li.active a .menu-icon-wrapper {
            background: var(--accent-color);
            transform: rotate(5deg) scale(1.1);
        }
        
        .menu-icon-wrapper i {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .sidebar-menu li a:hover .menu-icon-wrapper i,
        .sidebar-menu li.active a .menu-icon-wrapper i {
            color: var(--primary-color);
        }
        
        /* Menu Text */
        .menu-text {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .menu-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 3px;
        }
        
        .menu-subtitle {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
        }
        
        /* Menu Badge/Arrow */
        .menu-badge {
            opacity: 0;
            transform: translateX(-10px);
            transition: all 0.3s ease;
        }
        
        .sidebar-menu li a:hover .menu-badge,
        .sidebar-menu li.active a .menu-badge {
            opacity: 1;
            transform: translateX(0);
        }
        
        .menu-badge i {
            color: var(--accent-color);
            font-size: 12px;
        }
        
        /* Sidebar Footer */
        .sidebar-footer {
            padding: 15px 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .system-status {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        .status-indicator.online {
            background: #4CAF50;
            box-shadow: 0 0 10px rgba(76, 175, 80, 0.5);
            animation: pulse 2s infinite;
        }
        
        .server-time {
            display: flex;
            align-items: center;
            color: rgba(255, 255, 255, 0.6);
            font-size: 12px;
        }
        
        .server-time i {
            margin-right: 8px;
            font-size: 12px;
        }
        
        /* Animations */
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        
        @keyframes menuGlow {
            0% { box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2); }
            50% { box-shadow: 0 5px 20px rgba(212, 175, 55, 0.4); }
            100% { box-shadow: 0 5px 15px rgba(212, 175, 55, 0.2); }
        }
        
        .sidebar-menu li.active a {
            animation: menuGlow 2s infinite;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
                margin-bottom: 20px;
            }
            
            .sidebar-menu li a {
                margin: 5px;
                padding: 14px 15px;
            }
            
            .menu-icon-wrapper {
                width: 36px;
                height: 36px;
                margin-right: 12px;
            }
        }
        /* ===================== AKHIR SIDEBAR STYLING ===================== */
        
        /* Main Content Area */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>