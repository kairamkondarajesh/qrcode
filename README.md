Goal of this module is to show the QR code to purchase of the products. They would like to display the link as a QR code, such that the site visitors can quickly open the product on the mobile app.
### User Instruction
There were added 4 fields to the product content type:

 - The product title
 - An Image
 - Product Description
 - App Purchase Link
 

## DEPENDENCIES

composer require jonasarts/phpqrcode-bundle

### Technical information
Implements 1 block - Fetches the purchase link and generate QR code.

Just click in any product node link and there you will be redirected to product page where you will get QR code to scan. 

Scan the QR on product page which redirect you to purchase app link on mobile.
