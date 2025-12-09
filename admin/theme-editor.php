<?php
include 'common.php';
include 'header.php';
include 'menu.php';

\Widget\Themes\Files::alloc()->to($files);
?>

<div class="container-fluid">
    
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <i class="fa-solid fa-paintbrush me-2 text-primary"></i><?php _e('编辑外观'); ?>
                        </h4>
                        <p class="text-muted mb-0 small">直接编辑主题文件代码</p>
                    </div>
                    <div>
                        <a href="https://forum.typecho.org/" target="_blank" class="btn btn-outline-primary px-4 fw-bold">
                            <i class="fa-solid fa-cart-plus me-2"></i><?php _e('获取更多主题'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row fade-in-up">
        <div class="col-12">
            
            <!-- 顶部导航 Tabs -->
            <div class="card-modern mb-4">
                <div class="card-body">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                        <ul class="nav nav-pills bg-light p-2 rounded-3">
                            <li class="nav-item">
                                <a class="nav-link text-muted" href="<?php $options->adminUrl('themes.php'); ?>"><?php _e('可以使用的外观'); ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active fw-bold shadow-sm" href="<?php $options->adminUrl('theme-editor.php'); ?>">
                                    <?php if ($options->theme == $files->theme): ?>
                                        <?php _e('编辑当前外观'); ?>
                                    <?php else: ?>
                                        <?php _e('编辑 %s 外观', '<span class="text-warning mx-1">' . $files->theme . '</span>'); ?>
                                    <?php endif; ?>
                                </a>
                            </li>
                            <?php if (\Widget\Themes\Config::isExists()): ?>
                            <li class="nav-item">
                                <a class="nav-link text-muted" href="<?php $options->adminUrl('options-theme.php'); ?>"><?php _e('设置外观'); ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>

                        <!-- 当前编辑文件提示 -->
                        <div class="d-flex align-items-center text-muted small">
                            <i class="fa-solid fa-file-code me-2"></i>
                            <span class="font-monospace"><?php echo $files->currentFile(); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 主编辑器区域 -->
            <div class="typecho-edit-theme">
                <div class="row g-4">
                    
                    <!-- 右侧：文件列表 (在移动端显示在上方或折叠，这里遵循 PC 优先，放在右侧更像 IDE) -->
                    <!-- 注意：Typecho 原生设计是文件列表在右，编辑器在左，这里我们保持这个逻辑但优化样式 -->
                    <div class="col-lg-3 order-lg-2">
                        <div class="card-modern h-100">
                            <div class="card-header bg-transparent border-bottom px-4 py-3">
                                <h5 class="fw-bold mb-0 text-dark small text-uppercase ls-1">
                                    <i class="fa-solid fa-folder-tree me-2 text-primary"></i><?php _e('模板文件'); ?>
                                </h5>
                            </div>
                            <div class="card-body p-0" style="max-height: 800px; overflow-y: auto;">
                                <div class="list-group list-group-flush">
                                    <?php while($files->next()): ?>
                                        <?php 
                                            // 根据扩展名判断图标
                                            $ext = pathinfo($files->file, PATHINFO_EXTENSION);
                                            $iconClass = 'fa-file-code';
                                            $iconColor = 'text-secondary';
                                            if($ext == 'css') { $iconClass = 'fa-brands fa-css3-alt'; $iconColor = 'text-primary'; }
                                            elseif($ext == 'js') { $iconClass = 'fa-brands fa-js'; $iconColor = 'text-warning'; }
                                            elseif($ext == 'php') { $iconClass = 'fa-brands fa-php'; $iconColor = 'text-info'; }
                                            elseif($ext == 'png' || $ext == 'jpg') { $iconClass = 'fa-regular fa-image'; $iconColor = 'text-success'; }
                                        ?>
                                        <a href="<?php $options->adminUrl('theme-editor.php?theme=' . $files->currentTheme() . '&file=' . $files->file); ?>" 
                                           class="list-group-item list-group-item-action border-0 py-2 px-4 d-flex align-items-center <?php if ($files->current): ?>active fw-bold bg-light-primary border-start border-4 border-primary text-primary<?php else: ?>text-muted<?php endif; ?>">
                                            <i class="<?php echo $iconClass . ' ' . $iconColor; ?> me-3" style="width: 20px; text-align: center;"></i>
                                            <span class="text-truncate font-monospace small"><?php $files->file(); ?></span>
                                            <?php if ($files->current): ?>
                                                <i class="fa-solid fa-pen-nib ms-auto small"></i>
                                            <?php endif; ?>
                                        </a>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 左侧：代码编辑器 -->
                    <div class="col-lg-9 order-lg-1">
                        <div class="card-modern h-100 p-0 overflow-hidden d-flex flex-column">
                            
                            <form method="post" name="theme" id="theme" action="<?php $security->index('/action/themes-edit'); ?>" class="h-100 d-flex flex-column">
                                
                                <!-- 编辑器 Toolbar -->
                                <div class="bg-light border-bottom px-4 py-2 d-flex justify-content-between align-items-center">
                                    <div class="small text-muted">
                                        <?php if ($files->currentIsWriteable()): ?>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                                <i class="fa-solid fa-pen me-1"></i> <?php _e('可写入'); ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill">
                                                <i class="fa-solid fa-lock me-1"></i> <?php _e('不可写'); ?>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-light text-muted" onclick="location.reload();" title="<?php _e('重置'); ?>">
                                            <i class="fa-solid fa-rotate-right"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- 代码输入区域 -->
                                <div class="flex-grow-1 position-relative">
                                    <label for="content" class="visually-hidden"><?php _e('编辑源码'); ?></label>
                                    <textarea name="content" id="content" class="form-control border-0 rounded-0 w-100 h-100 font-monospace p-3 text-dark bg-white" 
                                              style="resize: none; font-size: 14px; line-height: 1.6; tab-size: 4; outline: none !important;"
                                              spellcheck="false"
                                              <?php if (!$files->currentIsWriteable()): ?>readonly<?php endif; ?>><?php echo $files->currentContent(); ?></textarea>
                                </div>

                                <!-- 底部提交栏 -->
                                <div class="card-footer bg-white border-top p-3 d-flex justify-content-between align-items-center">
                                    <div class="text-muted small">
                                        <i class="fa-solid fa-code-branch me-1"></i> <?php echo $files->currentTheme(); ?> / <?php echo $files->currentFile(); ?>
                                    </div>
                                    <div>
                                        <?php if ($files->currentIsWriteable()): ?>
                                            <input type="hidden" name="theme" value="<?php echo $files->currentTheme(); ?>" />
                                            <input type="hidden" name="edit" value="<?php echo $files->currentFile(); ?>" />
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                                <i class="fa-solid fa-floppy-disk me-2"></i><?php _e('保存文件'); ?>
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-secondary rounded-pill px-4 disabled">
                                                <i class="fa-solid fa-lock me-2"></i><?php _e('无法保存'); ?>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* 列表激活状态样式 */
.list-group-item.active {
    background-color: var(--primary-soft);
    border-left-width: 4px;
}

/* 编辑器区域微调 */
textarea#content {
    min-height: 600px;
    font-family: 'Fira Code', 'Consolas', 'Monaco', 'Courier New', monospace; 
}
textarea#content:focus {
    box-shadow: none;
    border-color: transparent;
}
</style>

<?php
include 'copyright.php';
include 'common-js.php';

// 插件钩子：例如有些插件会在这里加载高亮编辑器 (CodeMirror/Ace)
// 如果使用了这些插件，上面的 textarea 会被自动替换，我们的容器布局依然兼容
\Typecho\Plugin::factory('admin/theme-editor.php')->bottom($files);

include 'footer.php';
?>

<script>
// 简单的 Tab 键支持 (在 textarea 中输入 Tab 而不是切换焦点)
document.getElementById('content').addEventListener('keydown', function(e) {
  if (e.key == 'Tab') {
    e.preventDefault();
    var start = this.selectionStart;
    var end = this.selectionEnd;

    // set textarea value to: text before caret + tab + text after caret
    this.value = this.value.substring(0, start) +
      "\t" + this.value.substring(end);

    // put caret at right position again
    this.selectionStart = this.selectionEnd = start + 1;
  }
});
</script>