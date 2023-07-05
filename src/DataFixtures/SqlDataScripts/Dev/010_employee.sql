INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth)
SELECT d.id, wp.id, '00001', 'Hrvoje', 'Horvat', 'hhorvat', 'hrvoje.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07'
FROM department d, work_position wp
WHERE d.code = '0034'
AND wp.code = 'C00017';

-- TODO  add multiple employees to facilitate different application functionalities