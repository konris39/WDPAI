# Manager List na Zakupy

Aplikacja webowa do zarządzania listami zakupów stworzona w PHP z użyciem Nginx, PHP-FPM oraz PostgreSQL. Projekt korzysta z Dockera i Docker Compose, co ułatwia konfigurację środowiska oraz wdrożenie.

## Wymagania

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## Instalacja i Uruchomienie

1. **Sklonuj repozytorium:**

Uruchom kontenery:

Aby zbudować obrazy i uruchomić aplikację w tle, wykonaj:


```
docker-compose up -d --build
```

Po kilku chwilach aplikacja będzie dostępna pod adresem:
http://localhost:8080

Inicjalizacja Bazy Danych

Automatyczna inicjalizacja:

W projekcie znajduje się plik database_setup.sql, który jest montowany do kontenera PostgreSQL (ścieżka: /docker-entrypoint-initdb.d/database_setup.sql). Skrypt tworzy tabele, widoki, funkcje i trigger-y, które są wykonywane przy pierwszym uruchomieniu kontenera (gdy wolumen danych jest pusty).

Wymuszenie ponownej inicjalizacji:

Jeśli chcesz zresetować bazę danych (np. po wprowadzeniu zmian w skrypcie), zatrzymaj kontenery i usuń wolumen danych:


```
docker-compose down -v

docker-compose up -d --build
```

# Korzystanie z Aplikacji
## Rejestracja i Logowanie

Stworzenie konta:

Przejdź do http://localhost:8080/register.

Wypełnij formularz rejestracyjny (podaj nazwę użytkownika, adres e‑mail oraz hasło).

Po pomyślnej rejestracji konto zostanie utworzone.


## Logowanie:

Następnie przejdź do http://localhost:8080/login.

Zaloguj się, podając adres e‑mail oraz hasło użyte przy rejestracji.

Zarządzanie Listami Zakupów

## Tworzenie listy:

Po zalogowaniu możesz tworzyć nowe listy zakupów, dodawać do nich elementy oraz usuwać je.

## Finalizacja listy:

Po finalizacji lista przechodzi w status finalized. Dla takich list automatycznie aktualizowane są statystyki użytkownika (liczba sfinalizowanych list oraz łączny koszt obliczony na podstawie pozycji listy).

## Profil Użytkownika, dostęp do profilu:

Po zalogowaniu przejdź do http://localhost:8080/profil, aby zobaczyć dane swojego konta oraz statystyki (np. liczba sfinalizowanych list i łączny koszt zakupów).

Opcje:

W profilu możesz zmienić hasło lub usunąć konto.



## Zakończenie
Aby zatrzymać kontenery, wykonaj:

```
docker-compose down
```

# Podsumowanie

## Rejestracja: 
Utwórz konto przez formularz na stronie /register.

## Logowanie: 
Zaloguj się na stronie /login używając danych rejestracyjnych.

##Zarządzanie listami: 
Twórz, edytuj i finalizuj listy zakupów.
