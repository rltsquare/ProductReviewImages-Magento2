<?php
/**
 * NOTICE OF LICENSE
 * You may not sell, distribute, sub-license, rent, lease or lend complete or portion of software to anyone.
 *
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade to newer
 * versions in the future.
 *
 * @package   RLTSquare_ProductReviewImages
 * @copyright Copyright (c) 2017 RLTSquare (https://www.rltsquare.com)
 * @contacts  support@rltsquare.com
 * @license  See the LICENSE.md file in module root directory
 */

namespace RLTSquare\ProductReviewImages\Setup;

/**
 * Class InstallSchema
 * @package RLTSquare\ProductReviewImages\Setup
 * @author Umar Chaudhry <umarch@rltsquare.com>
 */
class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    protected $_io;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    protected $_directoryList;

    /**
     * InstallSchema constructor.
     * 
     * @param \Magento\Framework\Filesystem\Io\File $io
     * @param \Magento\Framework\App\Filesystem\DirectoryList $directoryList
     */
    public function __construct(
        \Magento\Framework\Filesystem\Io\File $io,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList
    )
    {
        $this->_io = $io;
        $this->_directoryList = $directoryList;
    }

    /**
     *
     * function,
     * processed when module is installed for first time
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface $setup
     * @param \Magento\Framework\Setup\ModuleContextInterface $context
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    public function install(
        \Magento\Framework\Setup\SchemaSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context)
    {
        $this->createReviewImagesDirectory();

        $installer = $setup;
        $installer->startSetup();

        $table = $installer->getConnection()->newTable(
            $installer->getTable('rltsquare_productreviewimages_reviewmedia')
        )->addColumn(
            'primary_id', \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT, null, ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true], 'primary id of this table'
        )->addColumn(
            'review_id', \Magento\Framework\DB\Ddl\Table::TYPE_BIGINT, null, ['nullable' => false, 'unsigned' => true], 'foreign key for review id'
        )->addColumn(
            'media_url', \Magento\Framework\DB\Ddl\Table::TYPE_TEXT, 255, ['nullable' => false], 'media url'
        )->addForeignKey(
            $installer->getFkName(
                'rltsquare_productreviewimages_reviewmedia', 'review_id', 'review', 'review_id'
            ), 'review_id', $installer->getTable('review'), 'review_id', \Magento\Framework\DB\Ddl\Table::ACTION_CASCADE
        );

        $installer->getConnection()->createTable($table);
        $installer->endSetup();
    }

    private function createReviewImagesDirectory()
    {
        $this->_io->mkdir($this->_directoryList->getPath('media') . '/review_images', 0755);
    }

}
