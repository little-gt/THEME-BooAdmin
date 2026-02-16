<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'typecho.js'); ?>"></script>
<script>
    (function () {
        $(document).ready(function() {
            // 处理消息机制
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
                    } else {
                        p.prependTo(document.body);
                    }

                    p.slideDown(function () {
                        var t = $(this), color = '#C6D880';
                        
                        if (t.hasClass('error')) {
                            color = '#FBC2C4';
                        } else if (t.hasClass('notice')) {
                            color = '#FFD324';
                        }

                        t.effect('highlight', {color : color})
                            .delay(5000).fadeOut(function () {
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

            if ($('.typecho-login').length == 0) {                // 现代化分页样式优化 v2
                // 查找所有可能的类型，包括直接的 .typecho-pager，或者是包含分页链接的 div/nav
                var $pagers = $('.typecho-pager');
                
                // 如果找不到标准的，尝试寻找包含 li.prev/next 的容器
                if ($pagers.length === 0) {
                     // 尝试找到包含分页链接的容器
                    $pagers = $('li.prev, li.next, li.current').parent();
                }

                if ($pagers.length > 0) {
                    $pagers.each(function() {
                        var $pager = $(this);
                        
                        // 1. 容器样式优化
                        // 移除默认的 list-style，增加 flex 布局
                        $pager.addClass('flex flex-wrap justify-center items-center gap-2 mt-8 mb-6 pl-0 list-none');
                        // 强制移除原有样式干扰
                        $pager.css('list-style', 'none');

                        // 2. 列表项 li 样式优化
                        var $items = $pager.find('li');
                        $items.each(function() {
                            var $li = $(this);
                            
                            // 移除 li 的默认圆点
                            $li.addClass('list-none m-0 p-0 inline-flex');
                            $li.css('list-style', 'none'); 

                            // 3. 链接/文字内容样式优化
                            var $child = $li.children('a, span');
                            
                            // 基础形状和排版
                            // min-w-[2rem] h-8: 确保它是至少 32x32 的方块
                            // px-3: 给文字留出水平空间
                            $child.addClass('flex items-center justify-center min-w-[2rem] h-8 px-3 rounded-md text-sm font-medium transition-all duration-200 no-underline shadow-sm leading-none');
                            
                            // 针对不同状态的样式
                            if ($li.hasClass('current')) {
                                // 当前页：高亮背景
                                $child.addClass('bg-discord-accent text-white border border-discord-accent hover:bg-discord-accent hover:text-white cursor-default');
                            } else {
                                // 普通页/前后页：白底灰字，hover变色
                                $child.addClass('bg-white text-gray-500 hover:text-discord-text hover:bg-discord-light hover:border-discord-light border border-gray-200');
                            }
                        });
                    });
                }
                
                $('a').each(function () {
                    var t = $(this), href = t.attr('href');

                    if ((href && href[0] == '#')
                        || /^<?php echo preg_quote($options->adminUrl, '/'); ?>.*$/.exec(href) 
                            || /^<?php echo substr(preg_quote(\Typecho\Common::url('s', $options->index), '/'), 0, -1); ?>action\/[_a-zA-Z0-9\/]+.*$/.exec(href)) {
                        return;
                    }

                    t.attr('target', '_blank')
                        .attr('rel', 'noopener noreferrer');
                });
            }
        });
    })();
</script>
