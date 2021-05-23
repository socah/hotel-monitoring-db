

-- Ερώτημα 7

SELECT S.description, U.bracelet_UID, U.timestamp_of_use, U.charge_amount
FROM Used as U join Service as S on (U.service_ID = S.service_ID) 
WHERE
	U.timestamp_of_use >= XXXXXX -- Χρησιμοποιούνται οι συνθήκες αυτές κατά περίπτωση
AND U.timestamp_of_use <= XXXXXXX
AND U.charge_amount >= XXXXXXX
AND U.charge_amount <= XXXXXXX
AND S.service_ID = XXXXXXX

-- Ερώτημα 8 β
-- Tα views ορίζονται στο αρχείο views.sql

SELECT * from Client_Info;
SELECT * from ServiceSales;

-- Ερώτημα 9

SELECT P.description_position, A.enter_timestamp, A.exit_timestamp
FROM Accessed as A join Place as P on (A.place_ID = P.ID)
WHERE bracelet_UID = XXXXX;

-- Ερώτημα 10

select bracelet_UID, first_name, last_name
from Client
where bracelet_UID in
(select Other.bracelet_UID
from (SELECT bracelet_UID, place_ID, enter_timestamp, TIMESTAMPADD(HOUR,1,exit_timestamp) as up_to FROM Accessed WHERE bracelet_UID = XXXXX) as ILL, Accessed as Other
where Other.place_ID = ILL.place_ID and (
    (ILL.enter_timestamp <= Other.exit_timestamp and Other.enter_timestamp <= ILL.up_to)
) and ILL.bracelet_UID <> Other.bracelet_UID);


-- Ερώτημα 11

-- Πιο πολυσύχναστοι χώροι
select description_position as description, count(*) as access_count
from (Client as C natural join Accessed as A) join (Place as P natural join NotRoom) on (P.ID = A.place_ID)

where

exit_timestamp >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)
exit_timestamp >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)

AND

C.age BETWEEN 20 AND 40
C.age BETWEEN 41 AND 60
C.age >= 61

group by place_ID
ORDER BY 'access_count' DESC


-- Συχνότερα χρησιμοποιούμενες υπηρεσίες
select S.description as description, count(*) as service_use_count
from Client as C natural join (Used as U join Service as S on (U.service_ID = S.service_ID))
where

U.timestamp_of_use >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)
U.timestamp_of_use >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)

AND

C.age BETWEEN 20 AND 40
C.age BETWEEN 41 AND 60
C.age >= 61

group by U.service_ID
ORDER BY 'service_use_count' DESC


-- Υπηρεσίες που χρησιμοποιούνται από τους περισσότερους πελάτες
select S.description as description, count(distinct U.bracelet_UID ) as client_count
from (Client as C natural join (Used as U join Service as S on (U.service_ID = S.service_ID)))

where

U.timestamp_of_use >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)
U.timestamp_of_use >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)

AND

C.age BETWEEN 20 AND 40
C.age BETWEEN 41 AND 60
C.age >= 61

group by S.service_ID
ORDER BY 'client_count' DESC
