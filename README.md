SnapCart - E-commerce Website
Project Overview
SnapCart is an e-commerce platform designed for an online shopping experience, where users can browse products, add items to their cart, manage their cart, and place orders. The project is developed as part of an internship and includes both admin and client-side features. The platform provides a user-friendly interface and essential e-commerce functionalities.

Features
Admin Side:
Login: Admins can securely log in to manage the store.
Add Products: Add new products to the catalog, including details such as name, description, price, and images.
View Products: View the list of all available products in the catalog.
Delete Products: Remove products from the catalog.
View Orders: Admins can view orders placed by customers.
Client Side:
Register: New users can create an account by registering with their email and other details.
Login: Users can log in to access their account and start shopping.
View Products: Browse through a catalog of products with prices and images.
Add to Cart: Add products to the shopping cart.
View Cart: View all items in the cart and their total price.
Delete Cart Items: Remove unwanted products from the cart.
Place Order: Confirm and place the order for the items in the cart.
Installation
Prerequisites:
XAMPP or any local server setup
PHP (7.4 or higher)
MySQL Database
Web browser (Chrome, Firefox, etc.)
Steps to Set Up Locally:
Download and Install XAMPP:

Download XAMPP and install it on your system.
Make sure Apache and MySQL services are running.
Clone or Download the Project:

Clone the repository or download it as a zip file.
Place the project folder in C:\xampp\htdocs\SnapCart.
Set Up the Database:

Open phpMyAdmin by navigating to http://localhost/phpmyadmin/ in your browser.
Create a new database called snapcart.
Import the SQL file (snapcart.sql) provided in the database folder into the snapcart database.
Configure Database Connection:

In the project folder, open the config.php file.
Update the following fields with your local setup:
php

$servername = "localhost";
$username = "root"; // Default XAMPP user
$password = ""; // Default XAMPP password (usually empty)
$dbname = "snapcart";
Start the Local Server:

Open XAMPP and start the Apache and MySQL services.
Run the Project:

Open your browser and navigate to http://localhost/SnapCart/index.php to see the client-side.
Navigate to http://localhost/SnapCart/admin.php to access the admin panel.
Folder Structure
bash

SnapCart/
├── assets/             # Contains CSS, JavaScript, and images
├── database/           # Contains the SQL file for database setup
├── admin.php           # Admin panel page
├── config.php          # Database connection file
├── index.php           # Main client-side page
├── login.php           # Login page
├── register.php        # User registration page
├── cart.php            # Shopping cart page
├── order.php           # Order placement page
├── README.md           # Readme file

Features Breakdown
Admin Side
Login: Provides secure access to manage the store's catalog and orders.
Add Products: Allows admin to upload new products with details such as product name, description, price, and images.
View & Manage Products: Admins can view, edit, or delete products from the catalog.
View Orders: Admins can see customer orders and manage the status of orders.

Client Side
Register: New customers can sign up by providing their details.
Login: Existing users can log in to their accounts and access the shopping experience.
Product Catalog: Users can browse products with prices, descriptions, and images.
Shopping Cart: Users can add products to their cart, review their selection, and modify quantities.
Order Placement: Users can finalize their purchase by placing an order through the cart.
## Project Structure:

