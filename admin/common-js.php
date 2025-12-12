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
    z-index: 1080;
    min-width: 300px;
    padding: 10px 20px;
    border-radius: 50rem; /* 圆角胶囊状 */
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    font-weight: bold;
    text-align: center;
    display: none; /* 默认隐藏 */
    animation: slideDownFade 0.3s ease-out;
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

<script>
/**
 * ============================================================================
 * 功能：检查并显示 Typecho 全局消息
 * 触发：页面加载 / PJAX 完成 / 表单提交回调
 * ============================================================================
 */
window.checkTypechoNotice = function() {
    var prefix = '<?php echo \Typecho\Cookie::getPrefix(); ?>',
        cookies = {
            notice      :   $.cookie(prefix + '__typecho_notice'),
            noticeType  :   $.cookie(prefix + '__typecho_notice_type'),
            highlight   :   $.cookie(prefix + '__typecho_notice_highlight')
        },
        path = '<?php echo \Typecho\Cookie::getPath(); ?>',
        domain = '<?php echo \Typecho\Cookie::getDomain(); ?>',
        secure = <?php echo json_encode(\Typecho\Cookie::getSecure()); ?>;

    // 清理旧的消息元素，防止堆叠
    $('.message.popup').remove();

    if (!!cookies.notice && 'success|notice|error'.indexOf(cookies.noticeType) >= 0) {
        var html = '<div class="message popup ' + cookies.noticeType + '">'
                 + $.parseJSON(cookies.notice).join('.')
                 + '</div>';

        var p = $(html);
        p.appendTo(document.body);

        // 动画显示与自动消失
        p.fadeIn(200).delay(3000).fadeOut(500, function () {
            $(this).remove();
        });

        // 销毁 Cookie，防止刷新再次出现
        $.cookie(prefix + '__typecho_notice', null, {path : path, domain: domain, secure: secure});
        $.cookie(prefix + '__typecho_notice_type', null, {path : path, domain: domain, secure: secure});
    }

    // 高亮特定元素 (Typecho 原生功能)
    if (cookies.highlight) {
        $('#' + cookies.highlight).effect('highlight', 1000);
        $.cookie(prefix + '__typecho_notice_highlight', null, {path : path, domain: domain, secure: secure});
    }
};

/**
 * ============================================================================
 * 核心逻辑：UI 渲染/劫持函数
 * 作用：将 Typecho 原生 HTML 转换为 Modern Bootstrap 风格
 * 触发时机：页面初次加载 + PJAX 每次跳转完成
 * ============================================================================
 */
window.renderBootstrapUI = function() {

    // --- 1. 执行消息检查 (关键修复：每次渲染UI时都检查是否有消息) ---
    window.checkTypechoNotice();

    // --- 2. 表单元素美化 ---
    $('.typecho-option input:not([type=radio]):not([type=checkbox]):not([type=submit]):not([type=hidden])').addClass('form-control');
    $('.typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');

    // Label & Description
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');

    // 必填项红星
    $('.typecho-option label.required').each(function() {
        if ($(this).find('span.text-danger').length === 0) {
            var html = $(this).html().replace('*', ' <span class="text-danger">*</span>');
            $(this).html(html);
        }
    });

    // 提交按钮容器
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-end gap-2');
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 fw-bold shadow-sm rounded-pill');
    $('.typecho-option-submit a').addClass('btn btn-light rounded-pill');

    // 单选/复选框组优化
    $('.typecho-option span').each(function() {
        var $span = $(this);
        if ($span.find('input[type=radio], input[type=checkbox]').length > 0) {
            $span.addClass('form-check d-inline-block me-3');
            $span.find('input').addClass('form-check-input');
            $span.find('label').addClass('form-check-label').removeClass('typecho-label');
        }
    });

    // 多行文本处理
    $('.typecho-option .multiline').addClass('d-block mb-1');

    // 隐藏无效的占位 LI
    $('.typecho-option li').each(function() {
        if ($(this).find('input[type=hidden]').length > 0 && $(this).children().length === 1) {
            $(this).hide();
        }
    });

    // 自动聚焦
    $('.typecho-option input[type=text]:visible:first').focus();

    // 强制清理可能残留的 NProgress (防止 PJAX 卡进度条)
    if (typeof NProgress !== 'undefined') NProgress.done();
};

/**
 * ============================================================================
 * 环境初始化逻辑 (Loaders)
 * 作用：加载 JS 库，绑定全局事件 (Menu/PJAX)
 * 触发时机：全生命周期只执行一次 (幂等保护)
 * ============================================================================
 */
if (!window.typechoAdminJsLoaded) {
    window.typechoAdminJsLoaded = true;

    // A. 动态加载 CSS
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = '<?php $options->adminStaticUrl('css', 'nprogress.css'); ?>';
    document.head.appendChild(link);

    // B. 定义依赖库
    var scripts = [
        '<?php $options->adminStaticUrl('js', 'jquery.js'); ?>',
        '<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>',
        '<?php $options->adminStaticUrl('js', 'typecho.js'); ?>',
        '<?php $options->adminStaticUrl('js', 'nprogress.js'); ?>',
        '<?php $options->adminStaticUrl('js', 'jquery.pjax.js'); ?>'
    ];

    // C. 顺序加载器
    function loadScripts(index) {
        if (index >= scripts.length) {
            initGlobalEnvironment(); // 库加载完毕，初始化全局环境
            return;
        }
        var script = document.createElement('script');
        script.src = scripts[index];
        script.onload = function() { loadScripts(index + 1); };
        document.body.appendChild(script);
    }

    loadScripts(0);

} else {
    // === PJAX 重载入口 ===
    // 这里的代码会在 common-js.php 再次被 PJAX 加载时执行
    $(function() {
        window.renderBootstrapUI();
    });
}

/**
 * 全局初始化函数 (只运行一次)
 */
function initGlobalEnvironment() {
    $(document).ready(function() {

        // 1. 首次渲染 UI (含消息检查)
        window.renderBootstrapUI();

        // 2. 绑定 PJAX
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

            $(document).on('pjax:start', function() { NProgress.start(); });

            // PJAX 完成后，重新触发 UI 渲染
            $(document).on('pjax:complete', function() {
                window.renderBootstrapUI();

                // 重新处理侧边栏高亮
                var currentUrl = window.location.href;
                $('.sidebar .nav-link').removeClass('active');
                $('.sidebar .nav-link').each(function() {
                    if (currentUrl.indexOf($(this).attr('href')) !== -1 && $(this).attr('href') !== 'index.php') {
                        $(this).addClass('active');
                    }
                });
            });
        }

        // 3. 全局事件委托 (菜单交互)
        $(document).on('click', '.menu-bar', function() {
            var nav = $(this).next('#typecho-nav-list');
            if (!$(this).toggleClass('focus').hasClass('focus')) {
                nav.removeClass('expanded noexpanded');
            }
        });

        // 4. 防止表单重复提交
        $(document).on('submit', 'form', function () {
            $('button[type=submit]', this).attr('disabled', 'disabled');
        });
    });
}
</script>