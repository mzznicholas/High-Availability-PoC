\connect db

CREATE TABLE patient (
     id serial PRIMARY KEY,
     fc varchar(16) UNIQUE,
     name varchar(255),
     emergency_phone varchar(255),
     blood_type varchar(10),
     allergies varchar(255) DEFAULT 'N/A'
);

CREATE TABLE doctor (
    id serial PRIMARY KEY,
    name varchar(255),
    specialization varchar(255)
);

CREATE TABLE message (
     id serial PRIMARY KEY,
     content text,
     patient_id int REFERENCES patient(id),
     doctor_id int REFERENCES doctor(id),
     created_at timestamp DEFAULT CURRENT_TIMESTAMP

);
