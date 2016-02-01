CREATE TABLE customers (
  id integer PRIMARY KEY, name varchar(100) NOT NULL, email varchar(100) NOT NULL
);
CREATE TABLE orders (
  id integer PRIMARY KEY,
  customer_id int REFERENCES customers(id) NOT NULL, order_number varchar(20) NOT NULL,
  description text NOT NULL,
  total float NOT NULL
);
CREATE TABLE invoices (
  id integer PRIMARY KEY,
  order_id int REFERENCES orders(id) NOT NULL, invoice_date date NOT NULL,
  total float NOT NULL
);

INSERT INTO customers(name, email) VALUES('Acme Corp', 'ap@acme.com');
INSERT INTO customers(name, email) VALUES('ABC Company', 'invoices@abc.com');
