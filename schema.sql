-- Create app user
CREATE USER noted_user;
GRANT CONNECT ON DATABASE noted TO noted_user;
-- Password set manually via `ALTER USER noted_user WITH PASSWORD '...';`

-- Users
CREATE TABLE users (
	id SERIAL PRIMARY KEY,
	email VARCHAR(255) NOT NULL UNIQUE,
	password_hash VARCHAR(255) NOT NULL,
	created_at TIMESTAMP DEFAULT NOW()
);

-- Note status enum
CREATE TYPE note_status AS ENUM ('active', 'archived', 'trashed');

-- Notes
CREATE TABLE notes (
	id SERIAL PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
	title VARCHAR(255) NOT NULL DEFAULT 'Untitled',
	content TEXT DEFAULT '',
	status note_status NOT NULL DEFAULT 'active',
	created_at TIMESTAMP DEFAULT NOW(),
	updated_at TIMESTAMP DEFAULT NOW()
);

-- Full text search index
CREATE INDEX notes_fts_idx ON notes USING GIN (to_tsvector('simple', title || ' ' || content));

-- Tags
CREATE TABLE tags (
	id SERIAL PRIMARY KEY,
	user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
	name VARCHAR(100) NOT NULL,
	UNIQUE (user_id, name)
);

-- Note <-> Tag (n-m)
CREATE TABLE note_tags (
	note_id INTEGER NOT NULL REFERENCES notes(id) ON DELETE CASCADE,
	tag_id INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
	PRIMARY KEY (note_id, tag_id)
);

-- Grant sequence permissions to noted_user
GRANT USAGE, SELECT, UPDATE ON ALL SEQUENCES IN SCHEMA public TO noted_user;