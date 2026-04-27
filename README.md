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

### Notes View - *26.3.*

1. `public/notes.php` Notiz-Grid mit Session-Check, dynamische Notizanzahl
2. `public/css/index.css` - Layout für Grid, Header, Footer

### UI & Auth Improvements - *20.4.*

1. `public/logout.php` - Session zerstören, Cache-Header gesetzt
2. `public/api/auth/check.php` - Auth-Check Endpoint für Client-seitigen Session-Check
3. `public/js/notes.js` - Dropdown-Menü Button mit Logout
4. `public/css/index.css` - Dropdown, Auth, Error Styles hinzugefügt
5. Error Messages in `index.php` und `register.php` eingebaut

### Archive, Trash & Folders - *27.4.*

1. `public/archive.php` - Archivierte Notizen Grid-Ansicht
2. `public/trash.php` - Papierkorb Grid-Ansicht mit Restore und Delete
3. `public/folders.php` - Hauptseite mit Navigation zu Notes, Archive, Trash
4. `public/api/notes/status.php` - Notiz-Status ändern (active, archived, trashed)
5. `public/api/notes/delete.php` - Notiz permanent löschen
6. Editor: Dropdown zeigt nur relevante Aktionen je nach Status

### Suche - *27.4.*

1. `public/search.php` - Volltextsuche mit FTS5