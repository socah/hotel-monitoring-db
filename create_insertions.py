import sys
import string
import random
from random import randrange
from datetime import datetime, timedelta, date


# names.txt, surnames.txt, cities.txt
table_clients = [];
table_phones = [];
table_emails = [];

# Service ISA
table_service = [];
table_service_with_sub = [];
table_service_without_sub = [];

# Place ISA
table_place = [];
table_not_room = [];
table_room = [];


# relations
table_booked = [];
table_provided_at = [];
table_registered = [];
table_accessed = [];
table_used = [];

random.seed(42);

def create_places():

    # Place = (ID, Name, Description_Postition)
    # NotRoom = (ID)
    # Room = (ID, bed_count, corridor_ID)


    # ------------------------------ ADDING MISC PLACES ------------------------------
    table_place.append( (0, "Χώρος υποδοχής", "Χώρος υποδοχής στο ισόγειο") );
    table_not_room.append((0,));

    # Ανελκυστήρες
    offset = len(table_place);
    for i in range(0, 5):
        table_place.append( (i + offset, "Ανελκυστήρες", "Ανελκυστήρας #{}".format(i+1)) );
        table_not_room.append((i + offset,));

    # ------------------------------ ADDING SERVICE ROOMS ------------------------------
    # μπαρ
    offset = len(table_place);
    for i in range(0, 6):
        table_place.append( (i+offset, "Μπαρ", "Μπαρ #{} στο ισόγειο".format(i+1)) );
        table_not_room.append((i+offset,));

    # εστιατόρια
    offset = len(table_place);
    for i in range(0, 4):
        table_place.append( (i+offset, "Εστιατόριο", "Εστιατόριο #{} στο ισόγειο".format(i+1)) );
        table_not_room.append((i+offset,));

    # αίθουσες εργασίας συνεδριάσεων
    offset = len(table_place);
    for i in range(0, 10):
        table_place.append( (i+offset, "Αίθουσα εργασίας/συνεδριάσεων", "Αίθουσα εργασίας/συνεδριάσεων #{} στο ισόγειο".format(i+1)) );
        table_not_room.append((i+offset,));

    # γυμναστήρια
    offset = len(table_place);
    for i in range(0, 4):
        table_place.append( (i+offset, "Γυμναστήριο", "Γυμναστήριο #{} στο ισόγειο".format(i+1)) );
        table_not_room.append((i+offset,));

    # σάουνες
    offset = len(table_place);
    for i in range(0, 10):
        table_place.append( (i+offset, "Σάουνα", "Σάουνα #{} στο ισόγειο".format(i+1)) );
        table_not_room.append((i+offset,));

    # κομμωτήριο
    offset = len(table_place);
    for i in range(0, 1):
        table_place.append( (i+offset, "Κομμωτήριο", "Κομμωτήριο #{} στο ισόγειο".format(i+1)) );
        table_not_room.append((i+offset,));

    # ------------------------------ ADDING Coorridors ------------------------------

    cooridor_IDs = [[-1, -1, -1, -1], [-1, -1, -1, -1], [-1, -1, -1, -1], [-1, -1, -1, -1], [-1, -1, -1, -1]];
    direction = ["Βόρειος", "Ανατολικός", "Νότιος", "Δυτικός"];

    for floor in range(1, 5+1):
        for dir in range(len(direction)):
            ID = len(table_place);
            table_place.append( (ID, "Διάδρομος", "{} διάδρομος στον όροφο {}".format(direction[dir], floor)) );
            table_not_room.append((ID,));
            cooridor_IDs[floor-1][dir] = ID;

    # ------------------------------ ADDING Rooms ------------------------------

    direction = ["Βόρειο", "Ανατολικό", "Νότιο", "Δυτικό"];

    room_number = 1;
    for floor in range(1, 5+1):
        for dir in range(len(direction)):
            for count in range(20):
                ID = len(table_place);
                table_place.append( (ID, "Δωμάτιο", "Δωμάτιο #{:03} στον όροφο {} στον {}".format(room_number, floor, direction[dir])) );
                table_room.append( (ID, random.randint(1, 5), cooridor_IDs[floor-1][dir]) );
                room_number = room_number + 1;

    # ------------------------------ GENERATE THE QUERIES ------------------------------

    print("INSERT INTO Place VALUES");
    print(*table_place, sep=",\n", end=";\n\n\n\n");

    print("INSERT INTO NotRoom VALUES");
    print(* [ ("(" + str(id[0]) + ")") for id in table_not_room] , sep=",\n", end=";\n\n\n\n");

    print("INSERT INTO Room VALUES");
    print(*table_room, sep=",\n", end=";\n\n\n\n");


