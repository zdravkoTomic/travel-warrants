INSERT INTO predefined_expense(expense_id, currency_id, amount, active)
select exp.id, cur.id, 0.50, 1
FROM expense_type exp, currency cur, employee emp
WHERE exp.code = 'PERSONAL_VEHICLE'
AND cur.code = 'EUR'
AND emp.code = '00001';