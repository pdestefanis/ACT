 -- Store chagnes to database here
 alter table stats add column modified datetime;
 -- generic patient
 insert into patients (number, consent) values ('P999999', 1);
 -- add comment field
 alter table units add column comment varchar(255);
 -- soft deleted for units
 alter table units add column deleted int(1) default 0;
  alter table units add column deleted_date datetime;
 -- clean up
 drop table modifiers;
 -- changes in deifinition of shortname
  alter table locations modify column shortname varchar(7) not null;
 -- enable inodb if not enabled in my.ini and convert all tables to inodb
 ALTER TABLE user_roles ENGINE=InnoDB;
ALTER TABLE users ENGINE=InnoDB;
ALTER TABLE units_items ENGINE=InnoDB;
ALTER TABLE units ENGINE=InnoDB;
ALTER TABLE treatments ENGINE=InnoDB;
ALTER TABLE tracks ENGINE=InnoDB;
ALTER TABLE statuses ENGINE=InnoDB;
ALTER TABLE stats ENGINE=InnoDB;
ALTER TABLE roles ENGINE=InnoDB;
ALTER TABLE rest_logs ENGINE=InnoDB;
ALTER TABLE rawreports ENGINE=InnoDB;
ALTER TABLE quantities ENGINE=InnoDB;
ALTER TABLE phones ENGINE=InnoDB;
ALTER TABLE patients ENGINE=InnoDB;
ALTER TABLE messagesents ENGINE=InnoDB;
ALTER TABLE messagereceiveds ENGINE=InnoDB;
ALTER TABLE locations ENGINE=InnoDB;
ALTER TABLE levels ENGINE=InnoDB;
ALTER TABLE kittypes ENGINE=InnoDB;
ALTER TABLE kits ENGINE=InnoDB;
ALTER TABLE items ENGINE=InnoDB;
ALTER TABLE groups ENGINE=InnoDB;
ALTER TABLE drugs_treatments ENGINE=InnoDB;
ALTER TABLE drugs_kittypes ENGINE=InnoDB;
ALTER TABLE drugs ENGINE=InnoDB;
ALTER TABLE batches ENGINE=InnoDB;
ALTER TABLE aros_acos ENGINE=InnoDB;
ALTER TABLE aros ENGINE=InnoDB;
ALTER TABLE approvals_stats ENGINE=InnoDB;
ALTER TABLE approvals ENGINE=InnoDB; 