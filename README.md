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

License
This project is for educational purposes and is licensed under the MIT License.
