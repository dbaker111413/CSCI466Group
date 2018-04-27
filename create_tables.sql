################################################
#
#  Owners: Daniel Baker, Jackie Salim, Tanner Martin, & Kevin Tee
#  CSCI 466
#  Spring 2018
#
#  Group Project # 8
#
#  Purpose of file: Script to create database tables
#
################################################

## drop tables if they exist before attempting to create them
drop table if exists Has;
drop table if exists Appointment;
drop table if exists Therapist;
drop table if exists Patient;
drop table if exists Exercise;


## now create the tables
create table Therapist(
  TID int primary key AUTO_INCREMENT,
  tFirstName varchar(20),
  tLastName varchar(20),
  numPatients int,
  phone varchar(12)
);

insert into Therapist (tFirstName, tLastName, numPatients, phone) values('Sigmund', 'Freud', 1, '123-456-7890');
insert into Therapist (tFirstName, tLastName, numPatients, phone) values('Pavlov', 'DolphinShtein', 3, '321-477-7890');
insert into Therapist (tFirstName, tLastName, numPatients, phone) values('Joseph', 'ShtalinShtein', 1, '999-876-7890');
insert into Therapist (tFirstName, tLastName, numPatients, phone) values('Principal', 'ShkinnerShtein', 0, '567-765-7890');

create table Patient(
  PID int primary key AUTO_INCREMENT,
  pFirstName varchar(20),
  pLastName varchar(20)
);

insert into Patient (pFirstName, pLastName) values ('Kevin', 'Tee');
insert into Patient (pFirstName, pLastName) values ('Tanner', 'Martin');
insert into Patient (pFirstName, pLastName) values ('Jackie', 'Salim');
insert into Patient (pFirstName, pLastName) values ('Daniel', 'Baker');
insert into Patient (pFirstName, pLastName) values ('Roger', 'Huxley');
insert into Patient (pFirstName, pLastName) values ('Pavlov', 'DolphinShtien Jr.');

create table Has(
  TID int,
  PID int,
  primary key(TID, PID),
  foreign key(TID) references Therapist(TID),
  foreign Key(PID) references Patient(PID)
);

insert into Has (TID, PID) values (2, 6);
insert into Has (TID, PID) values (1, 1);
insert into Has (TID, PID) values (3, 2);
insert into Has (TID, PID) values (2, 3);
insert into Has (TID, PID) values (2, 4);

create table Exercise(
  EID int primary key AUTO_INCREMENT,
  bodyPart varchar(20),
  bandColor varchar(20),
  numReps int
);

insert into Exercise (bodyPart, bandColor, numReps) values ('Gluteous Maximi', 'Red', 9001); #1
insert into Exercise (bodyPart, bandColor, numReps) values ('Biceps', 'Green', 9002);        #2
insert into Exercise (bodyPart, bandColor, numReps) values ('Triceps', 'Blue', 10);          #3
insert into Exercise (bodyPart, bandColor, numReps) values ('Calves', 'Blue', 10);           #4
insert into Exercise (bodyPart, bandColor, numReps) values ('Erector Spinae', 'Yellow', 20); #5
insert into Exercise (bodyPart, bandColor, numReps) values ('Hamstring', 'Purple', 5);       #6
insert into Exercise (bodyPart, bandColor, numReps) values ('Shoulder', 'Aquamarine', 50);   #7
insert into Exercise (bodyPart, bandColor, numReps) values ('Wrist', 'Aquamarine', 20);      #8
insert into Exercise (bodyPart, bandColor, numReps) values ('Big Toe', 'Fuchsia', 9001);     #9

create table Appointment(
  AID int AUTO_INCREMENT,
  TID int,
  PID int,
  EID int,
  apptDate date,
  apptTime time,
  primary key(AID, TID, PID, EID),
  foreign key(TID) references Therapist(TID),
  foreign key(PID) references Patient(PID),
  foreign key(EID) references Exercise(EID)
);

insert into Appointment (TID, PID, EID, apptDate, apptTime) values (2, 6, 4, '2018-05-05', '12:00:00');
insert into Appointment (TID, PID, EID, apptDate, apptTime) values (1, 1, 9, '2018-05-07', '11:00:00');
insert into Appointment (TID, PID, EID, apptDate, apptTime) values (3, 2, 3, '2018-05-07', '11:30:00');
insert into Appointment (TID, PID, EID, apptDate, apptTime) values (2, 3, 4, '2018-05-07', '12:30:00');
insert into Appointment (TID, PID, EID, apptDate, apptTime) values (2, 4, 5, '2018-05-08', '02:30:00');

