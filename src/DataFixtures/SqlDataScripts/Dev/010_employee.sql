INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth)
SELECT d.id, wp.id, '00001', 'Hrvoje', 'Horvat', 'hhorvat', 'hrvoje.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07'
FROM department d, work_position wp
WHERE d.code = '0034'
AND wp.code = 'C00017';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth)
SELECT d.id, wp.id, '00002', 'Pero', 'Peric', 'pperic', 'pero.peric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2000.05.07'
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'C00005';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth)
SELECT d.id, wp.id, '000032', 'Ana', 'Anić', 'aanic', 'ana.anic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2002.05.07'
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'B00002';

-- TODO  add multiple employees to facilitate different application functionalities