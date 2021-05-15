

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
