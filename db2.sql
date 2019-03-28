-- usage: mysql -u root DB2 < db2.sql
drop table if exists textUsed;
drop table if exists post;
drop table if exists timeSlot;
drop table if exists participate;
drop table if exists moderate;
drop table if exists teach;
drop table if exists enroll;
drop table if exists studyMaterials;
drop table if exists sessions;
drop table if exists sections_belong;
drop table if exists courses;
drop table if exists moderators;
drop table if exists custody;
drop table if exists mentees;
drop table if exists mentors;
drop table if exists students;
drop table if exists parents;
drop table if exists users;

create table users (
	uid int NOT NULL AUTO_INCREMENT,
	username char(50),
	password char(20),
	name char(20),
	emailAddress char (50),
	phoneNumber char(10),
	primary key (uid) 
);
ALTER TABLE users AUTO_INCREMENT = 01534400;

create table students (
	stu_id int,
	grade char (10),
	primary key (stu_id),
	constraint students_id foreign key (stu_id) references users(uid) ON UPDATE CASCADE ON DELETE CASCADE
);

create table parents (
	par_id int,
	primary key (par_id),
	constraint parents_id foreign key (par_id) references users(uid) ON UPDATE CASCADE ON DELETE CASCADE
);

create table custody (
	par_id int,
	stu_id int,
	primary key (par_id, stu_id),
	constraint custody_pid foreign key (par_id) references parents(par_id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint custody_sid foreign key (stu_id) references students(stu_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table mentees (
	mtee_id int,
	primary key (mtee_id),
	foreign key (mtee_id) references students(stu_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table mentors (
	mtor_id int,
	primary key (mtor_id),
	constraint mentors_id foreign key (mtor_id) references students(stu_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table moderators (
	mdtor_id int,
	primary key (mdtor_id),
	constraint moderators_id foreign key (mdtor_id) references parents(par_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table courses (
	cid int,
	title char(20),
	description text,
	mtees_req int,
	mtors_req int,
	primary key (cid, title)
);

create table sections_belong (
	cid int,
	title char(20),
	sec_id int,
	name char(20),
	startDate DATE,
	endDate DATE,
	capacity int,
	slot_id int NOT NULL UNIQUE,
	primary key (cid, title, sec_id),
	constraint sections_course foreign key (cid, title) references courses(cid, title) ON UPDATE CASCADE ON DELETE CASCADE
);

create table sessions (
	cid int,
	title char(20),
	sec_id int,
	ses_id int,
	name char(20),
	announcement text,
	date date,
	primary key(cid,title,sec_id,ses_id),
	constraint sessions_section foreign key (cid, title, sec_id) references sections_belong(cid, title, sec_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table enroll (
	cid int,
	title char(20),
	sec_id int,
	mtee_id int,
	constraint enroll_sec_id foreign key (cid, title, sec_id) references sections_belong(cid, title, sec_id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint enroll_mtee_id foreign key (mtee_id) references mentees(mtee_id) ON UPDATE CASCADE ON DELETE CASCADE
);
create table teach (
	cid int,
	title char(20),
	sec_id int,
	mtor_id int,
	constraint teach_section_id foreign key (cid, title, sec_id) references sections_belong(cid, title, sec_id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint teach_mtor_id foreign key (mtor_id) references mentors(mtor_id) ON UPDATE CASCADE ON DELETE CASCADE
);
create table moderate (
	cid int,
	title char(20),
	mdtor_id int,
	sec_id int,
	constraint moderate_sec_id foreign key (cid, title, sec_id) references sections_belong(cid, title, sec_id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint  moderate_mdtor_id foreign key (mdtor_id) references moderators(mdtor_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table participate (
	stu_id int,
	cid int,
	title char(20),
	sec_id int,
	ses_id int,
	date date
	-- constraint participate_sec_ses foreign key (cid, title, sec_id, ses_id) references sessions(cid, title, sec_id, ses_id),
	-- constraint participate_stu foreign key (stu_id) references students(stu_id) ON DELETE CASCADE
);

create table studyMaterials (
	sm_id int primary key,
	title char(20),
	author char(30),
	type char(20),
	URL char(50),
	assignedDate DATE,
	notes text
);

create table timeSlot (
	slot_id int,
	startTime time,
	endTime time,
	weekDay char(10),
	primary key (slot_id),
	foreign key (slot_id) references sections_belong(slot_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table textUsed (
	cid int,
	title char(20),
	sec_id int,
	ses_id int,
	sm_id int,
	primary key(cid, title, sec_id, ses_id, sm_id),
	constraint textUsed_session foreign key (cid, title, sec_id, ses_id) references sessions(cid, title, sec_id, ses_id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint textUsed_studyMaterial foreign key (sm_id) references studyMaterials(sm_id) ON UPDATE CASCADE ON DELETE CASCADE
);

create table post (
	sm_id int,
	mdtor_id int,
	primary key (sm_id),
	constraint post_studyMaterial foreign key (sm_id) references studyMaterials(sm_id) ON UPDATE CASCADE ON DELETE CASCADE,
	constraint post_mdtor foreign key (mdtor_id) references moderators(mdtor_id) ON UPDATE CASCADE ON DELETE CASCADE
);

--Insert into courses sections 
INSERT INTO courses(cid, title, mtees_req, mtors_req) VALUES ('0','database1','1','1');
INSERT INTO sections_belong(cid,title,sec_id,name,startDate,endDate,capacity,slot_id) VALUES ('0','database1','100','100','2019-03-21','2019-03-22','9','0');
INSERT INTO timeSlot VALUES ('0','08:00:00','09:00:00','MWF');

INSERT INTO courses(cid, title, mtees_req, mtors_req) VALUES ('1','database2','1','1');
INSERT INTO sections_belong(cid,title,sec_id,name,startDate,endDate,capacity,slot_id) VALUES ('1','database2','101','101','2019-03-21','2019-03-22','9','1');
INSERT INTO timeSlot VALUES ('1','08:00:00','09:00:00','MWF');

INSERT INTO courses(cid, title, mtees_req, mtors_req) VALUES ('2','algorithm','3','3');
INSERT INTO sections_belong(cid,title,sec_id,name,startDate,endDate,capacity,slot_id) VALUES ('2','algorithm','102','102','2019-03-21','2019-03-22','9','2');
INSERT INTO timeSlot VALUES ('2','08:00:00','09:00:00','TTh');

INSERT INTO sessions VALUES ('0','database1','100','0','100-1','','2019-03-26');
INSERT INTO sessions VALUES ('0','database1','100','1','100-2','','2019-03-28');
INSERT INTO sessions VALUES ('1','database2','101','0','101-1','','2019-03-26');
INSERT INTO sessions VALUES ('1','database2','101','1','101-2','','2019-03-28');
INSERT INTO sessions VALUES ('2','algorithm','102','0','102-1','','2019-03-26');