<?php
// 引入通用配置、头部和菜单文件
include 'common.php';
include 'header.php';
include 'menu.php';

// 初始化统计组件，用于获取各种网站数据
$stat = Typecho\Widget::widget('Widget_Stat');

// --- 获取最近7天的评论数据用于图表 ---
$db = Typecho\Db::get();
$prefix = $db->getPrefix();
$now = time();
$sevenDaysAgo = $now - (7 * 24 * 3600);
$chartData = [];
$chartLabels = [];

// 初始化最近7天的日期和评论数为0
for ($i = 6; $i >= 0; $i--) {
    $date = date('m-d', strtotime("-$i days"));
    $chartLabels[] = $date;
    $chartData[$date] = 0;
}

try {
    // 执行数据库查询，按天统计评论数
    $sql = "SELECT FROM_UNIXTIME(created, '%m-%d') as date, COUNT(*) as count
            FROM {$prefix}comments
            WHERE created > {$sevenDaysAgo}
            GROUP BY date";
    $results = $db->fetchAll($sql);

    // 将查询结果合并到图表数据中
    foreach ($results as $row) {
        if (isset($chartData[$row['date']])) {
            $chartData[$row['date']] = $row['count'];
        }
    }
} catch (Exception $e) {
    // 数据库查询容错处理，防止异常导致页面崩溃
    // Typecho 的 Db::fetchAll 可能会在查询错误时抛出异常
}
?>

