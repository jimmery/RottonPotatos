 CREATE TABLE Movie(
    id int PRIMARY KEY,
    title varchar(100),
    year int,
    rating varchar(10),
    company varchar(50),
	CHECK (id <= MaxMovieID && id >= 0)
 );
 
 CREATE TABLE Actor(
    id int PRIMARY KEY,
    last varchar(20),
    first varchar(20),
    sex varchar(6),
    dob date,
    dod date,
	CHECK (id <= MaxPersonID.id && id >= 0)
 );
 
 CREATE TABLE Director(
    id int PRIMARY KEY,
    last varchar(20),
    first varchar(20),
    dob date,
    dod date,
	CHECK (id <= MaxPersonID.id && id >= 0)
 );
 
 
 CREATE TABLE MovieDirector(
    mid int,
    did int,
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (did) REFERENCES Director(id) ON UPDATE CASCADE ON DELETE CASCADE
 );
 
 CREATE TABLE MovieActor(
    mid int,
    aid int,
    role varchar(50),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE,
	FOREIGN KEY (aid) REFERENCES Actor(id) ON UPDATE CASCADE ON DELETE CASCADE
 );
 
 
 /*project 1c stuff
 ==============================*/
 CREATE TABLE Review(
    name varchar(20),
    time timestamp,
    mid int,
    rating int,
    comment varchar(500),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE
 );
 
 CREATE TABLE MaxPersonID(
    id int,
	CHECK (id >= 0)
 );
 
CREATE TABLE MaxMovieID(
    id int,
	CHECK (id >= 0)
);
/* end 1c stuff
===============================*/
 
  CREATE TABLE MovieGenre(
    mid int, 
    genre varchar(20),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE,
	CHECK (mid < MaxMovieID.id && mid >= 0)
 ); 
