#!/bin/bash

# Database credentials
DB_HOST="127.0.0.1"
DB_PORT="3306"
DB_USER="root"
DB_PASSWORD=""
DB_NAME="stylehub"

# SQL commands
SQL_COMMANDS=$(cat <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME;

USE $DB_NAME;

CREATE TABLE IF NOT EXISTS User (
    userID INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Seller (
    sellerID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Item (
    itemID INT AUTO_INCREMENT PRIMARY KEY,
    sellerID INT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    description TEXT NOT NULL,
    stock INT NOT NULL,
    imageURL VARCHAR(2083),
    FOREIGN KEY (sellerID) REFERENCES Seller(sellerID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Request (
    requestID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Ticket (
    ticketID INT AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    FOREIGN KEY (userID) REFERENCES User(userID) ON DELETE CASCADE
);
EOF
)

# Execute SQL commands
/opt/lampp/bin/mysql -h $DB_HOST -P $DB_PORT -u $DB_USER -e "$SQL_COMMANDS"

# Output success message
if [ $? -eq 0 ]; then
    echo "Database and tables have been initialized successfully."
else
    echo "An error occurred during initialization."
fi
