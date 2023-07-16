INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.code = 'hhorvat'
AND r.name = 'ROLE_ADMINISTRATOR'
AND dep.code = '0034';

INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.code = 'hhorvat'
  AND r.name = 'ROLE_ADMINISTRATOR'
  AND dep.code = '0001';

INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.code = 'hhorvat2'
  AND r.name = 'ROLE_APPROVER'
  AND dep.code = '0023';

INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.code = 'hhorvat3'
  AND r.name = 'ROLE_PROCURATOR'
  AND dep.code = '0023';