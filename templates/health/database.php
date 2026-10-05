<?php if ($connected): ?>
    <h1>Database connection healthy</h1>
    <p class="status-ok">PHP connected to MySQL and executed a prepared statement successfully.</p>
<?php else: ?>
    <h1>Database connection unavailable</h1>
    <p class="status-error">The application could not connect to the configured database.</p>
<?php endif; ?>
