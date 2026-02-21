<?php
include 'common.php';
include 'header.php';
include 'menu.php';

$stat = \Widget\Stat::alloc();

// 获取最近7天的文章和评论数据
$db = \Typecho\Db::get();
$days = [];
$postsData = [];
$commentsData = [];

for ($i = 6; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-{$i} days"));
    $dayName = date('m/d', strtotime("-{$i} days"));
    $days[] = $dayName;
    
    // 当天文章数
    $startTime = strtotime($date . ' 00:00:00');
    $endTime = strtotime($date . ' 23:59:59');
    
    $postCount = $db->fetchObject($db->select(['COUNT(cid)' => 'num'])
        ->from('table.contents')
        ->where('type = ?', 'post')
        ->where('status = ?', 'publish')
        ->where('created >= ?', $startTime)
        ->where('created <= ?', $endTime))->num;
    $postsData[] = (int)$postCount;
    
    // 当天评论数
    $commentCount = $db->fetchObject($db->select(['COUNT(coid)' => 'num'])
        ->from('table.comments')
        ->where('status = ?', 'approved')
        ->where('created >= ?', $startTime)
        ->where('created <= ?', $endTime))->num;
    $commentsData[] = (int)$commentCount;
}

$chartDays = json_encode($days);
$chartPosts = json_encode($postsData);
$chartComments = json_encode($commentsData);
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
                            <?php _e('内容趋势'); ?>
                        </h3>
                         <div class="flex items-center space-x-4 text-xs">
                            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span><?php _e('文章'); ?></span>
                            <span class="flex items-center"><span class="w-3 h-3 rounded-full bg-green-500 mr-1"></span><?php _e('评论'); ?></span>
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
                                        <?php 
                                        $gravatarUrl = \Typecho\Common::gravatarUrl($comments->mail, 32);
                                        $authorName = $comments->author(false);
                                        $firstChar = mb_substr($authorName, 0, 1, 'UTF-8');
                                        ?>
                                        <img src="<?php echo $gravatarUrl; ?>" 
                                             alt="<?php echo htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8'); ?>" 
                                             class="comment-avatar w-8 h-8 rounded-full flex-shrink-0 border border-gray-200"
                                             data-fallback="<?php echo htmlspecialchars($firstChar, ENT_QUOTES, 'UTF-8'); ?>"
                                             onerror="generateFallbackAvatar(this, this.dataset.fallback, '#5865F2', 32);" />
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
                 <div class="lg:col-span-3 bg-gradient-to-r from-indigo-50 via-white to-purple-50 rounded-xl shadow-sm p-5 border border-gray-100 mt-2">
                    <div class="flex items-center justify-between mb-4">
                         <h3 class="text-sm font-bold text-gray-700 flex items-center">
                            <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center mr-3 shadow-sm">
                                <i class="fas fa-bullhorn text-white text-xs"></i>
                            </span>
                            <?php _e('Typecho 官方动态'); ?>
                        </h3>
                        <a href="https://typecho.org" target="_blank" class="text-xs text-indigo-500 hover:text-indigo-700 font-medium flex items-center transition-colors">
                            <?php _e('访问官网'); ?> <i class="fas fa-external-link-alt ml-1 text-[10px]"></i>
                        </a>
                    </div>
                    <div id="typecho-message" class="text-sm text-gray-600">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3" id="typecho-news-grid">
                            <div class="animate-pulse flex items-center space-x-3 p-3 bg-white rounded-lg border border-gray-100">
                                <div class="w-10 h-10 bg-gray-200 rounded-lg"></div>
                                <div class="flex-1">
                                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-2 bg-gray-100 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <!-- Footer在main内部 -->
    <?php include 'copyright.php'; ?>
