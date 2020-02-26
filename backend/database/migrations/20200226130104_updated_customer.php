<?php

use Phinx\Migration\AbstractMigration;

class UpdatedCustomer extends AbstractMigration
{
    public function up(): void {        
        $customer = $this->table('customer');
        $customer->addColumn('bio', 'string', ['null' => true])
              ->addColumn('website', 'string', ['null' => true])
              ->addColumn('instagram', 'string', ['null' => true])
              ->addColumn('twitter', 'string', ['null' => true])
              ->addColumn('facebook', 'string', ['null' => true])
              ->save();
    }

    public function down(): void {
        $customer = $this->table('customer');
        $customer->removeColumn('bio')
              ->removeColumn('website')
              ->removeColumn('instagram')
              ->removeColumn('twitter')
              ->removeColumn('facebook')
              ->save();
    }
}
