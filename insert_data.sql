--insert sample data 

-- CREATE TRIGGER courses_trigger
-- AFTER INSERT ON courses FOR EACH ROW
-- BEGIN
--     INSERT INTO sections_belong(cid,title) VALUES (new.cid, new.title);
-- END;

INSERT INTO courses(cid, title, mtees_req, mtors_req) VALUES ('0','database1','junior','junior');
INSERT INTO sections_belong(cid,title,sec_id,name,startDate,endDate,capacity) VALUES ('0','database1','000','DB','2019-03-21','2019-03-22','5');