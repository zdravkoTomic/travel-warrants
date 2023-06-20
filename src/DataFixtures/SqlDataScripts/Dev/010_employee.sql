INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth)
SELECT d.id, wp.id, '00001', 'Hrvoje', 'Horvat', 'hhorvat', 'hrvoje.horvat@test.hr', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', '1995.05.07'
FROM department d, work_position wp
WHERE d.code = '0034'
AND wp.code = 'C00017';

-- TODO  add multiple employees to facilitate different application functionalities