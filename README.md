# ProductReviewImages-Magento2

# Overview

The Product Review Images for Magento 2 extension has been developed by the product team at RLTSquare. This extension will allow your customers to upload images within default product reviews for the products they buy and can also show the unique ways they are using your products in real life. So, why not let your customers take an active part in your online eCommerce business and eventually get benefit from it?

Here are some of the salient features for the extension:

```
1. Allow your customers to upload images in Magento 2 default product reviews
2. Admin approval required to ensure relevant and appropriate images only to be viewed at frontend
3. Allows uploading of up to 10 product images
4. Allows potential customers to view the review images uploaded by other customers
5. Original image size results in good user experience
```

## Installation

### Magento® Marketplace

This extension will also be available on the Magento® Marketplace when approved.

### Manually

1. Go to Magento® 2 root folder

2. Require/Download this extension:

   Enter following commands to install extension.

   ```
   composer require rltsquare/product-review-images
   ```

   Wait while composer is updated.
   
   #### OR
   
   You can also download code from this repo under Magento® 2 following directory:
    
    ```
    app/code/RLTSquare/ProductReviewImages
    ```    

3. Enter following commands to enable the module:

   ```
   php bin/magento module:enable RLTSquare_ProductReviewImages
   php bin/magento setup:upgrade
   php bin/magento cache:clean
   php bin/magento cache:flush
   ```

4. If Magento® is running in production mode, deploy static content: 

   ```
   php bin/magento setup:static-content:deploy
   ```


## Requirements

1. This Magento® extension works on Magento 2.1 and 2.2 versions. Tested on versions 2.1.6 and above.

2. Tested on different themes specifically Ultimo, Porto and certain custom themes

For details, read our blog:
https://www.rltsquare.com/blog/product-review-images-magento-2-extension/
