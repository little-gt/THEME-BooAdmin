<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

    </main>

    <!-- Bootstrap 5 Bundle (包含 Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js (用于仪表盘) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 补充 UI 逻辑 -->
    <script>
    // 将初始化函数挂载到 window 对象，供 PJAX 调用
    window.initBootstrapComponents = function() {
        if (typeof bootstrap !== 'undefined') {
            // 销毁旧的实例以防内存泄漏（特别是 Tooltip）
            // Bootstrap 5 会自动处理大部分，但手动清理更安全
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                // 如果已存在实例，先销毁（防止双重绑定）
                var existingTooltip = bootstrap.Tooltip.getInstance(tooltipTriggerEl);
                if (existingTooltip) existingTooltip.dispose();
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }
    };

    (function() {
        $(document).ready(function() {
            // 1. 首次加载时初始化
            window.initBootstrapComponents();

            // 2. 动画效果
            $('.main-content').addClass('fade-in-up');
        });
    })();
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