def create_services():

    # without subscription
    table_service.append( (0, "σερβίρισμα ποτού στο μπαρ") );
    table_service_without_sub.append( (0,) );

    table_service.append( (1, "σερβίρισμα φαγητού-ποτού στο εστιατόριο") );
    table_service_without_sub.append( (1,) );

    table_service.append( (2, "υπηρεσίες κομμωτηρίου") );
    table_service_without_sub.append( (2,) );

    # with subscription
    table_service.append( (3, "χρήση γυμναστηρίου") );
    table_service_with_sub.append( (3,) );

    table_service.append( (4, "χρήση σάουνας") );
    table_service_with_sub.append( (4,) );

    table_service.append( (5, "χρήση αίθουσας συνεδρίασης") );
    table_service_with_sub.append( (5,) );

    print("INSERT INTO Service VALUES");
    print(*table_service, sep=",\n", end=";\n\n\n");

    print("INSERT INTO Service_With_Subscription VALUES");
    print(* [ ("(" + str(id[0]) + ")") for id in table_service_with_sub] , sep=",\n", end=";\n\n\n\n");

    print("INSERT INTO Service_Without_Subscription VALUES");
    print(* [ ("(" + str(id[0]) + ")") for id in table_service_without_sub] , sep=",\n", end=";\n\n\n\n");

def create_provided_at():

    # μπαρ

    service_room_name = ["Μπαρ",  "Εστιατόριο", "Κομμωτήριο",
            "Αίθουσα εργασίας/συνεδριάσεων",  "Γυμναστήριο",
            "Σάουνα"];
    service_name = ["σερβίρισμα ποτού στο μπαρ",
            "σερβίρισμα φαγητού-ποτού στο εστιατόριο",
            "υπηρεσίες κομμωτηρίου",
            "χρήση αίθουσας συνεδρίασης",
            "χρήση γυμναστηρίου",
            "χρήση σάουνας"];

    service_room_count = [6, 4, 1, 10, 4, 10];


    for i in range(len(service_name)):
        service_id = [serv[0] for serv in table_service if serv[1] == service_name[i]][0];
        room_ids = [service_room[0] for service_room in table_not_room if table_place[service_room[0]][1] == service_room_name[i]];

        if (len(room_ids) != service_room_count[i]):
            raise "count of " + service_room_name + " should be " + str(service_room_count[i]);

        for room_id in room_ids:
            table_provided_at.append( (service_id, room_id) );

    print("INSERT INTO Provided_At VALUES");
    print(*table_provided_at, sep=",\n", end=";\n\n\n");


def get_datetime(string):
    #print("get date", string);
    return datetime.strptime(string, '%Y-%m-%d %H:%M:%S');

def get_date(string):
    #print("get date", string);
    return datetime.strptime(string, '%Y-%m-%d').date();

def date_to_datetime(DATE, h, m, s):
    #print("get date", string);
    return datetime(DATE.year, DATE.month, DATE.day, h, m, s);


