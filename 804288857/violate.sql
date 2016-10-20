INSERT INTO Movie
VALUES (-3, "Hello World", 2017, "GG", "Googol");
/* fails because we cannot have a negative id. */

INSERT INTO Movie
VALUES (272, "Foobar", 2018, "GP-31", "Nile");
/* fails because 272 is already the id of "Baby Take a Bow" */

INSERT INTO Actor
VALUES(-5, "Einstein", "Albert", "M", 1234-12-12, 1234-12-13);
/* id attribute is negative */

INSERT INTO Actor
VALUES (1, "Moran", "Antonio", 1996-06-26, NULL);
/* fails because 1 is already the id of "Isabelle A". */

INSERT INTO Director
VALUES (-1, "Chung", "Aaron", 1995-03-06, NULL);
/* id attribute is negative */

INSERT INTO Director
VALUES (16, "Smith", "John", 1234-05-06, 7890-12-31);
/* fails because 16 is already the id of "Willie Aames". */

INSERT INTO MovieDirector
VALUES(15, -1);
/* fails because there is no way that -1 can be a valid director id (all director ids must be nonnegative) */

INSERT INTO MovieDirector
VALUES(-1, 15);
/* fails because there is no way that an id of -1 can exist in the Movie table (all movie ids must be nonnegative) */

INSERT INTO MovieActor
VALUES(15, -1);
/* fails because there is no way that an id of -1 can exist in the Actor table (all actor ids must be nonnegative) */

INSERT INTO MovieActor
VALUES(-1, 15);
/* fails because there is no way that an id of -1 can exist in the Movie table (all movie ids must be nonnegative) */

INSERT INTO Review
VALUES("yung antonio", 6969-04-20, -1, "I love folkloriko!!!!!");
/* fails because there is no way that an id of -1 can exist in the Movie table (all movie ids must be nonnegative) */

INSERT INTO MovieGenre
VALUES(1, "Feels bad man");
/* fails because there is no way that an id of -1 can exist in the Movie table (all movie ids must be nonnegative) */

INSERT INTO MaxPersonID
VALUES(-1);
/* fails because max person id must be nonnegative */
    
INSERT INTO MaxMovieID
VALUES(-1);
/* fails because max movie id must be nonnegative */
