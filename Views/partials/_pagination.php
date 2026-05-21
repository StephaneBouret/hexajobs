<?php
$page = (int) ($pagination['page'] ?? 1);
$pages = (int) ($pagination['pages'] ?? 1);
$path = (string) ($pagination['path'] ?? '');
$query = $pagination['query'] ?? [];
$window = (int) ($pagination['window'] ?? 5);

if ($pages <= 1) {
    return;
}

$paginationUrl = static function (int $targetPage) use ($path, $query): string {
    $params = $query;
    $params['page'] = $targetPage;

    return $path . '?' . http_build_query($params);
};

$half = intdiv($window, 2);
$start = max(1, $page - $half);
$end = min($pages, $page + $half);

if (($end - $start + 1) < $window) {
    $missing = $window - ($end - $start + 1);
    $start = max(1, $start - $missing);
    $end = min($pages, $end + ($window - ($end - $start + 1)));
}
?>

<nav aria-label="<?= htmlspecialchars($pagination['label'] ?? 'Pagination', ENT_QUOTES, 'UTF-8'); ?>">
    <ul class="pagination justify-content-center mt-4">
        <li class="page-item <?= $page <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?= $page > 1 ? htmlspecialchars($paginationUrl($page - 1), ENT_QUOTES, 'UTF-8') : '#'; ?>">
                &laquo;
            </a>
        </li>

        <li class="page-item <?= $page === 1 ? 'active' : ''; ?>">
            <a class="page-link" href="<?= htmlspecialchars($paginationUrl(1), ENT_QUOTES, 'UTF-8'); ?>">1</a>
        </li>

        <?php if ($start > 2): ?>
            <li class="page-item disabled"><span class="page-link">…</span></li>
        <?php endif; ?>

        <?php for ($i = max(2, $start); $i <= min($pages - 1, $end); $i++): ?>
            <li class="page-item <?= $page === $i ? 'active' : ''; ?>">
                <a class="page-link" href="<?= htmlspecialchars($paginationUrl($i), ENT_QUOTES, 'UTF-8'); ?>">
                    <?= $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <?php if ($end < $pages - 1): ?>
            <li class="page-item disabled"><span class="page-link">…</span></li>
        <?php endif; ?>

        <li class="page-item <?= $page === $pages ? 'active' : ''; ?>">
            <a class="page-link" href="<?= htmlspecialchars($paginationUrl($pages), ENT_QUOTES, 'UTF-8'); ?>">
                <?= $pages; ?>
            </a>
        </li>

        <li class="page-item <?= $page >= $pages ? 'disabled' : ''; ?>">
            <a class="page-link" href="<?= $page < $pages ? htmlspecialchars($paginationUrl($page + 1), ENT_QUOTES, 'UTF-8') : '#'; ?>">
                &raquo;
            </a>
        </li>
    </ul>
</nav>