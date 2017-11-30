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

namespace RLTSquare\ProductReviewImages\Observer;

/**
 * Class AdminProductReviewSaveAfter
 *
 * @package RLTSquare\ProductReviewImages\Observer
 * @author Umar Chaudhry <umarch@rltsquare.com>
 */
class AdminProductReviewSaveAfter implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $_request;

    /**
     * @var \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory
     */
    protected $_reviewMediaFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList::MEDIA
     */
    protected $_mediaDirectory;

    /**
     * @var \Magento\Framework\Filesystem\Driver\File
     */
    protected $_fileHandler;

    /**
     * AdminProductReviewSaveAfter constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\Framework\Filesystem\Driver\File $fileHandler
     * @param \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory $reviewMediaFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Driver\File $fileHandler,
        \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory $reviewMediaFactory
    )
    {
        $this->_request = $request;
        $this->_fileHandler = $fileHandler;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_reviewMediaFactory = $reviewMediaFactory;
    }


    /**
     * function
     * executes after review is saved
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $target = $this->_mediaDirectory->getAbsolutePath('review_images');
        $deletedMediaString = $this->_request->getParam('deleted_media');

        if ($deletedMediaString)
            try {
                $ids = explode(",", trim($deletedMediaString, ","));
                foreach ($ids as $id) {
                    $reviewMedia = $this->_reviewMediaFactory->create()->load($id);
                    $path = $target . $reviewMedia->getMediaUrl();
                    if ($this->_fileHandler->isExists($path)) {
                        $this->_fileHandler->deleteFile($path);
                    }
                    $reviewMedia->delete();
                }
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while updating review attachment(s).'));
            }
    }
}