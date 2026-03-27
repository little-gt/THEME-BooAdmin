<?php
/**
 * BooAdmin 文件完整性校验工具
 * 
 * 支持命令行和Web界面两种操作方式
 * 用于计算、存储和验证admin目录下所有文件的HASH值
 * 
 * @version 1.0.0
 * @author BooAdmin
 */

declare(strict_types=1);

// ===== 配置项 =====

// 默认目标目录（当 position.txt 不存在时使用）
// 例如: 'admin' 表示 {项目根目录}/admin/
const DEFAULT_TARGET_DIR = 'admin';

// position.txt 路径（与本文件同目录，内容为目标目录名，如 ABC）
const POSITION_FILE = __DIR__ . '/position.txt';

// 远程HASH文件URL
const REMOTE_HASH_URL = 'https://cdn.garfieldtom.cool/resource/libs/booadmin/1.2.0/hash.json';

// 自身完整性校验URL
// php -r "echo hash_file('sha256', 'verify/hash_tool.php');"
const SELF_VERIFY_URL = 'https://cdn.garfieldtom.cool/resource/libs/booadmin/1.2.0/tool.txt';

// 工具版本号
const VERSION = '1.0.0';

// 运行时解析目标目录：优先 position.txt，否则使用默认值
function getTargetDir(): string
{
    if (file_exists(POSITION_FILE)) {
        $content = trim(file_get_contents(POSITION_FILE));
        return ($content !== '') ? $content : DEFAULT_TARGET_DIR;
    }
    return DEFAULT_TARGET_DIR;
}

/**
 * Hash生成器类
 */
class HashGenerator
{
    private string $basePath;
    private array $files = [];
    private int $totalFiles = 0;
    private int $totalSize = 0;

    public function __construct(string $basePath)
    {
        $resolved = realpath(rtrim($basePath, DIRECTORY_SEPARATOR));
        if ($resolved === false) {
            throw new RuntimeException("目录不存在或无法访问: {$basePath}");
        }
        $this->basePath = $resolved;
    }

    /**
     * 遍历目录并计算所有文件的HASH
     */
    public function scan(): void
    {
        $this->files = [];
        $this->totalFiles = 0;
        $this->totalSize = 0;

        if (!is_dir($this->basePath)) {
            throw new RuntimeException("目录不存在: {$this->basePath}");
        }

        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->basePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = $this->getRelativePath($file->getPathname());
                
                // 排除hash.json自身
                if (basename($relativePath) === 'hash.json') {
                    continue;
                }

                $this->addFile($relativePath, $file->getPathname());
            }
        }
    }

    /**
     * 添加文件信息
     */
    private function addFile(string $relativePath, string $fullPath): void
    {
        $hash = hash_file('sha256', $fullPath);
        $size = filesize($fullPath);
        $modified = date('c', filemtime($fullPath));

        $this->files[$relativePath] = [
            'hash' => 'sha256:' . $hash,
            'size' => $size,
            'modified' => $modified
        ];

        $this->totalFiles++;
        $this->totalSize += $size;
    }

    /**
     * 获取相对路径（相对于项目根目录）
     */
    private function getRelativePath(string $fullPath): string
    {
        $realPath = realpath($fullPath);
        $projectRoot = dirname($this->basePath);
        $relative = str_replace($projectRoot . DIRECTORY_SEPARATOR, '', $realPath);
        return str_replace('\\', '/', $relative);
    }

    /**
     * 生成JSON数据
     */
    public function toJson(): string
    {
        $data = [
            'version' => VERSION,
            'generated_at' => date('c'),
            'base_path' => basename($this->basePath),
            'total_files' => $this->totalFiles,
            'total_size' => $this->totalSize,
            'files' => $this->files
        ];

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    /**
     * 保存到文件
     */
    public function save(string $outputPath): bool
    {
        $json = $this->toJson();
        return file_put_contents($outputPath, $json) !== false;
    }

    /**
     * 获取文件列表
     */
    public function getFiles(): array
    {
        return $this->files;
    }

    /**
     * 获取统计信息
     */
    public function getStats(): array
    {
        return [
            'total_files' => $this->totalFiles,
            'total_size' => $this->totalSize
        ];
    }
}

/**
 * Hash校验器类
 */
class HashVerifier
{
    private string $basePath;
    private array $baselineHashes = [];
    private array $results = [
        'valid' => [],
        'modified' => [],
        'added' => [],
        'missing' => []
    ];

    public function __construct(string $basePath)
    {
        $resolved = realpath(rtrim($basePath, DIRECTORY_SEPARATOR));
        if ($resolved === false) {
            throw new RuntimeException("目录不存在或无法访问: {$basePath}");
        }
        $this->basePath = $resolved;
    }