# return a tuple of valid (room_ID, from_date, to_date)
def find_reservation(from_date, duration_min, duration_max):

    room_IDS = [room[0] for room in table_place if room[1] == "Δωμάτιο"];

    def overlaping(from_date, to_date, bookings_for_specific_room):
        for other_from, other_to in bookings_for_specific_room:
            cond1 = (other_from <= from_date and from_date <= other_to);
            cond2 = (other_from <= to_date and to_date <= other_to);
            cond3 = (from_date <= other_from and other_to <= to_date);
            #print("reserve", (from_date, to_date), "   ", (cond1, cond2, cond3))
            if cond1 or cond2 or cond3:
                    return True;
        return False;


    for _ in range(100):

        room_ID = random.choice(room_IDS);

        bookings_for_specific_room = [(get_date(booked[3]), get_date(booked[4])) for booked in table_booked if booked[1] == room_ID];

        duration = random.randint(duration_min, duration_max);
        to_date = from_date + timedelta(days=duration);
        #print("alreadY-reserved ", bookings_for_specific_room);
        #print("trying ", from_date, to_date, overlaping(from_date, to_date, bookings_for_specific_room));
        if(not overlaping(from_date, to_date, bookings_for_specific_room)):
            return (room_ID, from_date, to_date);

    raise "Did not get lucky enough";


def create_booked():
    # Booked = (bracelet_ID, room_ID, charge, from_date, to_date)

    for client in table_clients:
        number_of_rooms = random.choice([1] * 10 + [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 2, 2, 2, 2, 2, 2, 3, 4]);

        for i in range(number_of_rooms):
            arrival_date = datetime.strptime(client[6], '%Y-%m-%d %H:%M:%S').date();

            if i == 0: # for the first room book from arrival moment
                desired_from_date = arrival_date;
            else:
                desired_from_date = random_date(arrival_date, arrival_date + timedelta(days=10));

            # find a valid reservation and make it
            room_ID, from_date, to_date = find_reservation(desired_from_date, 0, 10);
            cost_per_day = random.uniform(50, 200);
            charge = ((to_date - from_date).days + 1) * cost_per_day;
            table_booked.append( (client[0], room_ID, charge, str(from_date), str(to_date)) );

    print("INSERT INTO Booked VALUES");
    print(*table_booked, sep=",\n", end=";\n\n\n");
    #for booking in table_booked:
    #    print("INSERT INTO Booked VALUES ", booking, ";");


def create_registered():

    # Registered = (bracelet_ID, Service_ID, Registration_timestamp)

    service_ids = [serv[0] for serv in table_service_with_sub];

    for client in table_clients:
        if random.randint(0, 1) == 0: # 50% chance not to register to anything
                arrival_timestamp = datetime.strptime(client[6], '%Y-%m-%d %H:%M:%S');
                last_booked_timestamp = max([get_date(booking[4]) for booking in table_booked if booking[0] == client[0]])
                last_booked_timestamp = date_to_datetime(last_booked_timestamp, 23, 59, 59);

                bracelet_id = client[0];
                service_number = random.randint(0, len(service_ids));
                chosen_service_ids = random.sample(service_ids, service_number);

                for serv_id in chosen_service_ids:
                    reg_timestamp = random_date(arrival_timestamp, last_booked_timestamp);

                    table_registered.append( (bracelet_id, serv_id, str(reg_timestamp) ) );

    print("INSERT INTO Registered VALUES");
    print(*table_registered, sep=",\n", end=";\n\n\n");


def check_access_overlap(bracelet_UID, place_ID, enter_timestamp, exit_timestamp):

    accesses_for_specific_client = [(get_datetime(acc[2]), get_datetime(acc[3])) for acc in table_accessed if acc[0] == bracelet_UID];

    for other_from, other_to in accesses_for_specific_client:
        cond1 = (other_from <= enter_timestamp and enter_timestamp <= other_to);
        cond2 = (other_from <= exit_timestamp and exit_timestamp <= other_to);
        cond3 = (enter_timestamp <= other_from and other_to <= exit_timestamp);
        #print("reserve", (from_date, to_date), "   ", (cond1, cond2, cond3))
        if cond1 or cond2 or cond3:
                return True;
    return False;


