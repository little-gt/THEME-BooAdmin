<?php
if (!defined('__TYPECHO_ADMIN__')) {
    exit;
}

$header = '
<!-- Custom Styles -->
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'normalize.css?v=1.1.15', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'grid.css?v=1.1.15', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'custom.css?v=1.1.15', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'style.css?v=1.1.15', true) . '">
<link rel="stylesheet" href="' . $options->adminStaticUrl('css', 'nprogress.css?v=1.1.15', true) . '">
<!-- NProgress -->
<script src="' . $options->adminStaticUrl('js', 'nprogress.js', true) . '"></script>
<!-- TailwindCSS -->
<script src="https://cdn.garfieldtom.cool/resource/libs/tailwind/3.4.17/tailwindcss.js"></script>
<!-- Font Awesome -->
<script src="https://cdn.garfieldtom.cool/resource/libs/fontawesome/7.1.0/js/all.min.js"></script>
<link href="https://cdn.garfieldtom.cool/resource/libs/fontawesome/7.1.0/css/all.min.css" rel="stylesheet">
<!-- ECharts -->
<script src="https://cdn.garfieldtom.cool/resource/libs/echarts/5.5.0/echarts.min.js"></script>
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
';

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
        <!-- NProgress Loading Indicator -->
        <div id="nprogress">
            <div class="bar" role="bar"></div>
            <div class="peg"></div>
            <div class="spinner">
                <div class="spinner-icon"></div>
                <span class="spinner-text"><?php _e('正在加载'); ?></span>
            </div>
        </div>
