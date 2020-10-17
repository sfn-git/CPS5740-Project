CREATE TABLE PRODUCT_ORDER(
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL,
    FOREIGN KEY (order_id) REFERENCES 2020F_nadeems.ORDER(order_id),
    FOREIGN KEY (product_id) REFERENCES 2020F_nadeems.PRODUCT(product_id)
);