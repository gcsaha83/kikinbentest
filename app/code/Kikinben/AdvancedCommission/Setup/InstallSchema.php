<?php
/**
 * Apptha
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.apptha.com/LICENSE.txt
 *
 * ==============================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * ==============================================================
 * This package designed for Magento COMMUNITY edition
 * Apptha does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * Apptha does not provide extension support in case of
 * incorrect edition usage.
 * ==============================================================
 *
 * @category    Apptha
 * @package     Apptha_Marketplace
 * @version     1.1
 * @author      Apptha Team <developers@contus.in>
 * @copyright   Copyright (c) 2016 Apptha. (http://www.apptha.com)
 * @license     http://www.apptha.com/LICENSE.txt
 *
 */
namespace Kikinben\AdvancedCommission\Setup;
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        //START: install stuff
        //END:   install stuff
        
//START table setup
$advanceCommissionTable = $installer->getConnection()->newTable(
            $installer->getTable('kikinben_advancedcommission_commission')
    )->addColumn(
            'kikinben_advancedcommission_commission_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'seller_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'nullable' => false, ],
            'Seller Id'
        )->addColumn(
            'seller_list_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'nullable' => false, ],
            'Seller Id'
        )->addColumn(
            'global_commission',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [ 'nullable' => true ],
            'Global Commission'
        )->addColumn(
            'uprice_range_from',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '10,2',
            [ 'nullable' => false ],
            'Price Range From'
        )->addColumn(
            'uprice_range_to',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '10,2',
            [ 'nullable' => false ],
            'Price Range To'
        )->addColumn(
            'uprice_range_from',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '10,2',
            [ 'nullable' => false ],
            'Price Range From'
        )->addColumn(
            'kikinben_filled',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [ 'nullable' => false ],
            'Filled by site owner'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [ 'nullable' => false ],
            'Filled by site owner'
        )->addColumn(
            'commission_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            null,
            [ 'nullable' => false ],
            'Commission Fixed or Percentage'
        )->addColumn(
            'amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
            '10,2',
            [ 'nullable' => false ],
            'Amount'
        )->addColumn(
            'is_active',
            \Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
            null,
            [ 'nullable' => false, 'default' => '1', ],
            'Is Active'
        );
$installer->getConnection()->createTable($advanceCommissionTable);
//END   table setup

//START table setup
$sellerCommissionCatgMap = $installer->getConnection()->newTable(
            $installer->getTable('kikinben_advancedcommission_seller_commission_catg_map')
    )->addColumn(
            'seller_commission_catg_map_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'commission_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Commission Table Mapped'
        )->addColumn(
            'category_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'nullable' => false ],
            'Category Table Mapped'
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
$installer->getConnection()->createTable($sellerCommissionCatgMap);
//END   table setup




//START table setup
    $sellerProductCommission = $installer->getConnection()->newTable(
            $installer->getTable('kikinben_advancedcommission_sellerproductcommission')
    )->addColumn(
            'kikinben_advancedcommission_sellerproductcommission_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Demo Title'
        )->addColumn(
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
        )->addColumn(
            'seller_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Seller Id'
    )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Product Id'
    )->addColumn(
            'kikibin_fullfiled',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Fulfilled By kikibin'
    )->addColumn(
            'percentage',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Percentage Or Amount'
    )->addColumn(
            'amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Comission Amount'
    );
    $installer->getConnection()->createTable($sellerProductCommission);
//END   table setup

//START table setup
$table = $installer->getConnection()->newTable(
            $installer->getTable('kikinben_advancedcommission_categorycommissionglobal')
    )->addColumn(
            'kikinben_advancedcommission_categorycommissionglobal_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'title',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Demo Title'
        )->addColumn(
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
//END   table setup

$product_commission_global_level_track = $installer->getConnection()->newTable(
            $installer->getTable('kikinben_advancedcommission_prd_commission_global_level_track')
    )->addColumn(
            'product_commission_global_level_track_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            [ 'identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true, ],
            'Entity ID'
        )->addColumn(
            'product_commission_global_level_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Product Commission'
        )->addColumn(
            'order_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Order Id'
        )->addColumn(
            'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Product Id'
        )->addColumn(
            'seller_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Product Id'
        )->addColumn(
            'buyer_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Buyer Id'
        )->addColumn(
            'purchaced_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
            255,
            [ 'nullable' => false, ],
            'Purchased Date'
        )->addColumn(
            'product_price',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Product Price'
        )->addColumn(
            'commission_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Commission Amount'
        )->addColumn(
            'kikinben_fullfiled',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Full Filed By '
        )->addColumn(
            'commission_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            255,
            [ 'nullable' => false, ],
            'Commission Type'
        )->addColumn(
            'seller_amount',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [ 'nullable' => false, ],
            'Commission Amount To Seller'
        )->addColumn(
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
$installer->getConnection()->createTable($product_commission_global_level_track);


$installer->endSetup();
    }
}
