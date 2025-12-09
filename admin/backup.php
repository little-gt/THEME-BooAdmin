<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$actionUrl = $security->getTokenUrl(
    \Typecho\Router::url('do', array('action' => 'backup', 'widget' => 'Backup'),
        \Typecho\Common::url('index.php', $options->rootUrl)));

$backupFiles = \Widget\Backup::alloc()->listFiles();
?>

<div class="container-fluid">
    
    <!-- 顶部标题 -->
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-database me-2 text-primary"></i><?php _e('备份与恢复'); ?>
                        </h4>
                        <p class="text-muted mb-0 small">定期备份是保护数据安全的最佳方式</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
        
        <!-- 左侧：备份 (导出) -->
        <div class="col-lg-6">
            <div class="card-modern h-100">
                <div class="card-header bg-transparent border-bottom px-4 py-3">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-cloud-arrow-down me-2 text-primary"></i><?php _e('备份数据'); ?>
                    </h5>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="alert alert-info border-0 shadow-sm rounded-3 mb-4">
                        <div class="d-flex">
                            <i class="fa-solid fa-circle-info mt-1 me-3"></i>
                            <div>
                                <h6 class="alert-heading fw-bold mb-1"><?php _e('备份说明'); ?></h6>
                                <ul class="mb-0 ps-3 small opacity-75">
                                    <li><?php _e('此备份操作仅包含<strong>内容数据</strong> (文章、评论、分类等)'); ?></li>
                                    <li><?php _e('并不包含<strong>主题文件</strong>、<strong>插件文件</strong>或<strong>上传的图片</strong>'); ?></li>
                                    <li><?php _e('生成的 .dat 文件是 Typecho 专用格式'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded-3 border mb-4 text-center">
                        <i class="fa-solid fa-hard-drive fa-3x text-secondary mb-3 opacity-50"></i>
                        <p class="text-muted small">
                            <?php _e('如果您的数据量过大, 为了避免操作超时, 建议您直接使用数据库提供的备份工具 (如 phpMyAdmin) 备份数据'); ?>
                        </p>
                    </div>

                    <div class="mt-auto">
                        <form action="<?php echo $actionUrl; ?>" method="post">
                            <input tabindex="1" type="hidden" name="do" value="export">
                            <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm" type="submit">
                                <i class="fa-solid fa-file-export me-2"></i><?php _e('开始备份数据'); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- 右侧：恢复 (导入) -->
        <div class="col-lg-6">
            <div class="card-modern h-100">
                <div class="card-header bg-transparent border-bottom px-4 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0 text-dark">
                        <i class="fa-solid fa-rotate-left me-2 text-danger"></i><?php _e('恢复数据'); ?>
                    </h5>
                </div>
                <div class="card-body p-4">
                    
                    <!-- Tabs 导航 -->
                    <ul class="nav nav-pills nav-fill bg-light p-1 rounded-3 mb-4" id="restoreTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill small fw-bold" id="upload-tab" data-bs-toggle="pill" data-bs-target="#from-upload" type="button" role="tab"><?php _e('本地上传'); ?></button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill small fw-bold" id="server-tab" data-bs-toggle="pill" data-bs-target="#from-server" type="button" role="tab"><?php _e('从服务器选择'); ?></button>
                        </li>
                    </ul>

                    <div class="tab-content" id="restoreTabContent">
                        
                        <!-- Tab 1: 本地上传 -->
                        <div class="tab-pane fade show active" id="from-upload" role="tabpanel">
                            <div class="text-center py-4 mb-3 border border-2 border-dashed rounded-3">
                                <i class="fa-solid fa-upload fa-3x text-muted mb-3 opacity-50"></i>
                                <p class="text-muted small mb-0"><?php _e('请选择本地的 .dat 备份文件'); ?></p>
                            </div>

                            <form action="<?php echo $actionUrl; ?>" method="post" enctype="multipart/form-data" class="restore-form">
                                <div class="mb-4">
                                    <input tabindex="2" id="backup-upload-file" name="file" type="file" class="form-control">
                                </div>
                                <input type="hidden" name="do" value="import">
                                <button tabindex="4" type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                                    <i class="fa-solid fa-cloud-arrow-up me-2"></i><?php _e('上传并恢复'); ?>
                                </button>
                            </form>
                        </div>

                        <!-- Tab 2: 服务器文件 -->
                        <div class="tab-pane fade" id="from-server" role="tabpanel">
                            <?php if (empty($backupFiles)): ?>
                                <div class="text-center py-5">
                                    <i class="fa-regular fa-folder-open fa-3x text-muted mb-3 opacity-50"></i>
                                    <p class="text-muted small">
                                        <?php _e('服务器上没有发现备份文件'); ?>
                                    </p>
                                    <p class="text-secondary small bg-light p-2 rounded">
                                        <?php _e('请将备份文件手动上传至服务器的 <br><code>%s</code><br> 目录下', __TYPECHO_BACKUP_DIR__); ?>
                                    </p>
                                </div>
                            <?php else: ?>
                                <form action="<?php echo $actionUrl; ?>" method="post" class="restore-form">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold text-dark mb-2" for="backup-select-file"><?php _e('选择一个备份文件'); ?></label>
                                        <select tabindex="5" name="file" id="backup-select-file" class="form-select form-select-lg">
                                            <?php foreach ($backupFiles as $file): ?>
                                                <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <input type="hidden" name="do" value="import">
                                    <button tabindex="7" type="submit" class="btn btn-danger w-100 py-2 fw-bold">
                                        <i class="fa-solid fa-clock-rotate-left me-2"></i><?php _e('确认恢复数据'); ?>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>

                    </div>
                    
                    <!-- 警告提示 -->
                    <div class="mt-4 pt-3 border-top text-center">
                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3">
                            <i class="fa-solid fa-triangle-exclamation me-1"></i> <?php _e('注意'); ?>
                        </span>
                        <p class="text-danger small mt-2 mb-0">
                            <?php _e('恢复操作将清除所有现有数据，且不可撤销！'); ?>
                        </p>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

<?php
include 'copyright.php';
include 'common-js.php';
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 恢复操作的二次确认逻辑
    const restoreForms = document.querySelectorAll('.restore-form');
    
    restoreForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('<?php _e('恢复操作将清除所有现有数据, 是否继续?'); ?>')) {
                e.preventDefault();
                return false;
            }
        });
    });
});
</script>

<?php include 'footer.php'; ?>