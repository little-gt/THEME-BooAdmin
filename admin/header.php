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
    }
    select:focus {
        border-color: #5865F2 !important; /* Discord accent */
        outline: none !important;
        box-shadow: 0 0 0 2px rgba(88, 101, 242, 0.2);
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
    <body class="bg-discord-light text-discord-text h-screen flex overflow-hidden">