    /**
     * 从远程URL加载HASH
     */
    public function loadFromRemote(string $url): bool
    {
        $content = $this->fetchRemote($url);
        if ($content === false) {
            throw new RuntimeException("无法获取远程HASH文件: {$url}");
        }

        return $this->parseJson($content);
    }

    /**
     * 从本地文件加载HASH
     */
    public function loadFromLocal(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            throw new RuntimeException("本地HASH文件不存在: {$filePath}");
        }

        $content = file_get_contents($filePath);
        return $this->parseJson($content);
    }

    /**
     * 获取远程内容
     */
    private function fetchRemote(string $url): string|false
    {
        // 优先使用cURL
        if (function_exists('curl_init')) {
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_USERAGENT => 'BooAdmin-HashTool/' . VERSION
            ]);
            $content = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode === 200) {
                return $content;
            }
            return false;
        }

        // 降级到file_get_contents
        $context = stream_context_create([
            'http' => [
                'timeout' => 30,
                'user_agent' => 'BooAdmin-HashTool/' . VERSION
            ],
            'ssl' => [
                'verify_peer' => true
            ]
        ]);

        return file_get_contents($url, false, $context);
    }

    /**
     * 解析JSON数据
     */
    private function parseJson(string $json): bool
    {
        $data = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException("JSON解析错误: " . json_last_error_msg());
        }

        if (!isset($data['files']) || !is_array($data['files'])) {
            throw new RuntimeException("无效的HASH文件格式");
        }

        // 去掉第一级目录前缀，使路径与目录名无关
        foreach ($data['files'] as $path => $info) {
            $normalizedPath = $this->stripDirPrefix($path);
            $this->baselineHashes[$normalizedPath] = $info;
        }
        return true;
    }

    /**
     * 去掉路径的第一级目录前缀
     * 例如: 'admin/js/jquery.js' → 'js/jquery.js'
     */
    private function stripDirPrefix(string $path): string
    {
        $parts = explode('/', $path);
        if (count($parts) > 1) {
            array_shift($parts);
        }
        return implode('/', $parts);
    }

    /**
     * 执行校验
     */
    public function verify(): void
    {
        $this->results = [
            'valid' => [],
            'modified' => [],
            'added' => [],
            'missing' => []
        ];

        // 检查基准文件是否存在（用去掉前缀后的子路径拼到当前 basePath）
        foreach ($this->baselineHashes as $subPath => $info) {
            $fullPath = $this->basePath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $subPath);
            
            if (!file_exists($fullPath)) {
                $this->results['missing'][$subPath] = $info;
                continue;
            }

            $currentHash = 'sha256:' . hash_file('sha256', $fullPath);
            
            if ($currentHash === $info['hash']) {
                $this->results['valid'][$subPath] = $info;
            } else {
                $this->results['modified'][$subPath] = array_merge($info, [
                    'current_hash' => $currentHash
                ]);
            }
        }

        // 检查新增文件
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->basePath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = $this->getRelativePath($file->getPathname());
                $subPath = $this->stripDirPrefix($relativePath);
                
                if (!isset($this->baselineHashes[$subPath]) && basename($subPath) !== 'hash.json') {
                    $this->results['added'][$subPath] = [
                        'hash' => 'sha256:' . hash_file('sha256', $file->getPathname()),
                        'size' => $file->getSize(),
                        'modified' => date('c', $file->getMTime())
                    ];
                }
            }
        }
    }

    /**
     * 获取相对路径（相对于项目根目录）
     */
    private function getRelativePath(string $fullPath): string
    {
        $realPath = realpath($fullPath);
        $projectRoot = dirname($this->basePath);
        $relative = str_replace($projectRoot . DIRECTORY_SEPARATOR, '', $realPath);
        return str_replace('\\', '/', $relative);
    }

    /**
     * 获取校验结果
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * 获取统计信息
     */
    public function getStats(): array
    {
        return [
            'total' => count($this->baselineHashes),
            'valid' => count($this->results['valid']),
            'modified' => count($this->results['modified']),
            'added' => count($this->results['added']),
            'missing' => count($this->results['missing']),
            'passed' => count($this->results['modified']) === 0 && 
                        count($this->results['added']) === 0 && 
                        count($this->results['missing']) === 0
        ];
    }
}

/**
 * CLI输出工具类
 */
class CLIOutput
{
    private static array $colors = [
        'red' => "\033[31m",
        'green' => "\033[32m",
        'yellow' => "\033[33m",
        'blue' => "\033[34m",
        'reset' => "\033[0m",
        'bold' => "\033[1m"
    ];