def client_has_access(bracelet_UID, place_ID, enter_timestamp, exit_timestamp):
    if(place_ID < 0): raise "Error";
    if (place_ID <= 15 or place_ID == 40): return True;

    rooms = [booking for booking in table_booked if booking[0] == bracelet_UID and enter_timestamp >= date_to_datetime(get_date(booking[3]), 0, 0, 0) and exit_timestamp <= date_to_datetime(get_date(booking[4]), 23, 59, 59)];
    room_ids = [r[1] for r in rooms];
    cooridor_ids = [r[2] for r in table_room if r[0] in room_ids];

    registered_services_ids = [reg[1] for reg in table_registered if reg[0] == bracelet_UID and enter_timestamp >= get_datetime(reg[2])];
    service_room_ids = [prov[1] for prov in table_provided_at if prov[0] in registered_services_ids];

    cond1 = place_ID in room_ids;
    cond2 = place_ID in cooridor_ids;
    cond3 = place_ID in service_room_ids;


    return cond1 or cond2 or cond3;

def client_has_access_to(bracelet_UID):

    rooms = [booking for booking in table_booked if booking[0] == bracelet_UID];
    room_ids = [r[1] for r in rooms];
    cooridor_ids = [r[2] for r in table_room if r[0] in room_ids];

    registered_services_ids = [reg[1] for reg in table_registered if reg[0] == bracelet_UID];
    service_room_ids = [prov[1] for prov in table_provided_at if prov[0] in registered_services_ids];

    return cooridor_ids + service_room_ids + [40] + [i for i in range(16)]; # excpet rooms

def create_accessed():

    # Accessed = (bracelet_UID, place_ID, enter_timestamp, exit_timestamp)

    # add accesses to rooms

    for booking in table_booked:
        bracelet_UID = booking[0];
        place_ID = booking[1];
        client = [client for client in table_clients if client[0] == bracelet_UID][0];
        booking_from_date = max(get_datetime(client[6]), date_to_datetime(get_date(booking[3]), 0, 0, 0));
        booking_to_date = get_date(booking[4]);


        booking_to_date = date_to_datetime(booking_to_date, 23, 59, 59);

        days = (booking_to_date - booking_from_date).days + 1;

        if((booking_to_date - booking_from_date).total_seconds()/(60 * 60) < 16): continue;


        for _ in range( random.randint(int(days/3), 2 * days) ):

            for _ in range(100): # try 100 times to insert one
                enter_timestamp = random_date(booking_from_date, booking_to_date - timedelta(hours=15));
                exit_timestamp = random_date(enter_timestamp, min(booking_to_date, enter_timestamp + timedelta(hours=14)));

                if not check_access_overlap(bracelet_UID, place_ID, (enter_timestamp), (exit_timestamp)):
                    table_accessed.append( (bracelet_UID, place_ID, str(enter_timestamp), str(exit_timestamp)) );
                    break;

    # add accesses to service_rooms

    
    for client in table_clients:

        bracelet_UID = client[0];
        #print(bracelet_UID);

        client_stay_from_datetime = get_datetime(client[6]);
        client_stay_to_datetime = max([get_date(booking[4]) for booking in table_booked if booking[0] == client[0]]);
        client_stay_to_datetime = date_to_datetime(client_stay_to_datetime, 23, 59, 59);

        if((client_stay_to_datetime - client_stay_from_datetime).total_seconds()/(60 * 60) < 6): continue;

        #print("ID", client[0], "stay", client_stay_from_datetime, " ", client_stay_to_datetime);

        max_rooms_accesses = ((client_stay_to_datetime - client_stay_from_datetime).days + 1) * 3; 

        #print("MAX", max_rooms_accesses, " days", max_rooms_accesses/5);

        for _ in range( random.randint(int(max_rooms_accesses/5), max_rooms_accesses) ):
            place_ID = random.choice(client_has_access_to(bracelet_UID));
            #place_ID = not_room[0];

            for _ in range(300): # try 100 times to insert one
                enter_timestamp = random_date(client_stay_from_datetime, client_stay_to_datetime - timedelta(hours=5));
                exit_timestamp = random_date(enter_timestamp, min(client_stay_to_datetime, enter_timestamp + timedelta(hours=4)));

                if client_has_access(bracelet_UID, place_ID, enter_timestamp, exit_timestamp) and (not check_access_overlap(bracelet_UID, place_ID, (enter_timestamp), (exit_timestamp))):
                    table_accessed.append( (bracelet_UID, place_ID, str(enter_timestamp), str(exit_timestamp)) );
                    break;
                    



    print("INSERT INTO Accessed VALUES");
    print(*table_accessed, sep=",\n", end=";\n\n\n");
    #for acc in table_accessed:
    #    print("INSERT INTO Accessed VALUES", acc, ";");


