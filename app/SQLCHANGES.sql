 --Store chagnes to database here
 alter table stats add column modified datetime;
 --generic patient
 insert into patients (number, consent) values (999999, 1);
 --add comment field
 alter table units add column comment varchar(255);