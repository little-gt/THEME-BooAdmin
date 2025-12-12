<?php
include 'common.php';
include 'header.php';
include 'menu.php';
\Widget\Contents\Page\Edit::alloc()->to($page);

// 准备附件数据
if ($page->have()) {
    \Widget\Contents\Attachment\Related::alloc(['parentId' => $page->cid])->to($attachment);
} else {
    \Widget\Contents\Attachment\Unattached::alloc()->to($attachment);
}

// 获取 PHP 上传限制
$phpMaxFilesize = function_exists('ini_get') ? trim(ini_get('upload_max_filesize')) : 0;
if (preg_match("/^([0-9]+)([a-z]{1,2})$/i", $phpMaxFilesize, $matches)) {
    $phpMaxFilesize = strtolower($matches[1] . $matches[2] . (1 == strlen($matches[2]) ? 'b' : ''));
}
?>

<div class="container-fluid">
    <div class="row rounded-pill">
        <div class="col-12">
            
            <!-- 主表单 -->
            <form action="<?php $security->index('/action/contents-page-edit'); ?>" method="post" name="write_page" id="write_page">
                <div class="row g-4">
                    
                    <!-- 左侧：主要编辑区域 -->
                    <div class="col-lg-9">
                        
                        <!-- 草稿提示 -->
                        <?php if ($page->draft): ?>
                            <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
                                <i class="fa-solid fa-clock-rotate-left me-2"></i>
                                <?php if ($page->draft['cid'] != $page->cid):
                                    $pageModifyDate = new \Typecho\Date($page->draft['modified']);
                                    _e('当前正在编辑的是保存于 <strong>%s</strong> 的草稿，你可以 <a href="%s" class="alert-link edit-draft-notice">删除它</a>', $pageModifyDate->word(),
                                    $security->getIndex('/action/contents-page-edit?do=deleteDraft&cid=' . $page->cid));
                                else:
                                    _e('当前正在编辑的是未发布的草稿');
                                endif; ?>
                                <input name="draft" type="hidden" value="<?php echo $page->draft['cid'] ?>" />
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card-modern">
                            <div class="card-body p-4">
                                <!-- 标题输入 -->
                                <div class="mb-3">
                                    <label for="title" class="visually-hidden"><?php _e('标题'); ?></label>
                                    <textarea id="title" name="title" rows="1" placeholder="<?php _e('在此输入页面标题...'); ?>" class="form-control form-control-lg border-0 fs-2 fw-bold px-0 shadow-none text-dark" style="background: transparent; resize: none; overflow: hidden;"><?php $page->title(); ?></textarea>
                                </div>

                                <!-- 永久链接 -->
                                <?php
                                $permalink = \Typecho\Common::url($options->routingTable['page']['url'], $options->index);
                                list($scheme, $permalink) = explode(':', $permalink, 2);
                                $permalink = ltrim($permalink, '/');
                                $permalink = preg_replace("/\[([_a-z0-9-]+)[^\]]*\]/i", "{\\1}", $permalink);
                                if ($page->have()) {
                                    $permalink = str_replace('{cid}', $page->cid, $permalink);
                                }
                                $input = '<span class="slug-input-wrapper"><input type="text" id="slug" name="slug" autocomplete="off" value="' . htmlspecialchars($page->slug ?? '') . '" class="form-control form-control-sm border-0 bg-light d-inline-block w-auto mx-1" style="min-width:100px;" /></span>';
                                ?>
                                <div class="mb-4 text-muted small d-flex align-items-center flex-wrap url-slug">
                                    <i class="fa-solid fa-link me-2"></i>
                                    <span><?php echo preg_replace("/\{slug\}/i", $input, $permalink); ?></span>
                                </div>

                                <!-- 编辑器区域 -->
                                <div class="editor-container">
                                    <label for="text" class="visually-hidden"><?php _e('页面内容'); ?></label>
                                    <textarea style="height: <?php echo $options->editorSize ? $options->editorSize . 'px' : '500px'; ?>" autocomplete="off" id="text" name="text" class="w-100 mono"><?php echo htmlspecialchars($page->text ?? ''); ?></textarea>
                                </div>
                                
                                <!-- 自定义字段 -->
                                <div class="mt-4 pt-3 border-top">
                                    <h5 class="mb-3 fw-bold text-dark small text-uppercase"><i class="fa-solid fa-list-ul me-2"></i><?php _e('自定义字段'); ?></h5>
                                    <div id="custom-field">
                                        <?php include 'custom-fields.php'; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 底部操作栏 -->
                        <div class="card-modern mt-3">
                             <div class="card-body d-flex justify-content-between align-items-center submit">
                                <div class="left d-flex align-items-center gap-3">
                                    <!-- 新增：自动保存开关 -->
                                    <div class="form-check form-switch mb-0">
                                        <input class="form-check-input" type="checkbox" id="auto-save-switch">
                                        <label class="form-check-label small text-muted" for="auto-save-switch"><?php _e('自动保存'); ?></label>
                                    </div>
                                    <span id="auto-save-message" class="small text-muted"></span>
                                </div>
                                <div class="d-flex gap-2 right">
                                    <input type="hidden" name="cid" value="<?php $page->cid(); ?>" />
                                    <button type="submit" name="do" value="save" id="btn-save" class="btn btn-light text-primary fw-bold rounded-pill px-3"><?php _e('保存草稿'); ?></button>
                                    <button type="submit" name="do" value="publish" id="btn-submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm"><i class="fa-solid fa-paper-plane me-1"></i> <?php _e('发布页面'); ?></button>
                                </div>
                            </div>
                        </div>
                        <?php \Typecho\Plugin::factory('admin/write-page.php')->content($page); ?>
                    </div>

                    <!-- 右侧：侧边栏选项 -->
                    <div class="col-lg-3" id="edit-secondary" role="complementary">
                        <div class="card-modern sticky-top" style="top: 20px; z-index: 100;">
                            <div class="card-body p-0">
                                <ul class="nav nav-pills nav-fill p-2 bg-light rounded-3 typecho-option-tabs mb-3">
                                    <li class="nav-item w-50"><a class="nav-link active py-1 small fw-bold rounded-3" href="#tab-advance" data-bs-toggle="pill"><?php _e('选项'); ?></a></li>
                                    <li class="nav-item w-50"><a class="nav-link py-1 small fw-bold rounded-3" href="#tab-files" id="tab-files-btn" data-bs-toggle="pill"><?php _e('附件'); ?></a></li>
                                </ul>

                                <div class="tab-content px-3 pb-3">
                                    
                                    <!-- 1. 选项 Tab -->
                                    <div id="tab-advance" class="tab-pane fade show active">
                                        <div class="mb-3"><label for="date" class="form-label small text-muted fw-bold mb-1"><?php _e('发布日期'); ?></label><input class="form-control form-control-sm" type="text" name="date" id="date" autocomplete="off" value="<?php $page->have() && $page->created > 0 ? $page->date('Y-m-d H:i') : ''; ?>" /></div>
                                        
                                        <div class="mb-3"><label for="order" class="form-label small text-muted fw-bold mb-1"><?php _e('页面顺序'); ?></label><input type="text" id="order" name="order" value="<?php $page->order(); ?>" class="form-control form-control-sm" /><p class="form-text small mt-1"><?php _e('数值越大，页面越靠后'); ?></p></div>

                                        <div class="mb-3"><label for="template" class="form-label small text-muted fw-bold mb-1"><?php _e('自定义模板'); ?></label>
                                            <select name="template" id="template" class="form-select form-select-sm">
                                                <option value=""><?php _e('不选择'); ?></option>
                                                <?php $templates = $page->getTemplates();
                                                foreach ($templates as $template => $name): ?>
                                                    <option value="<?php echo $template; ?>"<?php if ($template == $page->template): ?> selected="true"<?php endif; ?>><?php echo $name; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <p class="form-text small mt-1"><?php _e('选择后将以该模板文件渲染'); ?></p>
                                        </div>

                                        <?php \Typecho\Plugin::factory('admin/write-page.php')->option($page); ?>

                                        <!-- 高级选项 -->
                                        <div class="accordion accordion-flush" id="advanceAccordion">
                                            <div class="accordion-item bg-transparent">
                                                <h2 class="accordion-header"><button class="accordion-button collapsed py-2 small bg-light" id="advance-panel-btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdv"><?php _e('高级选项'); ?></button></h2>
                                                <div id="collapseAdv" class="accordion-collapse collapse">
                                                    <div class="accordion-body p-2 pt-3">
                                                        <div id="advance-panel">
                                                            <div class="mb-2 form-check form-switch">
                                                                <input class="form-check-input" type="checkbox" id="markdown" name="markdown" value="1" <?php if ($options->markdown && (!$page->have() || $page->isMarkdown)): ?>checked="true"<?php endif; ?>>
                                                                <label class="form-check-label small" for="markdown"><?php _e('启用 Markdown 语法'); ?></label>
                                                            </div>
                                                            <div class="mb-2"><label for="visibility" class="form-label small text-muted mb-1"><?php _e('公开度'); ?></label>
                                                                <select id="visibility" name="visibility" class="form-select form-select-sm">
                                                                    <option value="publish"<?php if ($page->status == 'publish' || !$page->status): ?> selected<?php endif; ?>><?php _e('公开'); ?></option>
                                                                    <option value="hidden"<?php if ($page->status == 'hidden'): ?> selected<?php endif; ?>><?php _e('隐藏'); ?></option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-2">
                                                                <label class="form-label small text-muted mb-1"><?php _e('权限控制'); ?></label>
                                                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="allowComment" name="allowComment" value="1" <?php if ($page->allow('comment')): ?>checked="true"<?php endif; ?>><label class="form-check-label small" for="allowComment"><?php _e('允许评论'); ?></label></div>
                                                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="allowPing" name="allowPing" value="1" <?php if ($page->allow('ping')): ?>checked="true"<?php endif; ?>><label class="form-check-label small" for="allowPing"><?php _e('允许被引用'); ?></label></div>
                                                                <div class="form-check form-switch"><input class="form-check-input" type="checkbox" id="allowFeed" name="allowFeed" value="1" <?php if ($page->allow('feed')): ?>checked="true"<?php endif; ?>><label class="form-check-label small" for="allowFeed"><?php _e('允许在聚合中出现'); ?></label></div>
                                                            </div>
                                                            <?php \Typecho\Plugin::factory('admin/write-page.php')->advanceOption($page); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- 2. 附件 Tab -->
                                    <div id="tab-files" class="tab-pane fade">
                                        <div id="upload-panel">
                                            <div class="upload-area-modern p-3 text-center border border-2 border-dashed rounded-3 bg-light mb-3">
                                                <div class="upload-area" draggable="true">
                                                    <a href="javascript:void(0);" class="upload-file text-decoration-none stretched-link">
                                                        <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2 d-block"></i>
                                                        <span class="text-muted small"><?php _e('拖拽或点击上传'); ?></span>
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            <ul id="file-list" class="list-group list-group-flush small">
                                                <?php while ($attachment->next()): 
                                                    $iconInfo = function($filename) {
                                                        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                                                        if (in_array($ext, ['jpg','jpeg','png','gif','webp','avif'])) return ['icon' => 'fa-regular fa-image', 'color' => 'text-primary'];
                                                        if (in_array($ext, ['mp4','mov','avi','wmv'])) return ['icon' => 'fa-regular fa-file-video', 'color' => 'text-danger'];
                                                        if (in_array($ext, ['mp3','wav','ogg'])) return ['icon' => 'fa-regular fa-file-audio', 'color' => 'text-warning'];
                                                        if (in_array($ext, ['zip','rar','7z'])) return ['icon' => 'fa-regular fa-file-zipper', 'color' => 'text-info'];
                                                        return ['icon' => 'fa-regular fa-file', 'color' => 'text-muted'];
                                                    };
                                                    $icon = $iconInfo($attachment->attachment->name);
                                                ?>
                                                <li class="list-group-item px-0" id="attachment-<?php $attachment->cid(); ?>" 
                                                    data-cid="<?php $attachment->cid(); ?>" 
                                                    data-url="<?php echo $attachment->attachment->url; ?>" 
                                                    data-image="<?php echo $attachment->attachment->isImage ? 1 : 0; ?>">
                                                    <div class="d-flex align-items-center w-100">
                                                        <div class="me-3"><i class="<?php echo $icon['icon'] . ' ' . $icon['color']; ?> fa-lg"></i></div>
                                                        <div class="flex-grow-1 min-width-0">
                                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                                <a class="insert text-dark fw-bold text-decoration-none text-truncate" href="###" title="<?php _e('点击插入文件'); ?>" style="max-width: 180px;"><?php $attachment->title(); ?></a>
                                                            </div>
                                                            <div class="small text-muted"><?php echo number_format(ceil($attachment->attachment->size / 1024)); ?> Kb</div>
                                                            <div class="small text-muted btn-group btn-group-sm opacity-50 hover-opacity-100">
                                                                <a class="btn btn-light text-secondary file" target="_blank" href="<?php $options->adminUrl('media.php?cid=' . $attachment->cid); ?>" title="<?php _e('编辑'); ?>"><i class="fa-solid fa-pen"></i></a>
                                                                <a class="btn btn-light text-danger delete" href="###" title="<?php _e('删除'); ?>"><i class="fa-solid fa-trash"></i></a>
                                                            </div>
                                                        </div>
                                                        <input type="hidden" name="attachment[]" value="<?php $attachment->cid(); ?>" />
                                                    </div>
                                                </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'copyright.php';
