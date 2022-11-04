
# Redirect Simple products to its parent configurable For Magento 2
Redirect simple products to its parent configurable product with pre-selected configurable options.

This extension is configurable on the backend.

![docs](https://i.imgur.com/uq58SGt.png)

## Usage Instructions

TechYouKnow RedirectSimpleProducts Extension - Installation steps

INSTALL TechYouKnow RedirectSimpleProducts EXTENSION FROM ZIP FILE ON YOUR DEV INSTANCE. TEST THAT THE EXTENSION
WAS INSTALLED CORRECTLY BEFORE SHIPPING THE CODE TO PRODUCTION

### INSTALLATION

#### Composer Installation
* Go to your magento root path
* Execute command `cd /var/www/Magento` or
 `cd /var/www/html/Magento` based on your server Centos or Ubuntu.
* run composer command: `composer require techyouknow/module-redirect-simple-products`
- To enable module execute `php bin/magento module:enable Techyouknow_RedirectSimpleProducts`
- Execute `php bin/magento setup:upgrade`
- Execute `php bin/magento setup:di:compile`
- Optional `php bin/magento setup:static-content:deploy`
- Execute `php bin/magento cache:clean`

#### Manual Installation
* extract files from an archive.
* Execute command `cd /var/www/Magento/app/code` or
 `cd /var/www/html/Magento/app/code` based on your server Centos or Ubuntu.
* Move files into Magento2 folder `app/code/Techyouknow/RedirectSimpleProducts`. If you downloaded zip file on github, you need to
create directory `app/code/Techyouknow/RedirectSimpleProducts`.
- To enable module execute `php bin/magento module:enable Techyouknow_RedirectSimpleProducts`
- Execute `php bin/magento setup:upgrade`
- Optional `php bin/magento setup:static-content:deploy`
- Execute `php bin/magento setup:di:compile`
- Execute `php bin/magento cache:clean`

## Requirements

Techyouknow RedirectSimpleProducts Extension For Magento2 requires
* Magento version 2.0 and above
* PHP 7.0 or greater