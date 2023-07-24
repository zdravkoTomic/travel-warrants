INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.username = 'hhorvat'
  AND r.name = 'ROLE_ADMIN'
  AND dep.code = '0001';

INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.username = 'mmaric'
  AND r.name = 'ROLE_APPROVER'
  AND dep.code = '0034';

INSERT INTO employee_roles (employee_id, role_id, department_id)
SELECT emp.id, r.id, dep.id
FROM employee emp, role r, department dep
WHERE emp.username = 'aanic'
  AND r.name = 'ROLE_PROCURATOR'
  AND dep.code = '0034';

-- INSERT INTO employee_roles (employee_id, role_id, department_id)
-- SELECT emp.id, r.id, dep.id
-- FROM employee emp, role r, department dep
-- WHERE emp.username = 'hhorvat2'
--   AND r.name = 'ROLE_APPROVER'
--   AND dep.code = '0023';
--
-- INSERT INTO employee_roles (employee_id, role_id, department_id)
-- SELECT emp.id, r.id, dep.id
-- FROM employee emp, role r, department dep
-- WHERE emp.username = 'hhorvat3'
--   AND r.name = 'ROLE_PROCURATOR'
--   AND dep.code = '0023';