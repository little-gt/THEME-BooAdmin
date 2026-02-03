<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

<!-- 1. 样式补丁 - Typecho 1.3.0 兼容 -->
<style>
/* Typecho 表单列表重置 */
ul.typecho-option { margin: 0; padding: 0; list-style: none; }
ul.typecho-option li { margin-bottom: 1.5rem; padding: 0; border: none; list-style: none; }
ul.typecho-option li.empty { display: none; }

/* 消息弹窗美化 (Bootstrap 风格) - 1.3.0 增强 */
.message.popup {
    position: fixed;
    top: 80px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1090;
    min-width: 320px;
    max-width: 90vw;
    padding: 14px 28px;
    border-radius: 50rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    font-weight: 600;
    text-align: center;
    display: none;
    animation: slideDownFade 0.3s ease-out;
    pointer-events: none;
    backdrop-filter: blur(10px);
}
.message.success { background-color: rgba(209, 231, 221, 0.95); color: #0f5132; border: 1px solid #badbcc; }
.message.success::before { content: '✓ '; font-weight: bold; }
.message.notice { background-color: rgba(255, 243, 205, 0.95); color: #664d03; border: 1px solid #ffecb5; }
.message.notice::before { content: 'ℹ '; font-weight: bold; }
.message.error { background-color: rgba(248, 215, 218, 0.95); color: #842029; border: 1px solid #f5c2c7; }
.message.error::before { content: '✕ '; font-weight: bold; }

@keyframes slideDownFade {
    from { opacity: 0; transform: translate(-50%, -20px); }
    to { opacity: 1; transform: translate(-50%, 0); }
}

/* 1.3.0 新增：消息在页面内显示样式 */
.message:not(.popup) {
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

<!-- 2. 核心库加载 -->
<script>
    // 检查库是否已加载，如果没有，使用 document.write 同步加载
    if (typeof jQuery === 'undefined') {
        document.write('<script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"><\/script>');
        document.write('<script src="<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>"><\/script>');
        document.write('<script src="<?php $options->adminStaticUrl('js', 'typecho.js'); ?>"><\/script>');
    }
</script>

<!-- 3. 全局逻辑定义 -->
<script>
$(document).ready(function() {
    // 1. 消息通知系统 (Flash Messages) - Typecho 1.3.0 兼容
    (function(){
        var prefix = '<?php echo \Typecho\Cookie::getPrefix(); ?>',
            cookies = {
                notice      :   $.cookie(prefix + '__typecho_notice'),
                noticeType  :   $.cookie(prefix + '__typecho_notice_type'),
                highlight   :   $.cookie(prefix + '__typecho_notice_highlight')
            },
            path = '<?php echo \Typecho\Cookie::getPath(); ?>',
            domain = '<?php echo \Typecho\Cookie::getDomain(); ?>',
            secure = <?php echo json_encode(\Typecho\Cookie::getSecure()); ?>;

        // 处理通知消息
        if (!!cookies.notice && 'success|notice|error'.indexOf(cookies.noticeType) >= 0) {
            var noticeData = $.parseJSON(cookies.notice);
            var noticeText = Array.isArray(noticeData) ? noticeData.join('<br>') : noticeData;

            // 检查是否有消息容器（用于页面内显示）
            var messageContainer = $('#typecho-message-container');
            if (messageContainer.length > 0) {
                // 在容器内显示消息
                var inlineHtml = '<div class="message ' + cookies.noticeType + '">' + noticeText + '</div>';
                var inlineMsg = $(inlineHtml).prependTo(messageContainer);
                inlineMsg.delay(5000).fadeOut(500, function () { $(this).remove(); });
            } else {
                // 使用弹窗显示消息（默认行为）
                var popupHtml = '<div class="message popup ' + cookies.noticeType + '">' + noticeText + '</div>';
                var popupMsg = $(popupHtml).appendTo(document.body);
                popupMsg.fadeIn(200).delay(4000).fadeOut(500, function () { $(this).remove(); });
            }

            // 清除 Cookie
            $.cookie(prefix + '__typecho_notice', null, {path : path, domain: domain, secure: secure});
            $.cookie(prefix + '__typecho_notice_type', null, {path : path, domain: domain, secure: secure});
        }

        // 处理高亮元素
        if (cookies.highlight) {
            var $highlight = $('#' + cookies.highlight);
            if ($highlight.length > 0) {
                if (typeof $highlight.effect === 'function') {
                    $highlight.effect('highlight', 1000);
                } else {
                    // 1.3.0 降级方案：使用 CSS 动画高亮
                    $highlight.css({
                        'transition': 'background-color 0.5s ease',
                        'background-color': 'rgba(108, 92, 231, 0.2)'
                    });
                    setTimeout(function() {
                        $highlight.css('background-color', '');
                    }, 1000);
                }
            }
            $.cookie(prefix + '__typecho_notice_highlight', null, {path : path, domain: domain, secure: secure});
        }
    })();

    // 2. UI 渲染与美化 (Bootstrap Adaption) - Typecho 1.3.0 增强
    // 表单控件
    $('.typecho-option input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=hidden]):not([type=button])').addClass('form-control');
    $('.typecho-option input[type="text"].text').addClass('form-control');
    $('.typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-2 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1 mb-0');
    $('.typecho-option .multiline').addClass('d-block mb-1');

    // 1.3.0 新增：改进的表单布局
    $('.typecho-option').each(function() {
        var $this = $(this);
        // 移除原生样式类
        $this.find('li').removeClass();
        // 确保垂直间距
        $this.css('margin-bottom', '1.5rem');
    });

    // 必填项红星
    $('.typecho-option label.required').each(function() {
        var $label = $(this);
        if (!$label.find('span.text-danger').length) {
            $label.html($label.html().replace(/\*/g, ' <span class="text-danger">*</span>'));
        }
    });

    // 提交按钮容器
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end gap-2');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 fw-bold shadow-sm');
    $('.typecho-option-submit input[type="submit"]').addClass('btn btn-primary px-4 fw-bold shadow-sm');
    $('.typecho-option-submit a').addClass('btn btn-outline-secondary');

    // 1.3.0 新增：改进的按钮状态
    $('.typecho-option-submit button, .typecho-option-submit input[type="submit"]').each(function() {
        var $btn = $(this);
        if ($btn.hasClass('primary')) {
            $btn.removeClass('primary').addClass('btn-primary');
        }
    });

    // 自动聚焦第一个可见的文本输入框
    $('.typecho-option input[type="text"]:visible:first').focus();

    // 3. 初始化 Bootstrap 组件 (Tooltips, Popovers等) - 1.3.0 增强
    if (typeof bootstrap !== 'undefined') {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    }

    // 4. 表格全选/操作行为 - 1.3.0 增强
    if (typeof window.initTableBehavior === 'function') {
        window.initTableBehavior();
    } else {
        // 内置表格行为
        $('table.typecho-list-table').each(function() {
            var $table = $(this);
            var $checkAll = $table.find('thead input[type="checkbox"]').first();
            var $checkboxes = $table.find('tbody input[type="checkbox"]');

            $checkAll.on('change', function() {
                var checked = this.checked;
                $checkboxes.prop('checked', checked).trigger('change');
            });

            $checkboxes.on('change', function() {
                var allChecked = $checkboxes.length > 0 && $checkboxes.filter(':checked').length === $checkboxes.length;
                $checkAll.prop('checked', allChecked);
                $(this).closest('tr').toggleClass('checked', this.checked);
            });
        });
    }

    // 5. 菜单高亮 (Sidebar Active State) - 1.3.0 改进
    // PHP 已经设置了初始 active 类，这里只用于页面切换后的动态更新
    var currentUrl = window.location.pathname + window.location.search;

    // 清除所有 active 类（避免重复）
    $('.sidebar .nav-link').removeClass('active');
    $('.sidebar .menu-category').removeClass('active-category');

    // 获取当前文件名
    var currentFileName = currentUrl.split('/').pop().split('?')[0];

    // 查找并激活当前菜单项
    var activeFound = false;
    $('.sidebar .nav-link').each(function() {
        var $link = $(this);
        var href = $link.attr('href');

        if (href) {
            // 提取 href 中的文件名
            var hrefFileName = href.split('/').pop().split('?')[0];

            // 精确匹配文件名
            if (currentFileName === hrefFileName) {
                $link.addClass('active');
                // 展开父级菜单
                var $collapse = $link.closest('.collapse');
                if ($collapse.length > 0) {
                    $collapse.addClass('show');
                    // 高亮一级菜单分类
                    var $menuCategory = $link.closest('.menu-group').find('.menu-category');
                    $menuCategory.attr('aria-expanded', 'true').addClass('active-category');
                }
                activeFound = true;
                return false; // 退出循环，只激活一个
            }
        }
    });

    // 如果没有找到匹配项，检查首页（index.php）
    if (!activeFound) {
        $('.sidebar .nav-link').each(function() {
            var $link = $(this);
            var href = $link.attr('href');
            var hrefFileName = href.split('/').pop().split('?')[0];

            if (hrefFileName === 'index.php') {
                // 当前 URL 是首页或不包含其他文件名时激活
                var pathParts = currentUrl.split('/').filter(function(p) { return p && p !== 'index.php'; });
                if (pathParts.length === 0 || pathParts.length === 1) {
                    $link.addClass('active');
                    $link.closest('.collapse').addClass('show');
                    // 高亮一级菜单分类
                    var $menuCategory = $link.closest('.menu-group').find('.menu-category');
                    $menuCategory.attr('aria-expanded', 'true').addClass('active-category');
                    return false;
                }
            }
        });
    }

    // 6. 通用确认逻辑 - 1.3.0 增强（支持自定义确认对话框）
    $(document).on('click', '.btn-operate, a[lang]', function (e) {
        var t = $(this), msg = t.attr('lang');
        if (msg && !confirm(msg)) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    // 1.3.0 新增：AJAX 加载动画
    $(document).ajaxStart(function() {
        if (typeof NProgress !== 'undefined') {
            NProgress.start();
        }
    });
    $(document).ajaxComplete(function() {
        if (typeof NProgress !== 'undefined') {
            NProgress.done();
        }
    });

    // 7. 页面进入动画
    $('.main-content').addClass('fade-in-up');

    // 8. 防止表单重复提交 - 1.3.0 改进
    $('form').on('submit', function () {
        var $form = $(this);
        var $submitBtn = $form.find('button[type=submit], input[type=submit]');
        $submitBtn.prop('disabled', true).addClass('disabled');
    });

    // 1.3.0 新增：重置禁用状态（表单验证失败时）
    $('form').on('invalid', function() {
        var $form = $(this);
        var $submitBtn = $form.find('button[type=submit], input[type=submit]');
        $submitBtn.prop('disabled', false).removeClass('disabled');
    }, true);
});
</script>