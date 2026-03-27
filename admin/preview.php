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

/** 
 * 作为 Iframe 使用，
 * 独立完成对 Markdown 输出的完整解析。
 */
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php $content->title(); ?> - 预览</title>
    <!-- KaTeX Resources -->
    <link rel="stylesheet" href="https://cdn.garfieldtom.cool/resource/libs/KaTeX/0.16.38/katex.min.css">
    <script src="https://cdn.garfieldtom.cool/resource/libs/KaTeX/0.16.38/katex.min.js"></script>
    <script src="https://cdn.garfieldtom.cool/resource/libs/KaTeX/0.16.38/contrib/auto-render.min.js"></script>
    <!-- Preview Style -->
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Noto+Sans+SC:wght@400;500;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap');

    :root {
        color-scheme: light;
        --preview-bg: #f4f6fa;
        --preview-panel: #ffffff;
        --preview-panel-soft: #f8fafc;
        --preview-panel-muted: #f1f4f8;
        --preview-border: #d7dee8;
        --preview-border-strong: #bcc8d6;
        --preview-text: #223042;
        --preview-muted: #607086;
        --preview-heading: #142033;
        --preview-accent: #4e73df;
        --preview-accent-soft: #e9f0ff;
        --preview-selection: rgba(78, 115, 223, 0.18);
        --preview-code-bg: #eef2f7;
        --preview-code-text: #243447;
        --preview-pre-bg: #f3f6fb;
        --preview-pre-border: #d9e1ec;
        --preview-pre-text: #223042;
        --preview-quote-bg: #f5f8fc;
        --preview-quote-text: #415166;
        --preview-quote-border: #9eb4d6;
        --preview-table-head-bg: #eef3f8;
        --preview-table-stripe: #f8fafd;
        --preview-table-hover: #eef4ff;
        --preview-table-border: #d7dee8;
        --preview-mark-bg: #fff0b8;
        --preview-hr: #dbe3ee;
        --preview-kbd-bg: #ffffff;
        --preview-kbd-text: #27384f;
        --preview-shadow: none;
    }

    @media (prefers-color-scheme: dark) {
        :root {
            color-scheme: dark;
            --preview-bg: #0f1217;
            --preview-panel: #171b22;
            --preview-panel-soft: #1b212b;
            --preview-panel-muted: #111722;
            --preview-border: #2b3442;
            --preview-border-strong: #3c4a5d;
            --preview-text: #dbe3ee;
            --preview-muted: #96a4b7;
            --preview-heading: #f2f6fb;
            --preview-accent: #7a8bff;
            --preview-accent-soft: #232d43;
            --preview-selection: rgba(122, 139, 255, 0.22);
            --preview-code-bg: #202938;
            --preview-code-text: #e7edf6;
            --preview-pre-bg: #111722;
            --preview-pre-border: #2b3442;
            --preview-pre-text: #e7edf6;
            --preview-quote-bg: #141b27;
            --preview-quote-text: #b7c3d2;
            --preview-quote-border: #5d76a4;
            --preview-table-head-bg: #1a2230;
            --preview-table-stripe: #141b26;
            --preview-table-hover: #1d2736;
            --preview-table-border: #324052;
            --preview-mark-bg: #5f5020;
            --preview-hr: #2b3442;
            --preview-kbd-bg: #1b2432;
            --preview-kbd-text: #e7edf6;
            --preview-shadow: none;
        }
    }

    * {
        box-sizing: border-box;
    }

    html {
        font-size: 16px;
        scroll-behavior: smooth;
    }

    body {
        margin: 0;
        font-family: "Inter", "Noto Sans SC", -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--preview-text);
        background:
            linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0)) 0 0 / 100% 100%,
            var(--preview-bg);
        line-height: 1.8;
        text-rendering: optimizeLegibility;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }

    ::selection {
        background: var(--preview-selection);
    }

    a {
        color: var(--preview-accent);
        text-decoration: none;
        text-underline-offset: 0.18em;
        transition: color 0.18s ease, border-color 0.18s ease, background-color 0.18s ease;
    }

    a:hover {
        text-decoration: underline;
    }

    img,
    svg,
    video,
    canvas,
    iframe {
        max-width: 100%;
    }

    img {
        display: block;
        height: auto;
        border: 1px solid var(--preview-border);
        background: var(--preview-panel-soft);
    }

    hr {
        margin: 2rem 0;
        border: 0;
        border-top: 1px solid var(--preview-hr);
    }

    .preview-shell {
        width: min(980px, calc(100vw - 32px));
        margin: 0 auto;
        padding: 28px 0 40px;
    }

    .preview-header,
    .preview-article {
        background: var(--preview-panel);
        border: 1px solid var(--preview-border);
        box-shadow: var(--preview-shadow);
    }

    .preview-header {
        padding: 24px 28px;
        margin-bottom: 16px;
    }

    .preview-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 28px;
        padding: 0 10px;
        margin-bottom: 16px;
        border: 1px solid var(--preview-border);
        background: var(--preview-panel-soft);
        color: var(--preview-muted);
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
    }

    .post-title {
        margin: 0;
        font-size: clamp(2rem, 3.4vw, 2.9rem);
        line-height: 1.18;
        color: var(--preview-heading);
        letter-spacing: -0.025em;
        word-break: break-word;
    }

    .post-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 14px;
        align-items: center;
        margin-top: 18px;
        color: var(--preview-muted);
        font-size: 0.95rem;
    }

    .post-meta .meta-separator {
        color: var(--preview-border-strong);
    }

    .preview-article {
        padding: 0;
        overflow: hidden;
    }

    .post-content {
        padding: 30px 28px 36px;
        word-wrap: break-word;
        overflow-wrap: anywhere;
    }

    .post-content > *:first-child {
        margin-top: 0;
    }

    .post-content > *:last-child {
        margin-bottom: 0;
    }

    .post-content p,
    .post-content ul,
    .post-content ol,
    .post-content blockquote,
    .post-content pre,
    .post-content table,
    .post-content dl,
    .post-content figure,
    .post-content details {
        margin: 1.2rem 0;
    }

    .post-content h1,
    .post-content h2,
    .post-content h3,
    .post-content h4,
    .post-content h5,
    .post-content h6 {
        margin: 2rem 0 0.9rem;
        color: var(--preview-heading);
        line-height: 1.28;
        font-weight: 800;
        letter-spacing: -0.02em;
    }

    .post-content h1 {
        font-size: 2rem;
        padding-bottom: 0.55rem;
        border-bottom: 1px solid var(--preview-border);
    }

    .post-content h2 {
        font-size: 1.65rem;
        padding-bottom: 0.45rem;
        border-bottom: 1px solid var(--preview-border);
    }

    .post-content h3 {
        font-size: 1.35rem;
    }

    .post-content h4 {
        font-size: 1.15rem;
    }

    .post-content h5,
    .post-content h6 {
        font-size: 1rem;
    }

    .post-content ul,
    .post-content ol {
        padding-left: 1.5rem;
    }

    .post-content li + li {
        margin-top: 0.45rem;
    }

    .post-content li > ul,
    .post-content li > ol {
        margin-top: 0.55rem;
        margin-bottom: 0.55rem;
    }

    .post-content blockquote {
        padding: 1rem 1.1rem;
        border-left: 4px solid var(--preview-quote-border);
        background: var(--preview-quote-bg);
        color: var(--preview-quote-text);
    }

    .post-content blockquote > *:first-child {
        margin-top: 0;
    }

    .post-content blockquote > *:last-child {
        margin-bottom: 0;
    }

    .post-content blockquote blockquote {
        margin: 0.9rem 0 0;
        background: transparent;
        border-left-color: var(--preview-border-strong);
    }

    .post-content code,
    .post-content kbd,
    .post-content samp {
        font-family: "JetBrains Mono", "Cascadia Code", monospace;
        font-size: 0.9em;
    }

    .post-content code {
        padding: 0.18rem 0.42rem;
        background: var(--preview-code-bg);
        color: var(--preview-code-text);
        border: 1px solid var(--preview-border);
    }

    .post-content pre {
        padding: 16px 18px;
        overflow-x: auto;
        background: var(--preview-pre-bg);
        color: var(--preview-pre-text);
        border: 1px solid var(--preview-pre-border);
        line-height: 1.7;
        tab-size: 4;
    }

    .post-content pre code {
        padding: 0;
        border: 0;
        background: transparent;
        color: inherit;
        font-size: 0.92rem;
    }

    .post-content table {
        display: block;
        width: 100%;
        overflow-x: auto;
        border-collapse: collapse;
        border: 1px solid var(--preview-table-border);
        background: var(--preview-panel);
    }

    .post-content thead {
        background: var(--preview-table-head-bg);
    }

    .post-content th,
    .post-content td {
        padding: 12px 14px;
        border: 1px solid var(--preview-table-border);
        text-align: left;
        vertical-align: top;
        white-space: nowrap;
    }

    .post-content th {
        color: var(--preview-heading);
        font-weight: 700;
    }

    .post-content tbody tr:nth-child(even) {
        background: var(--preview-table-stripe);
    }

    .post-content tbody tr:hover {
        background: var(--preview-table-hover);
    }

    .post-content figure {
        margin-left: 0;
        margin-right: 0;
    }

    .post-content figcaption {
        margin-top: 0.75rem;
        color: var(--preview-muted);
        font-size: 0.92rem;
        text-align: center;
    }

    .post-content strong {
        color: var(--preview-heading);
        font-weight: 700;
    }

    .post-content mark {
        background: var(--preview-mark-bg);
        color: inherit;
        padding: 0.08em 0.22em;
    }

    .post-content kbd {
        display: inline-flex;
        align-items: center;
        min-height: 1.7rem;
        padding: 0 0.45rem;
        border: 1px solid var(--preview-border-strong);
        background: var(--preview-kbd-bg);
        color: var(--preview-kbd-text);
        box-shadow: none;
    }

    .post-content abbr[title] {
        text-decoration-style: dotted;
        cursor: help;
    }

    .post-content dl dt {
        margin-top: 1rem;
        font-weight: 700;
        color: var(--preview-heading);
    }

    .post-content dl dd {
        margin: 0.35rem 0 0 1.25rem;
        color: var(--preview-muted);
    }

    .post-content details {
        border: 1px solid var(--preview-border);
        background: var(--preview-panel-soft);
    }

    .post-content summary {
        cursor: pointer;
        padding: 0.9rem 1rem;
        font-weight: 600;
        color: var(--preview-heading);
    }

    .post-content details > :not(summary) {
        padding: 0 1rem 1rem;
    }

    .post-content input[type="checkbox"] {
        width: 1rem;
        height: 1rem;
        margin-right: 0.45rem;
        accent-color: var(--preview-accent);
        vertical-align: -0.12rem;
    }

    .post-content .contains-task-list,
    .post-content .task-list {
        list-style: none;
        padding-left: 0;
    }

    .post-content .contains-task-list li,
    .post-content .task-list li {
        padding-left: 0.15rem;
    }

    .post-content .footnotes {
        margin-top: 2.4rem;
        padding-top: 1.1rem;
        border-top: 1px solid var(--preview-border);
        color: var(--preview-muted);
        font-size: 0.94rem;
    }

    .post-content .footnotes hr {
        display: none;
    }

    .post-content .footnote-ref,
    .post-content .footnote-backref {
        color: var(--preview-accent);
        text-decoration: none;
    }

    .post-content .katex {
        font-size: 1.02em;
    }

    .post-content .katex-display {
        margin: 1.5rem 0;
        overflow-x: auto;
        overflow-y: hidden;
        padding: 0.2rem 0.1rem;
    }

    .post-content iframe,
    .post-content video {
        display: block;
        width: 100%;
        min-height: 360px;
        border: 1px solid var(--preview-border);
        background: var(--preview-panel-soft);
    }

    .post-content .table-wrap {
        margin: 1.2rem 0;
        overflow-x: auto;
    }

    .post-content .table-wrap table {
        margin: 0;
    }

    @media (max-width: 720px) {
        .preview-shell {
            width: min(100vw, calc(100vw - 20px));
            padding-top: 10px;
            padding-bottom: 20px;
        }

        .preview-header {
            padding: 18px 16px;
            margin-bottom: 10px;
        }

        .preview-article {
            border-top: 0;
        }

        .post-content {
            padding: 20px 16px 24px;
        }

        .post-meta {
            gap: 8px 10px;
            font-size: 0.88rem;
        }

        .post-content iframe,
        .post-content video {
            min-height: 220px;
        }
    }
    </style>
</head>
<body>
    <main class="preview-shell">
        <header class="preview-header">
            <div class="preview-kicker"><?php _e('预览'); ?></div>
            <h1 class="post-title"><?php $content->title(); ?></h1>
            <div class="post-meta">
                <time datetime="<?php $content->date('c'); ?>"><?php $content->date(); ?></time>
                <span class="meta-separator">&bull;</span>
                <span><?php $content->author(); ?></span>
            </div>
        </header>

        <article class="preview-article">
            <div class="post-content">
                <?php $content->content(); ?>
            </div>
        </article>
    </main>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.post-content table').forEach(function (table) {
            if (table.parentElement && table.parentElement.classList.contains('table-wrap')) {
                return;
            }

            var wrapper = document.createElement('div');
            wrapper.className = 'table-wrap';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);
        });

        renderMathInElement(document.body, {
            delimiters: [
                { left: "$$", right: "$$", display: true },
                { left: "$", right: "$", display: false },
                { left: "\\(", right: "\\)", display: false },
                { left: "\\[", right: "\\]", display: true }
            ],
            throwOnError: false
        });
    });
</script>
</html>
