<?= $this->extend('template') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">📢 Announcements</h1>

    <?php if (empty($announcements)): ?>
        <p class="text-center text-muted">No announcements yet.</p>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($announcements as $a): ?>
                <div class="list-group-item mb-3 shadow-sm">
                    <h4><?= esc($a['title']) ?></h4>
                    <p><?= esc($a['content']) ?></p>
                    <small class="text-muted">Posted on: <?= esc($a['created_at']) ?></small>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
