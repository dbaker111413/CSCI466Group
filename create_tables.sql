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
drop table if exists Therapy;
drop table if exists Performs;
drop table if exists Therapist;
drop table if exists Patient;
drop table if exists Appointment;
drop table if exists Exercise;


## now create the tables
create table Therapist(
  TID int primary key AUTO_INCREMENT,
  tFirstName varchar(12),
  tLastName varchar(12),
  numPatients int,
  phone varchar(12)
);

create table Patient(
  PID int primary key AUTO_INCREMENT,
  pFirstName varchar(12),
  pLastName varchar(12)
);

create table Appointment(
  AID int primary key AUTO_INCREMENT,
  apptDate date,
  apptTime time
);

create table Therapy(
  TID int,
  PID int,
  AID int,
  primary key(TID, PID, AID),
  foreign key(TID) references Therapist(TID),
  foreign key(PID) references Patient(PID),
  foreign key(AID) references Appointment(AID)
);

create table Exercise(
  EID int primary key AUTO_INCREMENT,
  bodyPart varchar(20),
  bandColor varchar(8),
  numReps int
);

create table Has(
  EID int,
  AID int,
  primary key(EID, AID),
  foreign key(EID) references Exercise(EID),
  foreign key(AID) references Appointment(AID)
);

create table Performs(
  PID int,
  EID int,
  primary key(PID, EID),
  foreign key(PID) references Patient(PID),
  foreign key(EID) references Exercise(EID)
);
