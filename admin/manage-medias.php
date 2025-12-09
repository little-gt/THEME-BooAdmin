<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
$attachments = \Widget\Contents\Attachment\Admin::alloc();

// 定义文件类型图标映射
function getFileIcon($mime) {
    if (strpos($mime, 'image/') === 0) return 'fa-regular fa-image';
    if (strpos($mime, 'video/') === 0) return 'fa-regular fa-file-video text-danger';
    if (strpos($mime, 'audio/') === 0) return 'fa-regular fa-file-audio text-warning';
    if (strpos($mime, 'text/') === 0) return 'fa-regular fa-file-lines text-secondary';
    if (strpos($mime, 'application/pdf') !== false) return 'fa-regular fa-file-pdf text-danger';
    if (strpos($mime, 'zip') !== false || strpos($mime, 'compressed') !== false) return 'fa-regular fa-file-zipper text-warning';
    if (strpos($mime, 'word') !== false) return 'fa-regular fa-file-word text-primary';
    if (strpos($mime, 'excel') !== false || strpos($mime, 'spreadsheet') !== false) return 'fa-regular fa-file-excel text-success';
    if (strpos($mime, 'powerpoint') !== false || strpos($mime, 'presentation') !== false) return 'fa-regular fa-file-powerpoint text-danger';
    return 'fa-regular fa-file text-muted';
}
?>

<div class="container-fluid">

    <!-- 顶部操作栏 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body">
                    <form method="get" class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">

                        <!-- 批量操作区 -->
                        <div class="operate d-flex align-items-center gap-2 w-100 w-md-auto">
                            <div class="form-check me-2">
                                <input class="form-check-input typecho-table-select-all" type="checkbox" id="selectAll">
                                <label class="form-check-label fw-bold" for="selectAll"><?php _e('全选'); ?></label>
                            </div>

                            <div class="btn-group">
                                <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fa-solid fa-check-double me-2 text-primary"></i><?php _e('选中项'); ?>
                                </button>
                                <ul class="dropdown-menu shadow border-0 p-2" style="border-radius: 12px;">
                                    <li>
                                        <button type="button" class="dropdown-item rounded-2 text-danger btn-operate" lang="<?php _e('你确认要删除这些文件吗?'); ?>" rel="<?php $security->index('/action/contents-attachment-edit?do=delete'); ?>">
                                            <i class="fa-solid fa-trash me-2"></i><?php _e('删除'); ?>
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <a href="<?php $security->index('/action/contents-attachment-edit?do=clear'); ?>"
                               class="btn btn-outline-danger btn-operate"
                               lang="<?php _e('您确认要清理未归档的文件吗?'); ?>">
                               <i class="fa-solid fa-broom me-1"></i> <?php _e('清理未归档'); ?>
                            </a>
                        </div>

                        <!-- 搜索框 -->
                        <div class="input-group w-100 w-md-auto" style="max-width: 300px;">
                            <?php if ('' != $request->keywords): ?>
                                <a href="<?php $options->adminUrl('manage-medias.php'); ?>" class="btn btn-outline-secondary" title="<?php _e('取消筛选'); ?>">
                                    <i class="fa-solid fa-xmark"></i>
                                </a>
                            <?php endif; ?>
                            <input type="text" class="form-control" placeholder="<?php _e('搜索文件名...'); ?>" value="<?php echo htmlspecialchars($request->keywords ?? ''); ?>" name="keywords" />
                            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 媒体网格 -->
    <form method="post" name="manage_medias" class="operate-form">
        <div class="row g-3 fade-in-up" style="animation-delay: 0.1s;">
            <?php if($attachments->have()): ?>
                <?php while($attachments->next()): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-xl-2 col-xxl-2">
                        <div class="card h-100 card-modern media-card position-relative border-0 shadow-sm">

                            <!-- 复选框 (绝对定位) -->
                            <div class="position-absolute top-0 start-0 p-2 z-2">
                                <div class="form-check">
                                    <input type="checkbox" value="<?php $attachments->cid(); ?>" name="cid[]" class="form-check-input border-2 shadow-sm" style="width: 1.2em; height: 1.2em;">
                                </div>
                            </div>

                            <!-- 所属文章提示 (绝对定位) -->
                            <?php if ($attachments->parentPost->cid): ?>
                                <div class="position-absolute top-0 end-0 p-2 z-2" data-bs-toggle="tooltip" title="<?php _e('所属文章: %s', $attachments->parentPost->title); ?>">
                                    <a href="<?php $options->adminUrl('write-' . (0 === strpos($attachments->parentPost->type, 'post') ? 'post' : 'page') . '.php?cid=' . $attachments->parentPost->cid); ?>" class="badge bg-white text-primary rounded-circle shadow-sm p-2 text-decoration-none">
                                        <i class="fa-solid fa-link"></i>
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="position-absolute top-0 end-0 p-2 z-2">
                                    <span class="badge bg-warning text-dark shadow-sm"><?php _e('未归档'); ?></span>
                                </div>
                            <?php endif; ?>

                            <!-- 缩略图/图标区域 -->
                            <div class="media-preview bg-light d-flex align-items-center justify-content-center rounded-top-4 overflow-hidden position-relative group-hover-overlay">
                                <?php if($attachments->attachment->isImage): ?>
                                    <img src="<?php $attachments->attachment->url(); ?>" class="img-fluid w-100 h-100 object-fit-cover" alt="<?php $attachments->title(); ?>" loading="lazy">
                                <?php else: ?>
                                    <i class="<?php echo getFileIcon($attachments->attachment->mime); ?> fa-4x opacity-50"></i>
                                <?php endif; ?>

                                <!-- 悬停遮罩与操作 -->
                                <div class="media-overlay d-flex align-items-center justify-content-center gap-2">
                                    <a href="<?php $options->adminUrl('media.php?cid=' . $attachments->cid); ?>" class="btn btn-light btn-sm shadow-sm" title="<?php _e('编辑'); ?>">
                                        <i class="fa-solid fa-pen"></i>
                                    </a>
                                    <a href="<?php $attachments->attachment->url(); ?>" target="_blank" class="btn btn-light btn-sm shadow-sm" title="<?php _e('查看原图'); ?>">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                </div>
                            </div>

                            <!-- 文件信息 -->
                            <div class="card-body p-2 text-center">
                                <h6 class="text-truncate mb-1 fw-bold text-dark" title="<?php $attachments->title(); ?>">
                                    <?php $attachments->title(); ?>
                                </h6>
                                <div class="d-flex justify-content-between align-items-center px-1">
                                    <small class="text-muted font-monospace" style="font-size: 0.7rem;">
                                        <?php echo number_format(ceil($attachments->attachment->size / 1024)); ?> KB
                                    </small>
                                    <small class="text-muted" style="font-size: 0.7rem;">
                                        <?php $attachments->dateWord(); ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="card-modern text-center py-5">
                        <div class="text-muted opacity-50">
                            <i class="fa-regular fa-folder-open fa-4x mb-3"></i>
                            <h4><?php _e('没有任何文件'); ?></h4>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </form>

    <!-- 分页 -->
    <?php if ($attachments->have()): ?>
    <div class="mt-4 d-flex justify-content-center">
        <?php $attachments->pageNav('&laquo;', '&raquo;', 3, '...', array('wrapTag' => 'ul', 'wrapClass' => 'pagination pagination-modern', 'itemTag' => 'li', 'textTag' => 'span', 'currentClass' => 'active', 'prevClass' => 'prev', 'nextClass' => 'next')); ?>
    </div>
    <?php endif; ?>
