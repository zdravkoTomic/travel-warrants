INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00001', 'Hrvoje', 'Horvat', 'hhorvat', 'hrvoje.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1, 0
FROM department d, work_position wp
WHERE d.code = '0034'
AND wp.code = 'C00017';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00002', 'Marko', 'Marić', 'mmaric', 'marko.maric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1, 0
FROM department d, work_position wp
WHERE d.code = '0034'
  AND wp.code = 'B00008';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00003', 'Ana', 'Anic', 'aanic', 'ana.anic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1995.05.07', 1, 0
FROM department d, work_position wp
WHERE d.code = '0034'
  AND wp.code = 'B00004';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00004', 'Ivan', 'Horvat', 'ihorvat', 'ivan.horvat@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1980.05.07', 1, 0
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'A00001';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00005', 'Petar', 'Kovac', 'pkovac', 'petar.kovac@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1975.06.15', 1, 0
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00006', 'Marko', 'Novak', 'mnovak', 'marko.novak@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1979.07.25', 1, 0
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'A00003';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00007', 'Marija', 'Jukic', 'mjukic', 'marija.jukic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1981.04.17', 1, 0
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'A00001';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00008', 'Ana', 'Perkovic', 'aperkovic', 'ana.perkovic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1978.03.09', 1, 0
FROM department d, work_position wp
WHERE d.code = '0010'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00009', 'Lucija', 'Babic', 'lbabic', 'lucija.babic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1977.06.11', 1, 0
FROM department d, work_position wp
WHERE d.code = '0011'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00010', 'Filip', 'Ivic', 'fivic', 'filip.ivic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1983.03.10', 1, 0
FROM department d, work_position wp
WHERE d.code = '0011'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00013', 'Ivana', 'Kralj', 'ikralj', 'ivana.kralj@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1984.04.14', 1, 0
FROM department d, work_position wp
WHERE d.code = '0012'
  AND wp.code = 'A00003';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00018', 'Toni', 'Maric', 'tmaric', 'toni.maric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1986.02.18', 1, 0
FROM department d, work_position wp
WHERE d.code = '0013'
  AND wp.code = 'A00001';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00023', 'Katarina', 'Petrovic', 'kpetrovic', 'katarina.petrovic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1975.01.15', 1, 0
FROM department d, work_position wp
WHERE d.code = '0020'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active, fully_authorized)
SELECT d.id, wp.id, '00028', 'Mario', 'Zoric', 'mzoric', 'mario.zoric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1992.09.17', 1, 0
FROM department d, work_position wp
WHERE d.code = '0030'
  AND wp.code = 'A00003';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00029', 'Tomislav', 'Novak', 'tnovak', 'tomislav.novak@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1979.06.09', 1
FROM department d, work_position wp
WHERE d.code = '0011'
  AND wp.code = 'A00001';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00030', 'Luka', 'Popovic', 'lpopovic', 'luka.popovic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1981.07.10', 1
FROM department d, work_position wp
WHERE d.code = '0011'
  AND wp.code = 'A00002';

-- For department '0012'
INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00031', 'Ivana', 'Kralj', 'ikralj', 'ivana.kralj@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1984.04.14', 1
FROM department d, work_position wp
WHERE d.code = '0012'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00032', 'Jelena', 'Jukic', 'jjukic', 'jelena.jukic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1980.02.15', 1
FROM department d, work_position wp
WHERE d.code = '0012'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00033', 'Zoran', 'Zoric', 'zzoric', 'zoran.zoric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1978.10.16', 1
FROM department d, work_position wp
WHERE d.code = '0012'
  AND wp.code = 'A00003';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00034', 'Miro', 'Matic', 'mmatic', 'miro.matic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1975.03.17', 1
FROM department d, work_position wp
WHERE d.code = '0012'
  AND wp.code = 'A00001';

-- For department '0013'
INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00035', 'Toni', 'Maric', 'tmaric', 'toni.maric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1986.05.18', 1
FROM department d, work_position wp
WHERE d.code = '0013'
  AND wp.code = 'A00002';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00036', 'Davor', 'Dadic', 'ddadic', 'davor.dadic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1985.11.19', 1
FROM department d, work_position wp
WHERE d.code = '0013'
  AND wp.code = 'A00003';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00037', 'Matea', 'Mlinaric', 'mmlinaric', 'matea.mlinaric@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1982.04.20', 1
FROM department d, work_position wp
WHERE d.code = '0013'
  AND wp.code = 'A00003';

INSERT INTO employee (department_id, work_position_id, code, name, surname, username, email, password, date_of_birth, active)
SELECT d.id, wp.id, '00038', 'Mirna', 'Stipic', 'mstipic', 'mirna.stipic@test.hr', '$2y$13$QHTG1h8AcfAyNKit6Z8gGe/K2u0JCWBvC40ytPRzGGPlZjblJeYzG', '1984.06.21', 1
FROM department d, work_position wp
WHERE d.code = '0013'
  AND wp.code = 'A00001';



-- TODO  add multiple employees to facilitate different application functionalities