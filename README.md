# noted

Eine browserbasierte Notiz-App, gebaut mit PHP und PostgreSQL.

## Commit Convention

Commits folgen dem [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/) Standard.

## Protokoll

### Setup - *16.3.*

1. Projektstruktur angelegt
2. PostgreSQL via Homebrew installiert
3. Datenbank `noted` mit `schema.sql` erstellt
4. ERD in pgAdmin erstellt und in `docs/erd.png` gespeichert
5. `.env` mit DB-Informationen angelegt (und `.env.example` für repo)

## Datenbankverbindung - *16.3.*

1. `src/db.php` mit PDO-Verbindung erstellt
2. `.env` wird zur Laufzeit geladen