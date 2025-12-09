<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

    <!-- 
        注意：页脚可视内容已移至 copyright.php
        此处仅负责闭合标签和加载 JS
    -->

</main><!-- 闭合 main-content (在 menu.php 中开启) -->

<!-- ======================================================= -->
<!-- 核心 JS 库 -->
<!-- ======================================================= -->

<!-- Bootstrap 5 Bundle (includes Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js (用于仪表盘统计) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- UI 交互逻辑 -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. 侧边栏切换逻辑 (移动端)
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    function toggleSidebar() {
        if (sidebar && overlay) {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            toggleSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener('click', toggleSidebar);
    }

    // 2. 初始化所有 Bootstrap Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // 3. 简单的选中状态增强 (针对原生 Typecho 表格)
    const checkboxes = document.querySelectorAll('input.typecho-table-select-all, input[name="cid[]"], input[name="uid[]"], input[name="mid[]"]');
    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            const row = this.closest('tr');
            if(row) {
                if(this.checked) row.classList.add('table-active');
                else row.classList.remove('table-active');
            }
        });
    });
});
</script>

<?php
/** 
 * 执行 Typecho 底部插件钩子
 * 必须保留，否则很多插件无法正常工作
 */
\Typecho\Plugin::factory('admin/footer.php')->end(); 
?>

</body>
</html>