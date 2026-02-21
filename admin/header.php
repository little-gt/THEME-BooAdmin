<?php
if (!defined('__TYPECHO_ADMIN__')) {
    exit;
}

$header = '<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'normalize.css', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'grid.css?t=202602220101', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'style.css?t=202602220101', true) . '">
<!-- TailwindCSS -->
<script src="https://image.uc.cn/s/uae/g/3n/mos-production/0915/3.4.17.js"></script>
<!-- Font Awesome -->
<script src="https://image.uc.cn/s/uae/g/3n/mos-production/fontawesome-free-7.1.0-web/js/all.min.js"></script>
<link href="https://image.uc.cn/s/uae/g/3n/mos-production/fontawesome-free-7.1.0-web/css/fontawesome.min.css" rel="stylesheet">
<!-- ECharts -->
<script src="https://image.uc.cn/s/uae/g/3n/mos-production/cdn-staticfile-net/echarts/5.4.3/echarts.min.js"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    discord: {
                        light: "#F2F3F5",
                        sidebar: "#E3E5E8",
                        active: "#D4D7DC",
                        accent: "#5865F2",
                        text: "#2E3338",
                        muted: "#5C5E66",
                    }
                }
            }
        }
    }
</script>
<style>
    body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; }
    /* Fix for some Typecho JS elements if needed */
    .typecho-option-submit { display: none; }
    
    /* Global Select Optimization */
    select {
        height: auto !important;
        min-height: 42px !important;
        padding-top: 8px !important;
        padding-bottom: 8px !important;
        line-height: 1.5 !important;
        background-color: #fff;
        border-color: #e5e7eb; /* Tailwind gray-200 */
        border-radius: 0.375rem; /* Tailwind rounded-md */
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        width: auto !important;
        display: inline-block !important;
    }
    select:focus {
        border-color: #5865F2 !important; /* Discord accent */
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(88, 101, 242, 0.2);
    }
    
    /* Sidebar fixed positioning on desktop */
    @media (min-width: 768px) {
        #sidebar {
            position: fixed !important;
            top: 0;
            left: 0;
            height: 100vh;
            z-index: 20;
        }
        /* Add left margin to main content to account for fixed sidebar */
        body:not(.body-100) > main {
            margin-left: 16rem; /* w-64 = 16rem */
        }
    }
    
    /* Mobile layout guardrails */
    @media (max-width: 767px) {
        body:not(.body-100) {
            overflow-x: hidden;
        }
        body:not(.body-100).sidebar-open {
            overflow: hidden;
        }
        body:not(.body-100) > main {
            margin-left: 0 !important;
            min-height: 100vh;
        }
        #sidebar {
            height: 100vh;
        }
    }
    
    /* Body Layout Classes */
    .body-100 {
        /* For login/register pages - no restrictions on height and layout */
        background-color: #F2F3F5;
        color: #2E3338;
    }
    
    body:not(.body-100) {
        /* For admin pages - natural scrolling layout */
        background-color: #F2F3F5;
        color: #2E3338;
        min-height: 100vh;
        display: flex;
        /* No overflow hidden - allow natural page scroll */
    }
    
    /* Main content area flows naturally */
    main {
        flex: 1;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        /* Remove overflow restrictions */
    }
    
    /* Override overflow-hidden class on main */
    main.overflow-hidden {
        overflow: visible !important;
    }
    
    /* Header stays at top of main */
    main > header {
        flex-shrink: 0;
        z-index: 10;
    }
    
    /* Content area expands to fill space, no internal scroll */
    main > .flex-1.overflow-y-auto,
    main > div.flex-1 {
        flex: 1 0 auto;
        overflow: visible !important; /* Override overflow-y-auto, let page scroll */
    }
    
    /* Footer follows content naturally */
    #admin-footer {
        flex-shrink: 0;
        margin-top: auto; /* Push to bottom when content is short */
    }
    
    /* ========================================
       Global Form Styling for Settings Pages
       ======================================== */
    
    /* Form container */
    .typecho-option {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .typecho-option:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    
    /* Labels */
    .typecho-option > label:first-child {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 0.75rem;
    }
    
    /* Text inputs */
    .typecho-option input[type="text"],
    .typecho-option input[type="password"],
    .typecho-option input[type="email"],
    .typecho-option input[type="url"],
    .typecho-option input[type="number"],
    .typecho-option textarea {
        width: 100%;
        max-width: 600px;
        min-width: 200px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: all 0.15s ease;
        color: #1f2937;
        box-sizing: border-box;
    }
    .typecho-option input[type="text"]:focus,
    .typecho-option input[type="password"]:focus,
    .typecho-option input[type="email"]:focus,
    .typecho-option input[type="url"]:focus,
    .typecho-option input[type="number"]:focus,
    .typecho-option textarea:focus {
        outline: none;
        border-color: #5865F2;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
    }
    
    /* Checkbox and Radio - keep on same line */
    .typecho-option label input[type="checkbox"],
    .typecho-option label input[type="radio"] {
        margin-right: 0.5rem;
        flex-shrink: 0;
        width: 1rem;
        height: 1rem;
        accent-color: #5865F2;
    }
    
    /* Checkbox/Radio label containers - inline flex */
    .typecho-option label:has(input[type="checkbox"]),
    .typecho-option label:has(input[type="radio"]) {
        display: inline-flex !important;
        align-items: center;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.375rem;
        margin-right: 0.5rem;
        border-radius: 0.375rem;
        background-color: #f9fafb;
        border: 1px solid transparent;
        transition: all 0.15s;
        cursor: pointer;
        white-space: nowrap;
        max-width: 100%;
        line-height: 1.5;
    }
    .typecho-option label:has(input[type="checkbox"]):hover,
    .typecho-option label:has(input[type="radio"]):hover {
        background-color: #f3f4f6;
        border-color: #e5e7eb;
    }
    .typecho-option label:has(input[type="checkbox"]:checked),
    .typecho-option label:has(input[type="radio"]:checked) {
        background-color: #eef2ff;
        border-color: #c7d2fe;
    }
    
    /* Fallback for browsers without :has() support */
    .typecho-option input[type="checkbox"] + *,
    .typecho-option input[type="radio"] + * {
        white-space: normal;
        word-break: break-word;
    }
    
    /* Description text */
    .typecho-option .description,
    .typecho-option small {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.5;
    }
    
    /* Select dropdowns */
    .typecho-option select {
        width: auto;
        min-width: 200px;
        max-width: 600px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        color: #1f2937;
        cursor: pointer;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        box-sizing: border-box;
    }
    .typecho-option select:focus {
        outline: none;
        border-color: #5865F2;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
    }

    @media (max-width: 767px) {
        select {
            width: 100% !important;
            max-width: 100% !important;
        }
        .typecho-option input[type="text"],
        .typecho-option input[type="password"],
        .typecho-option input[type="email"],
        .typecho-option input[type="url"],
        .typecho-option input[type="number"],
        .typecho-option textarea,
        .typecho-option select {
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }
        .typecho-option label:has(input[type="checkbox"]),
        .typecho-option label:has(input[type="radio"]) {
            white-space: normal;
            width: 100%;
        }
    }
    
    /* ========================================
       Mobile Optimizations
       ======================================== */
    
    /* 1. Table scroll indicator - gradient masks */
    @media (max-width: 767px) {
        .table-wrapper {
            position: relative;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-wrapper::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 40px;
            background: linear-gradient(to left, rgba(255,255,255,0.95), transparent);
            pointer-events: none;
            opacity: 1;
            transition: opacity 0.3s;
        }
        .table-wrapper.scrolled-end::after {
            opacity: 0;
        }
        .table-wrapper::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            bottom: 0;
            width: 40px;
            background: linear-gradient(to right, rgba(255,255,255,0.95), transparent);
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            z-index: 1;
        }
        .table-wrapper.scrolled-start::before {
            opacity: 1;
        }
    }
    
    /* 2. Top operation area - stack vertically on mobile */
    @media (max-width: 640px) {
        .operate-bar {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.75rem !important;
        }
        .operate-bar > div {
            width: 100%;
            justify-content: space-between;
        }
    }

    /* ========================================
       Touch Target Size Optimization
       ======================================== */
    
    /* Increase touch targets for mobile */
    @media (max-width: 767px) {
        /* Table checkboxes */
        .typecho-list-table input[type="checkbox"] {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            min-height: 20px !important;
        }
        
        /* Table cells padding for easier tapping */
        .typecho-list-table td {
            padding: 1rem 0.5rem !important;
        }
        
        /* Buttons minimum height (exclude view-toggle buttons) */
        button:not(.view-toggle button), 
        .btn:not(.view-toggle button), 
        a.btn {
            min-height: 44px !important;
            padding-top: 0.625rem !important;
            padding-bottom: 0.625rem !important;
        }
        
        /* Links in action rows */
        .typecho-list-table .group a {
            padding: 0.5rem !important;
            margin: -0.5rem 0 !important;
        }
        
        /* Dropdown buttons */
        .btn-dropdown-toggle {
            min-height: 44px !important;
        }
        
        /* View toggle buttons remain compact */
        .view-toggle button {
            min-height: 36px !important;
            padding: 0.5rem 0.75rem !important;
        }
    }

    /* ========================================
       Card Layout View for List Pages
       ======================================== */
    
    /* Card view container */
    .card-view-container {
        display: none;
        grid-template-columns: 1fr;
        gap: 1rem;
        padding: 1rem;
    }
    
    .card-view-container.active {
        display: grid;
    }
    
    /* Mobile: 1 column */
    @media (min-width: 768px) {
        .card-view-container {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    /* Desktop: 3 columns */
    @media (min-width: 1280px) {
        .card-view-container {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    /* Individual card styles */
    .content-card {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 1.25rem;
        transition: all 0.2s ease;
        position: relative;
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .content-card:hover {
        border-color: #5865F2;
        box-shadow: 0 4px 12px rgba(88, 101, 242, 0.15);
        transform: translateY(-2px);
    }
    
    .content-card .card-checkbox {
        position: absolute;
        top: 1rem;
        left: 1rem;
        width: 20px;
        height: 20px;
    }
    
    .content-card .card-header {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding-left: 2rem;
    }
    
    .content-card .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2E3338;
        line-height: 1.4;
        flex: 1;
        word-break: break-word;
    }
    
    .content-card .card-title:hover {
        color: #5865F2;
    }
    
    .content-card .card-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 0.375rem;
        margin-top: 0.25rem;
    }
    
    .content-card .card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        font-size: 0.875rem;
        color: #6b7280;
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
    }
    
    .content-card .card-meta-item {
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .content-card .card-actions {
        display: flex;
        gap: 0.75rem;
        padding-top: 0.75rem;
        border-top: 1px solid #f3f4f6;
    }
    
    .content-card .card-actions a {
        font-size: 0.875rem;
        color: #6b7280;
        text-decoration: none;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .content-card .card-actions a:hover {
        color: #5865F2;
    }
    
    .content-card .card-comment-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 2rem;
        height: 2rem;
        padding: 0 0.5rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }
    
    /* Card view container - hidden by default */
    .card-view-container {
        display: none !important;
    }
    
    /* Hide table when card view is active */
    .view-mode-card .table-wrapper {
        display: none !important;
    }
    
    .view-mode-card .card-view-container {
        display: grid !important;
    }
    
    /* View toggle buttons */
    .view-toggle {
        display: inline-flex;
        gap: 0.25rem;
        background: #f3f4f6;
        padding: 0.25rem;
        border-radius: 0.375rem;
    }
    
    .view-toggle button {
        padding: 0.375rem 0.75rem;
        border: none;
        background: transparent;
        color: #6b7280;
        border-radius: 0.25rem;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.375rem;
        min-height: 32px;
    }
    
    .view-toggle button:hover {
        color: #2E3338;
    }
    
    .view-toggle button.active {
        background: white;
        color: #5865F2;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    /* Hide view toggle on very small screens */
    @media (max-width: 480px) {
        .view-toggle {
            display: none;
        }
    }
        }
    }
    
    /* 3. Settings tabs - scroll snap and hint */
    .settings-tabs {
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE/Edge */
    }
    .settings-tabs::-webkit-scrollbar {
        display: none; /* Chrome/Safari */
    }
    .settings-tabs > a {
        scroll-snap-align: start;
        flex-shrink: 0;
        min-width: 120px;
    }
    
    @media (max-width: 767px) {
        .settings-tabs-wrapper {
            position: relative;
        }
        .settings-tabs-wrapper::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 60px;
            background: linear-gradient(to left, rgba(243,244,246,0.95), transparent);
            pointer-events: none;
            border-radius: 0 0.5rem 0.5rem 0;
        }
        .settings-tabs > a {
            min-width: 100px;
        }
    }
    
    /* Submit button styling */
    .typecho-option-submit {
        display: block !important;
        margin-top: 2rem;
        padding-top: 1.5rem;
        border-top: 1px solid #e5e7eb;
    }
    .typecho-option-submit button,
    .typecho-option-submit input[type="submit"] {
        background: linear-gradient(135deg, #5865F2 0%, #4752c4 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(88, 101, 242, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        text-align: center;
        line-height: 1.5;
        min-width: 120px;
    }
    .typecho-option-submit button:hover,
    .typecho-option-submit input[type="submit"]:hover {
        background: linear-gradient(135deg, #4752c4 0%, #3c45a5 100%);
        box-shadow: 0 4px 8px rgba(88, 101, 242, 0.4);
        transform: translateY(-1px);
    }
    
    /* Required field indicator */
    .typecho-option .required {
        color: #ef4444;
        margin-left: 0.25rem;
    }
    
    /* ========================================
       Typecho Reform Style (for plugin/theme settings)
       ======================================== */
    .typecho-reform-style ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .typecho-reform-style li {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid #f3f4f6;
    }
    .typecho-reform-style li:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .typecho-reform-style label {
        display: block;
        font-weight: 600;
        font-size: 0.875rem;
        color: #374151;
        margin-bottom: 0.5rem;
    }
    .typecho-reform-style input[type="text"],
    .typecho-reform-style input[type="password"],
    .typecho-reform-style input[type="email"],
    .typecho-reform-style input[type="url"],
    .typecho-reform-style input[type="number"],
    .typecho-reform-style textarea {
        width: 100%;
        max-width: 600px;
        min-width: 200px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: all 0.15s ease;
        color: #1f2937;
        box-sizing: border-box;
    }
    .typecho-reform-style select {
        width: auto;
        min-width: 200px;
        max-width: 600px;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        padding: 0.625rem 0.875rem;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: all 0.15s ease;
        color: #1f2937;
        box-sizing: border-box;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .typecho-reform-style input:focus,
    .typecho-reform-style textarea:focus,
    .typecho-reform-style select:focus {
        outline: none;
        border-color: #5865F2;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
    }
    .typecho-reform-style .description {
        display: block;
        margin-top: 0.5rem;
        font-size: 0.75rem;
        color: #6b7280;
        line-height: 1.5;
    }
    .typecho-reform-style button[type="submit"],
    .typecho-reform-style input[type="submit"] {
        background: linear-gradient(135deg, #5865F2 0%, #4752c4 100%);
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 2px 4px rgba(88, 101, 242, 0.3);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        white-space: nowrap;
        text-align: center;
        line-height: 1.5;
        min-width: 120px;
    }
    .typecho-reform-style button[type="submit"]:hover,
    .typecho-reform-style input[type="submit"]:hover {
        background: linear-gradient(135deg, #4752c4 0%, #3c45a5 100%);
        box-shadow: 0 4px 8px rgba(88, 101, 242, 0.4);
        transform: translateY(-1px);
    }
    /* Radio and checkbox styling for reform style */
    .typecho-reform-style input[type="radio"],
    .typecho-reform-style input[type="checkbox"] {
        margin-right: 0.5rem;
        accent-color: #5865F2;
        flex-shrink: 0;
    }
    .typecho-reform-style .typecho-option span,
    .typecho-reform-style li span {
        margin-right: 1.5rem;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
    }

    .typecho-reform-style label:has(input[type="checkbox"]),
    .typecho-reform-style label:has(input[type="radio"]) {
        display: inline-flex !important;
        align-items: center;
        padding: 0.5rem 0.75rem;
        margin-bottom: 0.375rem;
        margin-right: 0.5rem;
        border-radius: 0.375rem;
        background-color: #f9fafb;
        border: 1px solid transparent;
        transition: all 0.15s;
        cursor: pointer;
        font-weight: normal;
        white-space: nowrap;
        line-height: 1.5;
    }
    .typecho-reform-style label:has(input[type="checkbox"]):hover,
    .typecho-reform-style label:has(input[type="radio"]):hover {
        background-color: #f3f4f6;
        border-color: #e5e7eb;
    }
    .typecho-reform-style label:has(input[type="checkbox"]:checked),
    .typecho-reform-style label:has(input[type="radio"]:checked) {
        background-color: #eef2ff;
        border-color: #c7d2fe;
    }
    @media (max-width: 767px) {
        .typecho-reform-style input[type="text"],
        .typecho-reform-style input[type="password"],
        .typecho-reform-style input[type="email"],
        .typecho-reform-style input[type="url"],
        .typecho-reform-style input[type="number"],
        .typecho-reform-style textarea,
        .typecho-reform-style select {
            width: 100%;
            max-width: 100%;
            min-width: 0;
        }
        .typecho-reform-style label:has(input[type="checkbox"]),
        .typecho-reform-style label:has(input[type="radio"]) {
            white-space: normal;
            width: 100%;
        }
    }
    
    /* ========================================
       Modern Notification System (Toast)
       ======================================== */
    
    /* 通知容器 - 固定在右上角 */
    #typecho-notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 400px;
        pointer-events: none;
    }
    
    /* 通知卡片 */
    .typecho-notification {
        pointer-events: all;
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15), 0 0 1px rgba(0, 0, 0, 0.1);
        padding: 16px 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        width: 100%;
        min-width: 320px;
        opacity: 0;
        transform: translateX(400px);
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        border-left: 4px solid #6b7280;
    }
    
    /* 显示动画 */
    .typecho-notification.show {
        opacity: 1;
        transform: translateX(0);
    }
    
    /* 隐藏动画 */
    .typecho-notification.hide {
        opacity: 0;
        transform: translateX(400px) scale(0.9);
        transition: all 0.3s ease-out;
    }
    
    /* 图标样式 */
    .typecho-notification-icon {
        flex-shrink: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 14px;
    }
    
    /* 内容区域 */
    .typecho-notification-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    /* 标题 */
    .typecho-notification-title {
        font-weight: 600;
        font-size: 14px;
        line-height: 1.4;
        color: #1f2937;
    }
    
    /* 消息列表 */
    .typecho-notification-messages {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .typecho-notification-messages li {
        font-size: 13px;
        line-height: 1.5;
        color: #4b5563;
        margin-bottom: 2px;
    }
    
    .typecho-notification-messages li:last-child {
        margin-bottom: 0;
    }
    
    /* 关闭按钮 */
    .typecho-notification-close {
        flex-shrink: 0;
        width: 20px;
        height: 20px;
        border: none;
        background: transparent;
        color: #9ca3af;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s;
        font-size: 16px;
        padding: 0;
        margin-top: -2px;
    }
    
    .typecho-notification-close:hover {
        background: #f3f4f6;
        color: #4b5563;
    }
    
    /* 成功样式 */
    .typecho-notification.success {
        border-left-color: #10b981;
    }
    
    .typecho-notification.success .typecho-notification-icon {
        background: #d1fae5;
        color: #059669;
    }
    
    .typecho-notification.success .typecho-notification-title {
        color: #065f46;
    }
    
    /* 错误样式 */
    .typecho-notification.error {
        border-left-color: #ef4444;
    }
    
    .typecho-notification.error .typecho-notification-icon {
        background: #fee2e2;
        color: #dc2626;
    }
    
    .typecho-notification.error .typecho-notification-title {
        color: #991b1b;
    }
    
    /* 警告样式 */
    .typecho-notification.notice {
        border-left-color: #f59e0b;
    }
    
    .typecho-notification.notice .typecho-notification-icon {
        background: #fef3c7;
        color: #d97706;
    }
    
    .typecho-notification.notice .typecho-notification-title {
        color: #92400e;
    }
    
    /* 信息样式 */
    .typecho-notification.info {
        border-left-color: #3b82f6;
    }
    
    .typecho-notification.info .typecho-notification-icon {
        background: #dbeafe;
        color: #2563eb;
    }
    
    .typecho-notification.info .typecho-notification-title {
        color: #1e40af;
    }
    
    /* 移动端适配 */
    @media (max-width: 640px) {
        #typecho-notification-container {
            top: 10px;
            right: 10px;
            left: 10px;
            max-width: none;
        }
        
        .typecho-notification {
            min-width: auto;
            width: 100%;
        }
    }
    
    /* 旧版兼容 - 隐藏旧的通知样式 */
    .message.popup {
        display: none !important;
    }
</style>';

/** 注册一个初始化插件 */
$header = \Typecho\Plugin::factory('admin/header.php')->filter('header', $header);

?><!DOCTYPE HTML>
<html>
    <head>
        <meta charset="<?php $options->charset(); ?>">
        <meta name="renderer" content="webkit">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php _e('%s - %s', $menu->title, $options->title); ?></title>
        <meta name="robots" content="noindex, nofollow">
        <?php echo $header; ?>
    </head>
    <body class="<?php echo isset($bodyClass) ? $bodyClass : ''; ?>">
