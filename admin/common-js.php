<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

<!-- 1. 样式补丁 -->
<style>
/* Typecho 表单列表重置 */
ul.typecho-option { margin: 0; padding: 0; list-style: none; }
ul.typecho-option li { margin-bottom: 1.5rem; padding: 0; border: none; list-style: none; }
ul.typecho-option li.empty { display: none; }

/* 消息弹窗美化 (Bootstrap 风格) */
.message.popup {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1090;
    min-width: 320px;
    padding: 12px 24px;
    border-radius: 50rem;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    font-weight: bold;
    text-align: center;
    display: none;
    animation: slideDownFade 0.3s ease-out;
    pointer-events: none;
}
.message.success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
.message.notice { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; }
.message.error { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }

@keyframes slideDownFade {
    from { opacity: 0; transform: translate(-50%, -20px); }
    to { opacity: 1; transform: translate(-50%, 0); }
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
    // 1. 消息通知系统 (Flash Messages)
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

        if (!!cookies.notice && 'success|notice|error'.indexOf(cookies.noticeType) >= 0) {
            var noticeData = $.parseJSON(cookies.notice);
            var noticeText = Array.isArray(noticeData) ? noticeData.join('<br>') : noticeData;

            var html = '<div class="message popup ' + cookies.noticeType + '">' + noticeText + '</div>';
            var p = $(html).appendTo(document.body);

            p.fadeIn(200).delay(3000).fadeOut(500, function () { $(this).remove(); });

            $.cookie(prefix + '__typecho_notice', null, {path : path, domain: domain, secure: secure});
            $.cookie(prefix + '__typecho_notice_type', null, {path : path, domain: domain, secure: secure});
        }

        if (cookies.highlight) {
            $('#' + cookies.highlight).effect('highlight', 1000);
            $.cookie(prefix + '__typecho_notice_highlight', null, {path : path, domain: domain, secure: secure});
        }
    })();

    // 2. UI 渲染与美化 (Bootstrap Adaption)
    // 表单控件
    $('.typecho-option input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=hidden])').addClass('form-control');
    $('.typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    $('.typecho-option .multiline').addClass('d-block mb-1');

    // 必填项红星
    $('.typecho-option label.required').each(function() {
        if ($(this).find('span.text-danger').length === 0) {
            $(this).html($(this).html().replace('*', ' <span class="text-danger">*</span>'));
        }
    });

    // 提交按钮容器
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end gap-2');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 fw-bold shadow-sm rounded-pill');
    $('.typecho-option-submit a').addClass('btn btn-light rounded-pill');

    // 自动聚焦
    $('.typecho-option input[type=text]:visible:first').focus();

    // 3. 初始化 Bootstrap 组件 (Tooltips等)
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // 4. 表格全选/操作行为
    if (typeof window.initTableBehavior === 'function') {
        window.initTableBehavior();
    }

    // 5. 菜单高亮 (Sidebar Active State)
    var currentUrl = window.location.href;
    $('.sidebar .nav-link').removeClass('active');
    $('.sidebar .nav-link').each(function() {
        var href = $(this).attr('href');
        if (currentUrl.indexOf(href) !== -1 && href !== 'index.php') {
            $(this).addClass('active');
        } else if (href === 'index.php' && currentUrl.endsWith('/admin/')) {
             $(this).addClass('active');
        }
    });

    // 6. 通用确认逻辑
    $(document).on('click', '.btn-operate, a[lang]', function (e) {
        var t = $(this), msg = t.attr('lang');
        if (msg && !confirm(msg)) {
            e.preventDefault();
            e.stopPropagation();
            return false;
        }
    });

    // 7. 页面进入动画
    $('.main-content').addClass('fade-in-up');

    // 8. 防止表单重复提交
    $('form').on('submit', function () {
        $('button[type=submit]', this).attr('disabled', 'disabled');
    });
});
</script>