<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$actionUrl = $security->getTokenUrl(
    \Typecho\Router::url('do', array('action' => 'backup', 'widget' => 'Backup'),
        \Typecho\Common::url('index.php', $options->rootUrl)));

$backupFiles = \Widget\Backup::alloc()->listFiles();
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-database mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('备份与恢复'); ?></span>
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
        <div class="w-full max-w-5xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <!-- Backup Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col h-full">
                <h3 class="text-lg font-bold text-discord-text mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fas fa-cloud-download-alt mr-2 text-discord-accent"></i>
                    <?php _e('备份您的数据'); ?>
                </h3>
                <form action="<?php echo $actionUrl; ?>" method="post" class="flex-1 flex flex-col">
                    <div class="text-sm text-gray-600 mb-6 space-y-3 flex-1">
                        <p class="flex items-start"><i class="fas fa-info-circle text-blue-400 mt-1 mr-2 flex-shrink-0"></i> <span><?php _e('此备份操作仅包含<strong>内容数据</strong>, 并不会涉及任何<strong>设置信息</strong>'); ?></span></p>
                        <p class="flex items-start"><i class="fas fa-exclamation-triangle text-yellow-500 mt-1 mr-2 flex-shrink-0"></i> <span><?php _e('如果您的数据量过大, 为了避免操作超时, 建议您直接使用数据库提供的备份工具备份数据'); ?></span></p>
                        <p class="flex items-start"><i class="fas fa-check-circle text-green-500 mt-1 mr-2 flex-shrink-0"></i> <strong class="text-discord-text"><?php _e('为了缩小备份文件体积, 建议您在备份前删除不必要的数据'); ?></strong></p>
                    </div>
                    <div class="mt-auto">
                        <button class="w-full py-2.5 bg-discord-accent text-white rounded-md font-medium hover:bg-blue-600 transition-colors shadow-sm flex items-center justify-center" type="submit">
                            <i class="fas fa-download mr-2"></i> <?php _e('开始备份'); ?>
                        </button>
                        <input type="hidden" name="do" value="export">
                    </div>
                </form>
            </div>

            <!-- Restore Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex flex-col h-full" id="backup-secondary">
                <h3 class="text-lg font-bold text-discord-text mb-4 pb-2 border-b border-gray-100 flex items-center">
                    <i class="fas fa-cloud-upload-alt mr-2 text-green-500"></i>
                    <?php _e('恢复数据'); ?>
                </h3>
                
                <div class="flex space-x-1 mb-4 bg-gray-100 p-1 rounded-lg typecho-option-tabs select-none">
                    <a href="#from-upload" class="flex-1 text-center py-1.5 text-sm font-medium rounded-md transition-all active-tab bg-white text-discord-text shadow-sm" data-target="from-upload"><?php _e('上传文件'); ?></a>
                    <a href="#from-server" class="flex-1 text-center py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-discord-text transition-all" data-target="from-server"><?php _e('从服务器'); ?></a>
                </div>

                <div id="from-upload" class="tab-content flex-1 flex flex-col">
                    <form action="<?php echo $actionUrl; ?>" method="post" enctype="multipart/form-data" class="flex-1 flex flex-col">
                        <div class="flex-1 flex flex-col items-center justify-center border-2 border-dashed border-gray-300 rounded-lg p-6 mb-6 hover:bg-gray-50 transition-colors cursor-pointer relative">
                            <i class="fas fa-file-upload text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500 mb-1"><?php _e('点击或拖拽文件至此'); ?></p>
                            <p class="text-xs text-gray-400"><?php _e('支持 .zip, .dat 等格式'); ?></p>
                            <input id="backup-upload-file" name="file" type="file" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full">
                            <div id="file-name-display" class="mt-2 text-sm text-discord-accent font-medium hidden"></div>
                        </div>
                        <div class="mt-auto">
                            <button type="submit" class="w-full py-2.5 bg-green-500 text-white rounded-md font-medium hover:bg-green-600 transition-colors shadow-sm flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i> <?php _e('上传并恢复'); ?>
                            </button>
                            <input type="hidden" name="do" value="import">
                        </div>
                    </form>
                </div>

                <div id="from-server" class="tab-content hidden flex-1 flex flex-col">
                    <form action="<?php echo $actionUrl; ?>" method="post" class="flex-1 flex flex-col">
                        <?php if (empty($backupFiles)): ?>
                            <div class="flex-1 flex flex-col items-center justify-center text-center p-6 text-gray-500">
                                <i class="fas fa-folder-open text-4xl text-gray-200 mb-3"></i>
                                <p class="text-sm"><?php _e('没有找到备份文件'); ?></p>
                                <p class="text-xs text-gray-400 mt-2"><?php _e('将备份文件手动上传至服务器的 %s 目录下后, 这里会出现文件选项', '<span class="font-mono bg-gray-100 px-1 rounded">/usr/backups</span>'); ?></p>
                            </div>
                        <?php else: ?>
                            <div class="flex-1 mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2" for="backup-select-file"><?php _e('选择一个备份文件'); ?></label>
                                <select name="file" id="backup-select-file" class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded text-sm focus:outline-none focus:border-discord-accent transition-colors">
                                    <?php foreach ($backupFiles as $file): ?>
                                        <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mt-auto">
                                <button type="submit" class="w-full py-2.5 bg-green-500 text-white rounded-md font-medium hover:bg-green-600 transition-colors shadow-sm flex items-center justify-center">
                                    <i class="fas fa-history mr-2"></i> <?php _e('选择并恢复'); ?>
                                </button>
                                <input type="hidden" name="do" value="import">
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer自然跟随内容 -->
    <?php include 'copyright.php'; ?>
</main>

<style>
/* Custom tab styling logic needs to be handled by JS now as classes changed */
</style>

<?php
include 'common-js.php';
include 'form-js.php';
?>
<script>
    $(document).ready(function() {
        // Tab switching
        $('.typecho-option-tabs a').click(function(e) {
            e.preventDefault();
            var targetId = $(this).attr('href');
            
            // Toggle classes for tabs
            $('.typecho-option-tabs a').removeClass('bg-white text-discord-text shadow-sm').addClass('text-gray-500 hover:text-discord-text');
            $(this).removeClass('text-gray-500 hover:text-discord-text').addClass('bg-white text-discord-text shadow-sm');
            
            // Toggle content
            $('.tab-content').addClass('hidden');
            $(targetId).removeClass('hidden');
        });

        // File input change
        $('#backup-upload-file').change(function() {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('#file-name-display').text(fileName).removeClass('hidden');
                $(this).parent().addClass('border-discord-accent bg-blue-50/30').removeClass('border-gray-300');
            }
        });

        // Confirmation
        $('#backup-secondary form').submit(function (e) {
            if (!confirm('<?php _e('恢复操作将清除所有现有数据, 是否继续?'); ?>')) {
                return false;
            }
        });
    });
</script>
<?php include 'footer.php'; ?>
