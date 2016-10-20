SELECT title
FROM Movie
WHERE rating="PG";
/* this query returns the title of all PG movies */

SELECT first, last
FROM Actor
WHERE id < 10;
/* this query returns the names of all actors with id < 10 */

SELECT first, last
FROM Director
WHERE id <> 10;
/* this query returns the names of all directors with id not equal to 10 */