</div>

<style>
/* 媒体网格专用样式 */
.media-card {
    transition: transform 0.2s, box-shadow 0.2s;
    overflow: hidden;
}
.media-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
}

/* 预览区固定高度 */
.media-preview {
    height: 160px; /* 默认高度 */
    background-image: radial-gradient(#e9ecef 1px, transparent 1px);
    background-size: 10px 10px;
}
@media (min-width: 1400px) {
    .media-preview { height: 180px; }
}

.object-fit-cover {
    object-fit: cover;
}

/* 悬停遮罩 */
.media-overlay {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.4);
    opacity: 0;
    transition: opacity 0.2s;
    backdrop-filter: blur(2px);
}
.media-card:hover .media-overlay {
    opacity: 1;
}

/* 选中状态高亮 */
.media-card.selected {
    border: 2px solid var(--primary-color) !important;
    background-color: var(--primary-soft);
}
</style>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script type="text/javascript">
(function () {
    $(document).ready(function () {
        // 自定义的全选逻辑 (适配 Grid View)
        // table-js.php 主要针对 table，这里手动实现更可靠
        $('.typecho-table-select-all').click(function () {
            var checked = $(this).prop('checked');
            $('input[name="cid[]"]').prop('checked', checked).trigger('change');
        });

        // 选中时高亮卡片
        $('input[name="cid[]"]').change(function () {
            var card = $(this).closest('.media-card');
            if ($(this).prop('checked')) {
                card.addClass('selected');
            } else {
                card.removeClass('selected');
            }
        });

        // 批量操作按钮逻辑
        $('.btn-operate').click(function (e) {
            e.preventDefault();
            var href = $(this).attr('href') || $(this).attr('rel'); // 兼容 a 和 button
            var msg = $(this).attr('lang');

            if (msg && !confirm(msg)) {
                return false;
            }

            var form = $('form[name="manage_medias"]');
            form.attr('action', href).submit();
        });
    });
})();
</script>

<?php include 'footer.php'; ?>