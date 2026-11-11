# info2180-project2
Minimum Viable Product (MVP) for a new Customer Relationship Management (CRM) System called Dolphin CRM

# INFO2180 Project 2 – Dolphin CRM

## Project Overview

Dolphin CRM is a Minimum Viable Product (MVP) for a Customer Relationship Management system developed as part of the INFO2180 course at the University of the West Indies, Mona.

The system is designed to help an organisation manage its customer contacts, track potential sales leads, handle support contacts, assign contacts to team members and maintain notes documenting interactions with customers.

## Features

The application includes the following features:

- **User Authentication:** Secure login and logout functionality using email addresses, passwords and PHP sessions.
- **User Management:** Administrators can create new users, assign roles and view the list of registered users.
- **Role-Based Access Control:** The system distinguishes between Admin and Member accounts, restricting administrative features to authorised users.
- **Contact Management:** Users can create and view customer contact records containing personal and company information.
- **Contact Assignment:** Contacts can be assigned to users, with an option for users to assign contacts to themselves.
- **Contact Classification:** Contacts can be categorised as either Sales Leads or Support contacts.
- **Contact Notes:** Users can add notes to contact records to document customer interactions. Notes display the author and date created.
- **Contact Filtering:** Users can filter contacts by all contacts, sales leads, support contacts, or contacts assigned to themselves.
- **AJAX Functionality:** Asynchronous requests are used to load and update information without requiring full page reloads.

## Technologies Used

The project was developed using:

- HTML5
- CSS3
- JavaScript
- PHP
- MySQL
- PDO (PHP Data Objects)
- AJAX (Fetch API)
- XAMPP
- Git and GitHub
- Visual Studio Code Live Share

## Installation and Setup

To run Dolphin CRM locally:

1. Install and open XAMPP.
2. Start the Apache and MySQL services.
3. Clone this repository into the XAMPP `htdocs` directory.
4. Create a local database configuration file by copying `config/database.example.php` and naming the copy `database.php`.
5. Ensure the database credentials in `config/database.php` match your local MySQL configuration.
6. Open phpMyAdmin and import the `schema.sql` file to create the `dolphin_crm` database, its tables and the initial administrator account.
7. Open the application in a browser using:

   `http://localhost/info2180-project2/`

## Initial Administrator Account

The database is supplied with an initial administrator account for accessing the system.

- **Email:** admin@project2.com
- **Password:** password123

The password is stored as a secure hash in the database.

## Group Collaboration and Development Workflow

This project was developed collaboratively by a group of three members:
Rheese-Ashley Roman
Ayanna Wizzard
Jayden Bloomfield

To coordinate development we used **Visual Studio Code Live Share** and Discord voice calls. Live Share allowed us to collaborate in real time, while Discord allowed us to coordinate.

To maintain consistency and reduce the possibility of conflicting Git changes, one group member coordinated the Git commits, repository updates and local testing through XAMPP. The other group members contributed to development through the shared coding sessions and group calls.

## Project Objective

The objective of Dolphin CRM is to provide a simple, functional system for managing customer relationships and supporting communication between team members. The project demonstrates the practical application of web development technologies, database management, user authentication, role-based access control and asynchronous client-server communication.