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

namespace RLTSquare\ProductReviewImages\Model\ResourceModel;

/**
 * Class ReviewMedia
 *
 * @package RLTSquare\ProductReviewImages\Model\ResourceModel
 * @author Umar Chaudhry <umarch@rltsquare.com>
 */
class ReviewMedia extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb {

    /**
     * constructor
     *
     */
    protected function _construct() {
        $this->_init('rltsquare_productreviewimages_reviewmedia', 'primary_id');
    }

}
