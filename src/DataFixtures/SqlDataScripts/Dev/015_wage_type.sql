INSERT INTO wage_type (code, name, wage_percentage_deduction, active)
VALUES('FULL_WAGE', 'Nisu pokrivani troškovi prehrane', 0, 1);

INSERT INTO wage_type (code, name, wage_percentage_deduction, active)
VALUES('ONE_MEAL_COVERED', 'Osiguran jedan obrok', 30, 1);

INSERT INTO wage_type (code, name, wage_percentage_deduction, active)
VALUES('TWO_MEAL_COVERED', 'Osigurana dva obroka', 60, 1);

INSERT INTO wage_type (code, name, wage_percentage_deduction, active)
VALUES('NO_WAGE', 'Ne isplačuje se dnevnica', 100, 1);