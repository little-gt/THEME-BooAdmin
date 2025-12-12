<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>

<!-- 标记脚本，防止 PJAX 重复加载基础库 -->
<script>
if (!window.typechoAdminJsLoaded) {
    window.typechoAdminJsLoaded = true;

    // 动态加载 CSS
    var link = document.createElement('link');
    link.rel = 'stylesheet';
    link.href = '<?php $options->adminStaticUrl('css', 'nprogress.css'); ?>';
    document.head.appendChild(link);

        // 动态加载 JS 库 (按顺序)
        var scripts = [
            '<?php $options->adminStaticUrl('js', 'jquery.js'); ?>',
            '<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>',
            '<?php $options->adminStaticUrl('js', 'jquery.pjax.js'); ?>',
            '<?php $options->adminStaticUrl('js', 'typecho.js'); ?>',
            '<?php $options->adminStaticUrl('js', 'nprogress.js'); ?>',
        ];

        function loadScripts(index) {
            if (index >= scripts.length) {
                initAdminLogic(); // 所有库加载完后执行初始化
                return;
            }
            var script = document.createElement('script');
            script.src = scripts[index];
            script.onload = function() { loadScripts(index + 1); };
            document.body.appendChild(script);
        }

        loadScripts(0);
    } else {
        // 如果是 PJAX 重载，仅触发页面特定逻辑，跳过库加载
        // 强制结束可能卡住的进度条
        if (typeof NProgress !== 'undefined') NProgress.done();
    }

    function initAdminLogic() {
        $(document).ready(function() {
            // 1. 消息提示机制
            (function () {
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
                    var head = $('.typecho-head-nav'),
                        p = $('<div class="message popup ' + cookies.noticeType + '">'
                        + '<ul><li>' + $.parseJSON(cookies.notice).join('</li><li>') 
                        + '</li></ul></div>'), offset = 0;

                    if (head.length > 0) {
                        p.insertAfter(head);
                        offset = head.outerHeight();
                    } else {
                        p.prependTo(document.body);
                    }

                    p.slideDown(function () {
                        var t = $(this), color = '#C6D880';
                        if (t.hasClass('error')) color = '#FBC2C4';
                        else if (t.hasClass('notice')) color = '#FFD324';

                        t.effect('highlight', {color : color}).delay(5000).fadeOut(function () {
                            $(this).remove();
                        });
                    });

                    $.cookie(prefix + '__typecho_notice', null, {path : path, domain: domain, secure: secure});
                    $.cookie(prefix + '__typecho_notice_type', null, {path : path, domain: domain, secure: secure});
                }

                if (cookies.highlight) {
                    $('#' + cookies.highlight).effect('highlight', 1000);
                    $.cookie(prefix + '__typecho_notice_highlight', null, {path : path, domain: domain, secure: secure});
                }
            })();

            // 2. 菜单与链接逻辑
            // 导航菜单 tab 聚焦时展开下拉菜单
            const menuBar = $('.menu-bar').click(function () {
                const nav = $(this).next('#typecho-nav-list');
                if (!$(this).toggleClass('focus').hasClass('focus')) {
                    nav.removeClass('expanded noexpanded');
                }
            });

            $('.main, .typecho-foot').on('click touchstart', function () {
                if (menuBar.hasClass('focus')) {
                    menuBar.trigger('click');
                }
            });

            if ($('.typecho-login').length == 0) {
                $('a').each(function () {
                    var t = $(this), href = t.attr('href');
                    if ((href && href[0] == '#')
                        || /^<?php echo preg_quote($options->adminUrl, '/'); ?>.*$/.exec(href) 
                            || /^<?php echo substr(preg_quote(\Typecho\Common::url('s', $options->index), '/'), 0, -1); ?>action\/[_a-zA-Z0-9\/]+.*$/.exec(href)) {
                        return;
                    }
                    t.attr('target', '_blank').attr('rel', 'noopener noreferrer');
                });
            }

            $('.main form').submit(function () {
                $('button[type=submit]', this).attr('disabled', 'disabled');
            });
        });
    }
    </script>