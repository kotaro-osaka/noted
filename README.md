# noted

Eine browserbasierte Notiz-App, gebaut mit PHP und PostgreSQL.

## Commit Convention

Commits folgen dem [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) Standard.

## Protokoll

### Setup - *16.3.*

1. Projektstruktur angelegt
2. PostgreSQL via Homebrew installiert
3. Datenbank `noted` mit `schema.sql` erstellt
4. Sequence-Rechte für `noted_user` vergeben
5. ERD in pgAdmin erstellt und in `docs/erd.png` gespeichert
6. `.env` mit DB-Informationen angelegt (und `.env.example` für repo)

### Datenbankverbindung - *16.3.*

1. `src/db.php` mit PDO-Verbindung erstellt
2. `.env` wird zur Laufzeit geladen

### Sign in & Register - *16.3.*

1. `public/index.php` - Login-Seite mit Session-Check
2. `public/register.php` - Register-Seite mit Session-Check

### Auth - *16.3.*

1. `src/auth.php` - Login und Register Funktionen mit Argon2 Hashing
2. `public/auth/login.php` - Login Endpoint
3. `public/auth/register.php` - Register Endpoint

### SQLite Migration - *26.3.*

1. Von PostgreSQL zu SQLite gewechselt
2. Schema angepasst: ENUMs durch CHECK Constraints ersetzt, FTS5 für Volltextsuche
3. `db.php` angepasst: SQLite DSN, PRAGMA für Fremdschlüssel aktiviert
4. `database/noted.db` erstellt und Schema ausgeführt

### Editor - *26.3.*

1. `public/editor.php` - Editor-Seite mit Session-Check und Notiz-Laden
2. `public/js/editor.js` - Auto-Save mit Debounce (1s), Save vor Navigation
3. `public/api/notes/save.php` - Save-Endpoint, erstellt bzw. aktualisiert Notiz