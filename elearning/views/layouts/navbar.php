<div class="main-wrapper">
    <header class="topbar">
        <div class="topbar-left" style="display:flex;align-items:center;gap:12px;min-width:0;flex:1;overflow:hidden;">
            <button id="sidebarToggle" class="btn btn-outline btn-sm" style="display:none;padding:6px 10px;flex-shrink:0;">
                <i data-lucide="menu" style="width:18px;height:18px;"></i>
            </button>
            <h1 class="page-title" style="min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></h1>
        </div>
        <div class="topbar-right">
            <span class="topbar-date" style="font-size:13px;font-weight:600;color:var(--text-secondary);display:flex;align-items:center;gap:6px;background:#f1f5f9;padding:6px 12px;border-radius:var(--radius-md);border:1px solid var(--border-subtle);">
                <i data-lucide="calendar" style="width:15px;height:15px;color:var(--c-blue);"></i>
                <?= date('d M Y') ?>
            </span>
            <a href="index.php?page=logout" class="btn btn-danger btn-sm" style="box-shadow:none;">
                <i data-lucide="power" style="width:14px;height:14px;"></i> Keluar
            </a>
        </div>
    </header>
    <main class="content-body">
        <?php if ($msg = Helper::flash('success')): ?>
            <div class="alert alert-success">
                <span><i data-lucide="check-circle" style="width:18px;vertical-align:middle;margin-right:8px;"></i> <?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>
        <?php if ($msg = Helper::flash('error')): ?>
            <div class="alert alert-error">
                <span><i data-lucide="alert-circle" style="width:18px;vertical-align:middle;margin-right:8px;"></i> <?= htmlspecialchars($msg) ?></span>
            </div>
        <?php endif; ?>
