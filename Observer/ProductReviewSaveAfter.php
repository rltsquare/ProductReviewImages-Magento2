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
 * Class ProductReviewSaveAfter
 *
 * @package RLTSquare\ProductReviewImages\Observer
 * @author Umar Chaudhry <umarch@rltsquare.com>
 */
class ProductReviewSaveAfter implements \Magento\Framework\Event\ObserverInterface
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
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;


    /**
     * ProductReviewSaveAfter constructor.
     * @param \Magento\Framework\App\RequestInterface $request
     * @param \Magento\Framework\Filesystem $filesystem
     * @param \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory
     * @param \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory $reviewMediaFactory
     */
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory $reviewMediaFactory
    )
    {
        $this->_request = $request;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->_reviewMediaFactory = $reviewMediaFactory;
    }

    /**
     * function
     * executed after a product review is saved
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $reviewId = $observer->getEvent()->getObject()->getReviewId();
        $media = $this->_request->getFiles('review_media');
        $target = $this->_mediaDirectory->getAbsolutePath('review_images');

        if ($media) {
            try {
                for ($i = 0; $i < count($media); $i++) {
                    $uploader = $this->_fileUploaderFactory->create(['fileId' => 'review_media[' . $i . ']']);
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png']);
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $uploader->setAllowCreateFolders(true);

                    $result = $uploader->save($target);

                    $reviewMedia = $this->_reviewMediaFactory->create();
                    $reviewMedia->setMediaUrl($result['file']);
                    $reviewMedia->setReviewId($reviewId);
                    $reviewMedia->save();
                }
            } catch (\Exception $e) {
                if ($e->getCode() == 0) {
                    $this->messageManager->addError("Something went wrong while saving review attachment(s).");
                }
            }
        }
    }
}