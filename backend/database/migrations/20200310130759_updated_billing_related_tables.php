<?php

use Phinx\Migration\AbstractMigration;

class UpdatedBillingRelatedTables extends AbstractMigration
{
    public function up(): void {        
        $customer = $this->table('customer');
        $customer->addColumn('phone', 'string', ['null' => true])
              ->addColumn('title', 'string', ['null' => true])
              ->save();

        $course = $this->table('course');
        $course->addColumn('product_id', 'string', ['null' => true])
              ->addColumn('sku_id', 'string', ['null' => true])
              ->save();
    }

    public function down(): void {
        $customer = $this->table('customer');
        $customer->removeColumn('phone')
              ->removeColumn('title')
              ->save();

        $course = $this->table('course');
        $course->removeColumn('product_id')
              ->removeColumn('sku_id')
              ->save();
    }
}
