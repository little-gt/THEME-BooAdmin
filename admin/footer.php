<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

    </main>

    <!-- NProgress 加载条 - Typecho 1.3.0 新增 -->
    <script src="https://cdn.bootcdn.net/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    <!-- Bootstrap 5 Bundle (包含 Popper) -->
    <script src="https://cdn.bootcdn.net/ajax/libs/twitter-bootstrap/5.3.8/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js (用于仪表盘) -->
    <script src="https://cdn.bootcdn.net/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>

    <!-- Typecho 1.3.0 增强：页面加载进度条 -->
    <script>
    // 页面加载时显示进度条
    if (typeof NProgress !== 'undefined') {
        NProgress.configure({
            minimum: 0.08,
            easing: 'ease',
            speed: 500,
            showSpinner: false,
            trickleSpeed: 200
        });

        // 页面加载开始
        NProgress.start();

        // 页面加载完成
        $(window).on('load', function() {
            NProgress.done();
        });

        // AJAX 请求时显示进度条（已在 common-js.php 中实现）
    }
    </script>

    <?php
    /**
     * 执行 Typecho 底部插件钩子
     * 必须保留，否则很多插件（如编辑器插件）无法正常工作
     */
    \Typecho\Plugin::factory('admin/footer.php')->end();
    ?>

</body>
</html>