?>

<!-- ======================================================= -->
<!-- ALL JAVASCRIPTS ARE EMBEDDED HERE -->
<!-- ======================================================= -->
<script src="<?php $options->adminStaticUrl('js', 'jquery.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'jquery-ui.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'hyperdown.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'pagedown.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'paste.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'purify.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'moxie.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'plupload.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'timepicker.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'tokeninput.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'typecho.js'); ?>"></script>

<script>
$(document).ready(function() {
    // =======================================================
    // MARKDOWN EDITOR INITIALIZATION
    // =======================================================
    (function() {
        var textarea = $('#text');
        var editorContainer = $('.editor-container');
        
        var toolbar = $('<div class="editor" id="wmd-button-bar"></div>').insertBefore(textarea);
        var preview = $('<div id="wmd-preview" class="wmd-hidetab"></div>').insertAfter(editorContainer);
        
        textarea.addClass('form-control').wrap('<div id="wmd-editarea"></div>');
        
        var options = {}, isMarkdown = <?php echo intval($page->isMarkdown || !$page->have()); ?>;
        
        options.strings = {
            bold: '<?php _e('加粗'); ?> <strong> Ctrl+B', boldexample: '<?php _e('加粗文字'); ?>',
            italic: '<?php _e('斜体'); ?> <em> Ctrl+I', italicexample: '<?php _e('斜体文字'); ?>',
            link: '<?php _e('链接'); ?> <a> Ctrl+L', linkdescription: '<?php _e('请输入链接描述'); ?>',
            quote:  '<?php _e('引用'); ?> <blockquote> Ctrl+Q', quoteexample: '<?php _e('引用文字'); ?>',
            code: '<?php _e('代码'); ?> <pre><code> Ctrl+K', codeexample: '<?php _e('请输入代码'); ?>',
            image: '<?php _e('图片'); ?> <img> Ctrl+G', imagedescription: '<?php _e('请输入图片描述'); ?>',
            olist: '<?php _e('数字列表'); ?> <ol> Ctrl+O', ulist: '<?php _e('普通列表'); ?> <ul> Ctrl+U', litem: '<?php _e('列表项目'); ?>',
            heading: '<?php _e('标题'); ?> <h1>/<h2> Ctrl+H', headingexample: '<?php _e('标题文字'); ?>',
            hr: '<?php _e('分割线'); ?> <hr> Ctrl+R', more: '<?php _e('摘要分割线'); ?> <!--more--> Ctrl+M',
            undo: '<?php _e('撤销'); ?> - Ctrl+Z', redo: '<?php _e('重做'); ?> - Ctrl+Y', redomac: '<?php _e('重做'); ?> - Ctrl+Shift+Z',
            imagedialog: '<p><b><?php _e('插入图片'); ?></b></p><p><?php _e('请在下方的输入框内输入要插入的远程图片地址'); ?></p><p><?php _e('您也可以使用附件功能插入上传的本地图片'); ?></p>',
            linkdialog: '<p><b><?php _e('插入链接'); ?></b></p><p><?php _e('请在下方的输入框内输入要插入的链接地址'); ?></p>',
            ok: '<?php _e('确定'); ?>', cancel: '<?php _e('取消'); ?>',
            help: '<?php _e('Markdown语法帮助'); ?>'
        };

        var converter = new HyperDown();
        var editor = new Markdown.Editor(converter, '', options);

        converter.enableHtml(true);
        converter.hook('makeHtml', function (html) {
            return DOMPurify.sanitize(html, {USE_PROFILES: {html: true}});
        });
        
        function initMarkdownEditor() {
            editor.run();
            
            var edittab = $('<div class="wmd-edittab nav nav-pills nav-sm mb-2"><a class="nav-link active" href="#wmd-editarea"><?php _e('撰写'); ?></a><a class="nav-link" href="#wmd-preview"><?php _e('预览'); ?></a></div>').insertBefore(toolbar);

            $(".wmd-edittab a").click(function(e) {
                e.preventDefault();
                $(".wmd-edittab a").removeClass('active');
                $(this).addClass("active");
                $("#wmd-editarea, #wmd-preview").addClass("wmd-hidetab");
                $($(this).attr("href")).removeClass("wmd-hidetab");
                $('#wmd-button-bar').toggleClass('wmd-visualhide', $(this).attr("href") === "#wmd-preview");
                $('#wmd-preview').outerHeight($('#wmd-editarea').innerHeight());
            });

            textarea.pastableTextarea().on('pasteImage', function (e, data) {
                var name = data.name ? data.name.replace(/[\(\)\[\]\*#!]/g, '') : (new Date()).toISOString().replace(/\..+$/, '');
                if (!name.match(/\.[a-z0-9]{2,}$/i)) {
                    var ext = data.blob.type.split('/').pop();
                    name += '.' + ext;
                }
                Typecho.uploadFile(new File([data.blob], name), name);
            });
        }

        if (isMarkdown) { 
            initMarkdownEditor();
        } else {
            $('#markdown').change(function() {
                if ($(this).is(':checked')) {
                    var notice = $('<div class="alert alert-info border-0 shadow-sm mt-3"><?php _e('您正在从普通模式切换到 Markdown 模式，部分HTML可能会失效。'); ?> '
                        + '<button type="button" class="btn btn-sm btn-primary yes ms-2"><?php _e('确认切换'); ?></button></div>').insertBefore(toolbar);
                    $('.yes', notice).click(function () {
                        notice.remove();
                        initMarkdownEditor();
                    });
                }
            }).prop('checked', false);
        }
    })();

    // =======================================================
    // GENERAL WRITE PAGE LOGIC & FIXES
    // =======================================================
    (function() {
        var titleTextarea = $('#title');
        function resizeTitleTextarea() {
            titleTextarea.css('height', 'auto').css('height', titleTextarea[0].scrollHeight + 'px');
        }
        titleTextarea.on('input', resizeTitleTextarea);
        resizeTitleTextarea();

        $('#date').datetimepicker({ hourText: '<?php _e('时'); ?>', minuteText: '<?php _e('分'); ?>', timeSeparator: ':', currentText: '<?php _e('现在'); ?>', closeText: '<?php _e('完成'); ?>' });
        $('#title').focus();
        
        var submitted = false, form = $('#write_page').submit(function () { submitted = true; });
        var changed = false;
        $(':input', form).on('input change', function() { changed = true; });
        form.on('field', function() { changed = true; });
        $(window).on('beforeunload', function() { if (changed && !submitted) return '<?php _e('内容已经改变尚未保存, 您确认要离开此页面吗?'); ?>'; });
        
        var doAction = $('<input type="hidden" name="do" value="publish" />').appendTo(form);
        $('#btn-save').click(function() { doAction.val('save'); });
        $('#btn-submit').click(function() { doAction.val('publish'); });

        $('#visibility').change(function () {
             $('#post-password').toggleClass('d-none', $(this).val() !== 'password');
        });
        
        $('.edit-draft-notice a').click(function () {
            return confirm('<?php _e('您确认要删除这份草稿吗?'); ?>');
        });

        // =======================================================
        // NEW: AUTO SAVE LOGIC
        // =======================================================
        var autoSaveTimer = null;
        var autoSaveSwitch = $('#auto-save-switch');

        // Restore switch state
        if (localStorage.getItem('typecho_autosave') === '1') {
            autoSaveSwitch.prop('checked', true);
            startAutoSave();
        }

        autoSaveSwitch.change(function() {
            var checked = $(this).is(':checked');
            localStorage.setItem('typecho_autosave', checked ? '1' : '0');
            if (checked) {
                startAutoSave();
            } else {
                stopAutoSave();
                $('#auto-save-message').text('');
            }
        });

        function startAutoSave() {
            if (autoSaveTimer) clearInterval(autoSaveTimer);
            autoSaveTimer = setInterval(function() {
                if (changed && !submitted) {
                    var formData = form.serialize() + '&do=save';
                    // Silent save via AJAX
                    $.post(form.attr('action'), formData, function(data) {
                        var time = new Date();
                        var timeStr = time.getHours().toString().padStart(2,'0') + ':' +
                                      time.getMinutes().toString().padStart(2,'0') + ':' +
                                      time.getSeconds().toString().padStart(2,'0');
                        $('#auto-save-message').text('<?php _e('已于'); ?> ' + timeStr + ' <?php _e('自动保存'); ?>');
                        changed = false; // Reset changed flag
                    });
                }
            }, 30000); // Check every 30 seconds
        }

        function stopAutoSave() {
            if (autoSaveTimer) clearInterval(autoSaveTimer);
        }

    })();


    // =======================================================
    // CUSTOM FIELDS LOGIC
    // =======================================================
    (function() {
        function attachDeleteEvent (el) {
            $('.btn-danger', el).click(function () {
                if (confirm('<?php _e('确认要删除此字段吗?'); ?>')) {
                    $(this).parents('tr').fadeOut(function () { $(this).remove(); });
                    $(this).parents('form').trigger('field');
                }
            });
        }
        $('#custom-field table tbody tr').each(function () { attachDeleteEvent(this); });
        $('#custom-field .operate-add').click(function () {
            var html = `<tr>
                <td><input type="text" name="fieldNames[]" placeholder="<?php _e('字段名称'); ?>" class="form-control form-control-sm"></td>
                <td><select name="fieldTypes[]" class="form-select form-select-sm">
                    <option value="str"><?php _e('字符'); ?></option><option value="int"><?php _e('整数'); ?></option><option value="float"><?php _e('小数'); ?></option>
                </select></td>
                <td><textarea name="fieldValues[]" placeholder="<?php _e('字段值'); ?>" class="form-control form-control-sm" rows="1"></textarea></td>
                <td><button type="button" class="btn btn-sm btn-danger"><i class="fa-solid fa-trash"></i></button></td>
            </tr>`;
            var el = $(html).hide().appendTo('#custom-field table tbody').fadeIn();
            $(':input', el).on('input change', function () { $(this).parents('form').trigger('field'); });
            attachDeleteEvent(el);
        });
        $('#custom-field table').addClass('table table-sm table-borderless').find('input, select, textarea').addClass('form-control form-control-sm');
        $('#custom-field button').addClass('btn btn-sm btn-danger');
        $('#custom-field .operate-add').removeClass('btn-xs').addClass('btn btn-sm btn-outline-primary');
    })();


    // =======================================================
    // FILE UPLOAD LOGIC (REWRITTEN & OPTIMIZED)
    // =======================================================
    (function() {
        window.Typecho.insertFileToEditor = function (file, url, isImage) {
            var textarea = $('#text');
            var text = textarea.val();
            var markdown = $('input[name=markdown]').is(':checked');

            // 1. Preserve Scroll and Selection
            var scrollPos = textarea.scrollTop();
            var selStart = textarea.prop('selectionStart');

            if (!markdown) {
                // HTML fallback
                var html = isImage ? '<img src="' + url + '" alt="' + file + '" />' : '<a href="' + url + '">' + file + '</a>';
                textarea.replaceSelection(html);
                return;
            }

            // 2. Calculate next Reference ID [n]
            var maxId = 0;
            // Matches start of line or space + [n]: url
            var regex = /(?:^|\s)\[(\d+)\]:\s+http/g;
            var match;
            while ((match = regex.exec(text)) !== null) {
                var currentId = parseInt(match[1]);
                if (currentId > maxId) maxId = currentId;
            }
            var nextId = maxId + 1;

            // 3. Prepare strings
            var refTag = isImage ? '![' + file + '][' + nextId + ']' : '[' + file + '][' + nextId + ']';
            var refDef = '\n  [' + nextId + ']: ' + url;

            // 4. Update Content: Insert tag at cursor, append def at bottom
            textarea.replaceSelection(refTag);

            var currentVal = textarea.val();
            textarea.val(currentVal + refDef);

            // 5. Restore Cursor (after the inserted tag) and Scroll
            var newCursorPos = selStart + refTag.length;
            textarea.prop('selectionStart', newCursorPos);
            textarea.prop('selectionEnd', newCursorPos);
            textarea.scrollTop(scrollPos);

            // Trigger change event
            textarea.trigger('input');
        };
        
        function updateAttachmentNumber() {
            var btn = $('#tab-files-btn');
            var count = $('#file-list > li').length;
            var balloon = btn.find('.badge');
            if (count > 0) {
                if (!balloon.length) {
                    balloon = $('<span class="badge bg-primary rounded-pill ms-2"></span>').appendTo(btn);
                }
                balloon.html(count);
            } else if (balloon.length > 0) {
                balloon.remove();
            }
        }
        updateAttachmentNumber();

        var uploader = null;
        $('a[href="#tab-files"]').one('shown.bs.tab', function() {
            if (uploader) return;
            uploader = new plupload.Uploader({
                browse_button: $('.upload-file').get(0),
                url: '<?php $security->index('/action/upload' . ($page->have() ? '?cid=' . $page->cid : '')); ?>',
                runtimes: 'html5,flash,html4',
                flash_swf_url: '<?php $options->adminStaticUrl('js', 'Moxie.swf'); ?>',
                drop_element: $('.upload-area').get(0),
                filters: { 
                    max_file_size: '<?php echo $phpMaxFilesize ?>', 
                    mime_types: [{'title': '<?php _e('允许上传的文件'); ?>', 'extensions': '<?php echo implode(',', $options->allowedAttachmentTypes); ?>'}]
                },
                init: {
                    FilesAdded: function(up, files) {
                        plupload.each(files, function(file) {
                             $('#file-list').prepend(`<li id="${file.id}" class="list-group-item px-0 d-flex justify-content-between align-items-center"><span class="text-truncate">${file.name}</span><div class="spinner-border spinner-border-sm text-primary" role="status"></div></li>`);
                        });
                        up.start();
                    },
                    FileUploaded: function(up, file, result) {
                        var li = $('#' + file.id);
                        if (200 == result.status) {
                            var data = $.parseJSON(result.response);
                            if (data && data[1]) {
                                var iconInfo = (isImage) => isImage ? {icon: 'fa-regular fa-image', color: 'text-primary'} : {icon: 'fa-regular fa-file', color: 'text-muted'};
                                var icon = iconInfo(data[1].isImage);
                                var newLiHtml = `
                                    <div class="d-flex align-items-center w-100">
                                        <div class="me-3"><i class="${icon.icon} ${icon.color} fa-lg"></i></div>
                                        <div class="flex-grow-1 min-width-0">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <a class="insert text-dark fw-bold text-decoration-none text-truncate" href="###" title="<?php _e('点击插入文件'); ?>">${data[1].title}</a>
                                            </div>
                                            <div class="small text-muted">${data[1].bytes}</div>
                                            <div class="small text-muted btn-group btn-group-sm opacity-50 hover-opacity-100">
                                                <a class="btn btn-light text-secondary file" target="_blank" href="<?php $options->adminUrl('media.php'); ?>?cid=${data[1].cid}" title="<?php _e('编辑'); ?>"><i class="fa-solid fa-pen"></i></a>
                                                <a class="btn btn-light text-danger delete" href="###" title="<?php _e('删除'); ?>"><i class="fa-solid fa-trash"></i></a>
                                            </div>
                                        </div>
                                        <input type="hidden" name="attachment[]" value="${data[1].cid}" />`;
                                li.html(newLiHtml)
                                    .data('cid', data[1].cid)
                                    .data('url', data[1].url)
                                    .data('image', data[1].isImage);
                                attachFileEvents(li);
                                updateAttachmentNumber();
                                if (window.Typecho.uploadComplete) {
                                    window.Typecho.uploadComplete(data[1]);
                                }
                                return;
                            }
                        }
                        li.html(`<span class="text-danger">${file.name} - <?php _e('上传失败'); ?></span>`);
                    },
                    Error: function(up, err) {
                         $('#' + err.file.id).html(`<span class="text-danger">${err.file.name} - ${err.message}</span>`);
                    }
                }
            });
            uploader.init();
            window.Typecho.uploadFile = function(file) { if(uploader) uploader.addFile(file); };
        });

        function attachFileEvents(li) {
            li.find('.insert').click(function () {
                Typecho.insertFileToEditor($(this).text(), li.data('url'), li.data('image'));
                return false;
            });
            li.find('.delete').click(function () {
                if (confirm('<?php _e('确认要删除文件吗?'); ?>')) {
                    $.post('<?php $security->index('/action/contents-attachment-edit'); ?>', 
                           {'do': 'delete', 'cid': li.data('cid')},
                           function () { 
                               li.fadeOut(function(){ 
                                   $(this).remove(); 
                                   updateAttachmentNumber();
                               }); 
                           });
                }
                return false;
            });
        }
        
        $('#file-list li').each(function() {
            attachFileEvents($(this));
        });
    })();
});
</script>

<!-- ======================================================= -->
<!-- EMBEDDED STYLES FOR A SINGLE FILE FIX -->
<!-- ======================================================= -->
<style>
/* For Markdown Editor */
#wmd-button-bar { background: #f8f9fa; border: 1px solid #dee2e6; border-bottom: none; border-radius: 12px 12px 0 0; padding: 8px 12px; }
.wmd-button-row { list-style: none; margin: 0; padding: 0; display: flex; flex-wrap: wrap; gap: 5px; }
.wmd-button { display: inline-block; padding: 5px; cursor: pointer; border-radius: 6px; transition: 0.2s; }
.wmd-button:hover { background-color: #e9ecef !important; }
.wmd-button span { display: block; width: 20px; height: 20px; background-image: url("<?php $options->adminStaticUrl('img', 'editor.png'); ?>"); }
@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .wmd-button-row li span { background-image: url("<?php $options->adminStaticUrl('img', 'editor@2x.png'); ?>"); background-size: 20px 300px; }
}
.wmd-button-row li#wmd-bold-button span { background-position: 0 0; }
.wmd-button-row li#wmd-italic-button span { background-position: 0 -140px; }
.wmd-button-row li#wmd-link-button span { background-position: 0 -160px; }
.wmd-button-row li#wmd-quote-button span { background-position: 0 -220px; }
.wmd-button-row li#wmd-code-button span { background-position: 0 -20px; }
.wmd-button-row li#wmd-image-button span { background-position: 0 -120px; }
.wmd-button-row li#wmd-olist-button span { background-position: 0 -200px; }
.wmd-button-row li#wmd-ulist-button span { background-position: 0 -260px; }
.wmd-button-row li#wmd-heading-button span { background-position: 0 -80px; }
.wmd-button-row li#wmd-hr-button span { background-position: 0 -100px; }
.wmd-button-row li#wmd-more-button span { background-position: 0 -180px; }
.wmd-hidetab { display: none; }
#wmd-preview { background: #fff; padding: 15px; border: 1px solid #eee; margin-top: 10px; border-radius: 8px; max-height: 500px; overflow-y: auto; }
#wmd-preview img { max-width: 100%; }
.wmd-prompt-dialog { position: fixed; z-index: 1081; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; width: 380px; background: #fff; border-radius: 12px; box-shadow: 0 5px 20px rgba(0,0,0,0.15); }
.wmd-prompt-dialog p { margin: 0 0 10px; }
.wmd-prompt-dialog input[type="text"] { width: 100%; }
.wmd-prompt-background { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.4); z-index: 1080; }
#text.mono { border-radius: 0 0 12px 12px !important; border-top: none !important; }

/* Custom Fields table styling */
#custom-field .table { margin-bottom: 10px; }
#custom-field .table td { padding: .5rem; vertical-align: middle; }
#custom-field .operate-add { margin-bottom: 10px; }
.hover-opacity-100:hover { opacity: 1 !important; }
</style>

<?php
include 'footer.php';
?>