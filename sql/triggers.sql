CREATE OR REPLACE FUNCTION projekt.validate_imie_nazwisko()
    RETURNS TRIGGER
    LANGUAGE plpgsql
    AS $$
    BEGIN
    IF LENGTH(TRIM(NEW.Imie)) = 0 OR LENGTH(TRIM(NEW.Nazwisko)) = 0 OR NEW.Imie !~ '^[A-Za-z]+$' OR NEW.Nazwisko !~ '^[A-Za-z]+$' THEN
        RAISE EXCEPTION 'Niepoprawne wartości Imie i/lub Nazwisko.';
    END IF;
  
    RETURN NEW;                                                          
    END;
    $$;

CREATE TRIGGER wyzw0
    AFTER INSERT OR UPDATE ON projekt.Uzytkownik
        FOR EACH ROW EXECUTE PROCEDURE projekt.validate_imie_nazwisko();

CREATE TRIGGER wyzw1
    AFTER INSERT OR UPDATE ON projekt.Manager
        FOR EACH ROW EXECUTE PROCEDURE projekt.validate_imie_nazwisko();

CREATE OR REPLACE FUNCTION projekt.imie_nazwisko_norm()
    RETURNS TRIGGER
    AS $$
    BEGIN
    IF LENGTH(TRIM(NEW.Imie)) = 0 OR LENGTH(TRIM(NEW.Nazwisko)) = 0 THEN
        RAISE EXCEPTION 'Niepoprawne wartości Imie i/lub Nazwisko.';
    END IF;
    NEW.Imie:=TRIM(CONCAT(upper(LEFT(NEW.Imie, 1)), lower(RIGHT(NEW.Imie, (LENGTH(NEW.Imie)-1)))));
    NEW.Nazwisko:=TRIM(CONCAT(upper(LEFT(NEW.Nazwisko, 1)), lower(RIGHT(NEW.Nazwisko, (LENGTH(NEW.Nazwisko)-1)))));
    RETURN NEW;                                                          
    END;
    $$ LANGUAGE plpgsql;


CREATE TRIGGER wyzw2
    BEFORE INSERT OR UPDATE ON projekt.Uzytkownik
        FOR EACH ROW EXECUTE PROCEDURE projekt.imie_nazwisko_norm();

CREATE TRIGGER wyzw3
    BEFORE INSERT OR UPDATE ON projekt.Manager
        FOR EACH ROW EXECUTE PROCEDURE projekt.imie_nazwisko_norm();

CREATE OR REPLACE FUNCTION projekt.ulica_norm()
    RETURNS TRIGGER
    AS $$
    BEGIN
    IF LENGTH(TRIM(NEW.Ulica)) = 0 THEN
        RAISE EXCEPTION 'Niepoprawne wartości - ulica.';
    END IF;
    NEW.Ulica:=TRIM(CONCAT(upper(LEFT(NEW.Ulica, 1)), lower(RIGHT(NEW.Ulica, (LENGTH(NEW.Ulica)-1)))));
    RETURN NEW;                                                          
    END;
    $$ LANGUAGE plpgsql;

CREATE TRIGGER wyzw4
    BEFORE INSERT OR UPDATE ON projekt.Restauracja
        FOR EACH ROW EXECUTE PROCEDURE projekt.ulica_norm();

CREATE TRIGGER wyzw5
    BEFORE INSERT OR UPDATE ON projekt.Adres
        FOR EACH ROW EXECUTE PROCEDURE projekt.ulica_norm();