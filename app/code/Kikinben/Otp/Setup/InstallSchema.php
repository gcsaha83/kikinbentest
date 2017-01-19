<?php
namespace Kikinben\Otp\Setup;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        
         $table = $installer->getConnection()
            ->newTable($installer->getTable('kikinben_otp_forgotpassword'))
                ->addColumn('kikinben_otp_forgotpassword_id', \Magento\Framework\DB\Ddl\Table
                ::TYPE_INTEGER,
                    null, [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary'  => true
                    ], 'Id')
                    ->addColumn('customer_id',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER, null, [
                        'nullable' => false
                    ], 'customer_id')
                    ->addColumn('otp',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'nullable' => false
                    ], 'otp')
                    ->addColumn('status',
                    \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                    null, [
                        'nullable' => false
                    ], 'status')
                    ->addColumn('mobile_number',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, null, [
                        'nullable' => false
                    ], 'mobile_number')
                    ->addColumn('created_at',
                    \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP, null, [
                        'nullable' => false
                    ], 'created_at')
                    ->setComment('Ipragmatech Registration 
                         ipragmatech_registration')
                         
                         ->addColumn(
                   'creation_time',
                   \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                  null,
                  [ 'nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT, ],
                  'Creation Time'
                  )->addColumn(
                 'update_time',
                 \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
                 null,
                 [ 'nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE, ],
                'Modification Time'
                 )->addColumn(
                'is_active',
                \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
                 null,
                [ 'nullable' => false, 'default' => '1', ],
                'Is Active'
              );

              $installer->getConnection()->createTable($table);

              $installer->endSetup();

           }
}
