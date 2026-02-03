<?php
if (!defined('__TYPECHO_ADMIN__')) {
    exit;
}

/** Header 头部信息定义 - 升级至 Typecho 1.3.0 */
$header = '
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.8/css/bootstrap.min.css" rel="stylesheet">
<!-- FontAwesome 6 -->
<link href="https://cdn.bootcdn.net/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<!-- Typecho Style -->
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'style.css?t=v1.0.2-r1', true) . '">
<!-- NProgress 加载条 -->
<link href="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">';

/** 注册一个初始化插件钩子 - 1.3.0 兼容 */
$header = \Typecho\Plugin::factory('admin/header.php')->filter('header', $header);

?>
<!DOCTYPE html>
<html lang="<?php echo $options->lang ? $options->lang : 'zh-CN'; ?>">
<head>
    <meta charset="<?php $options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title><?php _e('%s - %s - Powered by Typecho', $menu->title, $options->title); ?></title>
    <meta name="robots" content="noindex, nofollow">
    <?php echo $header; ?>

    <!-- Modern UI Core Styles - Typecho 1.3.0 兼容 -->
    <style>
        :root {
            /* 核心配色：参考 Discord/Twitch 风格 */
            --primary-color: #6c5ce7;
            --primary-light: #a29bfe;
            --primary-hover: #5b4cc4;
            --primary-soft: rgba(108, 92, 231, 0.1);
            --secondary-bg: #f8f9fd;
            --sidebar-bg: #ffffff;
            --text-main: #2d3436;
            --text-muted: #636e72;
            --text-light: #b2bec3;

            /* 尺寸变量 */
            --sidebar-width: 250px;
            --header-height: 70px;
            --card-radius: 20px;
            --btn-radius: 12px;

            /* 状态颜色 */
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #ff7675;
            --info-color: #0984e3;

            /* 过渡动画 */
            --transition-speed: 0.3s;
            --transition-timing: cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Nunito', 'PingFang SC', 'Microsoft YaHei', system-ui, -apple-system, sans-serif;
            background-color: var(--secondary-bg);
            color: var(--text-main);
            overflow-x: hidden;
            font-size: 0.925rem;
            -webkit-font-smoothing: antialiased;
        }

        a { text-decoration: none; color: var(--primary-color); transition: color 0.2s ease; }
        a:hover { color: var(--primary-hover); }

        /* 移动端遮罩层 */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.3);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
            backdrop-filter: blur(2px);
        }
        .overlay.show { display: block; opacity: 1; }

        /* 隐藏 Typecho 原生顶部导航栏，我们将完全重写它 */
        .typecho-head-nav { display: none !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in-up {
            animation: fadeInUp 0.4s ease-out;
        }

        /* 滚动条美化 */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb:hover { background: #a0aec0; }

        /* NProgress 颜色覆盖 - Typecho 1.3.0 改进 */
        #nprogress .bar {
            background: var(--primary-color) !important;
            height: 3px !important;
            box-shadow: 0 0 10px var(--primary-color) !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px var(--primary-color), 0 0 5px var(--primary-color) !important;
        }
        #nprogress .spinner-icon {
            border-top-color: var(--primary-color) !important;
            border-left-color: var(--primary-color) !important;
        }

        /* PJAX 容器动画 */
        .main-content {
            transition: opacity 0.2s ease;
        }
        .main-content.pjax-loading {
            opacity: 0.5;
            pointer-events: none;
        }

        /* Typecho 1.3.0 新增样式兼容 */
        .typecho-option ul { padding: 0; list-style: none; }
        .typecho-option li { padding: 0; }

        /* 响应式字体调整 */
        @media (max-width: 768px) {
            body { font-size: 0.875rem; }
        }
    </style>
</head>
<body<?php if (isset($bodyClass)) {echo ' class="' . $bodyClass . '"';} ?>>
    <!-- 移动端侧边栏遮罩 -->
    <div class="overlay" id="sidebarOverlay"></div>