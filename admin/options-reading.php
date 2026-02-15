<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-book-open mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('阅读设置'); ?></span>
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
            
            <!-- Settings Tabs -->
            <div class="flex space-x-1 mb-6 bg-gray-100 p-1 rounded-lg select-none overflow-x-auto">
                <a href="<?php $options->adminUrl('options-general.php'); ?>" class="flex-1 text-center px-4 py-2 text-sm font-medium rounded-md transition-all text-gray-500 hover:text-discord-text hover:bg-gray-50"><?php _e('基本设置'); ?></a>
                <a href="<?php $options->adminUrl('options-discussion.php'); ?>" class="flex-1 text-center px-4 py-2 text-sm font-medium rounded-md transition-all text-gray-500 hover:text-discord-text hover:bg-gray-50"><?php _e('评论设置'); ?></a>
                <a href="<?php $options->adminUrl('options-reading.php'); ?>" class="flex-1 text-center px-4 py-2 text-sm font-medium rounded-md transition-all shadow-sm bg-white text-discord-text"><?php _e('阅读设置'); ?></a>
                <a href="<?php $options->adminUrl('options-permalink.php'); ?>" class="flex-1 text-center px-4 py-2 text-sm font-medium rounded-md transition-all text-gray-500 hover:text-discord-text hover:bg-gray-50"><?php _e('永久链接'); ?></a>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <?php \Widget\Options\Reading::alloc()->form()->render(); ?>
            </div>
        </div>
    </div>
</main>

<style>
/* Discord-style form customization */
.typecho-option { margin-bottom: 1.5rem; }
.typecho-option label { display: block; font-weight: 500; color: #4b5563; margin-bottom: 0.5rem; }
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
.typecho-option .description { display: block; margin-top: 0.375rem; font-size: 0.75rem; color: #9ca3af; }
.typecho-option .required { color: #ef4444; margin-left: 0.25rem; }
.typecho-option-submit button {
    background-color: #5865F2;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.875rem;
    transition: background-color 0.2s;
    border: none;
    cursor: pointer;
}
.typecho-option-submit button:hover { background-color: #4752c4; }
</style>

<?php
include 'common-js.php';
include 'form-js.php';
?>
<script>
$('#frontPage-recent,#frontPage-page,#frontPage-file').change(function () {
    var t = $(this);
    if (t.prop('checked')) {
        if ('frontPage-recent' == t.attr('id')) {
            $('.front-archive').addClass('hidden');
        } else {
            $('.front-archive').insertAfter(t.parent()).removeClass('hidden');
        }
    }
});
</script>
<?php
include 'footer.php';
?>
