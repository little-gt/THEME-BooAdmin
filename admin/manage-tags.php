<?php
include 'common.php';
include 'header.php';
include 'menu.php';

\Widget\Metas\Tag\Admin::alloc()->to($tags);
?>

<main class="flex-1 flex flex-col overflow-hidden bg-discord-light">
    <!-- Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
             <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-tags mr-2 hidden md:inline"></i>
            <span class="font-medium text-discord-text"><?php _e('管理标签'); ?></span>
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
        <div class="w-full max-w-none mx-auto grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Tag List -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden h-full flex flex-col">
                    <form method="post" name="manage_tags" class="operate-form flex flex-col h-full">
                        <div class="p-3 border-b border-gray-100 flex items-center justify-between bg-gray-50/50">
                             <div class="flex items-center space-x-2">
                                 <label class="flex items-center space-x-2 text-sm text-gray-500 cursor-pointer select-none">
                                     <input type="checkbox" class="typecho-table-select-all rounded text-discord-accent focus:ring-discord-accent border-gray-300">
                                     <span><?php _e('全选'); ?></span>
                                 </label>
                                 <div class="relative group">
                                    <button type="button" class="btn-dropdown-toggle px-3 py-1 text-xs font-medium bg-white border border-gray-300 rounded hover:bg-gray-50 text-gray-700 shadow-sm flex items-center">
                                        <?php _e('选中项'); ?> <i class="fas fa-chevron-down ml-1"></i>
                                    </button>
                                    <div class="dropdown-menu absolute left-0 mt-1 w-64 bg-white rounded-md shadow-lg border border-gray-100 py-1 hidden group-hover:block z-50">
                                        <a lang="<?php _e('你确认要删除这些标签吗?'); ?>" href="<?php $security->index('/action/metas-tag-edit?do=delete'); ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:text-red-700"><?php _e('删除'); ?></a>
                                        <a lang="<?php _e('刷新标签可能需要等待较长时间, 你确认要刷新这些标签吗?'); ?>" href="<?php $security->index('/action/metas-tag-edit?do=refresh'); ?>" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"><?php _e('刷新'); ?></a>
                                        <div class="border-t border-gray-100 my-1"></div>
                                        <div class="px-4 py-2">
                                            <div class="flex items-center space-x-2">
                                                <button type="submit" lang="<?php _e('你确认要合并这些标签吗?'); ?>" class="btn-merge px-2 py-1 text-xs bg-discord-accent text-white rounded hover:bg-blue-600 transition-colors" rel="<?php $security->index('/action/metas-tag-edit?do=merge'); ?>"><?php _e('合并到'); ?></button>
                                                <input type="text" name="merge" class="text-xs border border-gray-300 rounded px-2 py-1 focus:outline-none focus:border-discord-accent w-24" placeholder="<?php _e('标签名'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                 </div>
                             </div>
                        </div>

                        <div class="p-4 flex-1 overflow-y-auto custom-scrollbar">
                            <?php if ($tags->have()): ?>
                                <ul class="flex flex-wrap gap-2 typecho-list-notable tag-list">
                                    <?php while ($tags->next()): ?>
                                        <li class="relative group" id="<?php $tags->theId(); ?>">
                                            <input type="checkbox" value="<?php $tags->mid(); ?>" name="mid[]" class="absolute opacity-0 pointer-events-none peer">
                                            <div class="flex items-center px-3 py-1.5 bg-gray-100 rounded-full border border-gray-200 hover:border-discord-accent hover:bg-blue-50 transition-all cursor-pointer peer-checked:bg-discord-accent peer-checked:text-white peer-checked:border-discord-accent select-none">
                                                <span class="text-sm font-medium mr-1" rel="<?php echo $request->makeUriByRequest('mid=' . $tags->mid); ?>"><?php $tags->name(); ?></span>
                                                <span class="text-xs opacity-60 bg-gray-200 px-1.5 rounded-full text-gray-600 ml-1 group-hover:bg-white peer-checked:text-discord-accent"><?php $tags->count(); ?></span>
                                                <a class="ml-2 text-gray-400 hover:text-discord-accent peer-checked:text-white peer-checked:hover:text-white opacity-0 group-hover:opacity-100 transition-opacity" href="<?php echo $request->makeUriByRequest('mid=' . $tags->mid); ?>" title="<?php _e('编辑'); ?>"><i class="fas fa-edit"></i></a>
                                            </div>
                                            <!-- Checkbox logic via JS needs to toggle checked state on click of the div -->
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <div class="text-center py-10 text-gray-500">
                                    <i class="fas fa-tags text-4xl mb-3 text-gray-300"></i>
                                    <p><?php _e('没有任何标签'); ?></p>
                                </div>
                            <?php endif; ?>
                            <input type="hidden" name="do" value="delete"/>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-4">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 pb-2 border-b border-gray-100"><?php _e(isset($request->mid) ? '编辑标签' : '新增标签'); ?></h3>
                    <?php \Widget\Metas\Tag\Edit::alloc()->form()->render(); ?>
                    <?php if (isset($request->mid)): ?>
                        <div class="mt-4 text-center">
                            <a href="<?php $options->adminUrl('manage-tags.php'); ?>" class="text-sm text-discord-accent hover:underline"><?php _e('取消编辑，返回新增'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer在main内部 -->
    <?php include 'copyright.php'; ?>
</main>

<style>
/* Tag selection styling */
.tag-list li input:checked ~ div {
    background-color: #5865F2;
    color: white;
    border-color: #5865F2;
}
.tag-list li input:checked ~ div .text-xs {
    color: #5865F2;
    background-color: white;
}
</style>

<?php
include 'common-js.php';
include 'form-js.php';
?>

<script type="text/javascript">
(function () {
    $(document).ready(function () {
        // Tag selection logic
        $('.tag-list li div').click(function(e) {
            // If clicking edit link, don't toggle checkbox
            if ($(e.target).closest('a').length) return;
            
            var checkbox = $(this).siblings('input[type="checkbox"]');
            checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
        });

        // Select All logic customization for this specific layout
        $('.typecho-table-select-all').change(function() {
            var checked = $(this).prop('checked');
            $('.tag-list input[type="checkbox"]').prop('checked', checked);
        });

        $('.btn-dropdown-toggle').dropdownMenu({
            btnEl       :   '.btn-dropdown-toggle',
            menuEl      :   '.dropdown-menu'
        });

        $('.btn-merge').click(function () {
            var btn = $(this);
            btn.parents('form').attr('action', btn.attr('rel')).submit();
        });
        
        // Ensure form JS works correctly
        $('.typecho-option input').first().focus();

        <?php if (isset($request->mid)): ?>
        $('#<?php echo $request->mid; ?> div').addClass('ring-2 ring-offset-1 ring-yellow-400 border-yellow-400');
        <?php endif; ?>
    });
})();
</script>


