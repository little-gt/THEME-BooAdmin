<?php
if (!defined('__TYPECHO_ADMIN__')) {
    exit;
}

/** Header 头部信息定义 */
$header = '
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- FontAwesome 6 -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<!-- Google Fonts (Nunito) -->
<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
<!-- Typecho Original Style (Optional preservation for plugins) -->
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'style.css', true) . '">';

/** 注册一个初始化插件 */
$header = \Typecho\Plugin::factory('admin/header.php')->header($header);

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

    <!-- Modern UI Core Styles -->
    <style>
        :root {
            /* 核心配色：参考 Discord/Twitch 风格 */
            --primary-color: #6c5ce7;
            --primary-light: #a29bfe;
            --primary-hover: #5b4cc4;
            --secondary-bg: #f8f9fd;
            --sidebar-bg: #ffffff;
            --text-main: #2d3436;
            --text-muted: #636e72;

            /* 尺寸变量 */
            --sidebar-width: 250px;
            --header-height: 70px;
            --card-radius: 20px;
            --btn-radius: 12px;

            /* 状态颜色 */
            --success-color: #00b894;
            --warning-color: #fdcb6e;
            --danger-color: #ff7675;
        }

        body {
            font-family: 'Nunito', 'PingFang SC', 'Microsoft YaHei', system-ui, -apple-system, sans-serif;
            background-color: var(--secondary-bg);
            color: var(--text-main);
            overflow-x: hidden;
            font-size: 0.9rem; /* 稍微调小字体使其更精致 */
        }

        a { text-decoration: none; color: var(--primary-color); transition: 0.2s; }
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
        }
        .overlay.show { display: block; opacity: 1; }

        /* 隐藏 Typecho 原生顶部导航栏，我们将完全重写它 */
        .typecho-head-nav { display: none !important; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 滚动条美化 */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 3px; }
        ::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>
<body<?php if (isset($bodyClass)) {echo ' class="' . $bodyClass . '"';} ?>>
    <!-- 移动端侧边栏遮罩 -->
    <div class="overlay" id="sidebarOverlay"></div>