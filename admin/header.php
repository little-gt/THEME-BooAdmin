<?php
if (!defined('__TYPECHO_ADMIN__')) {
    exit;
}

$header = '<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'normalize.css', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'grid.css', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'style.css', true) . '">
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
