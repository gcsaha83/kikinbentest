<?php

/**
 * Copyright © 2015 Ipragmatech. All rights reserved.
 */
namespace Ipragmatech\Registration\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     *
     * {@inheritdoc}
     *
     */
    public function install(
        SchemaSetupInterface $setup,
        ModuleContextInterface $context
    ) {
        $configContext =  $context->getConfig();
        $installer = $setup;

        $installer->startSetup();

        /**
         * Create table 'ipragmatech_otp'
         */
        $table = $installer->getConnection()
            ->newTable($installer->getTable('ipragmatech_otp'))
                ->addColumn('id', \Magento\Framework\DB\Ddl\Table
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
                         ipragmatech_registration');

                    $installer->getConnection()->createTable($table);

                    $installer->endSetup();
    }
}
