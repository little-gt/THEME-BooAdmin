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
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap');

    :root {
        color-scheme: light;
        --preview-bg: #f9fafb;
        --preview-text: #333;
        --preview-heading: #111;
        --preview-border: #eaeaea;
        --preview-accent: #5865f2;
        --preview-quote-bg: #f0f2fd;
        --preview-quote-text: #555;
        --preview-code-bg: #eee;
        --preview-pre-bg: #2f3136;
        --preview-pre-text: #eee;
        --preview-meta: #888;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            color-scheme: dark;
            --preview-bg: #0f1217;
            --preview-text: #d6dbe2;
            --preview-heading: #f3f6fb;
            --preview-border: #2b3442;
            --preview-accent: #7a8bff;
            --preview-quote-bg: #1a2232;
            --preview-quote-text: #b8c2cf;
            --preview-code-bg: #1d232d;
            --preview-pre-bg: #111722;
            --preview-pre-text: #e8edf3;
            --preview-meta: #93a0b1;
        }
    }

    body {
        font-family: "Inter", "Noto Sans SC", -apple-system, BlinkMacSystemFont, sans-serif;
        line-height: 1.6;
        color: var(--preview-text);
        max-width: 800px;
        margin: 0 auto;
        padding: 40px 20px;
        background-color: var(--preview-bg);
    }
    img { max-width: 100%; height: auto; }
    h1, h2, h3, h4, h5, h6 { color: var(--preview-heading); margin-top: 1.5em; margin-bottom: 0.5em; font-weight: 700; }
    h1 { font-size: 2.25rem; border-bottom: 1px solid var(--preview-border); padding-bottom: 0.3em; }
    h2 { font-size: 1.75rem; }
    a { color: var(--preview-accent); text-decoration: none; }
    a:hover { text-decoration: underline; }
    blockquote { border-left: 4px solid var(--preview-accent); margin: 1.5em 0; padding-left: 1em; color: var(--preview-quote-text); background: var(--preview-quote-bg); padding: 10px 15px; }
    code { background: var(--preview-code-bg); padding: 2px 5px; font-family: "Cascadia Code", monospace; font-size: 0.9em; }
    pre { background: var(--preview-pre-bg); color: var(--preview-pre-text); padding: 15px; overflow-x: auto; font-family: "Cascadia Code", monospace; }
    pre code { background: none; padding: 0; color: inherit; }
    .post-title { text-align: center; margin-bottom: 40px; }
    .post-meta { text-align: center; color: var(--preview-meta); margin-bottom: 40px; font-size: 0.9em; }
</style>
