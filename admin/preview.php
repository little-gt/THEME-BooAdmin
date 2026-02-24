<?php

include 'common.php';

/** 获取内容 Widget */
\Widget\Archive::alloc('type=single&checkPermalink=0&preview=1')->to($content);

/** 检测是否存在 */
if (!$content->have()) {
    $response->redirect($options->adminUrl);
}

/** 检测权限 */
if (!$user->pass('editor', true) && $content->authorId != $user->uid) {
    $response->redirect($options->adminUrl);
}

/** Output Content */
// Instead of rendering the theme, we output a clean HTML structure
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $content->title(); ?> - Preview</title>
</head>
<body>
    <article>
        <header class="post-title">
            <h1><?php $content->title(); ?></h1>
            <div class="post-meta">
                <time datetime="<?php $content->date('c'); ?>"><?php $content->date(); ?></time>
                <span>&bull;</span>
                <span><?php $content->author(); ?></span>
            </div>
        </header>
        <div class="post-content">
            <?php $content->content(); ?>
        </div>
    </article>
<script>
    window.onbeforeunload = function () {
        // Updated for modern preview modal handling
        // No-op or send message if needed
    }
</script>
<style>
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        line-height: 1.6;
        color: #333;
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: #f9fafb;
    }
    img { max-width: 100%; height: auto; }
    h1, h2, h3, h4, h5, h6 { color: #111; margin-top: 1.5em; margin-bottom: 0.5em; font-weight: 700; }
    h1 { font-size: 2.25rem; border-bottom: 1px solid #eaeaea; padding-bottom: 0.3em; }
    h2 { font-size: 1.75rem; }
    a { color: #5865F2; text-decoration: none; }
    a:hover { text-decoration: underline; }
    blockquote { border-left: 4px solid #5865F2; margin: 1.5em 0; padding-left: 1em; color: #555; background: #f0f2fd; padding: 10px 15px; }
    code { background: #eee; padding: 2px 5px; font-family: monospace; font-size: 0.9em; }
    pre { background: #2f3136; color: #eee; padding: 15px; overflow-x: auto; }
    pre code { background: none; padding: 0; color: inherit; }
    .post-title { text-align: center; margin-bottom: 40px; }
    .post-meta { text-align: center; color: #888; margin-bottom: 40px; font-size: 0.9em; }
</style>
