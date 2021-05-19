INSERT INTO `Client` (`bracelet_UID`, `first_name`, `last_name`, `id_type`, `id_number`, `id_authority`, `arrival_timestamp`, `birth_date`)
VALUES ('40', 'John', 'Smith', 'ID', '123456', 'Unye Police Department', '2021-05-12 11:45:48', '2000-05-18');

INSERT INTO `Client` (`bracelet_UID`, `first_name`, `last_name`, `id_type`, `id_number`, `id_authority`, `arrival_timestamp`, `birth_date`)
VALUES ('41', 'John', 'Doe', 'ID', '111111', 'Al Qamishli Police Department', '2021-05-11 11:36:28', '2000-05-16');

INSERT INTO `Registered` (`bracelet_UID`, `service_ID`, `registration_timestamp`) VALUES ('0000000040', '3', '2021-05-17 11:46:13');

INSERT INTO `Registered` (`bracelet_UID`, `service_ID`, `registration_timestamp`) VALUES ('0000000041', '3', '2021-05-17 11:47:02');

INSERT INTO `Accessed` (`bracelet_UID`, `place_ID`, `enter_timestamp`, `exit_timestamp`) VALUES ('0000000040', '26', '2021-05-18 10:30:00', '2021-05-18 12:00:00');

INSERT INTO `Accessed` (`bracelet_UID`, `place_ID`, `enter_timestamp`, `exit_timestamp`) VALUES ('0000000041', '26', '2021-05-18 12:50:54', '2021-05-18 14:00:00');


INSERT INTO `Client` (`bracelet_UID`, `first_name`, `last_name`, `id_type`, `id_number`, `id_authority`, `arrival_timestamp`, `birth_date`)
VALUES ('42', 'Galileo', 'Galilei', 'ID', '654321', 'Siegen Police Department', '2021-05-10 11:45:48', '1999-05-18');

INSERT INTO `Client` (`bracelet_UID`, `first_name`, `last_name`, `id_type`, `id_number`, `id_authority`, `arrival_timestamp`, `birth_date`)
VALUES ('43', 'Johannes', 'Kepler', 'ID', '222222', 'Dondo Police Department', '2021-05-09 11:36:28', '1999-05-16');

INSERT INTO `Registered` (`bracelet_UID`, `service_ID`, `registration_timestamp`) VALUES ('0000000042', '5', '2021-05-16 11:46:13');

INSERT INTO `Registered` (`bracelet_UID`, `service_ID`, `registration_timestamp`) VALUES ('0000000043', '5', '2021-05-16 11:47:02');

INSERT INTO `Accessed` (`bracelet_UID`, `place_ID`, `enter_timestamp`, `exit_timestamp`) VALUES ('0000000042', '16', '2021-05-17 13:00:00', '2021-05-17 15:00:00');

INSERT INTO `Accessed` (`bracelet_UID`, `place_ID`, `enter_timestamp`, `exit_timestamp`) VALUES ('0000000043', '16', '2021-05-17 14:30:54', '2021-05-17 16:00:00');
