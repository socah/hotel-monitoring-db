

-- Ερώτημα 8 β
-- create view ServiceSales as
-- select S.description as service_description, count(U.charge_amount) as sales_count, COALESCE(sum(U.charge_amount), 0) as sales_total
-- from Service as S left outer join Used as U on (S.service_ID = U.service_ID)
-- group by S.description;

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


select S.description as description, count(distinct U.bracelet_UID ) as client_count
from (Client as C natural join (Used as U join Service as S on (U.service_ID = S.service_ID)))

where

U.timestapm_of_use >= TIMESTAMPADD(MONTH,-1,CURRENT_TIMESTAMP)
U.timestamp_of_use >= TIMESTAMPADD(YEAR,-1,CURRENT_TIMESTAMP)

AND

C.age BETWEEN 20 AND 40
C.age BETWEEN 41 AND 60
C.age >= 61

group by S.service_ID
ORDER BY 'client_count' DESC
