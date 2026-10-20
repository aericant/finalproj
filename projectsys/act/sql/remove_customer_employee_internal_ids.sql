-- Back up sip_n_tell_db in phpMyAdmin before running this migration.
-- Existing customer and employee rows are retained; only their numeric internal ID columns are removed.

ALTER TABLE customers
    DROP PRIMARY KEY,
    DROP COLUMN id,
    ADD PRIMARY KEY (customer_id);

ALTER TABLE employees
    DROP PRIMARY KEY,
    DROP COLUMN id,
    ADD PRIMARY KEY (employee_id);
