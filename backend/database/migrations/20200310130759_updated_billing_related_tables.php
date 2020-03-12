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

        $course_reservation = $this->table('course_reservation');
        $course_reservation->removeColumn('number')
            ->save();

        $course_payment = $this->table('course_payment');
        $course_payment->addColumn('number', 'string')
            ->addColumn('method', 'integer')
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

        $course_payment = $this->table('course_payment');
        $course_payment->removeColumn('number')
            ->removeColumn('method')
            ->save();
    }
}
