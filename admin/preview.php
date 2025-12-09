<?php
include 'common.php';

// 获取内容 Widget
\Widget\Archive::alloc('type=single&checkPermalink=0&preview=1')->to($content);

// 检测是否存在
if (!$content->have()) {
    $response->redirect($options->adminUrl);
}

// 检测权限
if (!$user->pass('editor', true) && $content->authorId != $user->uid) {
    $response->redirect($options->adminUrl);
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview</title>
    <!-- 引入 Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
            padding: 40px;
            font-family: 'Nunito', sans-serif;
            color: #2d3436;
        }
        .preview-container {
            max-width: 800px;
            margin: 0 auto;
        }
        /* 文章内容样式重置，模拟前台 */
        img { max-width: 100%; height: auto; border-radius: 8px; }
        blockquote { border-left: 4px solid #eee; padding-left: 15px; color: #666; margin: 20px 0; }
        pre { background: #f8f9fa; padding: 15px; border-radius: 8px; }
        h1, h2, h3 { margin-top: 30px; margin-bottom: 15px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="preview-container">
        <h1 class="mb-4 display-5 fw-bold"><?php $content->title(); ?></h1>
        <div class="article-content">
            <?php $content->content(); ?>
        </div>
    </div>
    
    <script>
        // 监听父窗口关闭信号
        window.onbeforeunload = function () {
            if (!!window.parent) {
                window.parent.postMessage('cancelPreview', '<?php $options->rootUrl(); ?>');
            }
        }
    </script>
</body>
</html>