</main>
<?php
include 'common-js.php';
?>
<script>
    // 生成降级头像的通用函数
    function generateFallbackAvatar(img, text, color, size) {
        if (!text) text = '?';
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}">
            <rect width="${size}" height="${size}" fill="${color}"/>
            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" 
                  fill="white" font-size="${Math.floor(size * 0.45)}" font-weight="bold" 
                  font-family="sans-serif">${text.toUpperCase()}</text>
        </svg>`;
        img.src = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(svg);
        img.onerror = null;
    }
    
    $(document).ready(function () {
        // Activity Chart Config - 使用真实数据
        var chartDays = <?php echo $chartDays; ?>;
        var chartPosts = <?php echo $chartPosts; ?>;
        var chartComments = <?php echo $chartComments; ?>;
        
        var activityOption = {
            grid: { top: 30, right: 20, bottom: 30, left: 40, containLabel: true },
            tooltip: { 
                trigger: 'axis',
                backgroundColor: 'rgba(255,255,255,0.95)',
                borderColor: '#e5e7eb',
                borderWidth: 1,
                textStyle: { color: '#374151' },
                formatter: function(params) {
                    var result = '<div style="font-weight:600;margin-bottom:8px">' + params[0].axisValue + '</div>';
                    params.forEach(function(item) {
                        result += '<div style="display:flex;align-items:center;margin:4px 0">' +
                            '<span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:' + item.color + ';margin-right:8px"></span>' +
                            item.seriesName + ': <strong style="margin-left:auto">' + item.value + '</strong></div>';
                    });
                    return result;
                }
            },
            xAxis: {
                type: 'category',
                data: chartDays,
                axisLine: { show: false },
                axisTick: { show: false },
                axisLabel: { color: '#9ca3af', fontSize: 11 }
            },
            yAxis: {
                type: 'value',
                minInterval: 1,
                splitLine: { lineStyle: { type: 'dashed', color: '#f3f4f6' } },
                axisLabel: { color: '#9ca3af' }
            },
            series: [
                {
                    name: '<?php _e('文章'); ?>',
                    data: chartPosts,
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
                },
                {
                    name: '<?php _e('评论'); ?>',
                    data: chartComments,
                    type: 'line',
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    itemStyle: { color: '#3BA55C' },
                    areaStyle: {
                        color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [
                            { offset: 0, color: 'rgba(59, 165, 92, 0.2)' },
                            { offset: 1, color: 'rgba(59, 165, 92, 0.02)' }
                        ])
                    },
                    lineStyle: { width: 3, shadowColor: 'rgba(59, 165, 92, 0.2)', shadowBlur: 10 }
                }
            ]
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

        var newsGrid = $('#typecho-news-grid'), cache = window.sessionStorage,
            html = cache ? cache.getItem('feed_v2') : '',
            update = cache ? cache.getItem('update') : '';
        
        var newsIcons = ['fa-newspaper', 'fa-code-branch', 'fa-rocket', 'fa-star', 'fa-bolt', 'fa-gift'];
        var newsColors = [
            'from-blue-500 to-indigo-500',
            'from-green-500 to-teal-500', 
            'from-purple-500 to-pink-500',
            'from-orange-500 to-red-500',
            'from-cyan-500 to-blue-500',
            'from-rose-500 to-pink-500'
        ];

        if (!!html) {
            newsGrid.html(html);
        } else {
            html = '';
            $.get('<?php $options->index('/action/ajax?do=feed'); ?>', function (o) {
                for (var i = 0; i < o.length && i < 6; i++) {
                    var item = o[i];
                    var icon = newsIcons[i % newsIcons.length];
                    var color = newsColors[i % newsColors.length];
                    html += '<a href="' + item.link + '" target="_blank" class="group flex items-center space-x-3 p-3 bg-white hover:bg-gray-50 rounded-lg border border-gray-100 hover:border-indigo-200 transition-all hover:shadow-sm">' +
                        '<div class="w-10 h-10 rounded-lg bg-gradient-to-br ' + color + ' flex items-center justify-center flex-shrink-0 shadow-sm group-hover:scale-105 transition-transform">' +
                        '<i class="fas ' + icon + ' text-white text-sm"></i></div>' +
                        '<div class="flex-1 min-w-0">' +
                        '<p class="text-sm font-medium text-gray-700 group-hover:text-indigo-600 truncate transition-colors">' + item.title + '</p>' +
                        '<p class="text-xs text-gray-400 mt-0.5">' + item.date + '</p>' +
                        '</div>' +
                        '<i class="fas fa-chevron-right text-gray-300 group-hover:text-indigo-400 text-xs transition-colors"></i></a>';
                }
                
                if (o.length === 0) {
                    html = '<div class="col-span-full text-center py-4 text-gray-400"><i class="fas fa-inbox mr-2"></i><?php _e('暂无动态'); ?></div>';
                }

                newsGrid.html(html);
                cache.setItem('feed_v2', html);
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
