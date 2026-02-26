<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$comments = \Widget\Comments\Admin::alloc();
$isAllComments = ('on' == $request->get('__typecho_all_comments') || 'on' == \Typecho\Cookie::get('__typecho_all_comments'));
?>
<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-comments mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('管理评论'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="<?php $options->siteUrl(); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('查看网站'); ?>" target="_blank">
                <i class="fas fa-globe"></i>
            </a>
            <a href="<?php $options->adminUrl('profile.php'); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('个人资料'); ?>">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="w-full max-w-none mx-auto">
            
            <!-- Tabs & Filters -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <div class="flex items-center bg-gray-100 p-1 select-none self-start">
                     <a href="<?php $options->adminUrl('manage-comments.php' . (isset($request->cid) ? '?cid=' . $request->filter('encode')->cid : '')); ?>" 
                        class="px-4 py-1.5 text-sm font-medium transition-all duration-200 <?php if (!isset($request->status) || 'approved' == $request->get('status')): ?>bg-white text-discord-text<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>">
                        <?php _e('已通过'); ?>
                     </a>
                     <a href="<?php $options->adminUrl('manage-comments.php?status=waiting' . (isset($request->cid) ? '&cid=' . $request->filter('encode')->cid : '')); ?>" 
                        class="px-4 py-1.5 text-sm font-medium transition-all duration-200 <?php if ('waiting' == $request->get('status')): ?>bg-white text-discord-text<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>">
                        <?php _e('待审核'); ?>
                        <?php if(!$isAllComments && $stat->myWaitingCommentsNum > 0 && !isset($request->cid)): ?> 
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs"><?php $stat->myWaitingCommentsNum(); ?></span>
                        <?php elseif($isAllComments && $stat->waitingCommentsNum > 0 && !isset($request->cid)): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs"><?php $stat->waitingCommentsNum(); ?></span>
                        <?php elseif(isset($request->cid) && $stat->currentWaitingCommentsNum > 0): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs"><?php $stat->currentWaitingCommentsNum(); ?></span>
                        <?php endif; ?>
                     </a>
                     <a href="<?php $options->adminUrl('manage-comments.php?status=spam' . (isset($request->cid) ? '&cid=' . $request->filter('encode')->cid : '')); ?>" 
                        class="px-4 py-1.5 text-sm font-medium transition-all duration-200 <?php if ('spam' == $request->get('status')): ?>bg-white text-discord-text<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>">
                        <?php _e('垃圾'); ?>
                        <?php if(!$isAllComments && $stat->mySpamCommentsNum > 0 && !isset($request->cid)): ?> 
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs"><?php $stat->mySpamCommentsNum(); ?></span>
                        <?php elseif($isAllComments && $stat->spamCommentsNum > 0 && !isset($request->cid)): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs"><?php $stat->spamCommentsNum(); ?></span>
                        <?php elseif(isset($request->cid) && $stat->currentSpamCommentsNum > 0): ?>
                            <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 text-xs"><?php $stat->currentSpamCommentsNum(); ?></span>
                        <?php endif; ?>
                     </a>
                </div>

                <form method="get" class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center bg-white  border border-gray-100 focus-within:border-discord-accent transition-colors search-input-container">
                        <div class="px-3 flex items-center">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" name="keywords" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" placeholder="<?php _e('请输入关键字'); ?>" class="pr-3 py-1.5 bg-white text-sm focus:outline-none w-48 md:w-64 border-0">
                    </div>
                    <?php if(isset($request->status)): ?>
                        <input type="hidden" value="<?php echo $request->filter('html')->status; ?>" name="status" />
                    <?php endif; ?>
                    <?php if(isset($request->cid)): ?>
                        <input type="hidden" value="<?php echo $request->filter('html')->cid; ?>" name="cid" />
                    <?php endif; ?>
                    <button type="submit" class="px-3 py-1.5 bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors text-sm font-medium"><?php _e('筛选'); ?></button>
                    <?php if ('' != $request->keywords || '' != $request->category): ?>
                        <a href="<?php $options->adminUrl('manage-comments.php' . (isset($request->status) || isset($request->cid) ? '?' . (isset($request->status) ? 'status=' . $request->filter('encode')->status : '') . (isset($request->cid) ? (isset($request->status) ? '&' : '') . 'cid=' . $request->filter('encode')->cid : '') : '')); ?>" class="px-2 py-1 bg-gray-200 hover:bg-gray-300 transition-colors text-xs text-gray-600"><?php _e('取消筛选'); ?></a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Comment List -->
            <div class="bg-white border border-gray-200 overflow-hidden">
                <form method="post" name="manage_comments" class="operate-form">
                    <div class="p-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50 operate-bar">
                         <div class="flex items-center space-x-2">
                             <label class="flex items-center space-x-2 text-sm text-gray-500 cursor-pointer select-none">
                                 <input type="checkbox" class="typecho-table-select-all text-discord-accent focus:ring-discord-accent border-gray-300">
                                 <span><?php _e('全选'); ?></span>
                             </label>
                             <div class="relative group">
                                <button type="button" class="btn-dropdown-toggle px-3 py-1 text-xs font-medium bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 flex items-center">
                                    <?php _e('选中项'); ?> <i class="fas fa-chevron-down ml-1"></i>
                                </button>
                                <div class="dropdown-menu absolute left-0 mt-1 w-40 bg-white border border-gray-100 py-1 hidden group-hover:block z-50">
                                    <a href="<?php $security->index('/action/comments-edit?do=approved'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('通过'); ?></a>
                                    <a href="<?php $security->index('/action/comments-edit?do=waiting'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('待审核'); ?></a>
                                    <a href="<?php $security->index('/action/comments-edit?do=spam'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('标记垃圾'); ?></a>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="<?php $security->index('/action/comments-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                </div>
                             </div>
                             <?php if('spam' == $request->get('status')): ?>
                                <button lang="<?php _e('你确认要删除所有垃圾评论吗?'); ?>" class="px-3 py-1 text-xs font-medium bg-red-50 border border-red-200 hover:bg-red-100 text-red-600 btn-operate" href="<?php $security->index('/action/comments-edit?do=delete-spam'); ?>"><?php _e('删除所有垃圾评论'); ?></button>
                             <?php endif; ?>
                         </div>
                         <?php if($user->pass('editor', true) && !isset($request->cid)): ?>
                            <div class="flex items-center space-x-2 text-xs">
                                <a href="<?php echo $request->makeUriByRequest('__typecho_all_comments=on'); ?>" class="<?php if($isAllComments): ?>text-discord-accent font-bold<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>"><?php _e('所有'); ?></a>
                                <span class="text-gray-300">|</span>
                                <a href="<?php echo $request->makeUriByRequest('__typecho_all_comments=off'); ?>" class="<?php if(!$isAllComments): ?>text-discord-accent font-bold<?php else: ?>text-gray-500 hover:text-discord-text<?php endif; ?>"><?php _e('我的'); ?></a>
                            </div>
                         <?php endif; ?>
                    </div>

                    <div class="table-wrapper" data-table-scroll>
                    <table class="w-full text-left border-collapse typecho-list-table">
                        <thead>
                            <tr class="text-xs font-bold text-gray-500 uppercase border-b border-gray-100 bg-gray-50/50">
                                <th class="w-10 pl-4 py-3"></th>
                                <th class="w-16 py-3 text-center"><i class="fas fa-user-circle"></i></th>
                                <th class="py-3"><?php _e('作者'); ?></th>
                                <th class="py-3"><?php _e('内容'); ?></th>
                                <th class="py-3 w-48 text-right pr-4"><?php _e('操作'); ?></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php if($comments->have()): ?>
                                <?php while($comments->next()): ?>
                                    <tr id="<?php $comments->theId(); ?>" data-comment="<?php
                                    $comment = array(
                                        'author'    =>  $comments->author,
                                        'mail'      =>  $comments->mail,
                                        'url'       =>  $comments->url,
                                        'ip'        =>  $comments->ip,
                                        'type'        =>  $comments->type,
                                        'text'      =>  $comments->text
                                    );

                                    echo htmlspecialchars(json_encode($comment));
                                    ?>" class="group hover:bg-gray-50 transition-colors">
                                        <td class="pl-4 py-3 align-top">
                                            <input type="checkbox" value="<?php $comments->coid(); ?>" name="coid[]" class="text-discord-accent focus:ring-discord-accent border-gray-300 mt-1">
                                        </td>
                                        <td class="py-3 text-center align-top">
                                            <div class="w-10 h-10 overflow-hidden bg-gray-200 mx-auto">
                                                <?php if ('comment' == $comments->type): ?>
                                                    <?php $comments->gravatar(40, null, true); ?>
                                                <?php else: ?>
                                                    <div class="flex items-center justify-center w-full h-full text-gray-500"><i class="fas fa-quote-right"></i></div>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="py-3 align-top text-sm">
                                            <div class="font-medium text-discord-text">
                                                <?php if($comments->url): ?>
                                                    <a href="<?php $comments->url(); ?>" target="_blank" class="hover:underline"><?php $comments->author(); ?></a>
                                                <?php else: ?>
                                                    <?php $comments->author(); ?>
                                                <?php endif; ?>
                                            </div>
                                            <div class="text-xs text-gray-400 mt-0.5">
                                                <?php if($comments->mail): ?>
                                                    <a href="mailto:<?php $comments->mail(); ?>" class="hover:text-discord-accent block"><?php $comments->mail(); ?></a>
                                                <?php endif; ?>
                                                <span class="block mt-0.5"><?php $comments->ip(); ?></span>
                                            </div>
                                        </td>
                                        <td class="py-3 align-top text-sm text-gray-600">
                                            <div class="mb-1 text-xs text-gray-400">
                                                <?php $comments->dateWord(); ?> 于 <a href="<?php $comments->permalink(); ?>" class="text-discord-accent hover:underline"><?php $comments->title(); ?></a>
                                            </div>
                                            <div class="comment-content prose prose-sm max-w-none text-gray-700">
                                                <?php $comments->content(); ?>
                                            </div>
                                        </td>
                                        <td class="py-3 pr-4 text-right align-top text-sm">
                                            <div class="flex flex-col items-end space-y-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <a href="#<?php $comments->theId(); ?>" rel="<?php $security->index('/action/comments-edit?do=reply&coid=' . $comments->coid); ?>" class="operate-reply text-discord-accent hover:underline"><i class="fas fa-reply mr-1"></i><?php _e('回复'); ?></a>
                                                <a href="#<?php $comments->theId(); ?>" rel="<?php $security->index('/action/comments-edit?do=edit&coid=' . $comments->coid); ?>" class="operate-edit text-gray-500 hover:text-discord-accent"><i class="fas fa-edit mr-1"></i><?php _e('编辑'); ?></a>
                                                
                                                <?php if('approved' == $comments->status): ?>
                                                    <!-- Current status approved -->
                                                <?php else: ?>
                                                    <a href="<?php $security->index('/action/comments-edit?do=approved&coid=' . $comments->coid); ?>" class="operate-approved text-green-600 hover:underline"><?php _e('通过'); ?></a>
                                                <?php endif; ?>
                                                
                                                <?php if('spam' == $comments->status): ?>
                                                    <!-- Current status spam -->
                                                <?php else: ?>
                                                    <a href="<?php $security->index('/action/comments-edit?do=spam&coid=' . $comments->coid); ?>" class="operate-spam text-orange-500 hover:underline"><?php _e('标为垃圾'); ?></a>
                                                <?php endif; ?>
                                                
                                                <a lang="<?php _e('你确认要删除%s的评论吗?', htmlspecialchars($comments->author)); ?>" href="<?php $security->index('/action/comments-edit?do=delete&coid=' . $comments->coid); ?>" class="operate-delete text-red-500 hover:underline"><i class="fas fa-trash-alt mr-1"></i><?php _e('删除'); ?></a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-500">
                                        <div class="mb-2 text-4xl text-gray-300"><i class="far fa-comments"></i></div>
                                        <?php _e('没有找到任何评论'); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    </div>
                    
                    <?php if(isset($request->cid)): ?>
                    <input type="hidden" value="<?php echo $request->filter('html')->cid; ?>" name="cid" />
                    <?php endif; ?>
                </form>
            </div>
            
            <?php if ($comments->have()): ?>
                <div class="mt-4 flex justify-end">
                     <?php $comments->pageNav('&laquo;', '&raquo;', 1, '...', array(
                         'wrapTag' => 'ul', 
                         'wrapClass' => 'flex items-center space-x-1 typecho-pager list-none', 
                         'itemTag' => 'li', 
                         'textTag' => 'span', 
                         'currentClass' => 'current', 
                         'prevClass' => 'prev', 
                         'nextClass' => 'next'
                     )); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <!-- Footer在main内部 -->
    <?php include 'copyright.php'; ?>
</main>
<style>
.typecho-pager li a, .typecho-pager li span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    background-color: white;
    color: #4b5563; /* text-gray-600 */
    font-size: 0.875rem; /* text-sm */
    border: 1px solid #e5e7eb; /* border-gray-200 */
    transition: all 0.2s;
    text-decoration: none;
}

.typecho-pager li a:hover {
    background-color: #f3f4f6; /* bg-gray-100 */
    color: #5865F2; /* text-discord-accent */
    border-color: #d1d5db; /* border-gray-300 */
}

.typecho-pager li.current span {
    background-color: #5865F2; /* bg-discord-accent */
    color: white;
    border-color: #5865F2;
    font-weight: 600;
}

.comment-content img {
    max-width: 100%;
}
</style>
<?php
include 'common-js.php';
include 'table-js.php';
?>
<script type="text/javascript">
$(document).ready(function () {
    // 记住滚动条
    function rememberScroll () {
        $(window).bind('beforeunload', function () {
            $.cookie('__typecho_comments_scroll', $('body').scrollTop());
        });
    }

    // 自动滚动
    (function () {
        var scroll = $.cookie('__typecho_comments_scroll');

        if (scroll) {
            $.cookie('__typecho_comments_scroll', null);
            $('html, body').scrollTop(scroll);
        }
    })();

    $('.operate-delete').click(function () {
        var t = $(this), href = t.attr('href'), tr = t.parents('tr');

        if (confirm(t.attr('lang'))) {
            tr.fadeOut(function () {
                rememberScroll();
                window.location.href = href;
            });
        }

        return false;
    });

    $('.operate-approved, .operate-waiting, .operate-spam').click(function () {
        rememberScroll();
        window.location.href = $(this).attr('href');
        return false;
    });

    $('.operate-reply').click(function () {
        var td = $(this).parents('td'), t = $(this);

        if ($('.comment-reply', td).length > 0) {
            $('.comment-reply').remove();
        } else {
            var form = $('<form method="post" action="'
                + t.attr('rel') + '" class="comment-reply mt-2 p-3 bg-gray-50 border border-gray-200">'
                + '<p><label for="text" class="sr-only"><?php _e('内容'); ?></label><textarea id="text" name="text" class="w-full p-2 border border-gray-300 text-sm focus:outline-none focus:border-discord-accent" rows="3"></textarea></p>'
                + '<p class="mt-2 flex space-x-2"><button type="submit" class="px-3 py-1 bg-discord-accent text-white text-sm hover:bg-blue-600 transition-colors"><?php _e('回复'); ?></button> <button type="button" class="px-3 py-1 bg-gray-200 text-gray-700 text-sm hover:bg-gray-300 transition-colors cancel"><?php _e('取消'); ?></button></p>'
                + '</form>').appendTo($('.comment-content', t.parents('tr')));

            $('.cancel', form).click(function () {
                $(this).parents('.comment-reply').remove();
            });

            var textarea = $('textarea', form).focus();

            form.submit(function () {
                var t = $(this), tr = t.parents('tr'), 
                    reply = $('<div class="comment-reply-content mt-2 p-2 bg-blue-50 text-blue-800 text-sm border border-blue-100"></div>').insertAfter($('.comment-content', tr));

                var html = DOMPurify.sanitize(textarea.val(), {USE_PROFILES: {html: true}});
                reply.html('<p>' + html + '</p>');
                $.post(t.attr('action'), t.serialize(), function (o) {
                    var html = DOMPurify.sanitize(o.comment.content, {USE_PROFILES: {html: true}});
                    reply.html(html)
                        .effect('highlight');
                }, 'json');

                t.remove();
                return false;
            });
        }

        return false;
    });

    $('.operate-edit').click(function () {
        var tr = $(this).parents('tr'), t = $(this), id = tr.attr('id'), comment = tr.data('comment');
        tr.hide();

        var edit = $('<tr class="comment-edit bg-white"><td colspan="5" class="p-4 border-b border-gray-200">'
                        + '<form method="post" action="' + t.attr('rel') + '" class="comment-edit-info space-y-4">'
                        + '<div class="grid grid-cols-1 md:grid-cols-3 gap-4">'
                        + '<div><label for="' + id + '-author" class="block text-sm font-medium text-gray-700 mb-1"><?php _e('用户名'); ?></label><input class="w-full px-3 py-1.5 border border-gray-300 text-sm focus:outline-none focus:border-discord-accent" id="' + id + '-author" name="author" type="text"></div>'
                        + '<div><label for="' + id + '-mail" class="block text-sm font-medium text-gray-700 mb-1"><?php _e('电子邮箱'); ?></label><input class="w-full px-3 py-1.5 border border-gray-300 text-sm focus:outline-none focus:border-discord-accent" type="email" name="mail" id="' + id + '-mail"></div>'
                        + '<div><label for="' + id + '-url" class="block text-sm font-medium text-gray-700 mb-1"><?php _e('个人主页'); ?></label><input class="w-full px-3 py-1.5 border border-gray-300 text-sm focus:outline-none focus:border-discord-accent" type="text" name="url" id="' + id + '-url"></div>'
                        + '</div>'
                        + '<div><label for="' + id + '-text" class="block text-sm font-medium text-gray-700 mb-1"><?php _e('内容'); ?></label><textarea name="text" id="' + id + '-text" rows="6" class="w-full px-3 py-2 border border-gray-300 text-sm focus:outline-none focus:border-discord-accent"></textarea></div>'
                        + '<div class="flex space-x-2"><button type="submit" class="px-4 py-2 bg-discord-accent text-white hover:bg-blue-600 transition-colors text-sm"><?php _e('提交'); ?></button> '
                        + '<button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 hover:bg-gray-300 transition-colors text-sm cancel"><?php _e('取消'); ?></button></div>'
                        + '</form></td></tr>')
                        .data('id', id).data('comment', comment).insertAfter(tr);

        $('input[name=author]', edit).val(comment.author);
        $('input[name=mail]', edit).val(comment.mail);
        $('input[name=url]', edit).val(comment.url);
        $('textarea[name=text]', edit).val(comment.text).focus();

        $('.cancel', edit).click(function () {
            var tr = $(this).parents('tr');

            $('#' + tr.data('id')).show();
            tr.remove();
        });

        $('form', edit).submit(function () {
            var t = $(this), tr = t.parents('tr'),
                oldTr = $('#' + tr.data('id')),
                comment = oldTr.data('comment');

            $('form', tr).each(function () {
                var items  = $(this).serializeArray();

                for (var i = 0; i < items.length; i ++) {
                    var item = items[i];
                    comment[item.name] = item.value;
                }
            });

            var unsafeHTML = '<div class="font-medium text-discord-text">'
                + (comment.url ? '<a target="_blank" href="' + comment.url + '" class="hover:underline">'
                + comment.author + '</a>' : comment.author) + '</div>'
                + '<div class="text-xs text-gray-400 mt-0.5">'
                + (comment.mail ? '<a href="mailto:' + comment.mail + '" class="hover:text-discord-accent block">'
                + comment.mail + '</a>' : '')
                + (comment.ip ? '<span class="block mt-0.5">' + comment.ip + '</span>' : '') + '</div>';

            var html = DOMPurify.sanitize(unsafeHTML, {USE_PROFILES: {html: true}});
            var content = DOMPurify.sanitize(comment.text, {USE_PROFILES: {html: true}});
            // Update the author info column (3rd column)
            oldTr.find('td:eq(2)').html(html).effect('highlight');
            // Update content
            oldTr.find('.comment-content').html(content);
            oldTr.data('comment', comment);

            $.post(t.attr('action'), comment, function (o) {
                var content = DOMPurify.sanitize(o.comment.content, {USE_PROFILES: {html: true}});
                oldTr.find('.comment-content').html(content).effect('highlight');
            }, 'json');
            
            oldTr.show();
            tr.remove();

            return false;
        });

        return false;
    });
});
</script>

