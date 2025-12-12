<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';

// 初始化统计组件，用于获取评论相关统计数据
$stat = \Widget\Stat::alloc();
// 初始化评论管理组件，用于获取和操作评论数据
$comments = \Widget\Comments\Admin::alloc();

// 判断是否显示所有评论或仅当前用户评论
$isAllComments = ('on' == $request->get('__typecho_all_comments') || 'on' == \Typecho\Cookie::get('__typecho_all_comments'));
?>

<div class="container-fluid">

    <!-- 顶部状态筛选栏 - 评论状态Tabs和所有/我的评论切换 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">

                    <!-- 评论状态 Tabs (已通过, 待审核, 垃圾) -->
                    <ul class="nav nav-pills mb-3 mb-md-0 gap-2">
                        <li class="nav-item">
                            <a class="nav-link <?php if(!isset($request->status) || 'approved' == $request->get('status')): ?>active<?php endif; ?>"
                               href="<?php $options->adminUrl('manage-comments.php' . (isset($request->cid) ? '?cid=' . $request->filter('encode')->cid : '')); ?>">
                               <i class="fa-solid fa-check-circle me-2"></i><?php _e('已通过'); ?>
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link <?php if('waiting' == $request->get('status')): ?>active<?php endif; ?>"
                               href="<?php $options->adminUrl('manage-comments.php?status=waiting' . (isset($request->cid) ? '&cid=' . $request->filter('encode')->cid : '')); ?>">
                               <i class="fa-solid fa-hourglass-half me-2"></i><?php _e('待审核'); ?>
                               <?php // 显示待审核评论数量徽章
                                $waitingNum = 0;
                                if(!$isAllComments && $stat->myWaitingCommentsNum > 0 && !isset($request->cid)) $waitingNum = $stat->myWaitingCommentsNum;
                                elseif($isAllComments && $stat->waitingCommentsNum > 0 && !isset($request->cid)) $waitingNum = $stat->waitingCommentsNum;
                                elseif(isset($request->cid) && $stat->currentWaitingCommentsNum > 0) $waitingNum = $stat->currentWaitingCommentsNum;

                                if ($waitingNum > 0): ?>
                                   <span class="badge bg-danger ms-2"><?php echo $waitingNum; ?></span>
                               <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item position-relative">
                            <a class="nav-link <?php if('spam' == $request->get('status')): ?>active<?php endif; ?>"
                               href="<?php $options->adminUrl('manage-comments.php?status=spam' . (isset($request->cid) ? '&cid=' . $request->filter('encode')->cid : '')); ?>">
                               <i class="fa-solid fa-trash-can me-2"></i><?php _e('垃圾'); ?>
                               <?php // 显示垃圾评论数量徽章
                                $spamNum = 0;
                                if(!$isAllComments && $stat->mySpamCommentsNum > 0 && !isset($request->cid)) $spamNum = $stat->mySpamCommentsNum;
                                elseif($isAllComments && $stat->spamCommentsNum > 0 && !isset($request->cid)) $spamNum = $stat->spamCommentsNum;
                                elseif(isset($request->cid) && $stat->currentSpamCommentsNum > 0) $spamNum = $stat->currentSpamCommentsNum;

                                if ($spamNum > 0): ?>
                                   <span class="badge bg-secondary ms-2"><?php echo $spamNum; ?></span>
                               <?php endif; ?>
                            </a>
                        </li>
                    </ul>

                    <!-- 权限切换 (所有评论 / 我的评论) -->
                    <?php if($user->pass('editor', true) && !isset($request->cid)): ?>
                    <div class="btn-group shadow-sm">
                        <a href="<?php echo $request->makeUriByRequest('__typecho_all_comments=on'); ?>"
                           class="btn btn-sm <?php if($isAllComments): ?>btn-primary<?php else: ?>btn-outline-primary<?php endif; ?>">
                           <?php _e('所有'); ?>
                        </a>
                        <a href="<?php echo $request->makeUriByRequest('__typecho_all_comments=off'); ?>"
                           class="btn btn-sm <?php if(!$isAllComments): ?>btn-primary<?php else: ?>btn-outline-primary<?php endif; ?>">
                           <?php _e('我的'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 评论列表主体区域 -->
    <div class="row" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">

                    <!-- 工具栏 - 批量操作和评论搜索 -->
                    <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
                        <form method="get" class="d-flex gap-2 flex-grow-1 operate-form-get">
                            <!-- 批量操作下拉菜单 -->
                            <div class="btn-group">
                                <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                </button>
                                <ul class="dropdown-menu shadow border-0" style="border-radius: 12px;">
                                    <li><a class="dropdown-item" href="<?php $security->index('/action/comments-edit?do=approved'); ?>"><i class="fa-solid fa-check me-2 text-success"></i><?php _e('通过'); ?></a></li>
                                    <li><a class="dropdown-item" href="<?php $security->index('/action/comments-edit?do=waiting'); ?>"><i class="fa-solid fa-clock me-2 text-warning"></i><?php _e('待审核'); ?></a></li>
                                    <li><a class="dropdown-item" href="<?php $security->index('/action/comments-edit?do=spam'); ?>"><i class="fa-solid fa-ban me-2 text-secondary"></i><?php _e('标记垃圾'); ?></a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" lang="<?php _e('你确认要删除这些评论吗?'); ?>" href="<?php $security->index('/action/comments-edit?do=delete'); ?>"><i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?></a></li>
                                </ul>
                            </div>

                            <?php if('spam' == $request->get('status')): // 仅在垃圾评论页显示清空垃圾评论按钮 ?>
                                <button lang="<?php _e('你确认要删除所有垃圾评论吗?'); ?>" class="btn btn-danger btn-operate" href="<?php $security->index('/action/comments-edit?do=delete-spam'); ?>">
                                    <i class="fa-solid fa-dumpster-fire me-1"></i> <?php _e('清空垃圾评论'); ?>
                                </button>
                            <?php endif; ?>

                            <!-- 搜索输入框 -->
                            <div class="input-group ms-auto" style="max-width: 300px;">
                                <?php if ('' != $request->keywords || '' != $request->category): // 显示取消筛选按钮 ?>
                                <a href="<?php $options->adminUrl('manage-comments.php' . (isset($request->status) || isset($request->cid) ? '?' . (isset($request->status) ? 'status=' . $request->filter('encode')->status : '') . (isset($request->cid) ? (isset($request->status) ? '&' : '') . 'cid=' . $request->filter('encode')->cid : '') : '')); ?>"
                                   class="btn btn-outline-secondary" title="<?php _e('取消筛选'); ?>"><i class="fa-solid fa-xmark"></i></a>
                                <?php endif; ?>

                                <input type="text" class="form-control" placeholder="<?php _e('搜索评论...'); ?>" value="<?php echo $request->filter('html')->keywords; ?>" name="keywords" />
                                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>

                                <?php // 隐藏的筛选参数，确保筛选条件持久化 ?>
                                <?php if(isset($request->status)): ?>
                                    <input type="hidden" value="<?php echo $request->filter('html')->status; ?>" name="status" />
                                <?php endif; ?>
                                <?php if(isset($request->cid)): ?>
                                    <input type="hidden" value="<?php echo $request->filter('html')->cid; ?>" name="cid" />
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- 评论列表表格 -->
                    <form method="post" name="manage_comments" class="operate-form">
                        <div class="table-responsive">
                            <table class="table modern-table table-hover typecho-list-table">
                                <thead class="d-none d-md-table-header-group">
                                    <tr>
                                        <th width="40" class="text-center">
                                            <input type="checkbox" class="form-check-input typecho-table-select-all" />
                                        </th>
                                        <th width="60"></th> <!-- 头像列 -->
                                        <th width="200"><?php _e('作者'); ?></th>
                                        <th><?php _e('内容'); ?></th>
                                        <th width="150" class="text-end"><?php _e('操作'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($comments->have()): // 遍历评论列表 ?>
                                <?php while($comments->next()): ?>
                                <tr id="<?php $comments->theId(); ?>" data-comment="<?php // 存储评论JSON数据，用于前端快速编辑 ?>
                                    <?php
                                    $comment = array(
                                        'author'    =>  $comments->author,
                                        'mail'      =>  $comments->mail,
                                        'url'       =>  $comments->url,
                                        'ip'        =>  $comments->ip,
                                        'type'      =>  $comments->type,
                                        'text'      =>  $comments->text
                                    );
                                    echo htmlspecialchars(json_encode($comment));
                                ?>" class="<?php if('waiting' == $comments->status) echo 'table-warning'; // 待审核评论高亮显示 ?>">

                                    <!-- 复选框 -->
                                    <td class="text-center align-top pt-4">
                                        <input type="checkbox" value="<?php $comments->coid(); ?>" name="coid[]" class="form-check-input" />
                                    </td>

                                    <!-- 作者头像 -->
                                    <td class="align-top pt-3">
                                        <div class="position-relative">
                                            <?php if ('comment' == $comments->type): ?>
                                                <img src="<?php echo \Typecho\Common::gravatarUrl($comments->mail, 48, 'X', 'mm', $request->isSecure()); ?>" class="rounded-circle shadow-sm" width="40" height="40">
                                            <?php else: // 非普通评论，如引用或pingback ?>
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted" style="width: 40px; height: 40px;">
                                                    <i class="fa-solid fa-quote-left"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- 作者信息（名称、邮箱、IP） -->
                                    <td class="align-top pt-3">
                                        <div class="mb-1">
                                            <?php if($comments->url): ?>
                                                <a href="<?php $comments->url(); ?>" target="_blank" class="fw-bold text-dark text-decoration-none">
                                                    <?php $comments->author(); ?> <i class="fa-solid fa-arrow-up-right-from-square small text-muted opacity-50 ms-1" style="font-size:0.7em;"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="fw-bold text-dark"><?php $comments->author(); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="small text-muted mb-1">
                                            <?php if($comments->mail): ?>
                                                <a href="mailto:<?php $comments->mail(); ?>" class="text-muted text-decoration-none"><i class="fa-regular fa-envelope me-1"></i><?php $comments->mail(); ?></a>
                                            <?php endif; ?>
                                        </div>
                                        <div class="small text-muted font-monospace bg-light d-inline-block px-2 rounded">
                                            <?php $comments->ip(); ?>
                                        </div>
                                    </td>

                                    <!-- 评论内容 (气泡样式) -->
                                    <td class="align-top pt-3">
                                        <div class="comment-bubble p-3 rounded-3 bg-light position-relative">
                                            <!-- 气泡箭头装饰 -->
                                            <div class="comment-arrow"></div>

                                            <div class="d-flex justify-content-between align-items-start mb-2 small text-muted">
                                                <span>
                                                    <?php _e('于'); ?> <a href="<?php $comments->permalink(); ?>" class="fw-bold text-primary text-decoration-none"><?php $comments->title(); ?></a>
                                                </span>
                                                <span><?php $comments->dateWord(); ?></span>
                                            </div>

                                            <div class="comment-content text-break">
                                                <?php $comments->content(); ?>
                                            </div>

                                            <?php if('waiting' == $comments->status): ?>
                                                <div class="mt-2">
                                                    <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i><?php _e('待审核'); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <!-- 操作按钮（回复、通过、编辑、垃圾、删除） -->
                                    <td class="align-top pt-3 text-end">
                                        <div class="btn-group btn-group-sm opacity-50 hover-opacity-100">
                                            <?php if('approved' == $comments->status): ?>
                                                <?php if('comment' == $comments->type): // 仅评论类型显示回复按钮 ?>
                                                <a href="#<?php $comments->theId(); ?>" rel="<?php $security->index('/action/comments-edit?do=reply&coid=' . $comments->coid); ?>" class="btn btn-outline-primary operate-reply" title="<?php _e('回复'); ?>">
                                                    <i class="fa-solid fa-reply"></i>
                                                </a>
                                                <?php endif; ?>
                                            <?php else: ?>
                                                <!-- 通过评论按钮 -->
                                                <a href="<?php $security->index('/action/comments-edit?do=approved&coid=' . $comments->coid); ?>" class="btn btn-outline-success operate-approved" title="<?php _e('通过'); ?>">
                                                    <i class="fa-solid fa-check"></i>
                                                </a>
                                            <?php endif; ?>

                                            <!-- 编辑评论按钮 -->
                                            <a href="#<?php $comments->theId(); ?>" rel="<?php $security->index('/action/comments-edit?do=edit&coid=' . $comments->coid); ?>" class="btn btn-outline-secondary operate-edit" title="<?php _e('编辑'); ?>">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>

                                            <!-- 标记垃圾评论按钮（非垃圾评论状态下显示） -->
                                            <?php if('spam' != $comments->status): ?>
                                            <a href="<?php $security->index('/action/comments-edit?do=spam&coid=' . $comments->coid); ?>" class="btn btn-outline-warning operate-spam" title="<?php _e('标记垃圾'); ?>">
                                                <i class="fa-solid fa-ban"></i>
                                            </a>
                                            <?php endif; ?>

                                            <!-- 删除评论按钮 -->
                                            <a lang="<?php _e('你确认要删除%s的评论吗?', htmlspecialchars($comments->author)); ?>" href="<?php $security->index('/action/comments-edit?do=delete&coid=' . $comments->coid); ?>" class="btn btn-outline-danger operate-delete" title="<?php _e('删除'); ?>">
                                                <i class="fa-solid fa-trash"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: // 没有找到评论时显示提示信息 ?>
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fa-regular fa-comment-dots fa-3x mb-3 opacity-50"></i>
                                            <p><?php _e('没有找到任何评论'); ?></p>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <?php // 隐藏的文章ID参数，用于评论筛选 ?>
                        <?php if(isset($request->cid)): ?>
                        <input type="hidden" value="<?php echo $request->filter('html')->cid; ?>" name="cid" />
                        <?php endif; ?>
                    </form>

                    <!-- 分页导航 -->
                    <?php if($comments->have()): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <?php $comments->pageNav('&laquo;', '&raquo;', 3, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination pagination-modern', 'itemTag' => 'li', 'textTag' => 'span', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 评论页专用样式 */

/* 评论气泡样式 */
.comment-bubble {
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    position: relative;
    transition: background-color 0.2s;
}

.comment-bubble:hover {
    background-color: #fff;
    border-color: var(--primary-light);
    box-shadow: 0 4px 15px rgba(0,0,0,0.03);
}

/* 气泡左侧的小箭头 */
.comment-arrow {
    position: absolute;
    top: 15px;
    left: -6px;
    width: 10px;
    height: 10px;
    background-color: #f8f9fa;
    border-left: 1px solid #e9ecef;
    border-bottom: 1px solid #e9ecef;
    transform: rotate(45deg);
    transition: background-color 0.2s;
}
.comment-bubble:hover .comment-arrow {
    background-color: #fff;
    border-color: var(--primary-light);
}

/* 待审核评论状态的高亮样式 */
.table-warning .comment-bubble {
    background-color: #fff3cd;
    border-color: #ffecb5;
}
.table-warning .comment-arrow {
    background-color: #fff3cd;
    border-color: #ffecb5;
}

/* 操作按钮悬停透明度 */
.hover-opacity-100:hover {
    opacity: 1 !important;
}

/* 快速回复/编辑框的样式 */
.comment-reply, .comment-edit {
    margin-top: 15px;
    padding: 15px;
    background: #fff;
    border: 1px solid var(--primary-light);
    border-radius: 12px;
    box-shadow: 0 5px 20px rgba(108, 92, 231, 0.1);
}
.comment-reply textarea, .comment-edit textarea {
    width: 100%;
    min-height: 100px;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 10px;
}
.comment-reply textarea:focus, .comment-edit textarea:focus {
    border-color: var(--primary-color);
    outline: none;
}
</style>

<?php
// 引入版权信息、通用JS和表格JS
include 'copyright.php';
include 'common-js.php';
include 'table-js.php'; // 包含表格交互JS，如全选、批量操作

// JavaScript 代码
?>
<script type="text/javascript">
$(document).ready(function () {
    // --- 评论列表交互逻辑 ---

    // 记住滚动条位置，以便页面刷新或重定向后恢复
    function rememberScroll () {
        $(window).bind('beforeunload', function () {
            $.cookie('__typecho_comments_scroll', $('body').scrollTop());
        });
    }

    // 页面加载时自动滚动到之前的位置
    (function () {
        var scroll = $.cookie('__typecho_comments_scroll');
        if (scroll) {
            $.cookie('__typecho_comments_scroll', null);
            $('html, body').scrollTop(scroll);
        }
    })();

    // 批量删除评论的确认弹窗
    $('.operate-delete').click(function () {
        var t = $(this), href = t.attr('href'), tr = t.parents('tr');
        if (confirm(t.attr('lang'))) {
            tr.fadeOut(function () {
                rememberScroll(); // 记住滚动位置后跳转
                window.location.href = href;
            });
        }
        return false;
    });

    // 批准、待审核、标记垃圾评论的跳转操作
    $('.operate-approved, .operate-waiting, .operate-spam').click(function () {
        rememberScroll(); // 记住滚动位置后跳转
        window.location.href = $(this).attr('href');
        return false;
    });

    // 快速回复评论功能
    $('.operate-reply').click(function () {
        var td = $(this).parents('td'), t = $(this);

        // 如果回复表单已存在，则移除
        if ($('.comment-reply', td).length > 0) {
            $('.comment-reply').remove();
        } else {
            // 构建并插入回复表单
            var form = $('<form method="post" action="'
                + t.attr('rel') + '" class="comment-reply">'
                + '<div class="mb-2"><label for="text" class="form-label fw-bold text-primary small"><?php _e('回复内容'); ?></label><textarea id="text" name="text" class="form-control" rows="3"></textarea></div>'
                + '<div class="d-flex justify-content-end gap-2"><button type="button" class="btn btn-sm btn-light cancel"><?php _e('取消'); ?></button> <button type="submit" class="btn btn-sm btn-primary"><?php _e('回复评论'); ?></button></div>'
                + '</form>').insertAfter($('.comment-bubble', td).next()); // 插入到评论气泡下方

            // 取消按钮点击事件
            $('.cancel', form).click(function () {
                $(this).parents('.comment-reply').remove();
            });

            // 聚焦到文本域
            var textarea = $('textarea', form).focus();

            // 回复表单提交事件
            form.submit(function () {
                var t = $(this), tr = t.parents('tr'),
                    // 在原评论内容下方显示回复内容
                    reply = $('<div class="comment-reply-content mt-3 p-3 bg-white border rounded"></div>').insertAfter($('.comment-content', tr));

                // 使用 DOMPurify 清理 HTML，防止 XSS 攻击
                var html = DOMPurify.sanitize(textarea.val(), {USE_PROFILES: {html: true}});
                reply.html('<p class="mb-0">' + html + '</p>'); // 立即显示回复内容

                // 异步提交回复数据
                $.post(t.attr('action'), t.serialize(), function (o) {
                    var html = DOMPurify.sanitize(o.comment.content, {USE_PROFILES: {html: true}});
                    reply.html(html).effect('highlight'); // 收到服务器响应后高亮显示
                }, 'json');

                t.remove(); // 移除回复表单
                return false;
            });
        }
        return false;
    });

    // 快速编辑评论功能
    $('.operate-edit').click(function () {
        var tr = $(this).parents('tr'), t = $(this), id = tr.attr('id'), comment = tr.data('comment');
        tr.hide(); // 隐藏原始评论行

        // 构建并插入编辑表单行
        var edit = $('<tr class="comment-edit-row"><td></td><td colspan="4">'
                        + '<form method="post" action="' + t.attr('rel') + '" class="comment-edit card p-4 shadow-sm">'
                        + '<div class="row g-3">'
                        + '<div class="col-md-4"><label class="form-label small text-muted"><?php _e('用户名'); ?></label><input class="form-control form-control-sm" name="author" type="text"></div>'
                        + '<div class="col-md-4"><label class="form-label small text-muted"><?php _e('电子邮箱'); ?></label><input class="form-control form-control-sm" type="email" name="mail"></div>'
                        + '<div class="col-md-4"><label class="form-label small text-muted"><?php _e('个人主页'); ?></label><input class="form-control form-control-sm" type="text" name="url"></div>'
                        + '<div class="col-12"><label class="form-label small text-muted"><?php _e('内容'); ?></label><textarea name="text" rows="6" class="form-control"></textarea></div>'
                        + '<div class="col-12 text-end"><button type="button" class="btn btn-sm btn-light cancel me-2"><?php _e('取消'); ?></button><button type="submit" class="btn btn-sm btn-primary"><?php _e('保存修改'); ?></button></div>'
                        + '</div></form></td></tr>')
                        .data('id', id).data('comment', comment).insertAfter(tr);

        // 填充表单数据
        $('input[name=author]', edit).val(comment.author);
        $('input[name=mail]', edit).val(comment.mail);
        $('input[name=url]', edit).val(comment.url);
        $('textarea[name=text]', edit).val(comment.text).focus();

        // 取消按钮点击事件
        $('.cancel', edit).click(function () {
            var tr = $(this).parents('tr');
            $('#' + tr.data('id')).show(); // 显示原始评论行
            tr.remove(); // 移除编辑表单行
        });

        // 编辑表单提交事件
        $('form', edit).submit(function () {
            var t = $(this), tr = t.parents('tr'),
                oldTr = $('#' + tr.data('id')),
                comment = oldTr.data('comment');

            // 更新 comment 数据对象
            $('form', tr).each(function () {
                var items  = $(this).serializeArray();
                for (var i = 0; i < items.length; i ++) {
                    var item = items[i];
                    comment[item.name] = item.value;
                }
            });

            // 提交数据并刷新页面以显示最新状态
            $.post(t.attr('action'), comment, function (o) {
                // 由于DOM结构可能复杂，直接刷新页面以确保一致性
                location.reload();
            }, 'json');

            return false;
        });

        return false;
    });
});
</script>

<?php include 'footer.php'; ?>