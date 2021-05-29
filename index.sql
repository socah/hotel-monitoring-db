


-- Χρησιμοποιούνται από το ερώτημα 7, ανάλογος όμως και από την επιλογή των κριτηρίων.
CREATE INDEX Use_Time ON Used(timestamp_of_use); -- Δεν χρησιμοποιείται στο ερώτημα 11 αν και θα ήταν μάλλον λογικό
CREATE INDEX Use_Charge ON Used(charge_amount);


-- Χρησιμοποιείται από το trigger κατά τον έλεγχο αν επικαλύπτεται η κράτηση με άλλη κράτηση του ίδιου δωματίου
CREATE INDEX Book_Room_From ON Booked(room_ID, from_date);


-- TODO: αφαίρεσε το
-- Χρησιμοποιείται στα triggers όταν ελέγχουμε για overlap
-- Μας φαίνεται λογικό επίσης να χρησιμοποιείται στα ερωτήματα για το interface αλλά δεν τα χρησιμοποιεί η βάση (ερώτημα 10)
CREATE INDEX Acc_Enter ON Accessed(enter_timestamp); -- Δεν χρησιμοποιείτε ούτε από το overlap νομίζω 25/5/2021