    public static function color(string $text, string $color): string
    {
        if (self::supportsColor()) {
            return self::$colors[$color] . $text . self::$colors['reset'];
        }
        return $text;
    }

    public static function success(string $text): void
    {
        echo self::color("✓ {$text}", 'green') . PHP_EOL;
    }

    public static function error(string $text): void
    {
        echo self::color("✗ {$text}", 'red') . PHP_EOL;
    }

    public static function warning(string $text): void
    {
        echo self::color("⚠ {$text}", 'yellow') . PHP_EOL;
    }

    public static function info(string $text): void
    {
        echo self::color("ℹ {$text}", 'blue') . PHP_EOL;
    }

    public static function bold(string $text): string
    {
        if (self::supportsColor()) {
            return self::$colors['bold'] . $text . self::$colors['reset'];
        }
        return $text;
    }

    private static function supportsColor(): bool
    {
        return php_sapi_name() === 'cli' && 
               (DIRECTORY_SEPARATOR === '/' || getenv('ANSICON') !== false || getenv('ConEmuANSI') === 'ON');
    }
}

/**
 * 处理Web API请求
 */
function handleApiRequest(): void
{
    header('Content-Type: application/json; charset=utf-8');
    
    $action = $_GET['action'] ?? '';
    $basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . getTargetDir();

    try {
        switch ($action) {
            case 'generate':
                $generator = new HashGenerator($basePath);
                $generator->scan();
                $json = $generator->toJson();
                file_put_contents(__DIR__ . '/hash.json', $json);
                
                echo json_encode([
                    'success' => true,
                    'message' => 'HASH生成成功',
                    'stats' => $generator->getStats()
                ]);
                break;

            case 'verify':
                $source = $_GET['source'] ?? 'remote';
                $verifier = new HashVerifier($basePath);
                
                if ($source === 'remote') {
                    $verifier->loadFromRemote(REMOTE_HASH_URL);
                } else {
                    $verifier->loadFromLocal(__DIR__ . '/hash.json');
                }
                
                $verifier->verify();
                $results = $verifier->getResults();
                $stats = $verifier->getStats();
                
                echo json_encode([
                    'success' => true,
                    'source' => $source,
                    'stats' => $stats,
                    'results' => $results
                ]);
                break;

            case 'status':
                $hashFile = __DIR__ . '/hash.json';
                $hasLocal = file_exists($hashFile);
                $localInfo = null;
                
                if ($hasLocal) {
                    $data = json_decode(file_get_contents($hashFile), true);
                    $localInfo = [
                        'generated_at' => $data['generated_at'] ?? null,
                        'total_files' => $data['total_files'] ?? 0,
                        'total_size' => $data['total_size'] ?? 0
                    ];
                }
                
                echo json_encode([
                    'success' => true,
                    'version' => VERSION,
                    'has_local_hash' => $hasLocal,
                    'local_info' => $localInfo,
                    'remote_url' => REMOTE_HASH_URL
                ]);
                break;

            default:
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => '无效的操作'
                ]);
        }
    } catch (\Throwable $e) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
}
function handleCliRequest(): void
{
    $options = getopt('h', ['help', 'generate', 'verify::', 'status']);
    
    if (isset($options['h']) || isset($options['help'])) {
        showHelp();
        return;
    }


    $basePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . getTargetDir();

    try {
        if (isset($options['generate'])) {
            CLIOutput::info('开始生成HASH...');
            
            $generator = new HashGenerator($basePath);
            $generator->scan();
            
            $outputPath = __DIR__ . '/hash.json';
            if ($generator->save($outputPath)) {
                $stats = $generator->getStats();
                CLIOutput::success("HASH生成完成");
                CLIOutput::info("文件总数: {$stats['total_files']}");
                CLIOutput::info("总大小: " . formatSize($stats['total_size']));
                CLIOutput::info("输出文件: {$outputPath}");
            } else {
                CLIOutput::error("HASH生成失败");
                exit(1);
            }
        } elseif (isset($options['verify'])) {
            $source = $options['verify'] ?? 'remote';
            CLIOutput::info("开始校验文件 (来源: {$source})...");
            
            $verifier = new HashVerifier($basePath);
            
            if ($source === 'remote') {
                CLIOutput::info("从远程加载HASH: " . REMOTE_HASH_URL);
                $verifier->loadFromRemote(REMOTE_HASH_URL);
            } else {
                CLIOutput::info("从本地加载HASH");
                $verifier->loadFromLocal(__DIR__ . '/hash.json');
            }
            
            $verifier->verify();
            $results = $verifier->getResults();
            $stats = $verifier->getStats();
            
            echo PHP_EOL;
            CLIOutput::info("校验统计:");
            echo "  总文件数: {$stats['total']}" . PHP_EOL;
            echo "  通过: " . count($results['valid']) . PHP_EOL;
            echo "  篡改: " . count($results['modified']) . PHP_EOL;
            echo "  新增: " . count($results['added']) . PHP_EOL;
            echo "  缺失: " . count($results['missing']) . PHP_EOL;
            
            if (!empty($results['modified'])) {
                echo PHP_EOL;
                CLIOutput::error("被篡改的文件:");
                foreach ($results['modified'] as $file => $info) {
                    echo "  - {$file}" . PHP_EOL;
                }
            }
            
            if (!empty($results['added'])) {
                echo PHP_EOL;
                CLIOutput::warning("新增的文件:");
                foreach ($results['added'] as $file => $info) {
                    echo "  + {$file}" . PHP_EOL;
                }
            }
            
            if (!empty($results['missing'])) {
                echo PHP_EOL;
                CLIOutput::warning("缺失的文件:");
                foreach ($results['missing'] as $file => $info) {
                    echo "  - {$file}" . PHP_EOL;
                }
            }
            
            echo PHP_EOL;
            if ($stats['passed']) {
                CLIOutput::success("所有文件校验通过！");
                exit(0);
            } else {
                CLIOutput::error("发现文件异常！");
                exit(1);
            }
        } elseif (isset($options['status'])) {
            $hashFile = __DIR__ . '/hash.json';
            
            echo CLIOutput::bold("BooAdmin 文件完整性校验工具 v" . VERSION) . PHP_EOL;
            echo str_repeat('-', 50) . PHP_EOL;
            
            if (file_exists($hashFile)) {
                $data = json_decode(file_get_contents($hashFile), true);
                CLIOutput::success("本地HASH文件存在");
                echo "  生成时间: {$data['generated_at']}" . PHP_EOL;
                echo "  文件总数: {$data['total_files']}" . PHP_EOL;
                echo "  总大小: " . formatSize($data['total_size']) . PHP_EOL;
            } else {
                CLIOutput::warning("本地HASH文件不存在");
            }
            
            echo PHP_EOL;
            echo "远程HASH URL: " . REMOTE_HASH_URL . PHP_EOL;
        } else {
            showHelp();
        }
    } catch (\Throwable $e) {
        CLIOutput::error($e->getMessage());
        exit(1);
    }
}

