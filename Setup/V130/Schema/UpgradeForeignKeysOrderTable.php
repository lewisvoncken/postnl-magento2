<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to servicedesk@tig.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\PostNL\Setup\V130\Schema;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * By default, Magento deletes quotes that are expired (converted to orders) after some period of time.
 * It uses cron job vendor/magento/module-sales/Cron/CleanexpiredQuotes.
 *
 * So if there is a FK connection between the quote table and the PostNLorder table,
 * there will be an integrity constrain violation when trying to update an PostNLorder when the quote is removed.
 */
class UpgradeForeignKeysOrderTable implements UpgradeSchemaInterface
{
    const TABLE_NAME = 'tig_postnl_order';

    /**
     * @param SchemaSetupInterface   $setup
     * @param ModuleContextInterface $context
     */
    // @codingStandardsIgnoreLine
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->upgrade($setup, $context);
    }

    /**
     * {@inheritdoc}
     */
    // @codingStandardsIgnoreLine
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $connection = $setup->getConnection(static::TABLE_NAME);
        $connection->dropForeignKey(
            $setup->getTable(static::TABLE_NAME),
            $setup->getFkName(
                static::TABLE_NAME,
                'quote_id',
                'quote',
                'entity_id'
            )
        );

        $setup->endSetup();
    }
}
