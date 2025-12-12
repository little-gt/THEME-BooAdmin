<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

<!-- 1. 样式补丁：列表重置与消息弹窗样式 -->
<style>
/* 进度条颜色 */
#nprogress .bar { background: #6c5ce7 !important; height: 3px !important; }
#nprogress .peg { box-shadow: 0 0 10px #6c5ce7, 0 0 5px #6c5ce7 !important; }
#nprogress .spinner-icon { border-top-color: #6c5ce7 !important; border-left-color: #6c5ce7 !important; }

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
.message.popup ul { margin: 0; padding: 0; list-style: none; }
.message.success { background-color: #d1e7dd; color: #0f5132; border: 1px solid #badbcc; }
.message.notice { background-color: #fff3cd; color: #664d03; border: 1px solid #ffecb5; }
.message.error { background-color: #f8d7da; color: #842029; border: 1px solid #f5c2c7; }

@keyframes slideDownFade {
    from { opacity: 0; transform: translate(-50%, -20px); }
    to { opacity: 1; transform: translate(-50%, 0); }
}
</style>

<!-- 2. 核心库加载 (关键修复：使用阻塞加载防止 $ 未定义) -->
<script>
    // 动态加载 CSS
    (function(){
        if (!document.getElementById('nprogress-css')) {
            var link = document.createElement('link');
            link.id = 'nprogress-css';
            link.rel = 'stylesheet';
            link.href = '<?php $options->adminStaticUrl('css', 'nprogress.css'); ?>';
            document.head.appendChild(link);
        }
    })();

    // 检查库是否已加载，如果没有，使用 document.write 同步加载
    // 这确保了在当前 script 标签执行完之前，jQuery 已经就绪，后续的 $(...) 不会报错
    if (typeof jQuery === 'undefined') {
        document.write('<script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"><\/script>');
        document.write('<script src="<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>"><\/script>');
        document.write('<script src="<?php $options->adminStaticUrl('js', 'typecho.js'); ?>"><\/script>');
        document.write('<script src="<?php $options->adminStaticUrl('js', 'nprogress.js'); ?>"><\/script>');
        document.write('<script src="<?php $options->adminStaticUrl('js', 'jquery.pjax.js'); ?>"><\/script>');
    }
</script>

<!-- 3. 全局逻辑定义 -->
<script>
/**
 * ============================================================================
 * 模块：消息通知系统
 * ============================================================================
 */
window.checkTypechoNotice = function() {
    // 延时执行，确保 Cookie 已写入
    setTimeout(function() {
        var prefix = '<?php echo \Typecho\Cookie::getPrefix(); ?>',
            cookies = {
                notice      :   $.cookie(prefix + '__typecho_notice'),
                noticeType  :   $.cookie(prefix + '__typecho_notice_type'),
                highlight   :   $.cookie(prefix + '__typecho_notice_highlight')
            },
            path = '<?php echo \Typecho\Cookie::getPath(); ?>',
            domain = '<?php echo \Typecho\Cookie::getDomain(); ?>',
            secure = <?php echo json_encode(\Typecho\Cookie::getSecure()); ?>;

        $('.message.popup').remove();

        if (!!cookies.notice && 'success|notice|error'.indexOf(cookies.noticeType) >= 0) {
            var noticeData = $.parseJSON(cookies.notice);
            var noticeText = Array.isArray(noticeData) ? noticeData.join('<br>') : noticeData;

            var html = '<div class="message popup ' + cookies.noticeType + '">' + noticeText + '</div>';
            var p = $(html);
            p.appendTo(document.body);

            p.fadeIn(200).delay(3000).fadeOut(500, function () { $(this).remove(); });

            $.cookie(prefix + '__typecho_notice', null, {path : path, domain: domain, secure: secure});
            $.cookie(prefix + '__typecho_notice_type', null, {path : path, domain: domain, secure: secure});
        }

        if (cookies.highlight) {
            $('#' + cookies.highlight).effect('highlight', 1000);
            $.cookie(prefix + '__typecho_notice_highlight', null, {path : path, domain: domain, secure: secure});
        }
    }, 100);
};

/**
 * ============================================================================
 * 模块：UI 渲染 (幂等函数)
 * ============================================================================
 */
window.renderBootstrapUI = function() {
    if (typeof NProgress !== 'undefined') NProgress.done();

    // 表单美化
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

    // 提交按钮
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end gap-2');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 fw-bold shadow-sm rounded-pill');
    $('.typecho-option-submit a').addClass('btn btn-light rounded-pill');

    // 自动聚焦
    $('.typecho-option input[type=text]:visible:first').focus();
};

/**
 * ============================================================================
 * 模块：页面重载控制器
 * ============================================================================
 */
window.reloadGlobalComponents = function() {
    window.renderBootstrapUI();
    window.checkTypechoNotice();

    // 重置 Tooltips
    $('.tooltip').remove();
    if (typeof bootstrap !== 'undefined') {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // 重置表格逻辑
    if (typeof window.initTableBehavior === 'function') {
        window.initTableBehavior();
    }

    // 菜单高亮
    var currentUrl = window.location.href;
    $('.sidebar .nav-link').removeClass('active');
    $('.sidebar .nav-link').each(function() {
        if (currentUrl.indexOf($(this).attr('href')) !== -1 && $(this).attr('href') !== 'index.php') {
            $(this).addClass('active');
        }
    });
};

/**
 * ============================================================================
 * 模块：系统初始化与事件绑定 (只执行一次)
 * ============================================================================
 */
(function() {
    if (window.typechoAdminJsLoaded) {
        // 如果是 PJAX 重载，仅执行页面级刷新
        $(function() {
            window.reloadGlobalComponents();
        });
        return;
    }

    window.typechoAdminJsLoaded = true;

    $(document).ready(function() {
        // 首次加载
        window.reloadGlobalComponents();

        // 初始化 PJAX
        if ($.fn.pjax) {
            $(document).pjax(
                'a:not([target="_blank"]):not([no-pjax]):not([href^="write-"]):not([href*="action/"]):not([href*="javascript"])',
                '.main-content',
                {
                    fragment: '.main-content',
                    timeout: 8000,
                    scrollTo: 0
                }
            );

            $(document).on('pjax:send', function() {
                NProgress.start();
                $('.dropdown-menu').removeClass('show');
                $('.tooltip').remove();
                $('.popover').remove();
                $('.modal-backdrop').remove();
                $('body').removeClass('modal-open').css('padding-right', '');
            });

            $(document).on('pjax:complete', function() {
                // pjax:complete 会在 HTML 替换后触发
                // 如果 HTML 中包含此 script，上面的重载入口会执行；如果没有，这里兜底执行
                window.reloadGlobalComponents();
            });

            $(document).on('pjax:timeout', function(event) {
                event.preventDefault();
            });
        }

        // 全局事件绑定 (防重复)
        $(document).off('click.menu').on('click.menu', '.menu-bar', function() {
            var nav = $(this).next('#typecho-nav-list');
            if (!$(this).toggleClass('focus').hasClass('focus')) {
                nav.removeClass('expanded noexpanded');
            }
        });

        $(document).off('submit.global').on('submit.global', 'form', function () {
            $('button[type=submit]', this).attr('disabled', 'disabled');
        });

        // 插件/通用确认逻辑
        $(document).off('click.confirm').on('click.confirm', '.btn-operate, a[lang]', function (e) {
            var t = $(this), msg = t.attr('lang');
            if (msg && !confirm(msg)) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    });
})();
</script>