def create_used():

    descriptions = [
            ["Αγορά μπύρας", "Αγορά Cocktail", "Αγορά Αναψυκτικού", "Αγορά κρασιού"],
            ["Παραγγελία Μακαρόνια με κιμά", "Παραγγελία στιφάδο", "Παραγγελία παστίτσιο", "Παραγγελία πίτσα", "ΣΟYΒΛΑΚΙΑ", "Παραγγελία Αναψυκτικού", "Παραγγελία Ποτού"],
            ["Χρήση αίθουσας για συνεδρίαση", "Χρήση χώρου για εργασία"],
            ["Χρήση διαδρόμου", "Χρήση ελλειπτικού", "Χρήση ελεύθερων βαρών"],
            ["Χρήση σάουνας"],
            ["Κούρεμα", "Χτένισμα", "Βάψιμο μαλλιών", "Ίσιωμα μαλλιών"]
            ];

    reasonalbe_use_count = [5, 5, 2, 2, 1, 2];

    charge_range = [(4, 40), (5, 50), (30, 50), (5, 20), (10, 20), (10, 100)];

    service_id = [0, 1, 5, 3, 4, 2];

    mapping = {6: 0, 7:0, 8:0, 9:0, 10:0, 11:0,
                12:1, 13:1, 14:1, 15:1,
                16:2, 17:2, 18:2, 19:2, 20:2, 21:2, 22:2, 23:2, 24:2, 25:2,
                26:3, 27:3, 28:3, 29:3,
                30:4, 31:4, 32:4, 33:4, 34:4, 35:4, 36:4, 37:4, 38:4, 39:4,
                40:5};

    for access in table_accessed:

        if(access[1] >= 6 and access[1] <= 40): # if it is a service room

            mapping_number = mapping[access[1]];

            for _ in range(  random.randint(0, reasonalbe_use_count[mapping_number]) ):

                desc = random.choice(descriptions[mapping_number]);

                enter_time = get_datetime(access[2]);
                exit_time = get_datetime(access[3]);

                time_of_use = random_date(enter_time, exit_time);

                bracelet_UID = access[0];
                min_charge, max_charge = charge_range[mapping_number];
                charge_amount = random.randint(min_charge, max_charge);

                
                table_used.append( (bracelet_UID, service_id[mapping_number], str(time_of_use), charge_amount, desc) );


    print("INSERT INTO Used  VALUES")
    print(*table_used, sep=",\n", end=";\n\n\n\n");

            




def random_date(start, end):
    if(end < start):
        print(start, "   - - -- ", end);
        raise "Err";
    """
    This function will return a random datetime between two datetime
    objects.
    """
    delta = end - start
    int_delta = (delta.days * 24 * 60 * 60) + delta.seconds
    random_second = randrange(int_delta)
    return start + timedelta(seconds=random_second)



