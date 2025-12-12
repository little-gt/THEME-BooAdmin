<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

</main><!-- 闭合 main-content (在 menu 中开启) -->

<!-- Bootstrap 5 Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Chart.js (用于仪表盘统计) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- UI 交互与 PJAX 逻辑 -->
<script>
$(document).ready(function() {

    // --- 1. 移动端侧边栏 (事件委托模式，支持 PJAX) ---
    $(document).on('click', '#sidebarToggle', function(e) {
        e.stopPropagation();
        $('#sidebar').toggleClass('show');
        $('#sidebarOverlay').toggleClass('show');
    });

    $(document).on('click', '#sidebarOverlay', function() {
        $('#sidebar').removeClass('show');
        $('#sidebarOverlay').removeClass('show');
    });

    // --- 2. 初始化 Bootstrap Tooltips (首次加载) ---
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // --- 3. PJAX 配置 ---
    // 检查浏览器是否支持 pushState，且排除特定链接
    if ($.support.pjax) {
        // 排除规则：
        // 1. target="_blank"
        // 2. 撰写页面 (write-*) -> 防止编辑器加载失败
        // 3. 动作链接 (action/) -> 避免误触后台操作
        // 4. 登出 (logout)
        // 5. 带有 no-pjax 属性的链接
        $(document).pjax(
            'a:not([target="_blank"]):not([href^="write-"]):not([href*="action/"]):not([href*="logout"]):not([no-pjax])',
            '.main-content',
            {
                fragment: '.main-content', // 仅替换 .main-content 内部
                timeout: 8000,
                scrollTo: 0
            }
        );

        // NProgress 进度条绑定
        $(document).on('pjax:start', function() {
            NProgress.start();
            $('.main-content').addClass('pjax-loading');
        });

        $(document).on('pjax:end',   function() {
            NProgress.done();
            $('.main-content').removeClass('pjax-loading');
        });

        // 搜索表单 PJAX 化
        $(document).on('submit', 'form.operate-form-get', function(event) {
            event.preventDefault();
            $.pjax.submit(event, '.main-content', {fragment: '.main-content'});
        });

        // --- 4. PJAX 完成后的重载逻辑 (Re-init) ---
        $(document).on('pjax:complete', function() {

            // A. 更新侧边栏菜单高亮
            let currentUrl = window.location.href;
            $('.sidebar .nav-link').removeClass('active');
            $('.sidebar .nav-link').each(function() {
                // 简单的 URL 匹配
                if (currentUrl.indexOf($(this).attr('href')) !== -1) {
                    $(this).addClass('active');
                }
            });

            // B. 重新初始化表格行为 (来自 table-js.php)
            if (typeof window.initTableBehavior === 'function') {
                window.initTableBehavior();
            }

            // C. 重新初始化 Bootstrap Tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // D. 执行新页面中的内联脚本 (Typecho 依赖)
            // 某些页面(如manage-comments)的逻辑写在 script 标签中，PJAX 默认会执行 script，
            // 但如果有些逻辑依赖 DOM ready，这里可以手动触发 resize 或其他事件来辅助
            $(window).trigger('resize');

            // E. 重新添加 fade-in 动画效果
            $('.main-content').addClass('fade-in-up');
        });
    }
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