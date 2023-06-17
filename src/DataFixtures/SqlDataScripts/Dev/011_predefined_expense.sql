INSERT INTO predefined_expense(expense_id, currency_id, created_by_id, amount, date_from, date_to, created_at)
select exp.id, cur.id, emp.id, 26.55, '2023.01.01', null, now()
FROM expense_type exp, currency cur, employee emp
WHERE exp.code = 'DOMICILE_WAGE'
AND cur.code = 'EUR'
AND emp.code = '000001';