<?php
include 'common.php';
include 'header.php';
include 'menu.php';

\Widget\Contents\Attachment\Edit::alloc()->prepare()->to($attachment);
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-edit mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('编辑文件'); ?></span>
        </div>
        
        <div class="flex items-center space-x-4">
            <a href="<?php $options->adminUrl('manage-medias.php'); ?>" class="px-3 py-1.5 bg-gray-100 text-gray-600 rounded text-sm font-medium hover:bg-gray-200 transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> <?php _e('返回'); ?>
            </a>
            <a href="<?php $options->siteUrl(); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('查看网站'); ?>" target="_blank">
                <i class="fas fa-globe"></i>
            </a>
            <a href="<?php $options->adminUrl('profile.php'); ?>" class="text-discord-muted hover:text-discord-accent transition-colors" title="<?php _e('个人资料'); ?>">
                <i class="fas fa-user-circle"></i>
            </a>
        </div>
    </header>

    <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="w-full max-w-none mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- File Preview & Info -->
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100"><?php _e('文件预览'); ?></h3>
                    
                    <div class="flex flex-col items-center">
                        <?php if ($attachment->attachment->isImage): ?>
                            <div class="mb-4 bg-gray-100 rounded-lg p-2 border border-gray-200">
                                <img src="<?php $attachment->attachment->url(); ?>" alt="<?php $attachment->attachment->name(); ?>" class="typecho-attachment-photo max-h-96 rounded shadow-sm"/>
                            </div>
                        <?php else: ?>
                            <div class="mb-4 w-32 h-32 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                <i class="fas fa-file text-5xl"></i>
                            </div>
                        <?php endif; ?>

                        <div class="text-center">
                            <?php $mime = \Typecho\Common::mimeIconType($attachment->attachment->mime); ?>
                            <div class="font-bold text-lg text-gray-800 flex items-center justify-center">
                                <i class="fas fa-<?php echo 'image' == $mime ? 'image' : 'file'; ?> mr-2 text-discord-accent"></i>
                                <?php $attachment->attachment->name(); ?>
                            </div>
                            <div class="text-sm text-gray-500 mt-1">
                                <span><?php echo number_format(ceil($attachment->attachment->size / 1024)); ?> Kb</span>
                                <span class="mx-2">•</span>
                                <span class="uppercase"><?php echo $attachment->attachment->type; ?></span>
                            </div>
                        </div>

                        <div class="mt-6 w-full max-w-xl">
                            <label class="block text-sm font-medium text-gray-700 mb-1"><?php _e('文件链接'); ?></label>
                            <div class="flex">
                                <input id="attachment-url" type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-sm text-gray-600 focus:outline-none focus:border-discord-accent" value="<?php $attachment->attachment->url(); ?>" readonly/>
                                <button type="button" class="px-4 py-2 bg-gray-100 border border-l-0 border-gray-300 rounded-r-md text-gray-600 hover:bg-gray-200 transition-colors text-sm font-medium" onclick="document.getElementById('attachment-url').select();document.execCommand('copy');alert('<?php _e('已复制到剪贴板'); ?>');"><?php _e('复制'); ?></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100"><?php _e('替换文件'); ?></h3>
                    <div id="upload-panel" class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:bg-gray-50 transition-colors relative">
                        <div class="upload-area cursor-pointer" data-url="<?php $security->index('/action/upload?do=modify'); ?>">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 font-medium"><?php _e('拖放文件到这里'); ?></p>
                            <p class="text-gray-400 text-sm mt-1"><?php _e('或者'); ?> <a href="###" class="upload-file text-discord-accent hover:underline"><?php _e('选择文件上传'); ?></a></p>
                        </div>
                        <ul id="file-list" class="mt-4 space-y-2"></ul>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100"><?php _e('编辑信息'); ?></h3>
                    <?php $attachment->form()->render(); ?>
                    
                    <div class="mt-6 pt-4 border-t border-gray-100 text-center">
                        <button class="text-red-500 hover:text-red-700 text-sm operate-delete" lang="<?php _e('你确认要删除文件 %s 吗?', $attachment->attachment->name); ?>" href="<?php $security->index('/action/contents-attachment-edit?do=delete&cid=' . $attachment->cid); ?>">
                            <i class="fas fa-trash-alt mr-1"></i> <?php _e('删除此文件'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Discord-style form customization */
.typecho-option { margin-bottom: 1rem; }
.typecho-option label { display: block; font-weight: 500; color: #4b5563; margin-bottom: 0.375rem; font-size: 0.875rem; }
.typecho-option input[type=text], .typecho-option textarea, .typecho-option select {
    width: 100%;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    background-color: #f9fafb;
    transition: all 0.2s;
}
.typecho-option input[type=text]:focus, .typecho-option textarea:focus, .typecho-option select:focus {
    outline: none;
    border-color: #5865F2;
    background-color: white;
    box-shadow: 0 0 0 2px rgba(88, 101, 242, 0.1);
}
.typecho-option .description { display: block; margin-top: 0.25rem; font-size: 0.75rem; color: #9ca3af; }
.typecho-option .required { color: #ef4444; margin-left: 0.25rem; }
.typecho-option-submit button {
    width: 100%;
    background-color: #5865F2;
    color: white;
    padding: 0.625rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 600;
    font-size: 0.875rem;
    transition: background-color 0.2s;
    border: none;
    cursor: pointer;
}
.typecho-option-submit button:hover { background-color: #4752c4; }
</style>

<?php
include 'common-js.php';
include 'file-upload-js.php';
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('#attachment-url').click(function () {
            $(this).select();
        });

        $('.operate-delete').click(function () {
            var t = $(this), href = t.attr('href');

            if (confirm(t.attr('lang'))) {
                window.location.href = href;
            }

            return false;
        });

        Typecho.uploadComplete = function (attachment) {
            if (attachment.isImage) {
                $('.typecho-attachment-photo').attr('src', attachment.url + '?' + Math.random());
            }

            $('#file-list').append($('<li class="text-sm text-green-600 bg-green-50 p-2 rounded"></li>').text('<?php _e('文件 %s 已经替换'); ?>'.replace('%s', attachment.title))
                .effect('highlight', 1000, function () {
                    setTimeout(function() {
                        $(this).fadeOut(function() { $(this).remove(); });
                    }.bind(this), 3000);
                }));
        };
    });
</script>
<?php
include 'footer.php';
?>
