<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();
?>
<main class="flex-1 flex flex-col overflow-hidden">
    <!-- Top Header -->
    <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm z-10">
        <div class="flex items-center text-discord-muted">
            <button id="mobile-menu-btn" class="mr-4 md:hidden text-discord-text focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
            <i class="fas fa-home mr-2 hidden md:inline"></i>
            <span class="mx-2 hidden md:inline">/</span>
            <span class="font-medium text-discord-text"><?php _e('控制台'); ?></span>
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
            
            <!-- Welcome Section -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6 flex items-center justify-between border-l-4 border-discord-accent">
                <div>
                    <h2 class="text-2xl font-bold text-discord-text mb-2"><?php _e('欢迎回来, %s!', $user->screenName); ?></h2>
                    <p class="text-discord-muted"><?php _e('目前有 <strong>%s</strong> 篇文章, 并有 <strong>%s</strong> 条关于你的评论在 <strong>%s</strong> 个分类中.',
                        $stat->myPublishedPostsNum, $stat->myPublishedCommentsNum, $stat->categoriesNum); ?></p>
                </div>
                <div class="flex space-x-3">
                    <a href="<?php $options->adminUrl('write-post.php'); ?>" class="bg-discord-accent hover:bg-blue-600 text-white px-4 py-2 rounded-md font-medium transition-colors shadow-sm text-sm">
                        <i class="fas fa-pen mr-2"></i> <?php _e('撰写新文章'); ?>
                    </a>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <h3 class="text-discord-muted font-bold text-xs uppercase tracking-wider"><?php _e('总文章数'); ?></h3>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-500">
                            <i class="fas fa-file-alt text-lg"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-800 relative z-10"><?php echo $stat->myPublishedPostsNum; ?></div>
                    <div class="mt-2 text-xs text-green-500 font-bold flex items-center relative z-10">
                        <i class="fas fa-arrow-up mr-1"></i> <span><?php _e('持续更新中'); ?></span>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-green-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <h3 class="text-discord-muted font-bold text-xs uppercase tracking-wider"><?php _e('总评论数'); ?></h3>
                         <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-500">
                            <i class="fas fa-comments text-lg"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-800 relative z-10"><?php echo $stat->myPublishedCommentsNum; ?></div>
                    <div class="mt-2 text-xs text-blue-500 font-bold flex items-center relative z-10">
                         <i class="fas fa-chart-line mr-1"></i> <span><?php _e('互动活跃'); ?></span>
                    </div>
                </div>
                 <!-- Card 3 -->
                 <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-yellow-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <h3 class="text-discord-muted font-bold text-xs uppercase tracking-wider"><?php _e('待审核'); ?></h3>
                         <div class="w-10 h-10 rounded-full bg-yellow-100 flex items-center justify-center text-yellow-500">
                            <i class="fas fa-hourglass-half text-lg"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-800 relative z-10"><?php echo $stat->waitingCommentsNum; ?></div>
                    <div class="mt-2 text-xs text-discord-muted font-bold flex items-center relative z-10">
                        <span><?php _e('需要处理'); ?></span>
                    </div>
                </div>
                 <!-- Card 4 -->
                 <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow relative overflow-hidden group">
                    <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-purple-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="flex items-center justify-between mb-4 relative z-10">
                        <h3 class="text-discord-muted font-bold text-xs uppercase tracking-wider"><?php _e('分类数量'); ?></h3>
                         <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-500">
                            <i class="fas fa-folder text-lg"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-gray-800 relative z-10"><?php echo $stat->categoriesNum; ?></div>
                     <div class="mt-2 text-xs text-discord-muted font-bold flex items-center relative z-10">
                        <span><?php _e('内容架构'); ?></span>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Main Activity Chart -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                         <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-chart-area mr-2 text-discord-accent"></i>
                            <?php _e('访问趋势'); ?>
                        </h3>
                         <div class="flex space-x-2">
                            <span class="px-2 py-1 bg-gray-100 text-xs rounded text-gray-500 cursor-pointer hover:bg-gray-200"><?php _e('本周'); ?></span>
                            <span class="px-2 py-1 bg-white text-xs rounded text-gray-400 cursor-pointer hover:bg-gray-50"><?php _e('本月'); ?></span>
                        </div>
                    </div>
                    <div id="activity-chart" style="height: 300px; width: 100%;"></div>
                </div>
                
                <!-- Distribution Chart -->
                <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                         <i class="fas fa-chart-pie mr-2 text-green-500"></i>
                        <?php _e('内容分布'); ?>
                    </h3>
                    <div id="distribution-chart" style="height: 300px; width: 100%;"></div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Recent Posts -->
                <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-0 border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-newspaper mr-2 text-gray-400"></i>
                            <?php _e('最近文章'); ?>
                        </h3>
                        <a href="<?php $options->adminUrl('write-post.php'); ?>" class="text-xs font-medium text-discord-accent hover:underline"><?php _e('写文章'); ?></a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50">
                                <tr class="text-gray-500">
                                    <th class="py-3 px-6 font-medium"><?php _e('标题'); ?></th>
                                    <th class="py-3 px-6 font-medium"><?php _e('日期'); ?></th>
                                    <th class="py-3 px-6 font-medium text-right"><?php _e('操作'); ?></th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                <?php \Widget\Contents\Post\Recent::alloc('pageSize=5')->to($posts); ?>
                                <?php if ($posts->have()): ?>
                                    <?php while ($posts->next()): ?>
                                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors group">
                                            <td class="py-3 px-6 font-medium">
                                                <a href="<?php $posts->permalink(); ?>" class="text-discord-text group-hover:text-discord-accent transition-colors"><?php $posts->title(); ?></a>
                                            </td>
                                            <td class="py-3 px-6 text-gray-400 text-xs"><?php $posts->date('Y-m-d'); ?></td>
                                            <td class="py-3 px-6 text-right">
                                                <a href="<?php $options->adminUrl('write-post.php?cid=' . $posts->cid); ?>" class="text-gray-400 hover:text-discord-accent transition-colors p-2 rounded-full hover:bg-gray-100" title="<?php _e('编辑'); ?>"><i class="fas fa-pencil-alt"></i></a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="3" class="py-8 text-center text-gray-400"><?php _e('暂时没有文章'); ?></td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Comments -->
                <div class="bg-white rounded-xl shadow-sm p-0 border border-gray-100 overflow-hidden flex flex-col">
                     <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-gray-800 flex items-center">
                            <i class="fas fa-comments mr-2 text-gray-400"></i>
                            <?php _e('最新回复'); ?>
                        </h3>
                    </div>
                    <div class="p-0 flex-1 overflow-y-auto max-h-[400px]">
                        <?php \Widget\Comments\Recent::alloc('pageSize=5')->to($comments); ?>
                        <?php if ($comments->have()): ?>
                            <div class="divide-y divide-gray-50">
                            <?php while ($comments->next()): ?>
                                <div class="p-4 hover:bg-gray-50 transition-colors">
                                    <div class="flex items-start space-x-3">
                                        <div class="w-8 h-8 rounded-full bg-discord-accent text-white flex-shrink-0 flex items-center justify-center text-xs font-bold">
                                            <?php echo strtoupper(substr($comments->author, 0, 1)); ?>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-bold text-gray-800 truncate"><?php $comments->author(false); ?></p>
                                                <span class="text-xs text-gray-400"><?php $comments->date('m-d'); ?></span>
                                            </div>
                                            <div class="text-xs text-gray-500 mb-1">
                                                <?php _e('在'); ?> <a href="<?php $comments->permalink(); ?>" class="text-discord-accent hover:underline"><?php $comments->title(); ?></a>
                                            </div>
                                            <div class="text-sm text-gray-600 bg-gray-50 p-2 rounded border border-gray-100 mt-1 line-clamp-2">
                                                <?php $comments->excerpt(60, '...'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="p-8 text-center text-sm text-gray-400"><?php _e('暂时没有回复'); ?></div>
                        <?php endif; ?>
                    </div>
                    <a href="<?php $options->adminUrl('manage-comments.php'); ?>" class="block p-3 text-center text-xs font-bold text-gray-500 hover:text-discord-accent bg-gray-50 border-t border-gray-100 transition-colors uppercase tracking-wide"><?php _e('查看所有评论'); ?></a>
                </div>

                <!-- Official Log (Compact) -->
                 <div class="lg:col-span-3 bg-white rounded-xl shadow-sm p-4 border border-gray-100 mt-2 opacity-75 hover:opacity-100 transition-opacity">
                    <div class="flex items-center justify-between">
                         <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider flex items-center">
                            <i class="fas fa-bullhorn mr-2"></i>
                            <?php _e('Typecho 动态'); ?>
                        </h3>
                    </div>
                    <div id="typecho-message" class="text-sm text-gray-600 mt-2">
                        <ul>
                            <li><?php _e('读取中...'); ?></li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>
<?php
include 'common-js.php';
?>
<script>
    $(document).ready(function () {
        // Activity Chart Config
        var activityOption = {
            grid: { top: 20, right: 20, bottom: 20, left: 40, containLabel: true },
            tooltip: { trigger: 'axis' },
            xAxis: {
                type: 'category',
                data: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                axisLine: { show: false },
                axisTick: { show: false },
                axisLabel: { color: '#9ca3af' }
            },
            yAxis: {
                type: 'value',
                splitLine: { lineStyle: { type: 'dashed', color: '#f3f4f6' } },
                axisLabel: { color: '#9ca3af' }
            },
            series: [{
                data: [15, 23, 18, 28, 20, 35, 42], // Simulated data
                type: 'line',
                smooth: true,
                symbol: 'circle',
                symbolSize: 8,
                itemStyle: { color: '#5865F2' },
                areaStyle: {
                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                        { offset: 0, color: 'rgba(88, 101, 242, 0.3)' },
                        { offset: 1, color: 'rgba(88, 101, 242, 0.05)' }
                    ])
                },
                lineStyle: { width: 3, shadowColor: 'rgba(88, 101, 242, 0.2)', shadowBlur: 10 }
            }]
        };

        // Distribution Chart Config
        var distributionOption = {
             tooltip: { trigger: 'item' },
             legend: { bottom: '0%', left: 'center', icon: 'circle' },
             series: [
                 {
                     name: '内容统计',
                     type: 'pie',
                     radius: ['40%', '70%'],
                     avoidLabelOverlap: false,
                     itemStyle: {
                         borderRadius: 10,
                         borderColor: '#fff',
                         borderWidth: 2
                     },
                     label: { show: false, position: 'center' },
                     emphasis: {
                         label: { show: true, fontSize: '18', fontWeight: 'bold' }
                     },
                     labelLine: { show: false },
                     data: [
                         { value: <?php echo $stat->myPublishedPostsNum; ?>, name: '<?php _e('文章'); ?>', itemStyle: { color: '#5865F2' } },
                         { value: <?php echo $stat->myPublishedCommentsNum; ?>, name: '<?php _e('评论'); ?>', itemStyle: { color: '#3BA55C' } },
                         { value: <?php echo $stat->categoriesNum; ?>, name: '<?php _e('分类'); ?>', itemStyle: { color: '#FAA61A' } },
                         { value: <?php echo $stat->waitingCommentsNum; ?>, name: '<?php _e('待审'); ?>', itemStyle: { color: '#ED4245' } }
                     ]
                 }
             ]
         };

        var activityChart = echarts.init(document.getElementById('activity-chart'));
        activityChart.setOption(activityOption);
        
        var distributionChart = echarts.init(document.getElementById('distribution-chart'));
        distributionChart.setOption(distributionOption);

        // Resize charts on window resize
        window.addEventListener('resize', function() {
            activityChart.resize();
            distributionChart.resize();
        });

        var ul = $('#typecho-message ul'), cache = window.sessionStorage,
            html = cache ? cache.getItem('feed') : '',
            update = cache ? cache.getItem('update') : '';

        if (!!html) {
            ul.html(html);
        } else {
            html = '';
            $.get('<?php $options->index('/action/ajax?do=feed'); ?>', function (o) {
                for (var i = 0; i < o.length; i++) {
                    var item = o[i];
                    html += '<li><span>' + item.date + '</span> <a href="' + item.link + '" target="_blank">' + item.title
                        + '</a></li>';
                }

                ul.html(html);
                cache.setItem('feed', html);
            }, 'json');
        }

        function applyUpdate(update) {
            if (update.available) {
                $('<div class="bg-blue-50 text-blue-700 p-3 rounded-md mb-4 text-sm"><i class="fas fa-info-circle mr-1"></i> '
                    + '<?php _e('您当前使用的版本是 %s'); ?>'.replace('%s', update.current) 
                    + ' <a href="' + update.link + '" target="_blank" class="font-bold underline ml-2">'
                    + '<?php _e('官方最新版本是 %s'); ?>'.replace('%s', update.latest) + '</a></div>')
                    .insertBefore('.typecho-page-main, .max-w-7xl');
            }
        }

        if (!!update) {
            applyUpdate($.parseJSON(update));
        } else {
            $.get('<?php $options->index('/action/ajax?do=checkVersion'); ?>', function (o, status, resp) {
                applyUpdate(o);
                cache.setItem('update', resp.responseText);
            }, 'json');
        }
    });
</script>
<?php include 'footer.php'; ?>
