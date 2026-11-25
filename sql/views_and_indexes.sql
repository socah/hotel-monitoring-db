

-- **************************************************  VIEWS **************************************************

--  View με τα στοιχεία του πελάτη
-- Αυτό το view επιστρέφει :
-- τo όνομα, το επώνυμο, την ηλικία, όλα τα email του πελάτη, όλα τα τηλέφωνα του πελάτη και όλες τις υπηρεσίες στις οποίες έχει γραφθεί
-- Το view Client_Info δεν είναι ενημερώσιμο λόγω των aggregations COALESCE
CREATE VIEW Client_Info as
(select first_name, last_name, age, COALESCE(GROUP_CONCAT(distinct email),'-') as emails, COALESCE(GROUP_CONCAT(distinct phone), '-') as phones, COALESCE(GROUP_CONCAT(distinct description), '-') as registered_to
from ((Client natural left outer join Emails) natural left outer join Phones) natural left outer join (Registered natural join Service)
group by first_name, last_name, bracelet_UID, age);
-- το age είναι στο group by όπως και το first_name και το last_name ώστε να μπορούμε να τα βάλουμε ως στήλες του τελικού πίνακα


-- View για της υπηρεσίες
-- Αυτό το view "επιστρέφει" έναν πίνακα με μία γραμμή για κάθε υπηρεσία με στήλες οι οποίες περιέχουν:
-- την περιγραφή της υπηρεσίας, το πλήθος των πωλήσεων που έχουν γίνει για την υπηρεσία αυτή (χρήσεις), τα συνολικά έσοδα από την υπηρεσία αυτή
-- Το View ServiceSales δεν είναι ενημερώσιμο λόγω των aggregations COALESCE
create view ServiceSales as
select S.description as service_description, count(U.charge_amount) as sales_count, COALESCE(sum(U.charge_amount), 0) as sales_total
from Service as S left outer join Used as U on (S.service_ID = U.service_ID)
group by S.description;




-- ************************************************** INDEXES **************************************************





-- Χρησιμοποιούνται από το ερώτημα 7, αναλόγως όμως και από την επιλογή των κριτηρίων.
-- Δείτε παραπάνω για τα indexes αυτά στην αναφορά στο μέρος 1b
CREATE INDEX Use_Time ON Used(timestamp_of_use);
CREATE INDEX Use_Charge ON Used(charge_amount);


-- Χρησιμοποιείται από το trigger κατά τον έλεγχο αν επικαλύπτεται η κράτηση με άλλη κράτηση του ίδιου δωματίου
-- Δείτε παραπάνω για το index αυτό στην αναφορά στο μέρος 1b
CREATE INDEX Book_Room_From ON Booked(room_ID, from_date);

