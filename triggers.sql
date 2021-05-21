-- Cardinallity constraint of Provided_At relation towards Service
DELIMITER //
CREATE TRIGGER every_service_provided_check
AFTER DELETE ON Provided_At
FOR EACH ROW
IF (OLD.service_ID not in (select service_ID from Provided_At)) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Deletion not allowed. Every service must be provided in some place.';
END IF;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER check_if_delete_place_is_legal
BEFORE DELETE ON Place
FOR EACH ROW
BEGIN
    IF ((SELECT count(*) FROM Provided_At WHERE service_ID = (SELECT service_ID FROM Provided_At WHERE place_ID = OLD.ID)) = 1) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot delete this place. It is the last service room for a service';
    END IF;
END; //
DELIMITER ;

DELIMITER //
CREATE TRIGGER check_if_this_NotRoom_can_be_deleted
BEFORE DELETE ON NotRoom
FOR EACH ROW
BEGIN
    IF ((SELECT count(*) FROM Provided_At WHERE service_ID = (SELECT service_ID FROM Provided_At WHERE place_ID = OLD.ID)) = 1) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot delete this NotRoom. It is the last service room for a service';
    END IF;
END; //
DELIMITER ;


-- Do not add use to a service that a Client is not registered to
DELIMITER //
CREATE TRIGGER use_check_if_registered_insert
BEFORE INSERT ON Used
FOR EACH ROW
IF (
not exists(
     SELECT * FROM Registered WHERE bracelet_UID = NEW.bracelet_UID AND service_ID = NEW.service_ID
) AND not exists(
    SELECT * FROM Service_Without_Subscription WHERE service_ID = NEW.service_ID
)

) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Trying to add use for a service which the client is not registered to and its not a free/autoregister service';
ELSEIF (not exists(
    SELECT * FROM  Accessed WHERE bracelet_UID = NEW.bracelet_UID AND
        place_ID in (SELECT place_ID FROM Provided_At WHERE service_ID = NEW.service_ID)
        AND NEW.timestamp_of_use BETWEEN enter_timestamp AND exit_timestamp)
) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Trying to add use for a service which the client has not accessed';
END IF;
//
DELIMITER ;


DELIMITER //
CREATE TRIGGER use_check_if_registered_update
BEFORE UPDATE ON Used
FOR EACH ROW
BEGIN
IF (
not exists(
     SELECT * FROM Registered WHERE bracelet_UID = NEW.bracelet_UID AND service_ID = NEW.service_ID
) AND not exists(
    SELECT * FROM Service_Without_Subscription WHERE service_ID = NEW.service_ID
)

) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Trying to update use for a service which the client is not registered to and its not a free/autoregister service';
END IF;

IF (not exists(
    SELECT * FROM  Accessed WHERE bracelet_UID = NEW.bracelet_UID AND
        place_ID in (SELECT place_ID FROM Provided_At WHERE service_ID = NEW.service_ID)
        AND NEW.timestamp_of_use BETWEEN enter_timestamp AND exit_timestamp)
) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Trying to add use for a service which the client has not accessed';
END IF;
END; //
DELIMITER ;


