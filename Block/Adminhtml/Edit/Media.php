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

namespace RLTSquare\ProductReviewImages\Block\Adminhtml\Edit;

/**
 * Class Media
 *
 * @package RLTSquare\ProductReviewImages\Block\Adminhtml\Edit
 * @author Umar Chaudhry <umarch@rltsquare.com>
 */
class Media extends \Magento\Backend\Block\Template
{
    /**
     * @var \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory
     */
    protected $_reviewMediaFactory;

    /**
     * Media constructor
     *
     * \Magento\Backend\Block\Template\Context $context
     * @param \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory $reviewMediaFactory
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \RLTSquare\ProductReviewImages\Model\ReviewMediaFactory $reviewMediaFactory
    )
    {
        $this->_reviewMediaFactory = $reviewMediaFactory;
        $this->setTemplate("media.phtml");
        parent::__construct($context);
    }

    /**
     * function
     * get media collection for a review
     *
     * @return \RLTSquare\ProductReviewImages\Model\ResourceModel\ReviewMedia\Collection
     */
    public function getMediaCollection()
    {
        $thisReviewMediaCollection = $this->_reviewMediaFactory->create()
            ->getCollection()
            ->addFieldToFilter('review_id', $this->getRequest()->getParam('id'));

        return $thisReviewMediaCollection;
    }

    /**
     * function
     * get review_images directory path
     *
     * @return string
     */
    public function getReviewMediaUrl()
    {
        $reviewMediaDirectoryPath = $this->_storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'review_images';

        return $reviewMediaDirectoryPath;
    }

}