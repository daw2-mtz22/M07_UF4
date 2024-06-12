CREATE DATABASE twitter;

\c twitter
CREATE TABLE tweets (
                        id SERIAL PRIMARY KEY,
                        url VARCHAR(255) NOT NULL,
                        author_name VARCHAR(255) NOT NULL,
                        author_url VARCHAR(255) NOT NULL,
                        html TEXT NOT NULL
);