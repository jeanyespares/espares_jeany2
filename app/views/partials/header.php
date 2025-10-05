<nav class="bg-blue-700 shadow-lg border-b border-blue-800">
  <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
    <h1 class="text-white text-2xl font-bold flex items-center gap-2">
      <i class="fa-solid fa-users"></i>
      <?= isset($title) ? html_escape($title) : 'Application' ?>
    </h1>
    <div class="flex items-center gap-3">
      <?php if (isset($_SESSION['user'])): ?>
        <span class="text-white">Hello, <?= html_escape($_SESSION['user']['username']) ?></span>
        <a href="<?= site_url('logout') ?>" class="bg-white text-blue-700 px-3 py-1 rounded">Logout</a>
      <?php else: ?>
        <a href="<?= site_url('login') ?>" class="bg-white text-blue-700 px-3 py-1 rounded">Login</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
