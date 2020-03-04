<?php

use Phinx\Migration\AbstractMigration;

class AddedCustomerPaymentInfo extends AbstractMigration
{
    public function up(): void {        
        $customerPayment = $this->table('customer_payment_method');
        $customerPayment->addColumn('customer_id', 'string')
              ->addColumn('token', 'string')
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('updated_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('deleted_at', 'timestamp', ['null' => true])
              ->create();
    }

    public function down(): void {
        $customerPayment = $this->table('customer_payment_method')->drop();
    }
}
