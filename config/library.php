<?php

return [
    'low_stock_threshold' => (int) env('LIBRARY_LOW_STOCK_THRESHOLD', 2),
    'backup_retention_days' => (int) env('LIBRARY_BACKUP_RETENTION_DAYS', 14),
];
