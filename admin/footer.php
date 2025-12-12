<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

    </main>

    <!-- Bootstrap 5 Bundle (包含 Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js (用于仪表盘) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- 补充 UI 逻辑 -->
    <script>
    (function() {
        // 定义 Tooltips 初始化函数
        function initBootstrapComponents() {
            if (typeof bootstrap !== 'undefined') {
                // 销毁旧的实例以防内存泄漏（可选，Bootstrap 5 通常能处理）
                $('.tooltip').remove();

                // 初始化所有 tooltip
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        }

        $(document).ready(function() {
            // 1. 首次加载时初始化
            initBootstrapComponents();

            // 2. 监听 PJAX 完成事件
            // 注意：核心 PJAX 配置在 common-js.php 中，这里只负责“补充”
            // 当 PJAX 替换内容后，我们需要重新激活新内容里的 Tooltips
            $(document).on('pjax:complete', function() {
                initBootstrapComponents();

                // 重新激活淡入动画 (UI 体验优化)
                $('.main-content').addClass('fade-in-up');
            });
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