def print_with_nulls(tuple_array):
    big_string = ""
    for tuple in tuple_array:
        string = "(";
        for element in list(tuple):
            if element == None:
                string += "NULL, ";
            else:
                string += "'" + str(element) + "'" + ", ";
        string = string[:-2] + "),\n";
        big_string += string;

    print(big_string[:-2], ";\n\n\n\n", sep="");


def create_clients(client_count):
    id_types = ["ID", "ID", "ID", "ID", "ID", "ID", "ID", "ID",
                "Passport", "Passport", "Passport", "Passport", "Passport",
                "None"];
    child_id_types = ["Passport", "Passport", "Passport", "None"];
    ids = random.sample(range(0, client_count), client_count);
    with open ("names.txt") as f:
        names = f.read().splitlines();
    with open ("surnames.txt") as f:
        surnames = f.read().splitlines();
    with open ("cities.txt") as f:
        cities = f.read().splitlines();

    d1 = datetime.strptime('1/1/1947 1:01 AM', '%d/%m/%Y %I:%M %p');
    d2 = datetime.strptime('10/5/2020 1:01 AM', '%d/%m/%Y %I:%M %p');

    d1_arrival = datetime.strptime('1/1/2020 1:01 AM', '%d/%m/%Y %I:%M %p');
    d2_arrival = datetime.strptime('10/5/2021 1:01 AM', '%d/%m/%Y %I:%M %p');


    for i in range(0, client_count):
        client_id_type = "ID";
        date_of_birth = str(random_date(d1, d2))[:10];
        arrival_timestamp = str(random_date(d1_arrival, d2_arrival));

        if int(date_of_birth[:4]) > 2006:
            client_id_type = random.choice(child_id_types);
        else:
            client_id_type = random.choice(id_types);

        if client_id_type == "ID":
            authority_type = " Police Department";
        elif client_id_type == "Passport":
            authority_type = " Document Center";


        if client_id_type == "None":
            table_clients.append( (i, random.choice(names), random.choice(surnames), client_id_type, None, None, arrival_timestamp, date_of_birth) );
        else:
            table_clients.append( (i, random.choice(names), random.choice(surnames), client_id_type, ids[i], random.choice(cities) + authority_type, arrival_timestamp, date_of_birth) );

    # get timestamp back with datetime.strptime(str(d), '%Y-%m-%d %H:%M:%S')

    print("INSERT INTO Client(bracelet_UID, first_name, last_name, id_type, id_number, id_authority, arrival_timestamp, birth_date)  VALUES")
    print_with_nulls(table_clients);

def create_phones(phone_number_count):
    # a client may have two  with different country codes but ok
    # Phone = (ID, Phone)

    for _ in range(phone_number_count):
        county_code = random.randint(0, 50);
        three_dig = random.randint(0, 9999999999);

        phone = "+{:02} {:010}".format(county_code, three_dig);
        ID = random.choice(table_clients)[0];

        table_phones.append( (ID, phone) );

    print("INSERT INTO Phones VALUES");
    print(*table_phones, sep=",\n", end=";\n\n\n\n");


def create_emails(email_count):
    # Emails = (ID, email)

    letters = string.ascii_lowercase;

    for _ in range(email_count):
        username = ''.join([random.choice(letters) for _ in range(random.randint(5, 9))]);
        domain = ''.join([random.choice(letters) for _ in range(random.randint(5, 9))]);
        ext = random.choice(["com", "gr", "co.uk", "io", "xyz"]);

        email = username + "@" + domain + "." + ext;
        ID = random.choice(table_clients)[0];

        table_emails.append( (ID, email) );

    print("INSERT INTO Emails VALUES");
    print(*table_emails, sep=",\n", end=";\n\n\n\n");



create_places();
create_services();
create_provided_at();

create_clients(30);
create_phones(30);
create_emails(30);

create_booked();
create_registered();

create_accessed();
create_used();
