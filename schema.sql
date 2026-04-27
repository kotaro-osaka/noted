-- Users
CREATE TABLE
	users (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		email TEXT NOT NULL UNIQUE,
		password_hash TEXT NOT NULL,
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP
	);

-- Notes
CREATE TABLE
	notes (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
		title TEXT NOT NULL DEFAULT 'Untitled',
		content TEXT DEFAULT '',
		status TEXT NOT NULL DEFAULT 'active' CHECK (status IN ('active', 'archived', 'trashed')),
		created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
		updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
	);

-- Full text search (FTS5)
CREATE VIRTUAL TABLE notes_fts USING fts5 (
	title,
	content
);

-- Tags
CREATE TABLE
	tags (
		id INTEGER PRIMARY KEY AUTOINCREMENT,
		user_id INTEGER NOT NULL REFERENCES users (id) ON DELETE CASCADE,
		name TEXT NOT NULL,
		UNIQUE (user_id, name)
	);

-- Note <-> Tag (n-m)
CREATE TABLE
	note_tags (
		note_id INTEGER NOT NULL REFERENCES notes (id) ON DELETE CASCADE,
		tag_id INTEGER NOT NULL REFERENCES tags (id) ON DELETE CASCADE,
		PRIMARY KEY (note_id, tag_id)
	);

-- FTS triggers
CREATE TRIGGER notes_fts_insert AFTER INSERT ON notes BEGIN
	INSERT INTO notes_fts(rowid, title, content) VALUES (new.id, new.title, new.content);
END;

CREATE TRIGGER notes_fts_update AFTER UPDATE ON notes BEGIN
	DELETE FROM notes_fts WHERE rowid = old.id;
	INSERT INTO notes_fts(rowid, title, content) VALUES (new.id, new.title, new.content);
END;

CREATE TRIGGER notes_fts_delete AFTER DELETE ON notes BEGIN
	DELETE FROM notes_fts WHERE rowid = old.id;
END;