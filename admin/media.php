<?php
include 'common.php';
include 'header.php';
include 'menu.php';

// 获取 PHP 上传限制
$phpMaxFilesize = function_exists('ini_get') ? trim(ini_get('upload_max_filesize')) : 0;
if (preg_match("/^([0-9]+)([a-z]{1,2})$/i", $phpMaxFilesize, $matches)) {
    $phpMaxFilesize = strtolower($matches[1] . $matches[2] . (1 == strlen($matches[2]) ? 'b' : ''));
}

$attachment = \Widget\Contents\Attachment\Edit::alloc();

// 辅助函数：获取图标
function getMediaIcon($mime) {
    if (strpos($mime, 'image/') === 0) return 'fa-regular fa-image text-purple';
    if (strpos($mime, 'video/') === 0) return 'fa-regular fa-file-video text-danger';
    if (strpos($mime, 'audio/') === 0) return 'fa-regular fa-file-audio text-warning';
    if (strpos($mime, 'text/') === 0) return 'fa-regular fa-file-lines text-secondary';
    if (strpos($mime, 'application/pdf') !== false) return 'fa-regular fa-file-pdf text-danger';
    if (strpos($mime, 'zip') !== false || strpos($mime, 'compressed') !== false) return 'fa-regular fa-file-zipper text-warning';
    return 'fa-regular fa-file text-muted';
}
?>

<div class="container-fluid">
    
    <div class="row mb-4 fade-in-up">
        <div class="col-12">
            <div class="card-modern">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-center">
                    编辑文件
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 fade-in-up" style="animation-delay: 0.1s;">
        
        <!-- 左侧：预览与替换 -->
        <div class="col-lg-8">
            <div class="card-modern h-100">
                <div class="card-body p-4 d-flex flex-column">
                    
                    <!-- 1. 预览区域 -->
                    <div class="preview-stage bg-light rounded-3 d-flex align-items-center justify-content-center mb-4 position-relative overflow-hidden border">
                        <?php if ($attachment->attachment->isImage): ?>
                            <img src="<?php $attachment->attachment->url(); ?>" 
                                 alt="<?php $attachment->attachment->name(); ?>" 
                                 class="typecho-attachment-photo img-fluid" 
                                 style="max-height: 400px; object-fit: contain;" />
                        <?php else: ?>
                            <div class="text-center p-5">
                                <i class="<?php echo getMediaIcon($attachment->attachment->mime); ?> fa-6x mb-3"></i>
                                <h5 class="text-muted"><?php echo $attachment->attachment->mime; ?></h5>
                            </div>
                        <?php endif; ?>
                        
                        <!-- 尺寸信息徽章 -->
                        <div class="position-absolute top-0 end-0 m-3">
                            <span class="badge bg-dark bg-opacity-75 backdrop-blur shadow-sm">
                                <?php echo number_format(ceil($attachment->attachment->size / 1024)); ?> KB
                            </span>
                        </div>
                    </div>

                    <!-- 2. 链接复制区 -->
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1"><?php _e('文件地址'); ?></label>
                        <div class="input-group">
                            <input type="text" id="attachment-url" class="form-control font-monospace text-muted bg-white" value="<?php $attachment->attachment->url(); ?>" readonly />
                            <button class="btn btn-primary" type="button" id="btn-copy" data-bs-toggle="tooltip" title="<?php _e('复制链接'); ?>">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                            <a href="<?php $attachment->attachment->url(); ?>" target="_blank" class="btn btn-outline-secondary" title="<?php _e('新窗口打开'); ?>">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </div>
                    </div>

                    <!-- 3. 替换文件 (Dropzone) -->
                    <div class="mt-auto">
                        <label class="form-label fw-bold small text-muted text-uppercase mb-1"><?php _e('替换文件'); ?></label>
                        <div id="upload-panel" class="upload-area-modern p-4 text-center border border-2 border-dashed rounded-3 bg-light transition-all">
                            <div class="upload-area" draggable="true">
                                <i class="fa-solid fa-cloud-arrow-up fa-2x text-muted mb-2"></i>
                                <p class="mb-0 text-muted small">
                                    <?php _e('拖放文件到这里或'); ?> 
                                    <a href="javascript:void(0)" class="upload-file text-primary fw-bold text-decoration-none stretched-link"><?php _e('点击上传'); ?></a>
                                </p>
                            </div>
                            <ul id="file-list" class="list-unstyled mt-2 mb-0"></ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- 右侧：元数据编辑 -->
        <div class="col-lg-4">
            <div class="card-modern h-100">
                <div class="card-header bg-transparent border-bottom px-4 py-3">
                    <h5 class="fw-bold mb-0 text-dark small text-uppercase ls-1">
                        <i class="fa-solid fa-pen-to-square me-2 text-primary"></i><?php _e('文件信息'); ?>
                    </h5>
                </div>
                <div class="card-body p-4 edit-media">
                    <!-- Typecho 表单渲染 -->
                    <div class="typecho-form-modern">
                        <?php $attachment->form()->render(); ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
