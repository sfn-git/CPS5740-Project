CREATE TABLE PRODUCT(

    product_id INT AUTO_INCREMENT PRIMARY KEY NOT NULL,
    description VARCHAR(500) NOT NULL,
    name VARCHAR(50) NOT NULL,
    vendor_id INT NOT NULL,
    cost DECIMAL(13,2) NOT NULL,
    sell_price DECIMAL(13,2) NOT NULL,
    quantity INT(9) NOT NULL,
    employee_id INT NOT NULL,
    FOREIGN KEY (vendor_id) REFERENCES CPS5740.VENDOR(vendor_id),
    FOREIGN KEY (employee_id) REFERENCES CPS5740.EMPLOYEE2(employee_id)
);