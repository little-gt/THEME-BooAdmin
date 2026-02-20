<?php if(!defined('__TYPECHO_ADMIN__')) exit; ?>
<script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'typecho.js'); ?>"></script>
<script>
    (function () {
        $(document).ready(function() {
            // ========================================
            // 现代化通知系统
            // ========================================
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

                // 创建通知容器（如果不存在）
                if ($('#typecho-notification-container').length === 0) {
                    $('body').append('<div id="typecho-notification-container"></div>');
                }

                // 显示通知函数
                function showNotification(messages, type) {
                    type = type || 'info';
                    
                    // 图标映射
                    var icons = {
                        'success': '<i class="fas fa-check-circle"></i>',
                        'error': '<i class="fas fa-times-circle"></i>',
                        'notice': '<i class="fas fa-exclamation-triangle"></i>',
                        'info': '<i class="fas fa-info-circle"></i>',
                        'warning': '<i class="fas fa-exclamation-triangle"></i>'
                    };
                    
                    // 标题映射
                    var titles = {
                        'success': '<?php _e('操作成功'); ?>',
                        'error': '<?php _e('出错了'); ?>',
                        'notice': '<?php _e('注意'); ?>',
                        'info': '<?php _e('提示'); ?>',
                        'warning': '<?php _e('警告'); ?>'
                    };
                    
                    // 构建消息列表
                    var messageHTML = '';
                    if (Array.isArray(messages)) {
                        if (messages.length === 1) {
                            messageHTML = '<div class="typecho-notification-messages">' + messages[0] + '</div>';
                        } else {
                            messageHTML = '<ul class="typecho-notification-messages">';
                            messages.forEach(function(msg) {
                                messageHTML += '<li>' + msg + '</li>';
                            });
                            messageHTML += '</ul>';
                        }
                    } else {
                        messageHTML = '<div class="typecho-notification-messages">' + messages + '</div>';
                    }
                    
                    // 创建通知元素
                    var notification = $('<div class="typecho-notification ' + type + '">' +
                        '<div class="typecho-notification-icon">' + icons[type] + '</div>' +
                        '<div class="typecho-notification-content">' +
                            '<div class="typecho-notification-title">' + titles[type] + '</div>' +
                            messageHTML +
                        '</div>' +
                        '<button class="typecho-notification-close" aria-label="关闭">' +
                            '<i class="fas fa-times"></i>' +
                        '</button>' +
                    '</div>');
                    
                    // 添加到容器
                    $('#typecho-notification-container').append(notification);
                    
                    // 显示动画
                    setTimeout(function() {
                        notification.addClass('show');
                    }, 10);
                    
                    // 关闭按钮事件
                    notification.find('.typecho-notification-close').on('click', function() {
                        closeNotification(notification);
                    });
                    
                    // 5秒后自动关闭
                    setTimeout(function() {
                        closeNotification(notification);
                    }, 5000);
                }
                
                // 关闭通知函数
                function closeNotification(notification) {
                    notification.addClass('hide');
                    setTimeout(function() {
                        notification.remove();
                    }, 300);
                }
                
                // 检查并显示Cookie中的通知
                if (!!cookies.notice && 'success|notice|error|info|warning'.indexOf(cookies.noticeType) >= 0) {
                    try {
                        var messages = $.parseJSON(cookies.notice);
                        showNotification(messages, cookies.noticeType);
                    } catch(e) {
                        console.error('通知消息解析失败:', e);
                    }
                    
                    // 清除Cookie
                    $.cookie(prefix + '__typecho_notice', null, {path : path, domain: domain, secure: secure});
                    $.cookie(prefix + '__typecho_notice_type', null, {path : path, domain: domain, secure: secure});
                }

                // 高亮元素
                if (cookies.highlight) {
                    var $highlightEl = $('#' + cookies.highlight);
                    if ($highlightEl.length > 0) {
                        // 使用CSS动画进行高亮
                        $highlightEl.css({
                            'animation': 'highlight-flash 1s ease-in-out',
                            'animation-iteration-count': '2'
                        });
                        
                        // 添加临时样式
                        if (!$('#highlight-animation-style').length) {
                            $('<style id="highlight-animation-style">' +
                                '@keyframes highlight-flash {' +
                                    '0%, 100% { background-color: transparent; }' +
                                    '50% { background-color: rgba(88, 101, 242, 0.2); }' +
                                '}' +
                            '</style>').appendTo('head');
                        }
                    }
                    $.cookie(prefix + '__typecho_notice_highlight', null, {path : path, domain: domain, secure: secure});
                }
                
                // 全局通知方法（供其他脚本调用）
                window.TypechoNotification = {
                    show: showNotification,
                    success: function(messages) { showNotification(messages, 'success'); },
                    error: function(messages) { showNotification(messages, 'error'); },
                    notice: function(messages) { showNotification(messages, 'notice'); },
                    info: function(messages) { showNotification(messages, 'info'); },
                    warning: function(messages) { showNotification(messages, 'warning'); }
                };
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
            
            // Table scroll indicator
            function updateTableScrollIndicator() {
                $('.table-wrapper[data-table-scroll]').each(function() {
                    var $wrapper = $(this);
                    var scrollLeft = $wrapper.scrollLeft();
                    var scrollWidth = $wrapper[0].scrollWidth;
                    var clientWidth = $wrapper[0].clientWidth;
                    
                    // 已滚动到最左边
                    if (scrollLeft <= 1) {
                        $wrapper.removeClass('scrolled-start');
                    } else {
                        $wrapper.addClass('scrolled-start');
                    }
                    
                    // 已滚动到最右边
                    if (scrollLeft + clientWidth >= scrollWidth - 1) {
                        $wrapper.removeClass('scrolled-end');
                        $wrapper.addClass('scrolled-end');
                    } else {
                        $wrapper.removeClass('scrolled-end');
                    }
                });
            }
            
            // Initialize and bind events
            $('.table-wrapper[data-table-scroll]').on('scroll', updateTableScrollIndicator);
            $(window).on('resize', updateTableScrollIndicator);
            updateTableScrollIndicator(); // Initial check

            // ========================================
            // View Mode Toggle (Table/Card)
            // ========================================
            (function() {
                // Only initialize if we have the view toggle
                if ($('.view-toggle').length === 0) {
                    return;
                }
                
                var VIEW_MODE_KEY = 'typecho_list_view_mode';
                
                // Get saved view mode from localStorage
                function getSavedViewMode() {
                    try {
                        return localStorage.getItem(VIEW_MODE_KEY) || 'table';
                    } catch(e) {
                        return 'table';
                    }
                }
                
                // Save view mode to localStorage
                function saveViewMode(mode) {
                    try {
                        localStorage.setItem(VIEW_MODE_KEY, mode);
                    } catch(e) {
                        // Ignore localStorage errors
                    }
                }
                
                // Apply view mode to the page
                function applyViewMode(mode) {
                    var $container = $('.operate-form').closest('.bg-white');
                    
                    if (mode === 'card') {
                        $container.addClass('view-mode-card');
                        $('.view-toggle .btn-table-view').removeClass('active');
                        $('.view-toggle .btn-card-view').addClass('active');
                    } else {
                        $container.removeClass('view-mode-card');
                        $('.view-toggle .btn-table-view').addClass('active');
                        $('.view-toggle .btn-card-view').removeClass('active');
                    }
                }
                
                // Initialize view mode on page load
                var savedMode = getSavedViewMode();
                applyViewMode(savedMode);
                
                // Handle view toggle button clicks
                $('.view-toggle button').on('click', function(e) {
                    e.preventDefault();
                    var $btn = $(this);
                    var newMode = $btn.hasClass('btn-table-view') ? 'table' : 'card';
                    
                    saveViewMode(newMode);
                    applyViewMode(newMode);
                    
                    return false;
                });
                
                // Sync checkbox state between table and card views
                $(document).on('change', '.content-card .card-checkbox', function() {
                    var $checkbox = $(this);
                    var cid = $checkbox.val();
                    var isChecked = $checkbox.prop('checked');
                    
                    // Update corresponding table checkbox
                    $('.typecho-list-table input[type="checkbox"][value="' + cid + '"]').prop('checked', isChecked);
                    
                    // Update select all checkbox state
                    updateSelectAllState();
                });
                
                $(document).on('change', '.typecho-list-table input[type="checkbox"]:not(.typecho-table-select-all)', function() {
                    var $checkbox = $(this);
                    var cid = $checkbox.val();
                    var isChecked = $checkbox.prop('checked');
                    
                    // Update corresponding card checkbox
                    $('.content-card .card-checkbox[value="' + cid + '"]').prop('checked', isChecked);
                });
                
                function updateSelectAllState() {
                    var $allCheckboxes = $('.typecho-list-table input[type="checkbox"]:not(.typecho-table-select-all)');
                    var $checkedCheckboxes = $allCheckboxes.filter(':checked');
                    var $selectAll = $('.typecho-table-select-all');
                    
                    if ($checkedCheckboxes.length === 0) {
                        $selectAll.prop('checked', false).prop('indeterminate', false);
                    } else if ($checkedCheckboxes.length === $allCheckboxes.length) {
                        $selectAll.prop('checked', true).prop('indeterminate', false);
                    } else {
                        $selectAll.prop('checked', false).prop('indeterminate', true);
                    }
                }
            })();
        });
    })();
</script>
