\connect db

CREATE TABLE log_mock (
    id SERIAL PRIMARY KEY,
    log_timestamp TIMESTAMP DEFAULT NOW()
);
