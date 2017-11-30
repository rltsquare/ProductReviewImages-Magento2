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
 * Class AdminProductReviewDeleteBefore
 *
 * @package RLTSquare\ProductReviewImages\Observer
 * @author Umar Chaudhry <umarch@rltsquare.com>
 */
class AdminProductReviewDeleteBefore implements \Magento\Framework\Event\ObserverInterface
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
     * AdminProductReviewDeleteBefore constructor.
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
     * executes before a review is deleted
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $target = $this->_mediaDirectory->getAbsolutePath('review_images');

        // single record deletion
        $reviewId = $this->_request->getParam('id', false);
        if ($reviewId) {
            $this->deleteReviewMedia($reviewId);
            return;
        }

        // mass deletion
        $reviewIds = $this->_request->getParam('reviews', false);
        if ($reviewIds) {
            foreach ($reviewIds as $id) {
                $this->deleteReviewMedia($id);
            }
            return;
        }
    }

    /**
     * function
     * delete media against a review
     *
     * @param $reviewId
     * @return void
     */
    private function deleteReviewMedia($reviewId)
    {
        $target = $this->_mediaDirectory->getAbsolutePath('review_images');

        try {
            $thisReviewMediaCollection = $this->_reviewMediaFactory->create()
                ->getCollection()
                ->addFieldToFilter('review_id', $reviewId);

            foreach ($thisReviewMediaCollection as $m) {
                $path = $target . $m->getMediaUrl();
                if ($this->_fileHandler->isExists($path)) {
                    $this->_fileHandler->deleteFile($path);
                }
            }
        } catch (\Exception $e) {
            $this->messageManager->addException($e, __('Something went wrong while deleting review(s) attachment(s).'));
        }
    }
}
