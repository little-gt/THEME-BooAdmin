<?php
include 'common.php';
include 'header.php';
include 'menu.php';
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Top Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
            <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-paint-brush mr-2 hidden md:inline"></i>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('外观'); ?></span>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('设置'); ?></span>
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

    <!-- Content Area -->
    <div class="flex-1 overflow-y-auto p-4 md:p-8">
        <div class="w-full max-w-none mx-auto">
             <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                 <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h2 class="text-lg font-bold text-discord-text flex items-center">
                        <i class="fas fa-sliders-h text-discord-accent mr-2"></i> <?php _e('外观设置'); ?>
                    </h2>
                     <a href="<?php $options->adminUrl('themes.php'); ?>" class="text-sm text-discord-muted hover:text-discord-accent">
                        <i class="fas fa-arrow-left mr-1"></i> <?php _e('返回外观列表'); ?>
                    </a>
                </div>
                <div class="p-8">
                     <div class="typecho-reform-style">
                        <?php \Widget\Themes\Config::alloc()->config()->render(); ?>
                     </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
/* Reusing the form styling from profile.php/options-plugin.php for consistency */
.typecho-reform-style ul {
    list-style: none;
    padding: 0;
    margin: 0;
}
.typecho-reform-style li {
    margin-bottom: 1.5rem;
}
.typecho-reform-style label {
    display: block;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #4B5563;
}
.typecho-reform-style input[type="text"],
.typecho-reform-style input[type="password"],
.typecho-reform-style input[type="email"],
.typecho-reform-style input[type="url"],
.typecho-reform-style textarea,
.typecho-reform-style select {
    width: 100%;
    padding: 0.5rem 0.75rem;
    background-color: #F9FAFB;
    border: 1px solid #E5E7EB;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    color: #1F2937;
    transition: all 0.2s;
}
.typecho-reform-style input:focus,
.typecho-reform-style textarea:focus,
.typecho-reform-style select:focus {
    outline: none;
    border-color: #5865F2;
    box-shadow: 0 0 0 3px rgba(88, 101, 242, 0.1);
    background-color: #FFFFFF;
}
.typecho-reform-style .description {
    display: block;
    margin-top: 0.25rem;
    font-size: 0.75rem;
    color: #9CA3AF;
}
.typecho-reform-style button[type="submit"] {
    background-color: #5865F2;
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 0.375rem;
    font-weight: 500;
    font-size: 0.875rem;
    border: none;
    cursor: pointer;
    transition: background-color 0.2s;
}
.typecho-reform-style button[type="submit"]:hover {
    background-color: #4752C4;
}
.typecho-reform-style input[type="radio"],
.typecho-reform-style input[type="checkbox"] {
    margin-right: 0.5rem;
    accent-color: #5865F2;
}
.typecho-reform-style .typecho-option span {
    margin-right: 1.5rem;
    display: inline-flex;
    align-items: center;
}
</style>

<?php
include 'common-js.php';
include 'form-js.php';
include 'footer.php';
?>
