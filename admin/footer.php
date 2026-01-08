<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

    </main>

    <!-- Bootstrap 5 Bundle (包含 Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js (用于仪表盘) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <?php
    /**
     * 执行 Typecho 底部插件钩子
     * 必须保留，否则很多插件（如编辑器插件）无法正常工作
     */
    \Typecho\Plugin::factory('admin/footer.php')->end();
    ?>

</body>
</html>