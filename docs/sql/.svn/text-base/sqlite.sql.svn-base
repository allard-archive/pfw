create table pfhosts (	id 			integer primary key unique, 
								name 			varchar(32) unique, 
								connect 		varchar(128), 
								master 		varchar(32),
								can_connect boolean default false,
								created_at 	date, 
								updated_at 	date);

CREATE TRIGGER update_created_time AFTER  INSERT ON pfhosts
BEGIN

UPDATE pfhosts SET created_at = DATETIME('NOW'), updated_at = DATETIME('NOW', 'localtime')
	WHERE rowid = new.rowid;

END;

CREATE TRIGGER update_updated_time AFTER UPDATE ON pfhosts
BEGIN

UPDATE pfhosts SET updated_at = DATETIME('NOW', 'localtime')
	WHERE rowid = old.rowid;

END;

insert into pfhosts (name, connect, can_connect) values ('localhost', 'localhost', 'true');