/* 预览区棋盘格背景 (透明图片友好) */
.preview-stage {
    min-height: 260px;
    background-color: #f8f9fa;
    background-image: 
        linear-gradient(45deg, #e9ecef 25%, transparent 25%), 
        linear-gradient(-45deg, #e9ecef 25%, transparent 25%), 
        linear-gradient(45deg, transparent 75%, #e9ecef 75%), 
        linear-gradient(-45deg, transparent 75%, #e9ecef 75%);
    background-size: 20px 20px;
    background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
}

/* 上传区悬停效果 */
.upload-area-modern:hover {
    background-color: #fff !important;
    border-color: var(--primary-light) !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.upload-area-modern.drag {
    background-color: var(--primary-soft) !important;
    border-color: var(--primary-color) !important;
}

.text-purple { color: #6c5ce7; }
.backdrop-blur { backdrop-filter: blur(4px); }
</style>

<?php
include 'copyright.php';
include 'common-js.php';
include 'form-js.php';
?>

<!-- 引入上传组件 -->
<script src="<?php $options->adminStaticUrl('js', 'moxie.js'); ?>"></script>
<script src="<?php $options->adminStaticUrl('js', 'plupload.js'); ?>"></script>

<script type="text/javascript">
$(document).ready(function () {
    // =======================================================
    // 1. 表单美化 Polyfill (复用通用逻辑)
    // =======================================================
    $('.typecho-option input[type=text], .typecho-option textarea').addClass('form-control');
    $('.typecho-option select').addClass('form-select');
    $('.typecho-option').addClass('list-unstyled mb-0');
    $('.typecho-option li').addClass('mb-3');
    $('.typecho-option label.typecho-label').addClass('form-label fw-bold text-dark small text-uppercase mb-1 d-block');
    $('.typecho-option p.description').addClass('form-text text-muted small mt-1');
    $('.typecho-option-submit').addClass('mt-4 pt-3 border-top d-flex justify-content-between align-items-center flex-row-reverse');
    
    // 提交按钮
    $('.typecho-option-submit button').addClass('btn btn-primary px-4 rounded-pill fw-bold shadow-sm');
    
    // 删除按钮 (美化 Typecho 生成的链接)
    $('.operate-delete').addClass('btn btn-outline-danger btn-sm border-0');
    $('.operate-delete').html('<i class="fa-solid fa-trash me-1"></i> <?php _e('永久删除'); ?>');

    // =======================================================
    // 2. 复制链接功能
    // =======================================================
    $('#btn-copy').click(function() {
        var urlField = document.getElementById('attachment-url');
        urlField.select();
        urlField.setSelectionRange(0, 99999); // For mobile devices
        
        try {
            document.execCommand('copy');
            var originalIcon = $(this).html();
            $(this).html('<i class="fa-solid fa-check"></i>').removeClass('btn-primary').addClass('btn-success');
            
            setTimeout(() => {
                $(this).html(originalIcon).removeClass('btn-success').addClass('btn-primary');
            }, 2000);
        } catch (err) {
            alert('复制失败，请手动复制');
        }
    });

    // =======================================================
    // 3. 删除确认逻辑
    // =======================================================
    $('.operate-delete').click(function () {
        var t = $(this), href = t.attr('href');
        if (confirm(t.attr('lang'))) {
            window.location.href = href;
        }
        return false;
    });

    // =======================================================
    // 4. Plupload 上传逻辑 (保留原版核心逻辑，适配新 UI)
    // =======================================================
    
    // 拖拽高亮效果
    $('.upload-area').bind({
        dragenter: function () {
            $(this).parent().addClass('drag');
        },
        dragover: function (e) {
            $(this).parent().addClass('drag');
        },
        drop: function () {
            $(this).parent().removeClass('drag');
        },
        dragend: function () {
            $(this).parent().removeClass('drag');
        },
        dragleave: function () {
            $(this).parent().removeClass('drag');
        }
    });

    function fileUploadStart(file) {
        $('<li id="' + file.id + '" class="loading">'
            + file.name + '</li>').appendTo('#file-list');
    }

    function fileUploadError(error) {
        var file = error.file, code = error.code, word; 
        
        switch (code) {
            case plupload.FILE_SIZE_ERROR:
                word = '<?php _e('文件大小超过限制'); ?>';
                break;
            case plupload.FILE_EXTENSION_ERROR:
                word = '<?php _e('文件扩展名不被支持'); ?>';
                break;
            case plupload.FILE_DUPLICATE_ERROR:
                word = '<?php _e('文件已经上传过'); ?>';
                break;
            case plupload.HTTP_ERROR:
            default:
                word = '<?php _e('上传出现错误'); ?>';
                break;
        }

        var fileError = '<?php _e('%s 上传失败'); ?>'.replace('%s', file.name),
            li, exist = $('#' + file.id);

        if (exist.length > 0) {
            li = exist.removeClass('loading').html(fileError);
        } else {
            li = $('<li>' + fileError + '<br />' + word + '</li>').appendTo('#file-list');
        }

        li.addClass('text-danger small mt-1').effect('highlight', {color : '#FBC2C4'}, 2000, function () {
            $(this).remove();
        });
    }

    function fileUploadComplete(id, url, data) {
        var img = $('.typecho-attachment-photo');
        
        // 更新预览图
        if (img.length > 0) {
            img.attr('src', '<?php $attachment->attachment->url(); ?>?' + Math.random());
        } else {
            // 如果原来不是图片，现在上传了图片，刷新页面
            window.location.reload();
        }
        
        // 更新 URL 输入框
        $('#attachment-url').val(data.url);

        $('#' + id).html('<span class="text-success"><i class="fa-solid fa-check me-1"></i><?php _e('文件 %s 已经替换'); ?>'.replace('%s', data.title) + '</span>')
            .effect('highlight', 1000, function () {
                $(this).fadeOut(function(){ $(this).remove(); });
            });
    }

    var uploader = new plupload.Uploader({
        browse_button: $('.upload-file').get(0),
        url: '<?php $security->index('/action/upload?do=modify&cid=' . $attachment->cid); ?>',
        runtimes: 'html5,flash,html4',
        flash_swf_url: '<?php $options->adminStaticUrl('js', 'Moxie.swf'); ?>',
        drop_element: $('.upload-area').get(0),
        filters: {
            max_file_size: '<?php echo $phpMaxFilesize ?>',
            mime_types: [{
                'title': '<?php _e('允许上传的文件'); ?>',
                'extensions': '<?php $attachment->attachment->type(); ?>'
            }],
            prevent_duplicates: true
        },
        multi_selection: false,

        init: {
            FilesAdded: function (up, files) {
                plupload.each(files, function (file) {
                    fileUploadStart(file);
                });
                uploader.start();
            },

            FileUploaded: function (up, file, result) {
                if (200 == result.status) {
                    var data = $.parseJSON(result.response);
                    if (data) {
                        fileUploadComplete(file.id, data[0], data[1]);
                        return;
                    }
                }
                fileUploadError({
                    code: plupload.HTTP_ERROR,
                    file: file
                });
            },

            Error: function (up, error) {
                fileUploadError(error);
            }
        }
    });

    uploader.init();
});
</script>

<?php include 'footer.php'; ?>