CREATE VIEW Client_Info as
(select first_name, last_name, COALESCE(GROUP_CONCAT(distinct email),'-') as emails, COALESCE(GROUP_CONCAT(distinct phone), '-') as phones, COALESCE(GROUP_CONCAT(distinct description), '-') as registered_to
from ((Client natural left outer join Emails) natural left outer join Phones) natural left outer join (Registered natural join Service)
group by first_name, last_name, bracelet_UID);



-- TODO check this when we have added some data
create view ServiceSales as
select S.description as service_description, count(U.charge_amount) as sales_count, COALESCE(sum(U.charge_amount), 0) as sales_total
from Service as S left outer join Used as U on (S.service_ID = U.service_ID)
group by S.description;
