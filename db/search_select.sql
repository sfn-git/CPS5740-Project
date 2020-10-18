-- Employee Search
SELECT p.product_id, p.name, p.description, p.cost, p.sell_price, p.quantity, p.vendor_id, v.name, e.name FROM 2020F_nadeems.PRODUCT AS p, CPS5740.VENDOR AS v, CPS5740.EMPLOYEE as e WHERE (p.vendor_id = v.vendor_id AND p.employee_id = e.employee_id) AND (p.name LIKE '%Sam%' or p.description LIKE '%Sam%');
