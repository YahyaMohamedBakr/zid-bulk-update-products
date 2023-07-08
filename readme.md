# Update Products script

This is a PHP script that allows you to update the prices of products in the Zid system. You can specify whether to add a percentage or a fixed number to the current prices.

## Getting Started

These instructions will guide you on how to use the script to update the product prices in the Zid system.

### Prerequisites

- PHP server (e.g., Apache)
- cURL extension enabled

### Installation

1. Clone the repository or download the ZIP file.
2. Place the PHP files in your PHP server's document root directory.

### Usage

1. Open the `index.php` file in a text editor.
2. Modify the styling of the form if needed.
3. Run the PHP server.
4. Access the script through the browser using the server's URL.
5. Fill in the required fields:
   - Store ID: The ID of the store in the Zid system.
   - Manager Token: The authentication token for the store manager.
   - Percentage/Fixed Number: The value to add to the prices.
   - Apply as Percentage: Check this box if the value is a percentage; leave unchecked if it's a fixed number.
6. Click the "Update Products" button.
7. The script will send a PATCH request to the Zid API to update the prices of the products.
8. If the update is successful, a success message will be displayed.

## Customize

You can customize the script according to your specific needs. Here are a few areas you may consider modifying:

- The API endpoints: If the endpoints change in the future, update them in the script.
- Error handling: Enhance the error handling mechanism to display more meaningful error messages.
- Validation: Add additional validation to the form fields to ensure the data is entered correctly.