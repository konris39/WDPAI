-- wdpai.sql

-- 1. USUNIĘCIE ISTNIEJĄCEJ BAZY DANYCH (JEŚLI POTRZEBNE)
-- DROP DATABASE IF EXISTS wdpai;

------------------------------------------------------------------
-- 2. UTWORZENIE BAZY DANYCH I POŁĄCZENIE Z NIĄ
------------------------------------------------------------------
--CREATE DATABASE post_db;
\connect post_db;

-- 1. USUNIĘCIE ISTNIEJĄCEJ BAZY DANYCH (JEŚLI POTRZEBNE)
-- DROP DATABASE IF EXISTS wdpai;

------------------------------------------------------------------
-- 2. UTWORZENIE BAZY DANYCH I POŁĄCZENIE Z NIĄ
------------------------------------------------------------------


------------------------------------------------------------------
-- 3. TABELA UŻYTKOWNIKÓW
------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
                                     id SERIAL PRIMARY KEY,
                                     username VARCHAR(50) NOT NULL UNIQUE,
                                     email VARCHAR(100) NOT NULL UNIQUE,
                                     password_hash VARCHAR(255) NOT NULL,
                                     created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

------------------------------------------------------------------
-- 4. TABELA PROFILI UŻYTKOWNIKÓW (1-1 z users)
------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_profiles (
                                             user_id INT PRIMARY KEY,
                                             full_name VARCHAR(100),
                                             phone_number VARCHAR(20),
                                             address TEXT,
                                             CONSTRAINT fk_user
                                                 FOREIGN KEY (user_id)
                                                     REFERENCES users (id)
                                                     ON DELETE CASCADE
                                                     ON UPDATE CASCADE
);

------------------------------------------------------------------
-- 5. TABELA LIST ZAKUPOWYCH (1-n do users)
------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS shopping_lists (
                                              id SERIAL PRIMARY KEY,
                                              user_id INT NOT NULL,
                                              list_name VARCHAR(100) NOT NULL,
                                              status VARCHAR(20) DEFAULT 'pending',
                                              created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                              updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                              CONSTRAINT fk_user_shoppinglist
                                                  FOREIGN KEY (user_id)
                                                      REFERENCES users (id)
                                                      ON DELETE CASCADE
                                                      ON UPDATE CASCADE
);

------------------------------------------------------------------
-- 6. TABELA ŁĄCZĄCA USERS <-> SHOPPING_LISTS (n-n) - ULUBIONE
------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS favorites (
                                         user_id INT NOT NULL,
                                         shopping_list_id INT NOT NULL,
                                         added_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                         PRIMARY KEY (user_id, shopping_list_id),
                                         CONSTRAINT fk_user_fav
                                             FOREIGN KEY (user_id)
                                                 REFERENCES users (id)
                                                 ON DELETE CASCADE
                                                 ON UPDATE CASCADE,
                                         CONSTRAINT fk_list_fav
                                             FOREIGN KEY (shopping_list_id)
                                                 REFERENCES shopping_lists (id)
                                                 ON DELETE CASCADE
                                                 ON UPDATE CASCADE
);

------------------------------------------------------------------
-- 7. TABELA POZYCJI (ITEMÓW) W LIŚCIE ZAKUPOWEJ (1-n do shopping_lists)
------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS shopping_list_items (
                                                   id SERIAL PRIMARY KEY,
                                                   shopping_list_id INT NOT NULL,
                                                   item_name VARCHAR(100) NOT NULL,
                                                   quantity INT NOT NULL DEFAULT 1,
                                                   price NUMERIC(10,2) NOT NULL DEFAULT 0,
                                                   CONSTRAINT fk_shopping_list
                                                       FOREIGN KEY (shopping_list_id)
                                                           REFERENCES shopping_lists (id)
                                                           ON DELETE CASCADE
                                                           ON UPDATE CASCADE
);

------------------------------------------------------------------
-- 8. FUNKCJA: oblicz łączny koszt listy (sum(quantity*price))
------------------------------------------------------------------
CREATE OR REPLACE FUNCTION fn_total_cost(list_id INT)
    RETURNS NUMERIC(10,2) AS $$
DECLARE
    total NUMERIC(10,2);
BEGIN
    SELECT COALESCE(SUM(quantity * price), 0)
    INTO total
    FROM shopping_list_items
    WHERE shopping_list_id = list_id;

    RETURN total;
END;
$$ LANGUAGE plpgsql;

------------------------------------------------------------------
-- 9. WYZWALACZ: aktualizacja updated_at w shopping_lists
------------------------------------------------------------------
CREATE OR REPLACE FUNCTION update_shopping_list_timestamp()
    RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_update_shopping_list_timestamp
    BEFORE UPDATE ON shopping_lists
    FOR EACH ROW
EXECUTE FUNCTION update_shopping_list_timestamp();

------------------------------------------------------------------
-- 10. WIDOK #1: Widok łączący listy i użytkowników (z total_cost)
------------------------------------------------------------------
CREATE OR REPLACE VIEW vw_all_lists_with_users AS
SELECT
    sl.id AS list_id,
    sl.list_name,
    u.username,
    fn_total_cost(sl.id) AS total_cost,
    sl.status,
    sl.created_at,
    sl.updated_at
FROM shopping_lists sl
         JOIN users u ON u.id = sl.user_id
ORDER BY sl.created_at DESC;

------------------------------------------------------------------
-- 11. WIDOK #2: Widok łączący użytkowników i profile
------------------------------------------------------------------
CREATE OR REPLACE VIEW vw_users_with_profiles AS
SELECT
    u.id AS user_id,
    u.username,
    u.email,
    up.full_name,
    up.phone_number,
    up.address,
    u.created_at
FROM users u
         LEFT JOIN user_profiles up ON up.user_id = u.id
ORDER BY u.id;

------------------------------------------------------------------
-- 12. TABELA STATYSTYK UŻYTKOWNIKÓW
------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS user_stats (
                                          user_id INT PRIMARY KEY,
                                          total_finalized_lists INT NOT NULL DEFAULT 0,
                                          total_spent NUMERIC(10,2) NOT NULL DEFAULT 0,
                                          CONSTRAINT fk_user_stats
                                              FOREIGN KEY (user_id)
                                                  REFERENCES users (id)
                                                  ON DELETE CASCADE
                                                  ON UPDATE CASCADE
);