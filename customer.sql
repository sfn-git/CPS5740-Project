CREATE TABLE CUSTOMER(

    customer_id INT(10) AUTO_INCREMENT PRIMARY KEY UNIQUE,
    login_id VARCHAR(32) NOT NULL UNIQUE,
    password VARCHAR(32) NOT NULL,
    first_name VARCHAR(32),
    last_name VARCHAR(32),
    TEL VARCHAR(16),
    address VARCHAR(32),
    city VARCHAR(32),
    zipcode VARCHAR(5),
    state VARCHAR(32)
);