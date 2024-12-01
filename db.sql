CREATE DATABASE IF NOT EXISTS stylehub;

USE stylehub;

CREATE TABLE IF NOT EXISTS User
(
    userID   INT AUTO_INCREMENT PRIMARY KEY,
    email    VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Seller
(
    sellerID INT AUTO_INCREMENT PRIMARY KEY,
    userID   INT NOT NULL,
    FOREIGN KEY (userID) REFERENCES User (userID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Item
(
    itemID      INT AUTO_INCREMENT PRIMARY KEY,
    sellerID    INT            NOT NULL,
    price       DECIMAL(10, 2) NOT NULL,
    description TEXT           NOT NULL,
    stock       INT            NOT NULL,
    imageURL    VARCHAR(2083),
    FOREIGN KEY (sellerID) REFERENCES Seller (sellerID) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Request
(
    requestID INT AUTO_INCREMENT PRIMARY KEY,
    name      VARCHAR(255) NOT NULL,
    contact   VARCHAR(255) NOT NULL,
    request   TEXT         NOT NULL
);

CREATE TABLE IF NOT EXISTS Ticket
(
    ticketID INT AUTO_INCREMENT PRIMARY KEY,
    userID   INT          NOT NULL,
    email    VARCHAR(255) NOT NULL,
    message  TEXT         NOT NULL,
    FOREIGN KEY (userID) REFERENCES User (userID) ON DELETE CASCADE
);

-- Inserts the admin user, password is 'password'
INSERT INTO User (email, password)
VALUES ('admin@example.com', '$2a$10$CcDrWRxSikOsFUCgs7l0FeyAefAmJ5Q506b/6pl8SAumhGcTNw5zi');
