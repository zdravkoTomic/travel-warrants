INSERT INTO department (code, name, parent, active)
VALUES('0001', 'Uprava', null, 1);

INSERT INTO department (code, name, parent, active)
SELECT '0010', 'Financije', d.id, 1
FROM department d
WHERE code = '0001';
INSERT INTO department (code, name, parent, active)
SELECT '0011', 'Računovodstvo', d.id, 1
FROM department d
WHERE code = '';
INSERT INTO department (code, name, parent, active)
SELECT '0012', 'Budžetiranje', d.id, 1
FROM department d
WHERE code = '';
INSERT INTO department (code, name, parent, active)
SELECT '0013', 'Prodaja', d.id, 1
FROM department d
WHERE code = '';

INSERT INTO department (code, name, parent, active)
SELECT '0020', 'Ljudski resursi', d.id, 1
FROM department d
WHERE code = '0001';
INSERT INTO department (code, name, parent, active)
SELECT '0021', 'Obačun plaća', d.id, 1
FROM department d
WHERE code = '0020';
INSERT INTO department (code, name, parent, active)
SELECT '0022', 'Zapošljavanje', d.id, 1
FROM department d
WHERE code = '0020';
INSERT INTO department (code, name, parent, active)
SELECT '0023', 'Zaštita na radu', d.id, 1
FROM department d
WHERE code = '0020';

INSERT INTO department (code, name, parent, active)
SELECT '0030', 'Informacijske i komunikacijske tehnologije', d.id, 1
FROM department d
WHERE code = '0001';
INSERT INTO department (code, name, parent, active)
SELECT '0031', 'Upravljanje i administracija', d.id, 1
FROM department d
WHERE code = '0030';
INSERT INTO department (code, name, parent, active)
SELECT '0032', 'IT sigurnost', d.id, 1
FROM department d
WHERE code = '0030';
INSERT INTO department (code, name, parent, active)
SELECT '0033', 'Korisnička podrška', d.id, 1
FROM department d
WHERE code = '0030';
INSERT INTO department (code, name, parent, active)
SELECT '0034', 'Razvoj aplikacija', d.id, 1
FROM department d
WHERE code = '0030';