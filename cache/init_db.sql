-- cache/init_db.sql

CREATE TABLE IF NOT EXISTS logs (
  id        INTEGER PRIMARY KEY AUTOINCREMENT,
  filename  TEXT    NOT NULL UNIQUE,
  mtime     INTEGER NOT NULL,
  size      INTEGER NOT NULL,
  html      TEXT    DEFAULT NULL,
  updated   INTEGER NOT NULL
);
CREATE INDEX IF NOT EXISTS idx_logs_mtime ON logs(mtime DESC);
