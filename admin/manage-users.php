<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$users = \Widget\Users\Admin::alloc();
?>

<div class="container-fluid">
    
    <!-- 顶部标题与操作 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <p class="text-muted mb-0">
                        <i class="fa-solid fa-shield-halved me-1"></i><?php _e('管理站点的注册用户及其权限'); ?>
                    </p>
                    <a href="<?php $options->adminUrl('user.php'); ?>" class="btn btn-primary px-4 shadow-sm fw-bold">
                        <i class="fa-solid fa-user-plus me-2"></i><?php _e('新增用户'); ?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- 主要内容区 -->
    <div class="row fade-in-up" style="animation-delay: 0.1s;">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    
                    <form method="post" name="manage_users" class="operate-form">
                        
                        <!-- 工具栏：批量操作 & 搜索 -->
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
                            <div class="operate">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                    </button>
                                    <ul class="dropdown-menu shadow border-0 p-2" style="border-radius: 12px;">
                                        <li>
                                            <button type="button" class="dropdown-item rounded-2 text-danger btn-operate" lang="<?php _e('你确认要删除这些用户吗?'); ?>" rel="<?php $security->index('/action/users-edit?do=delete'); ?>">
                                                <i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <!-- 搜索框 -->
                            <div class="input-group" style="max-width: 300px;">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" class="form-control border-start-0 ps-0" placeholder="<?php _e('请输入关键字'); ?>" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" name="keywords">
                                <button type="submit" class="btn btn-primary fw-bold"><?php _e('筛选'); ?></button>
                                <?php if ('' != $request->keywords): ?>
                                    <a href="<?php $options->adminUrl('manage-users.php'); ?>" class="btn btn-outline-secondary" title="<?php _e('取消筛选'); ?>">
                                        <i class="fa-solid fa-xmark"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- 用户列表表格 -->
                        <div class="table-responsive">
                            <table class="table modern-table table-hover typecho-list-table">
                                <thead>
                                    <tr>
                                        <th width="40" class="text-center">
                                            <input type="checkbox" class="form-check-input typecho-table-select-all" />
                                        </th>
                                        <th width="60"></th> <!-- 头像列 -->
                                        <th><?php _e('用户名 / 昵称'); ?></th>
                                        <th><?php _e('电子邮件'); ?></th>
                                        <th><?php _e('用户组'); ?></th>
                                        <th class="text-center"><?php _e('文章'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if($users->have()): ?>
                                    <?php while($users->next()): ?>
                                    <tr id="user-<?php $users->uid(); ?>" class="align-middle">
                                        <td class="text-center">
                                            <input type="checkbox" value="<?php $users->uid(); ?>" name="uid[]" class="form-check-input" />
                                        </td>
                                        
                                        <!-- 头像 -->
                                        <td>
                                            <a href="<?php $options->adminUrl('user.php?uid=' . $users->uid); ?>">
                                                <img src="<?php echo \Typecho\Common::gravatarUrl($users->mail, 40, 'X', 'mm', $request->isSecure()); ?>" class="rounded-circle shadow-sm border" width="40" height="40" alt="Avatar">
                                            </a>
                                        </td>

                                        <!-- 用户名 & 昵称 -->
                                        <td>
                                            <div class="d-flex flex-column">
                                                <a href="<?php $options->adminUrl('user.php?uid=' . $users->uid); ?>" class="fw-bold text-dark text-decoration-none">
                                                    <?php $users->name(); ?>
                                                </a>
                                                <span class="small text-muted">
                                                    <?php $users->screenName(); ?>
                                                    <a href="<?php $users->permalink(); ?>" title="<?php _e('浏览 %s', $users->screenName); ?>" target="_blank" class="ms-1 text-muted opacity-50 hover-opacity-100">
                                                        <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 0.75rem;"></i>
                                                    </a>
                                                </span>
                                            </div>
                                        </td>

                                        <!-- 邮件 -->
                                        <td>
                                            <?php if ($users->mail): ?>
                                                <a href="mailto:<?php $users->mail(); ?>" class="text-secondary text-decoration-none font-monospace small">
                                                    <i class="fa-regular fa-envelope me-1"></i><?php $users->mail(); ?>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small"><?php _e('暂无'); ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- 用户组 (彩色徽章) -->
                                        <td>
                                            <?php 
                                            $badgeClass = 'bg-secondary';
                                            $icon = 'fa-user';
                                            switch ($users->group) {
                                                case 'administrator':
                                                    $badgeClass = 'bg-dark text-white';
                                                    $icon = 'fa-user-shield';
                                                    break;
                                                case 'editor':
                                                    $badgeClass = 'bg-primary text-white';
                                                    $icon = 'fa-user-pen';
                                                    break;
                                                case 'contributor':
                                                    $badgeClass = 'bg-info text-dark';
                                                    $icon = 'fa-feather';
                                                    break;
                                                case 'subscriber':
                                                    $badgeClass = 'bg-success text-white';
                                                    $icon = 'fa-user-check';
                                                    break;
                                                case 'visitor':
                                                    $badgeClass = 'bg-light text-muted border';
                                                    $icon = 'fa-user';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge rounded-pill <?php echo $badgeClass; ?> fw-normal px-3 py-2">
                                                <i class="fa-solid <?php echo $icon; ?> me-1"></i>
                                                <?php 
                                                switch ($users->group) {
                                                    case 'administrator': _e('管理员'); break;
                                                    case 'editor': _e('编辑'); break;
                                                    case 'contributor': _e('贡献者'); break;
                                                    case 'subscriber': _e('关注者'); break;
                                                    case 'visitor': _e('访问者'); break;
                                                    default: echo $users->group; break;
                                                } 
                                                ?>
                                            </span>
                                        </td>

                                        <!-- 文章数 -->
                                        <td class="text-center">
                                            <a href="<?php $options->adminUrl('manage-posts.php?__typecho_all_posts=off&uid=' . $users->uid); ?>" 
                                               class="badge bg-light text-primary border rounded-pill text-decoration-none px-3 py-2"
                                               data-bs-toggle="tooltip" title="<?php _e('查看 %s 发布的文章', $users->screenName); ?>">
                                                <i class="fa-solid fa-file-pen me-1"></i><?php $users->postsNum(); ?>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="fa-solid fa-user-slash fa-3x mb-3 opacity-25"></i>
                                                <p><?php _e('没有找到用户'); ?></p>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </form>

                    <!-- 分页 -->
                    <?php if($users->have()): ?>
                    <div class="mt-4 d-flex justify-content-center">
                        <?php $users->pageNav('&laquo;', '&raquo;', 3, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination pagination-modern', 'itemTag' => 'li', 'textTag' => 'span', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
                    </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
include 'table-js.php';
?>

<script type="text/javascript">
(function () {
    $(document).ready(function () {
        // 自定义全选逻辑 (因为表格结构变化，table-js.php 可能需要辅助)
        $('.typecho-table-select-all').click(function () {
            var checked = $(this).prop('checked');
            $('input[name="uid[]"]').prop('checked', checked).trigger('change');
        });

        // 选中行高亮
        $('input[name="uid[]"]').change(function() {
            var tr = $(this).closest('tr');
            if ($(this).prop('checked')) {
                tr.addClass('table-active');
            } else {
                tr.removeClass('table-active');
            }
        });

        // 批量操作按钮逻辑
        $('.btn-operate').click(function (e) {
            e.preventDefault();
            var btn = $(this);
            var msg = btn.attr('lang');
            var href = btn.attr('rel');

            if (confirm(msg)) {
                var form = btn.parents('form');
                form.attr('action', href).submit();
            }
        });
    });
})();
</script>

<?php include 'footer.php'; ?>