/**
 * 显示帮助信息
 */
function showHelp(): void
{
    echo CLIOutput::bold("BooAdmin 文件完整性校验工具 v" . VERSION) . PHP_EOL;
    echo PHP_EOL;
    echo "用法:" . PHP_EOL;
    echo "  php hash_tool.php [选项]" . PHP_EOL;
    echo PHP_EOL;
    echo "选项:" . PHP_EOL;
    echo "  --generate          生成HASH文件" . PHP_EOL;
    echo "  --verify[=remote|local]  校验文件 (默认使用远程HASH)" . PHP_EOL;
    echo "  --status            显示当前状态" . PHP_EOL;
    echo "  -h, --help          显示此帮助信息" . PHP_EOL;
    echo PHP_EOL;
    echo "示例:" . PHP_EOL;
    echo "  php hash_tool.php --generate" . PHP_EOL;
    echo "  php hash_tool.php --verify" . PHP_EOL;
    echo "  php hash_tool.php --verify=local" . PHP_EOL;
}

/**
 * 格式化文件大小
 */
function formatSize(int $bytes): string
{
    $units = ['B', 'KB', 'MB', 'GB'];
    $unitIndex = 0;
    
    while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
        $bytes /= 1024;
        $unitIndex++;
    }
    
    return round($bytes, 2) . ' ' . $units[$unitIndex];
}

/**
 * 输出Web界面HTML
 */