<div class="container-fluid">

    <!-- 欢迎语与网站状态概览 -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-modern bg-white p-4">
                <div class="d-md-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">
                            <?php _e('欢迎回来, %s！', $user->screenName); ?>
                        </h4>
                        <p class="text-muted mb-0 small">
                            <?php _e('目前有 %s 篇文章, %s 条评论, %s 个分类',
                                '<span class="fw-bold text-primary">'.$stat->myPublishedPostsNum.'</span>',
                                '<span class="fw-bold text-primary">'.$stat->myPublishedCommentsNum.'</span>',
                                '<span class="fw-bold text-primary">'.$stat->categoriesNum.'</span>'
                            ); ?>
                        </p>
                    </div>
                    <?php if($user->pass('editor', true)): // 仅编辑及以上权限显示撰写按钮 ?>
                    <div class="mt-3 mt-md-0">
                        <a href="<?php $options->adminUrl('write-post.php'); ?>" class="btn btn-primary px-4 shadow-sm fw-bold">
                            <i class="fa-solid fa-pen-nib me-2"></i><?php _e('撰写新文章'); ?>
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- 统计卡片行 - 显示关键数据概览 -->
    <div class="row g-4 mb-4" style="animation-delay: 0.1s;">
        <!-- 已发布文章数 -->
        <div class="col-xl-3 col-sm-6">
            <div class="card-modern stat-widget h-100 d-flex align-items-center p-4">
                <div class="stat-icon bg-light-primary">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo $stat->myPublishedPostsNum; ?></h3>
                    <span class="text-muted small text-uppercase fw-bold"><?php _e('已发布文章'); ?></span>
                </div>
            </div>
        </div>

        <!-- 收到的评论数 -->
        <div class="col-xl-3 col-sm-6">
            <div class="card-modern stat-widget h-100 d-flex align-items-center p-4">
                <div class="stat-icon bg-light-success">
                    <i class="fa-solid fa-comments"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo $stat->myPublishedCommentsNum; ?></h3>
                    <span class="text-muted small text-uppercase fw-bold"><?php _e('收到的评论'); ?></span>
                </div>
            </div>
        </div>

        <!-- 待审核评论数 - 根据数量显示不同颜色 -->
        <div class="col-xl-3 col-sm-6">
            <div class="card-modern stat-widget h-100 d-flex align-items-center p-4">
                <div class="stat-icon <?php echo $stat->waitingCommentsNum > 0 ? 'bg-light-danger' : 'bg-light'; ?>">
                    <i class="fa-solid fa-user-clock"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 <?php echo $stat->waitingCommentsNum > 0 ? 'text-danger' : ''; ?>">
                        <?php echo $stat->waitingCommentsNum; ?>
                    </h3>
                    <span class="text-muted small text-uppercase fw-bold"><?php _e('待审核评论'); ?></span>
                </div>
            </div>
        </div>

        <!-- 分类数量 -->
        <div class="col-xl-3 col-sm-6">
            <div class="card-modern stat-widget h-100 d-flex align-items-center p-4">
                <div class="stat-icon bg-light-warning">
                    <i class="fa-solid fa-folder-tree"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0"><?php echo $stat->categoriesNum; ?></h3>
                    <span class="text-muted small text-uppercase fw-bold"><?php _e('分类数量'); ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- 图表与官方动态区域 -->
    <div class="row g-4 mb-4">
        <!-- 统计图表 - 最近7天评论趋势 -->
        <div class="col-lg-8" style="animation-delay: 0.2s;">
            <div class="card-modern h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-chart-area me-2 text-primary"></i><?php _e('互动趋势 (近7天评论)'); ?></h5>
                </div>
                <!-- Chart.js 渲染容器，限制高度以确保布局美观 -->
                <div class="chart-container" style="position: relative; height: 320px; width: 100%;">
                    <canvas id="commentChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 官方动态/版本信息 -->
        <div class="col-lg-4" style="animation-delay: 0.3s;">
            <div class="card-modern h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark"><i class="fa-solid fa-bullhorn me-2 text-warning"></i><?php _e('官方动态'); ?></h5>
                </div>

                <div id="typecho-message" class="typecho-message-list">
                    <div class="text-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary mb-2" role="status"></div>
                        <p class="small mb-0"><?php _e('正在读取最新动态...'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 最近文章与评论列表 -->
    <div class="row g-4" style="animation-delay: 0.4s;">
        <!-- 最近文章列表 -->
        <div class="col-lg-6">
            <div class="card-modern h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark"><?php _e('最近文章'); ?></h5>
                    <a href="<?php $options->adminUrl('manage-posts.php'); ?>" class="btn btn-sm btn-light text-primary fw-bold"><?php _e('更多'); ?></a>
                </div>
                <div class="table-responsive">
                    <table class="table modern-table table-borderless table-hover mb-0">
                        <thead>
                            <tr>
                                <th><?php _e('日期'); ?></th>
                                <th><?php _e('标题'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php // 调用 Typecho Widget 获取最近 5 篇文章
                            Typecho\Widget::widget('Widget_Contents_Post_Recent', 'pageSize=5')->to($posts); ?>
                            <?php if($posts->have()): ?>
                                <?php while($posts->next()): ?>
                                <tr>
                                    <td class="text-muted small" style="width: 100px;">
                                        <?php $posts->date('M j'); ?>
                                    </td>
                                    <td>
                                        <a href="<?php $posts->permalink(); ?>" class="text-decoration-none text-dark fw-bold text-truncate d-block" style="max-width: 280px;">
                                            <?php $posts->title(); ?>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="2" class="text-center text-muted py-4"><?php _e('暂时没有文章'); ?></td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 最近回复列表 -->
        <div class="col-lg-6">
            <div class="card-modern h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-dark"><?php _e('最近回复'); ?></h5>
                    <a href="<?php $options->adminUrl('manage-comments.php'); ?>" class="btn btn-sm btn-light text-primary fw-bold"><?php _e('更多'); ?></a>
                </div>

                <div class="comment-list-modern">
                    <?php // 调用 Typecho Widget 获取最近 5 条评论
                    Typecho\Widget::widget('Widget_Comments_Recent', 'pageSize=5')->to($comments); ?>
                    <?php if($comments->have()): ?>
                        <?php while($comments->next()): ?>
                        <div class="d-flex mb-3 border-bottom pb-3 last-no-border">
                            <!-- 评论者头像 -->
                            <img src="<?php echo \Typecho\Common::gravatarUrl($comments->mail, 40, 'X', 'mm', $request->isSecure()); ?>"
                                 class="rounded-circle me-3 border" width="40" height="40" alt="Avatar">

                            <div style="flex: 1; min-width: 0;">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="fw-bold text-dark font-size-sm"><?php $comments->author(false); ?></span>
                                    <span class="badge bg-light text-muted small fw-normal"><?php $comments->dateWord(); ?></span>
                                </div>
                                <div class="text-muted small text-truncate">
                                    <?php _e('于'); ?> <a href="<?php $comments->permalink(); ?>" class="text-primary text-decoration-none"><?php $comments->title(); ?></a>:
                                </div>
                                <p class="text-secondary small mb-0 mt-1 line-clamp-1">
                                    <?php $comments->excerpt(35, '...'); ?>
                                </p>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="text-center text-muted py-4"><?php _e('暂时没有回复'); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// 引入版权信息、通用JS和页脚文件
include 'copyright.php';
include 'common-js.php';
?>

<script>
// 使用 IIFE (立即执行函数) 包裹代码，防止全局变量污染和 PJAX 重复声明错误
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        // 由于 PJAX 可能不会触发 DOMContentLoaded (如果脚本是在 PJAX 之后插入的)，
        // 我们需要一个更可靠的检查，或者依赖 jQuery 的 ready
        initDashboard();
    });

    // 兼容 PJAX：如果是在 PJAX reload 时，脚本被直接执行，此时 document 已经 ready
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        initDashboard();
    }

    function initDashboard() {
        // 检查 Canvas 元素是否存在，防止报错
        const ctxElement = document.getElementById('commentChart');
        if (!ctxElement) return;

        // 避免重复初始化 Chart
        if (ctxElement.chartInstance) {
            ctxElement.chartInstance.destroy();
        }

        // --- Chart.js 初始化：渲染近7天评论趋势图 ---
        const chartLabels = <?php echo json_encode($chartLabels); ?>;
        const chartData = <?php echo json_encode(array_values($chartData)); ?>;

        const ctx = ctxElement.getContext('2d');

        // 定义图表渐变色
        let gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(108, 92, 231, 0.2)'); // 主色调
        gradient.addColorStop(1, 'rgba(108, 92, 231, 0.0)');

        // 保存实例以便下次销毁
        ctxElement.chartInstance = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: '<?php _e("评论数量"); ?>',
                    data: chartData,
                    backgroundColor: gradient,
                    borderColor: '#6c5ce7',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#6c5ce7',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    fill: true,
                    tension: 0.4 // 贝塞尔曲线平滑度
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false, // 允许填满容器高度
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#2d3436',
                        padding: 10,
                        titleFont: { size: 13 },
                        bodyFont: { size: 13 },
                        displayColors: false,
                        cornerRadius: 8,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { precision: 0, color: '#b2bec3' }, // 整数刻度
                        grid: { color: '#f1f2f6', borderDash: [5, 5] },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#b2bec3' },
                        border: { display: false }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });

        // --- 异步加载 Typecho 官方动态 ---
        const msgContainer = document.getElementById('typecho-message');
        if (msgContainer) {
            const cache = window.sessionStorage;
            const feedHtml = cache ? cache.getItem('feed') : '';

            if (feedHtml) {
                // 如果缓存中存在，直接使用缓存内容
                msgContainer.innerHTML = '<ul class="list-unstyled mb-0">' + feedHtml + '</ul>';
            } else {
                // 否则通过 AJAX 从 Typecho 后端获取最新动态
                $.get('<?php $options->index('/action/ajax?do=feed'); ?>', function (o) {
                    let html = '<ul class="list-unstyled mb-0">';
                    for (let i = 0; i < o.length; i++) {
                        let item = o[i];
                        html += `<li class="mb-2 pb-2 border-bottom last-no-border">
                            <div class="d-flex justify-content-between">
                                <a href="${item.link}" target="_blank" class="text-dark text-decoration-none fw-bold small text-truncate" style="max-width: 75%;">${item.title}</a>
                                <span class="badge bg-light text-muted fw-normal scale-8">${item.date}</span>
                            </div>
                        </li>`;
                    }
                    html += '</ul>';
                    msgContainer.innerHTML = html;
                    if(cache) cache.setItem('feed', html); // 缓存内容 (不带ul标签以便后续处理)
                }, 'json');
            }
        }
    }
})();
</script>

<?php include 'footer.php'; ?>