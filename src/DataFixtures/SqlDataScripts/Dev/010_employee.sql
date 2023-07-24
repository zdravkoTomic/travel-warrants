INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00001', 'Hrvoje', 'Horvat', 'hhorvat', 'hrvoje.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1
FROM department d, work_position wp
WHERE d.code = '0034'
AND wp.code = 'C00017';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00002', 'Marko', 'Marić', 'mmaric', 'marko@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1
FROM department d, work_position wp
WHERE d.code = '0034'
  AND wp.code = 'B00008';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00003', 'Ana', 'Anic', 'aanic', 'ana@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1
FROM department d, work_position wp
WHERE d.code = '0034'
  AND wp.code = 'B00004';

-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00003', 'Hrvoje3', 'Horvat3', 'hhorvat3', 'hrvoje3.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0034'
--   AND wp.code = 'C00017';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00004', 'Hrvoje4', 'Horvat4', 'hhorvat4', 'hrvoje4.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0023'
--   AND wp.code = 'C00017';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00005', 'Hrvoje5', 'Horvat5', 'hhorvat5', 'hrvoje5.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0023'
--   AND wp.code = 'C00017';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00006', 'Pero', 'Peric', 'pperic', 'pero.peric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2000.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0023'
--   AND wp.code = 'C00005';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00007', 'Ana', 'Anić', 'aanic', 'ana.anic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2002.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0023'
--   AND wp.code = 'B00002';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00008', 'Pero2', 'Peric2', 'pperic2', 'pero2.peric2@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2002.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0023'
--   AND wp.code = 'B00002';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00009', 'Pero3', 'Pero3', 'pperic3', 'pero3.peric3@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2002.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0034'
--   AND wp.code = 'B00008';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '00010', 'Pero4', 'Peric4', 'pperic4', 'pero4.peric2@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2002.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0023'
--   AND wp.code = 'B00002';
--
-- INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
-- SELECT d.id, wp.id, '000011', 'Pero5', 'Pero5', 'Peric5', 'pero5.peric3@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '2002.05.07', 1
-- FROM department d, work_position wp
-- WHERE d.code = '0034'
--   AND wp.code = 'B00008';

-- TODO  add multiple employees to facilitate different application functionalities