function renderWebUI(): void
{
    ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BooAdmin 文件完整性校验工具</title>
    
    <!-- TailwindCSS -->
    <script src="https://cdn.garfieldtom.cool/resource/libs/tailwind/3.4.17/tailwindcss.js"></script>
    
    <!-- Font Awesome -->
    <script src="https://cdn.garfieldtom.cool/resource/libs/fontawesome/7.2.0/js/all.min.js"></script>
    <link href="https://cdn.garfieldtom.cool/resource/libs/fontawesome/7.2.0/css/all.min.css" rel="stylesheet">
    
    <!-- ECharts -->
    <script src="https://cdn.garfieldtom.cool/resource/libs/echarts/5.5.0/echarts.min.js"></script>
    
    <style>
        body {
            font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #F3F4F6;
        }
        .btn {
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        .btn:active {
            transform: translateY(0);
        }
    </style>
</head>
<body class="min-h-screen">
    <!-- 顶部导航 -->
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-500 flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900">BooAdmin 文件完整性校验工具</h1>
                        <p class="text-sm text-gray-500">File Integrity Checker</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 text-sm font-medium">v<?= VERSION ?></span>
                    <span id="status-badge" class="px-3 py-1 bg-gray-100 text-gray-600 text-sm">未检测</span>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 py-6">
        <!-- 操作面板 -->
        <div class="bg-white border border-gray-200 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-cog mr-2"></i>操作面板
            </h2>
            <div class="flex flex-wrap gap-3">
                <!--
                <button onclick="generateHash()" class="btn px-6 py-3 bg-blue-500 text-white font-medium hover:bg-blue-600 flex items-center">
                    <i class="fas fa-sync-alt mr-2"></i>生成 HASH
                </button>
                -->
                <button onclick="verifyRemote()" class="btn px-6 py-3 bg-green-500 text-white font-medium hover:bg-green-600 flex items-center">
                    <i class="fas fa-cloud-download-alt mr-2"></i>远程校验
                </button>
                <button onclick="verifyLocal()" class="btn px-6 py-3 bg-gray-600 text-white font-medium hover:bg-gray-700 flex items-center">
                    <i class="fas fa-file-alt mr-2"></i>本地校验
                </button>
            </div>
            
            <!-- 进度条 -->
            <div id="progress-container" class="mt-4 hidden">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600" id="progress-text">处理中...</span>
                    <span class="text-sm text-gray-600" id="progress-percent">0%</span>
                </div>
                <div class="w-full bg-gray-200 h-2">
                    <div id="progress-bar" class="h-2 bg-blue-500 transition-all duration-300" style="width: 0%"></div>
                </div>
            </div>
        </div>

        <!-- 统计卡片 -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">文件总数</p>
                        <p class="text-2xl font-semibold text-gray-900" id="stat-total">-</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 flex items-center justify-center">
                        <i class="fas fa-file text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">文件大小</p>
                        <p class="text-2xl font-semibold text-gray-900" id="stat-size">-</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 flex items-center justify-center">
                        <i class="fas fa-database text-purple-500 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">校验通过</p>
                        <p class="text-2xl font-semibold text-green-600" id="stat-passed">-</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
            
            <div class="bg-white border border-gray-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">异常文件</p>
                        <p class="text-2xl font-semibold text-red-600" id="stat-abnormal">-</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- 结果展示区 -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- 文件列表 -->
            <div class="bg-white border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">
                        <i class="fas fa-list mr-2"></i>文件列表
                    </h3>
                </div>
                <div class="p-4">
                    <div class="flex space-x-2 mb-4">
                        <button onclick="filterFiles('all')" class="filter-btn px-3 py-1 text-sm bg-blue-500 text-white" data-filter="all">全部</button>
                        <button onclick="filterFiles('valid')" class="filter-btn px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" data-filter="valid">正常</button>
                        <button onclick="filterFiles('modified')" class="filter-btn px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" data-filter="modified">篡改</button>
                        <button onclick="filterFiles('added')" class="filter-btn px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" data-filter="added">新增</button>
                        <button onclick="filterFiles('missing')" class="filter-btn px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200" data-filter="missing">缺失</button>
                    </div>
                    <div id="file-list" class="max-h-96 overflow-y-auto">
                        <div class="text-center text-gray-400 py-8">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>暂无数据，请先执行操作</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 图表区域 -->
            <div class="bg-white border border-gray-200">
                <div class="px-4 py-3 border-b border-gray-200">
                    <h3 class="font-semibold text-gray-900">
                        <i class="fas fa-chart-pie mr-2"></i>统计图表
                    </h3>
                </div>
                <div class="p-4">
                    <div id="chart-container" class="h-80">
                        <div class="text-center text-gray-400 py-16">
                            <i class="fas fa-chart-bar text-4xl mb-2"></i>
                            <p>暂无数据</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 日志区域 -->
        <div class="bg-white border border-gray-200">
            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">
                    <i class="fas fa-terminal mr-2"></i>操作日志
                </h3>
                <button onclick="clearLog()" class="text-sm text-gray-500 hover:text-gray-700">
                    <i class="fas fa-trash-alt mr-1"></i>清空
                </button>
            </div>
            <div id="log-container" class="p-4 max-h-48 overflow-y-auto bg-gray-50 font-mono text-sm">
                <div class="text-gray-400">等待操作...</div>
            </div>
        </div>
    </main>

    <!-- 底部 -->
    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="max-w-7xl mx-auto px-4 py-4 text-center text-sm text-gray-500">
            BooAdmin File Integrity Checker v<?= VERSION ?> | 远程HASH: <a href="<?= REMOTE_HASH_URL ?>" target="_blank" class="text-blue-500 hover:underline"><?= REMOTE_HASH_URL ?></a>
        </div>
    </footer>

    <script>
        let currentResults = null;
        let chart = null;

        // 添加日志
        function addLog(message, type = 'info') {
            const container = document.getElementById('log-container');
            const time = new Date().toLocaleTimeString();
            const colors = {
                'info': 'text-blue-600',
                'success': 'text-green-600',
                'error': 'text-red-600',
                'warning': 'text-yellow-600'
            };
            const icons = {
                'info': 'fa-info-circle',
                'success': 'fa-check-circle',
                'error': 'fa-times-circle',
                'warning': 'fa-exclamation-circle'
            };
            
            const html = `<div class="${colors[type]}">
                <i class="fas ${icons[type]} mr-2"></i>
                <span class="text-gray-400">[${time}]</span> ${message}
            </div>`;
            
            container.innerHTML += html;
            container.scrollTop = container.scrollHeight;
        }

        // 清空日志
        function clearLog() {
            document.getElementById('log-container').innerHTML = '<div class="text-gray-400">等待操作...</div>';
        }

        // 显示进度
        function showProgress(show, text = '处理中...', percent = 0) {
            const container = document.getElementById('progress-container');
            const bar = document.getElementById('progress-bar');
            const textEl = document.getElementById('progress-text');
            const percentEl = document.getElementById('progress-percent');
            
            if (show) {
                container.classList.remove('hidden');
                textEl.textContent = text;
                bar.style.width = percent + '%';
                percentEl.textContent = percent + '%';
            } else {
                container.classList.add('hidden');
            }
        }

        // 更新统计
        function updateStats(total, size, passed, abnormal) {
            document.getElementById('stat-total').textContent = total;
            document.getElementById('stat-size').textContent = size;
            document.getElementById('stat-passed').textContent = passed;
            document.getElementById('stat-abnormal').textContent = abnormal;
        }

        // API请求
        async function apiRequest(action, params = {}) {
            const url = new URL(window.location.href);
            url.searchParams.set('action', action);
            Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
            
            const response = await fetch(url.toString());
            if (!response.ok) {
                let errorMsg = 'HTTP ' + response.status;
                try { const data = await response.json(); errorMsg = data.error || errorMsg; } catch(e) {}
                throw new Error(errorMsg);
            }
            return await response.json();
        }

        // 生成HASH
        async function generateHash() {
            showProgress(true, '正在生成HASH...', 30);
            addLog('开始生成HASH文件...', 'info');
            
            try {
                const result = await apiRequest('generate');
                
                if (result.success) {
                    showProgress(true, '生成完成', 100);
                    addLog(`HASH生成成功！共 ${result.stats.total_files} 个文件`, 'success');
                    updateStats(
                        result.stats.total_files,
                        formatSize(result.stats.total_size),
                        result.stats.total_files,
                        0
                    );
                    document.getElementById('status-badge').textContent = '已生成';
                    document.getElementById('status-badge').className = 'px-3 py-1 bg-green-100 text-green-700 text-sm';
                    
                    setTimeout(() => showProgress(false), 1000);
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                showProgress(false);
                addLog('生成失败: ' + error.message, 'error');
            }
        }

        // 远程校验
        async function verifyRemote() {
            showProgress(true, '正在从远程获取HASH...', 30);
            addLog('开始远程校验...', 'info');
            
            try {
                showProgress(true, '正在比对文件...', 60);
                const result = await apiRequest('verify', { source: 'remote' });
                
                if (result.success) {
                    showProgress(true, '校验完成', 100);
                    currentResults = result.results;
                    
                    const abnormal = result.stats.modified + result.stats.added + result.stats.missing;
                    addLog(`远程校验完成！通过: ${result.stats.valid}, 异常: ${abnormal}`, 
                           result.stats.passed ? 'success' : 'warning');
                    
                    updateStats(
                        result.stats.total,
                        '-',
                        result.stats.valid,
                        abnormal
                    );
                    
                    updateFileList(result.results);
                    updateChart(result.stats);
                    updateStatusBadge(result.stats.passed);
                    
                    setTimeout(() => showProgress(false), 1000);
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                showProgress(false);
                addLog('远程校验失败: ' + error.message, 'error');
            }
        }

        // 本地校验
        async function verifyLocal() {
            showProgress(true, '正在读取本地HASH...', 30);
            addLog('开始本地校验...', 'info');
            
            try {
                showProgress(true, '正在比对文件...', 60);
                const result = await apiRequest('verify', { source: 'local' });
                
                if (result.success) {
                    showProgress(true, '校验完成', 100);
                    currentResults = result.results;
                    
                    const abnormal = result.stats.modified + result.stats.added + result.stats.missing;
                    addLog(`本地校验完成！通过: ${result.stats.valid}, 异常: ${abnormal}`, 
                           result.stats.passed ? 'success' : 'warning');
                    
                    updateStats(
                        result.stats.total,
                        '-',
                        result.stats.valid,
                        abnormal
                    );
                    
                    updateFileList(result.results);
                    updateChart(result.stats);
                    updateStatusBadge(result.stats.passed);
                    
                    setTimeout(() => showProgress(false), 1000);
                } else {
                    throw new Error(result.error);
                }
            } catch (error) {
                showProgress(false);
                addLog('本地校验失败: ' + error.message, 'error');
            }
        }

        // 更新文件列表
        function updateFileList(results) {
            const container = document.getElementById('file-list');
            let html = '';
            
            const statusIcons = {
                'valid': '<i class="fas fa-check-circle text-green-500"></i>',
                'modified': '<i class="fas fa-times-circle text-red-500"></i>',
                'added': '<i class="fas fa-plus-circle text-blue-500"></i>',
                'missing': '<i class="fas fa-minus-circle text-gray-400"></i>'
            };
            
            const statusLabels = {
                'valid': '正常',
                'modified': '篡改',
                'added': '新增',
                'missing': '缺失'
            };
            
            for (const [type, files] of Object.entries(results)) {
                for (const [path, info] of Object.entries(files)) {
                    html += `
                        <div class="file-item flex items-center justify-between py-2 px-3 hover:bg-gray-50 border-b border-gray-100" data-status="${type}">
                            <div class="flex items-center space-x-2 truncate">
                                ${statusIcons[type]}
                                <span class="text-sm truncate">${path}</span>
                            </div>
                            <span class="text-xs px-2 py-1 ${type === 'valid' ? 'bg-green-100 text-green-700' : type === 'modified' ? 'bg-red-100 text-red-700' : type === 'added' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600'}">${statusLabels[type]}</span>
                        </div>
                    `;
                }
            }
            
            container.innerHTML = html || '<div class="text-center text-gray-400 py-8">暂无数据</div>';
        }

        // 筛选文件
        function filterFiles(status) {
            const buttons = document.querySelectorAll('.filter-btn');
            buttons.forEach(btn => {
                if (btn.dataset.filter === status) {
                    btn.className = 'filter-btn px-3 py-1 text-sm bg-blue-500 text-white';
                } else {
                    btn.className = 'filter-btn px-3 py-1 text-sm bg-gray-100 text-gray-700 hover:bg-gray-200';
                }
            });
            
            const items = document.querySelectorAll('.file-item');
            items.forEach(item => {
                if (status === 'all' || item.dataset.status === status) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        // 更新图表
        function updateChart(stats) {
            const container = document.getElementById('chart-container');

            // 销毁旧实例，避免残留DOM冲突
            if (chart) {
                chart.dispose();
                chart = null;
            }

            chart = echarts.init(container);
            
            const option = {
                tooltip: {
                    trigger: 'item',
                    formatter: '{b}: {c} ({d}%)'
                },
                legend: {
                    orient: 'vertical',
                    right: 10,
                    top: 'center',
                    textStyle: {
                        fontFamily: 'Source Sans Pro'
                    }
                },
                series: [{
                    type: 'pie',
                    radius: ['40%', '70%'],
                    avoidLabelOverlap: false,
                    itemStyle: {
                        borderRadius: 0,
                        borderColor: '#fff',
                        borderWidth: 2
                    },
                    label: {
                        show: false
                    },
                    emphasis: {
                        label: {
                            show: true,
                            fontSize: 14,
                            fontWeight: 'bold'
                        }
                    },
                    labelLine: {
                        show: false
                    },
                    data: [
                        { value: stats.valid, name: '正常', itemStyle: { color: '#10B981' } },
                        { value: stats.modified, name: '篡改', itemStyle: { color: '#EF4444' } },
                        { value: stats.added, name: '新增', itemStyle: { color: '#3B82F6' } },
                        { value: stats.missing, name: '缺失', itemStyle: { color: '#9CA3AF' } }
                    ]
                }]
            };
            
            chart.setOption(option);
        }

        // 更新状态徽章
        function updateStatusBadge(passed) {
            const badge = document.getElementById('status-badge');
            if (passed) {
                badge.textContent = '校验通过';
                badge.className = 'px-3 py-1 bg-green-100 text-green-700 text-sm';
            } else {
                badge.textContent = '发现异常';
                badge.className = 'px-3 py-1 bg-red-100 text-red-700 text-sm';
            }
        }

        // 格式化文件大小
        function formatSize(bytes) {
            const units = ['B', 'KB', 'MB', 'GB'];
            let i = 0;
            while (bytes >= 1024 && i < units.length - 1) {
                bytes /= 1024;
                i++;
            }
            return bytes.toFixed(2) + ' ' + units[i];
        }

        // 页面加载时获取状态
        window.onload = async function() {
            try {
                const result = await apiRequest('status');
                if (result.success && result.has_local_hash) {
                    updateStats(
                        result.local_info.total_files,
                        formatSize(result.local_info.total_size),
                        '-',
                        '-'
                    );
                    addLog(`检测到本地HASH文件，生成于 ${result.local_info.generated_at}`, 'info');
                }
            } catch (error) {
                // 忽略错误
            }
        };

        // 窗口大小变化时重绘图表
        window.onresize = function() {
            if (chart) {
                chart.resize();
            }
        };
    </script>
</body>
</html>
<?php
}

// ===== 主程序入口 =====

// 自身完整性校验
if (!selfVerify()) {
    if (php_sapi_name() === 'cli') {
        CLIOutput::error("自身完整性校验失败！本文件可能已被篡改！");
        CLIOutput::error("官方版本: " . REMOTE_HASH_URL);
        exit(1);
    } elseif (isset($_GET['action'])) {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'error' => '自身完整性校验失败，本文件可能已被篡改',
            'self_verify_failed' => true
        ]);
        exit;
    } else {
        renderTamperedUI();
        exit;
    }
}

// 判断运行模式
if (php_sapi_name() === 'cli') {
    handleCliRequest();
} elseif (isset($_GET['action'])) {
    handleApiRequest();
} else {
    renderWebUI();
}

/**
 * 自身完整性校验
 * 比对当前文件的SHA256与远程存储的官方HASH
 * @return bool 校验通过返回true，失败或跳过返回true
 */
function selfVerify(): bool
{
    // 未配置远程校验URL，跳过
    if (SELF_VERIFY_URL === '') {
        return true;
    }

    $selfHash = hash_file('sha256', __FILE__);
    if ($selfHash === false) {
        return true;
    }

    // 获取远程HASH
    $remoteHash = fetchRemoteText(SELF_VERIFY_URL);
    if ($remoteHash === false) {
        // 网络问题不阻塞，放行
        return true;
    }

    $remoteHash = trim($remoteHash);
    return hash_equals($remoteHash, $selfHash);
}

/**
 * 获取远程纯文本内容
 */
function fetchRemoteText(string $url): string|false
{
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_USERAGENT => 'BoAdmin-HashTool/' . VERSION
        ]);
        $content = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($httpCode === 200) ? $content : false;
    }

    $context = stream_context_create([
        'http' => ['timeout' => 10, 'user_agent' => 'BooAdmin-HashTool/' . VERSION],
        'ssl' => ['verify_peer' => true]
    ]);
    return file_get_contents($url, false, $context);
}

/**
 * 篡改警告页面
 */
function renderTamperedUI(): void
{
    ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>安全警告 - BooAdmin</title>
    <script src="https://cdn.garfieldtom.cool/resource/libs/tailwind/3.4.17/tailwindcss.js"></script>
    <link href="https://cdn.garfieldtom.cool/resource/libs/fontawesome/7.2.0/css/all.min.css" rel="stylesheet">
</head>
<body class="min-h-screen bg-red-50 flex items-center justify-center">
    <div class="bg-white border-2 border-red-500 p-8 max-w-lg w-full">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-3xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-red-700">安全警告</h1>
            <p class="text-red-500 mt-2">自身完整性校验失败</p>
        </div>
        <div class="bg-red-50 border border-red-200 p-4 mb-6">
            <p class="text-sm text-red-700">
                本校验工具 (<code class="bg-red-100 px-1">hash_tool.php</code>) 的文件哈希与官方记录不匹配，文件可能已被篡改。请从官方渠道重新下载以获取可信版本。
            </p>
        </div>
        <div class="text-center">
            <p class="text-xs text-gray-400">BooAdmin File Integrity Checker v<?= VERSION ?></p>
        </div>
    </div>
</body>
</html>
<?php
}
