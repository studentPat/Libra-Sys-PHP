<h1>Database connection verified</h1>
<p class="status-ok">Success: the application connected using PDO.</p>
<ul>
    <li><strong>Database:</strong> <?= htmlspecialchars((string) $databaseName, ENT_QUOTES, 'UTF-8') ?></li>
    <li><strong>Server time:</strong> <?= htmlspecialchars((string) $serverTime, ENT_QUOTES, 'UTF-8') ?></li>
</ul>