- **assets/**  
  Contains images, scripts, and other assets used throughout the site.

- **uploads/**  
  Stores files and images uploaded by users or vendors, such as product images.

- **add_to_cart.php**  
  Logic to add selected products to the user's cart.

- **add_to_wishlist.php**  
  Handles adding products to the user's wishlist.

- **cart.php**  
  Displays the contents of the user's shopping cart.

- **checkout.php**  
  Handles the checkout process for placing orders.

- **delete_from_wishlist.php**  
  Logic for removing products from the user's wishlist.

- **fetch_cart_items.php**  
  Retrieves and displays the items in the user's cart.

- **fetch_product_description.php**  
  Fetches detailed descriptions of products.

- **fetch_product_details.php**  
  Fetches product details such as price, availability, and images.

- **fetch_user_details.php**  
  Retrieves user-specific information for displaying account details or orders.

- **fetch_wishlist_items.php**  
  Retrieves the items from the user's wishlist.

- **index.php**  
  Main landing page for the website with product browsing.

- **login_index.php**  
  User login page.

- **login_style.css**  
  Styling for the user login page.

- **manage_orders.php**  
  Allows users or vendors to manage their orders.

- **offer.php**  
  Displays special offers or promotions.

- **online_payment.php**  
  Handles online payments using various methods (UPI, credit card, etc.).

- **order_confirmation.php**  
  Displays the order confirmation after a successful purchase.

- **order_details.php**  
  Shows detailed information about a specific order.

- **payment.php**  
  Handles the payment process, redirects to appropriate payment gateways.

- **payment_failure.php**  
  Page displayed when a payment fails.

- **payment_success.php**  
  Page displayed after a successful payment.

- **place_order.php**  
  Processes the order placement by confirming items and payment.

- **register.php**  
  Handles user registration.

- **register_style.css**  
  Styling for the user registration page.

- **remove_from_cart.php**  
  Removes an item from the user's cart.

- **remove_from_wishlist.php**  
  Removes an item from the user's wishlist.

- **snap_index_style.css**  
  Main CSS file for styling the homepage.

- **track_order.php**  
  Allows users to track their order status.

- **update_product.php**  
  Handles updates to product information (used by vendors).

- **update_profile.php**  
  Allows users to update their account details.

- **user_login.php**  
  User login processing script.

- **user_logout.php**  
  Handles user logout.

- **user_logout_confirmation.php**  
  Displays a confirmation when the user logs out.

- **vendor/**  
  Folder containing all the vendor-specific files for adding, editing, and deleting products.

- **vendor_add_product.php**  
  Allows vendors to add new products to their inventory.

- **vendor_delete_product.php**  
  Allows vendors to delete a product from their inventory.

- **vendor_edit_product.php**  
  Allows vendors to edit the details of an existing product.

- **vendor_login.php**  
  Handles vendor login functionality.

- **vendor_logout.php**  
  Handles vendor logout.

- **vendor_logout_confirmation.php**  
  Displays a confirmation message when a vendor logs out.

- **vendor_register.php**  
  Allows vendors to register and create an account.

- **view_order_details.php**  
  Displays detailed information about an order for both users and vendors.

- **view_orders.php**  
  Displays a list of all orders placed by the user or managed by the vendor.

- **wishlist.php**  
  Displays the user's wishlist.

## Installation Instructions:

1. Clone the repository or download the project files.
2. Configure the database by importing the SQL files.
3. Set up the database connection in `config.php`.
4. Run the project on a local server (e.g., XAMPP, WAMP) or deploy it to a web server.

## Features:

- User registration and login
- Vendor management system
- Cart and wishlist functionality
- Multiple payment methods (UPI, credit card, debit card)
- Order management and tracking
- Product management for vendors
- Wishlist and cart persistence
- Online and cash-on-delivery payment support

## Payment Methods:

- **Cash on Delivery**  
  The customer can pay for the order when it arrives.

- **UPI**  
  Customers can use their UPI ID for payment.

- **Credit Card / Debit Card**  
  Secure online card payment for faster checkouts.

Technologies Used
Frontend:
HTML5, CSS3 (Styled similarly to popular e-commerce websites)
JavaScript (for dynamic content and form validations)
Backend:
PHP (For server-side logic and handling requests)
MySQL (Database for storing user details, products, and orders)
Database:
The database stores user information, product catalog, shopping cart data, and order history.

Future Enhancements
Implementing payment gateway integration.
Adding product search and filter functionality.
Developing a user profile page to manage orders and account information.
Implementing wishlists and product reviews.

## Contributing:
To contribute to the project, submit a pull request or open an issue for discussion.

License
This project is for educational purposes and is licensed under the MIT License.
