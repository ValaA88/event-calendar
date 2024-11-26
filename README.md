# event-calendar

Overview:

The Event Management Application is a user-friendly platform designed for managing and exploring sports events. Whether you're a guest, a registered user, or an admin, this application provides a seamless experience for viewing, creating, and managing events and their details. The platform also includes administrative features for enhanced control and security.

###

Features:

View Events:
Everyone can browse all available events and explore detailed information, such as participating teams, results, and venue details.

Filter Events:
Easily filter events by sport or date to find the ones you're interested in.

Create Events:
Guests and registered users can create events. Registering/logging in unlocks additional personalized features.

Admin Privileges:
Create, update, and delete events.
View all user data and manage accounts by blocking or unblocking users.

###

Setup and Run Instructions:
Prerequisites
Web Server: A server environment with PHP support, such as XAMPP, MAMP, or WAMP.
Database: A MySQL server running and ready to use.
PHP Version: PHP 7.4 or later is required.

###

Installation Steps

Clone the Repository:
git clone <https://github.com/ValaA88/event-calendar>
cd <project_folder>

Set Up the Database:
Import the SQL dump file to set up the database schema and seed data
mysql -u <root> -p <Sport_Event_Calender> < db_dump.sql

Configure your database connection in the db_connect_mamp.php file:
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'event_management';

###

Run the Application:
Place the project folder in your server's document root (e.g., htdocs for XAMPP).
Start the web server and access the application in your browser:
http://localhost/<project_folder>

Login or Use as a Guest:
Default admin credentials are provided in the database for testing, Guests can explore events and create new ones without registering.

###

Assumptions and Development Choices
User Roles:
Guests: Can browse and create events.
Registered Users: Can create and manage their events.
Admins: Have full control over events, users, and the application data.

Database Design:
Normalized structure includes events, teams, event_result, team_event_result, stage and users.
Ensures scalability for managing multiple teams and their results for each event.

Front-End Design:
Bootstrap is used for a modern, responsive user interface.
Forms and navigation are designed for simplicity and clarity.

Filtering:
Event filtering by sport and date enhances user experience by narrowing down the displayed events.

Error Handling and Validation:
Server and client-side validations are in place for accurate data handling.
User feedback is provided through alerts for errors and success.
