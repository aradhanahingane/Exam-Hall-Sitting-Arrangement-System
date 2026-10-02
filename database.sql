create database halldb;

use halldb;

create table students
(
	rollno int(6) primary key,
	student_name varchar(50),
	semester varchar(20),
	mobno varchar(15)
);

create table subjects
(
	semester varchar(20),
	subject_code varchar(20) primary key,
	subject_name varchar(50),
	paper_date date,
	paper_time varchar(50),
	unique(semester, subject_code, subject_name, paper_date, paper_time)
);

create table sitting_arrangement
(
	rollno int(6),
	student_name varchar(50),
	semester varchar(20),
	subject_name varchar(50),
	hall_name_location varchar(100),
	sitting_position varchar(20),
	paper_date date,
	paper_time varchar(50),
	unique(rollno, student_name, semester, subject_name, hall_name_location, sitting_position)
);









