\connect db

INSERT INTO patient (fc, name, emergency_phone, blood_type, allergies)
VALUES
    ('1234567890123456', 'John Doe', '555-123-4567', 'A+', 'water,salt'),
    ('9876543210987654', 'Jane Smith', '555-987-6543', 'B-', 'air'),
    ('1111222233334444', 'Alice Johnson', '555-111-2222', 'O+', 'wood');

-- Insert sample doctor data
INSERT INTO doctor (name, specialization)
VALUES
    ('Dr. Smith', 'Cardiologist'),
    ('Dr. Johnson', 'Orthopedic Surgeon'),
    ('Dr. Brown', 'Dermatologist');

-- Insert sample messages
INSERT INTO message (content, patient_id, doctor_id, created_at)
VALUES
    ('Hello, doctor. I have been experiencing chest pain lately.', 1, 1, NOW() - INTERVAL '2 days'),
    ('Hi there! I need an appointment for my knee pain.', 2, 2, NOW() - INTERVAL '1 day'),
    ('Can you prescribe a medication for my skin condition?', 3, 3, NOW());