-- ADD ACCESS to service rooms that provide the service that a client registered to
DELIMITER //
CREATE TRIGGER check_if_register_is_after_arrival
BEFORE INSERT ON Registered
FOR EACH ROW
BEGIN
    IF (exists (select * from Client as C where (C.bracelet_UID = NEW.bracelet_UID) AND (C.arrival_timestamp > NEW.registration_timestamp)) ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Registration prohibited. Registration time is ahead of client arrival time.';
    END IF;
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER check_if_access_is_allowed
BEFORE INSERT ON Accessed
FOR EACH ROW
BEGIN
    IF (NOT EXISTS (select * from Has_Access
                            where bracelet_UID = NEW.bracelet_UID
                            AND place_ID = NEW.place_ID
                            AND NEW.enter_timestamp >= from_timestamp
                            AND NEW.exit_timestamp <= to_timestamp)
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Access not allowed';
    END IF;

    IF (exists(
             SELECT * FROM Accessed as A WHERE A.bracelet_UID = NEW.bracelet_UID AND
             (
                 A.enter_timestamp <= NEW.exit_timestamp AND NEW.enter_timestamp <= A.exit_timestamp
             )
         )) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Client access is overlapping with an other access';
    END IF;
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER stop_update_on_access
BEFORE UPDATE ON Accessed
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'An Access entry cannot be updated. It can only be inserted or deleted';
END; //
DELIMITER ;

DELIMITER //
CREATE TRIGGER stop_update_on_registered
BEFORE UPDATE ON Registered
FOR EACH ROW
BEGIN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'A registered entry cannot be updated. It can only be inserted or deleted';
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER check_booked_rooms_insert
BEFORE INSERT ON Booked
FOR EACH ROW
BEGIN
IF (exists (select * from Client as C where (C.bracelet_UID = NEW.bracelet_UID) AND (C.arrival_timestamp > TIMESTAMP(NEW.from_date, "23:59:59"))) ) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot insert booking with from_date ahead of client arrival time.';
END IF;
-- Check for already booked rooms
IF (exists(
     SELECT * FROM Booked as A WHERE A.room_ID = NEW.room_ID AND
     (
         A.from_date <= NEW.to_date AND NEW.from_date <= A.to_date
     )
 )) THEN
    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Room already Booked for some of the days';
END IF;
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER check_booked_rooms_update
BEFORE UPDATE ON Booked
FOR EACH ROW
BEGIN
    IF (exists (select * from Client as C where (C.bracelet_UID = NEW.bracelet_UID) AND (C.arrival_timestamp > TIMESTAMP(NEW.from_date, "23:59:59"))) ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot insert booking with from_date ahead of client arrival time.';
    END IF;
    -- check if room already booked for some days
    IF (exists(
         SELECT * FROM Booked as A WHERE A.room_ID = NEW.room_ID AND
         (
             A.from_date <= NEW.to_date AND NEW.from_date <= A.to_date
         )
     )) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Room already Booked for some of the days';
    END IF;
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER remove_access_from_booked_room
AFTER DELETE ON Booked
FOR EACH ROW
BEGIN
    IF (OLD.bracelet_UID not in (select bracelet_UID from Booked)) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Deletion not allowed. Every client must have at least one room.';
    END IF;

    DELETE FROM Has_Access WHERE bracelet_UID = OLD.bracelet_UID AND place_ID = (SELECT corridor_id FROM Room WHERE ID = OLD.room_ID);
    DELETE FROM Has_Access WHERE bracelet_UID = OLD.bracelet_UID AND place_ID = OLD.room_ID;
END; //
DELIMITER ;


-- ADD ACCESS to booked room_ID
DELIMITER //
CREATE TRIGGER add_access_to_booked_room
AFTER INSERT ON Booked
FOR EACH ROW
BEGIN
    INSERT INTO Has_Access values (NEW.bracelet_UID, NEW.room_ID, TIMESTAMP(NEW.from_date), TIMESTAMP(NEW.to_date,'23:59:59'));
    INSERT INTO Has_Access values (NEW.bracelet_UID, (SELECT corridor_id FROM Room WHERE ID = NEW.room_ID), TIMESTAMP(NEW.from_date), TIMESTAMP(NEW.to_date,'23:59:59'));
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER update_access_to_booked_room_and_check_overlaping_days
AFTER UPDATE ON Booked
FOR EACH ROW
BEGIN
    UPDATE Has_Access SET bracelet_UID = NEW.bracelet_UID, place_ID = NEW.room_ID, from_timestamp = TIMESTAMP(NEW.from_date), to_timestamp = TIMESTAMP(NEW.to_date,'23:59:59') WHERE bracelet_UID = OLD.bracelet_UID AND place_ID = OLD.room_ID ;
    UPDATE Has_Access SET bracelet_UID = NEW.bracelet_UID, place_ID = NEW.room_ID, from_timestamp = TIMESTAMP(NEW.from_date), to_timestamp = TIMESTAMP(NEW.to_date,'23:59:59') WHERE bracelet_UID = OLD.bracelet_UID AND place_ID = (SELECT corridor_id FROM Room WHERE ID = NEW.room_ID);
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER get_access_to_all_free_services
AFTER INSERT ON Client
FOR EACH ROW
BEGIN
FOR element IN
(
select ID
from Place
inner join Provided_At on Place.ID = Provided_At.place_ID
inner join Service using (service_ID)
inner join Service_Without_Subscription using (service_ID)
)
DO
INSERT INTO Has_Access (bracelet_UID, place_ID, from_timestamp)
VALUES (NEW.bracelet_UID, element.ID, NEW.arrival_timestamp);
END FOR;

INSERT INTO Has_Access (bracelet_UID, place_ID, from_timestamp)
(SELECT NEW.bracelet_UID, ID, NEW.arrival_timestamp from Place
where name = 'Χώρος υποδοχής' OR name = 'Ανελκυστήρες');

END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER add_access_to_service_room_on_register
AFTER INSERT ON Registered
FOR EACH ROW
BEGIN
    INSERT INTO Has_Access(bracelet_UID, place_ID, from_timestamp) (select NEW.bracelet_UID, place_ID, NEW.registration_timestamp from Service natural join Provided_At where service_ID = NEW.service_ID);
END; //
DELIMITER ;


DELIMITER //
CREATE TRIGGER remove_access_from_service_room_on_unregister
AFTER DELETE ON Registered
FOR EACH ROW
BEGIN
    DELETE FROM Has_Access WHERE (bracelet_UID, place_ID) in (select OLD.bracelet_UID, place_ID from Service natural join Provided_At where service_ID = OLD.service_ID);
END; //
DELIMITER ;


-- ISA if specilization gets deleted so does the Generalization
DELIMITER //
CREATE TRIGGER isa_delete_gen_service_with_subscription
AFTER DELETE ON Service_With_Subscription
FOR EACH ROW
DELETE FROM Service WHERE Service.service_ID = OLD.service_ID;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER isa_delete_gen_service_without_subscription
AFTER DELETE ON Service_Without_Subscription
FOR EACH ROW
DELETE FROM Service WHERE Service.service_ID = OLD.service_ID;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER isa_delete_gen_place_room
AFTER DELETE ON Room
FOR EACH ROW
DELETE FROM Place WHERE Place.ID = OLD.ID;
//
DELIMITER ;

DELIMITER //
CREATE TRIGGER isa_delete_gen_place_not_room
AFTER DELETE ON NotRoom
FOR EACH ROW
DELETE FROM Place WHERE Place.ID = OLD.ID;
//
DELIMITER ;

-- ISA triggers done
