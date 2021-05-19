-- 1) add domain restrictions
-- 2) add other constaints όπως λεει η εκφώνηση


CREATE TABLE Client (
  bracelet_UID      int unsigned zerofill,
  first_name        varchar(20) not null,
  last_name         varchar(20) not null,
  id_type           enum ('ID', 'Passport', 'None') default 'None' not null,
  id_number         varchar(15), -- children may not have a ID
  id_authority      varchar(50),
  arrival_timestamp timestamp, -- if set null then the current time is set, no point in not null
  birth_date        date not null,
  age int GENERATED ALWAYS AS (FLOOR(DATEDIFF(NOW(), birth_date) / 365.25)) VIRTUAL,

  constraint ID_NULL_ERROR check ( (id_type = 'None' and id_number is Null and id_authority is NULL)
                                or (id_type != 'None' and id_number is not Null and id_authority is not NULL)),
  primary key (bracelet_UID)
);

-- MULTIVALUED BEGIN
CREATE TABLE Phones (
    bracelet_UID  int unsigned zerofill,
    phone         varchar(15), -- e.g. +30 69..... it is text  -- not null not needed because it is in the primary key

    primary key (bracelet_UID, phone),
    foreign key (bracelet_UID) references Client(bracelet_UID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Emails (
    bracelet_UID  int unsigned zerofill,
    email         varchar(35), -- not null not needed because it is in the primary key

    constraint INVALID_EMAIL check(email like '%@%.%'),
    primary key (bracelet_UID, email),
    foreign key (bracelet_UID) references Client(bracelet_UID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
-- MULTIVALUED END

-- SERVICE ISA BEGIN
CREATE TABLE Service (
    service_ID int unsigned AUTO_INCREMENT, -- not null not needed because it is in the primary key
    description varchar(50) not null, -- thinking of making it a enum

    unique (description),
    primary key (service_ID)
);

CREATE TABLE Service_With_Subscription (
    service_ID int unsigned,  -- not null not needed because it is in the primary key

    primary key (service_ID),
    foreign key  (service_ID) references Service(service_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Service_Without_Subscription (
    service_ID int unsigned,  -- not null not needed because it is in the primary key

    primary key (service_ID),
    foreign key  (service_ID) references Service(service_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
-- SERVICE ISA END

-- ROOM ISA BEGIN
CREATE TABLE Place (
    ID int unsigned AUTO_INCREMENT, -- not null not needed because it is in the primary key
    name varchar(35) not null,  -- maybe it is not needed
    description_position varchar(60) not null,

    primary key (ID)
);

CREATE TABLE NotRoom (
    ID int unsigned, -- not null not needed because it is in the primary key

    primary key (ID),
    foreign key  (ID) references Place(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE Room (
    ID int unsigned, -- not null not needed because it is in the primary key
    bed_count tinyint unsigned not null,
    corridor_ID int unsigned not null,

    constraint TOO_MANY_BEDS check (bed_count < 6),
    primary key (ID),
    foreign key  (ID) references Place(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    foreign key (corridor_ID) references NotRoom(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
-- ROOM ISA END

-- RELATIONS TABLES BEGIN
CREATE TABLE Registered (
  bracelet_UID            int unsigned zerofill, -- not null not needed because it is in the primary key
  service_ID              int unsigned, -- not null not needed because it is in the primary key
  registration_timestamp  timestamp, -- if set null then the current time is set, no point in not null

  primary key (bracelet_UID, service_ID),
  foreign key (bracelet_UID) references Client (bracelet_UID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  foreign key (service_ID) references Service_With_Subscription (service_ID)
    ON DELETE RESTRICT -- if at least one is registered, then a service cannot be deleted
    ON UPDATE CASCADE
);

CREATE TABLE Booked (
  bracelet_UID  int unsigned zerofill, -- not null not needed because it is in the primary key
  room_ID       int unsigned, -- not null not needed because it is in the primary key
  charge_amount numeric(6,2) unsigned not null,
  from_date     date not null,
  to_date       date not null,

  constraint FROM_TO_DATE_ERROR check(from_date <= to_date),
  primary key (bracelet_UID, room_ID),  -- One client can book a specific room once
  foreign key (bracelet_UID) references Client (bracelet_UID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  foreign key (room_ID) references Room (ID)
    ON DELETE RESTRICT -- if someone has the room booked then you cannot delete it
    ON UPDATE CASCADE
);

-- cbeck that the room is not booked already
-- with check this does not work
-- ALTER TABLE Booked ADD constraint ALREADY_BOOKED_FOR_SUM_OF_THE_DAYS check( not exists(
--      SELECT * FROM Booked as A WHERE A.room_ID = room_ID AND
--      (
--          (A.from_date BETWEEN from_date AND to_date) OR
--          (A.to_date BETWEEN from_date AND to_date)
--      )
--  ));

CREATE TABLE Accessed (
    bracelet_UID    int unsigned zerofill, -- not null not needed because it is in the primary key
    place_ID        int unsigned, -- not null not needed because it is in the primary key
    enter_timestamp timestamp,
    exit_timestamp  timestamp,

    constraint ENTER_EXIT_TIMESTAMP_ERROR check (enter_timestamp < exit_timestamp),
    primary key (bracelet_UID, place_ID, enter_timestamp),
    foreign key (bracelet_UID) references Client(bracelet_UID)
        ON DELETE RESTRICT -- you have to delete the Accesses first, before you delete a client. Coronavirus Measure
        ON UPDATE CASCADE,
    foreign key (place_ID) references Place(ID)
        ON DELETE RESTRICT -- you have to delete the Accesses first, before you any room.  Coronavirus Measure
        ON UPDATE CASCADE
);

CREATE TABLE Has_Access (
  bracelet_UID    int unsigned zerofill, -- not null not needed because it is in the primary key
  place_ID        int unsigned, -- not null not needed because it is in the primary key
  from_timestamp  timestamp,
  to_timestamp    timestamp default '2038-01-19 03:14:07', -- if not defined set to max value, this works with comparisons too.

  constraint FROM_TO_TIMESTAMP_ERROR check(from_timestamp < to_timestamp),
  primary key (bracelet_UID, place_ID, from_timestamp),
  foreign key (bracelet_UID) references Client (bracelet_UID)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  foreign key (place_ID) references Place(ID)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);


CREATE TABLE Provided_At (
    service_ID int unsigned not null,
    place_ID int unsigned, -- not null not needed because it is in the primary key

    primary key (place_ID),
    foreign key (service_ID) references Service(service_ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE,
    foreign key (place_ID) references NotRoom(ID)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);

CREATE TABLE Used (
  bracelet_UID      int unsigned zerofill, -- not null not needed because it is in the primary key
  service_ID        int unsigned, -- not null not needed because it is in the primary key
  timestamp_of_use  timestamp,
  charge_amount     numeric(6, 2) unsigned,
  description       varchar(150), -- can be null

  primary key (bracelet_UID, service_ID, timestamp_of_use),
  foreign key (bracelet_UID) references Client (bracelet_UID)
      ON DELETE CASCADE
      ON UPDATE CASCADE,
  foreign key (service_ID) references Service (service_ID)
    ON DELETE RESTRICT
    ON UPDATE CASCADE
);
-- RELATIONS TABLES END
