 CREATE TABLE Movie(
    id int PRIMARY KEY, /* each movie id should be unique */
    title varchar(100),
    year int,
    rating varchar(10),
    company varchar(50),
	CHECK (id <= MaxMovieID && id >= 0) /* movie id cannot be greater than maxmovieid, but it must not be negative */
 );
 
 CREATE TABLE Actor(
    id int PRIMARY KEY /* each actor id should be unique */,
    last varchar(20),
    first varchar(20),
    sex varchar(6),
    dob date,
    dod date,
	CHECK (id <= MaxPersonID.id && id >= 0) /* actor id cannot be greater than maxpersonid, but it must not be negative */
 );
 
 CREATE TABLE Director(
    id int PRIMARY KEY,
    last varchar(20),
    first varchar(20),
    dob date,
    dod date,
	CHECK (id <= MaxPersonID.id && id >= 0) /* director id cannot be greater than maxpersonid, but it must not be negative */
 );
 
 
 CREATE TABLE MovieDirector(
    mid int,
    did int,
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE, /* if the movie id gets updated or removed, 
																				then this entry should 
																				also be deleted/updated accordingly.
																				Also, the movieid that we are referring to
																				in this entry must exist in the Movie table */
	FOREIGN KEY (did) REFERENCES Director(id) ON UPDATE CASCADE ON DELETE CASCADE /* if the director's id gets updated or removed
																					then this entry should also get updated/delted
																					accordingly.
																					Also, the director id that we are referring to
																					in this entry must exist in the Director table */
 );
 
 CREATE TABLE MovieActor(
    mid int,
    aid int,
    role varchar(50),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE, /* if the movie id gets updated or removed, 
																				then this entry should 
																				also be deleted/updated accordingly.
																				Also, the movieid that we are referring to
																				in this entry must exist in the Movie table */
	FOREIGN KEY (aid) REFERENCES Actor(id) ON UPDATE CASCADE ON DELETE CASCADE /* if the actor's id gets updated or removed
																					then this entry should also get updated/delted
																					accordingly.
																					Also, the actor id that we are referring to
																					in this entry must exist in the Actor table */
 );
 
 
 /*project 1c stuff
 ==============================*/
 CREATE TABLE Review(
    name varchar(20),
    time timestamp,
    mid int,
    rating int,
    comment varchar(500),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE /* The movie being reviewed must exist in the Movie
																					table. Also, if we remove/update the movie
																					id in the Movie table, it should be updated/removed
																					accordingly here*/
 );
 
 CREATE TABLE MaxPersonID(
    id int,
	CHECK (id >= 0) /* Max id cannot be negative */
 );
 
CREATE TABLE MaxMovieID(
    id int,
	CHECK (id >= 0) /* Max id cannot be negative */
);
/* end 1c stuff
===============================*/
 
  CREATE TABLE MovieGenre(
    mid int, 
    genre varchar(20),
	FOREIGN KEY (mid) REFERENCES Movie(id) ON UPDATE CASCADE ON DELETE CASCADE /* The movie id must exist in the Movie
																					table. Also, if we remove/update the movie
																					id in the Movie table, it should be updated/removed
																					accordingly here*/
	); 
