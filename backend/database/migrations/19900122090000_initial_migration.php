<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

class InitialMigration extends AbstractMigration
{
    public function up(): void
    {
        $this->execute(file_get_contents(__DIR__ . '/schema.sql'));
    }

    public function down(): void
    {
        $this->execute(file_get_contents(__DIR__ . '/clear.sql'));
    }
}
