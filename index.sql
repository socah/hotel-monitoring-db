


CREATE INDEX Use_Time ON Used(timestamp_of_use); -- Δεν χρησιμοποιείται στο ερώτημα 11 αν και θα ήταν μάλλον λογικό
CREATE INDEX Use_Charge ON Used(charge_amount);

-- Χρησιμοποιείται στα triggers όταν ελέγχουμε για overlap
-- Μας φαίνεται λογικό επίσης να χρησιμοποιείται στα ερωτήματα για το interface αλλά δεν τα χρησιμοποιεί η βάση (ερώτημα 10)
CREATE INDEX Acc_Enter ON Accessed(enter_timestamp); 
