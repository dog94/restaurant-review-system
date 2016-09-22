{\rtf1\ansi\ansicpg1252\cocoartf1344\cocoasubrtf720
{\fonttbl\f0\fswiss\fcharset0 Helvetica;}
{\colortbl;\red255\green255\blue255;}
\margl1440\margr1440\vieww10800\viewh8400\viewkind0
\pard\tx566\tx1133\tx1700\tx2267\tx2834\tx3401\tx3968\tx4535\tx5102\tx5669\tx6236\tx6803\pardirnatural

\f0\fs24 \cf0 drop table Orders;\
drop table Delivery_Order;\
drop table Reservation;\
drop table Review;\
drop table Users;\
drop table Food;\
drop table Tables;\
drop table Restaurant;\
\
CREATE TABLE Restaurant \
	(restName	CHAR(30),\
	restAddress	CHAR(50),\
	cuisineType CHAR(20),\
	phoneNumber	CHAR(12),\
	hours 		CHAR(17),\
	PRIMARY KEY (restName, restAddress ));\
\
CREATE TABLE Tables\
	(restName 	CHAR(30),\
	restAddress CHAR(50),\
	tableNumber INTEGER,\
	numOfSeats	INTEGER,\
	availability	INTEGER,\
	PRIMARY KEY (restName, restAddress, tableNumber),\
	FOREIGN KEY (restName, restAddress) REFERENCES Restaurant(restName, restAddress)\
		ON DELETE CASCADE);	\
		\
CREATE TABLE Food \
	(restName		CHAR(30),\
	restAddress		CHAR(50),\
	foodName 		CHAR(30),\
	price 			REAL,\
	description		CHAR(50),\
	numOfTimesOrdered	INTEGER,\
	PRIMARY KEY (restName, restAddress, foodName),\
	FOREIGN KEY (restName, restAddress) REFERENCES Restaurant(restName, restAddress)\
		ON DELETE CASCADE);\
		\
CREATE TABLE Users\
	(username	CHAR(20),\
	firstName	CHAR(20),\
	lastName	CHAR(20),\
	address		CHAR(50),\
	phoneNumber	CHAR(12),\
	email		CHAR(30),\
	password	CHAR(20),\
	PRIMARY KEY (username),\
	UNIQUE (email));\
	\
CREATE TABLE Review \
	(reviewID	INTEGER,\
	restName 	CHAR(30),\
	restAddress CHAR(50),\
	username	CHAR(20),\
	rating 		REAL,\
	title 		CHAR(50),\
	comments 	CHAR(300),\
	PRIMARY KEY (reviewId),\
	FOREIGN KEY (restName, restAddress) REFERENCES Restaurant(restName, restAddress)\
		ON DELETE CASCADE,\
	FOREIGN KEY (username) REFERENCES Users(username)\
		ON DELETE CASCADE);\
\
CREATE TABLE Reservation \
	(confNumber	INTEGER,\
	fromTime	CHAR(10),\
	dateReserved	DATE,\
	numOfPeople	CHAR(20),\
	restName	CHAR(30),\
	restAddress	CHAR(50),\
	tableNumber	INTEGER,\
	username 	CHAR(20),\
	PRIMARY KEY (confNumber),\
	FOREIGN KEY (restName, restAddress, tableNumber) REFERENCES Tables(restName, restAddress, tableNumber)\
		ON DELETE CASCADE,\
	FOREIGN KEY (username) REFERENCES Users(username)\
		ON DELETE CASCADE);\
\
CREATE TABLE Delivery_Order\
	(orderNo	INTEGER,\
	deliveryAddr	CHAR(50),\
	orderTotal 	REAL,\
	numOfItems	INTEGER,\
	PRIMARY KEY (orderNo) );\
\
CREATE TABLE Orders\
	(restName 	CHAR(30),\
	restAddress	CHAR(50),\
	foodName	CHAR(30),\
	orderNo	INTEGER,\
	username	CHAR(20),\
	dateOrdered		DATE,\
	PRIMARY KEY (restName, restAddress, foodName, orderNo, username),\
	FOREIGN KEY (restName, restAddress, foodName) REFERENCES Food(restName, restAddress, foodName)\
		ON DELETE CASCADE,\
	FOREIGN KEY (username) REFERENCES Users(username)\
		ON DELETE CASCADE,\
	FOREIGN KEY (orderNo) REFERENCES Delivery_Order(orderNo)\
		ON DELETE CASCADE);\
\
		\
INSERT INTO Restaurant\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 'Mediterranean', '604-738-5487', '11:00am - 10:00pm');\
\
INSERT INTO Restaurant\
VALUES ('The Sandbar Restaurant', '1535 Johnston St, Vancouver, BC', 'Oceanian', '604-669-9030', '11:30am - 12:00am');\
\
INSERT INTO Restaurant\
VALUES ('Seasons in the Park', 'West 33rd Avenue and Cambie Street', 'Fusion',  '604-874-8008', '11:30am - 10:00pm');\
\
INSERT INTO Restaurant\
VALUES ('Bridges Restaurant', '1696 Duranleau St, Vancouver, BC', 'Oceanian', '604-687-4400', '11:00am - 10:00pm');\
\
INSERT INTO Restaurant\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 'Mediterranean', '604-222-1980', '11:00am - 10:00pm');\
\
INSERT INTO Restaurant\
VALUES ('BANANA LEAF (FAIRVIEW)', '43005 W Broadway Vancouver, BC', 'Chinese', '604-222-1980', '10:00am - 11:00pm');\
\
INSERT INTO Tables\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 1, 3-4, 0);\
\
INSERT INTO Tables\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 2, 3-4, 1);\
\
INSERT INTO Tables\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 3, 1-2, 1);\
\
INSERT INTO Tables\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 4, 5-6, 1);\
\
INSERT INTO Tables\
VALUES ('The Sandbar Restaurant', '1535 Johnston St, Vancouver, BC', 1, 3-4, 1);\
\
INSERT INTO Tables\
VALUES ('The Sandbar Restaurant', '1535 Johnston St, Vancouver, BC', 2, 8, 1);\
\
INSERT INTO Tables\
VALUES ('The Sandbar Restaurant', '1535 Johnston St, Vancouver, BC', 3, 3-4, 1);\
\
INSERT INTO Tables\
VALUES ('Seasons in the Park', 'West 33rd Avenue and Cambie Street', 1,  2, 0);\
\
INSERT INTO Tables\
VALUES ('Seasons in the Park', 'West 33rd Avenue and Cambie Street', 2,  5-6, 1);\
\
INSERT INTO Tables\
VALUES ('Seasons in the Park', 'West 33rd Avenue and Cambie Street', 3,  3-4, 0);\
\
INSERT INTO Tables\
VALUES ('Seasons in the Park', 'West 33rd Avenue and Cambie Street', 4,  15, 0);\
\
INSERT INTO Tables\
VALUES ('Seasons in the Park', 'West 33rd Avenue and Cambie Street', 5,  1-2, 0);\
\
INSERT INTO Tables\
VALUES ('Bridges Restaurant', '1696 Duranleau St, Vancouver, BC', 1, 4, 1);\
\
INSERT INTO Tables\
VALUES ('Bridges Restaurant', '1696 Duranleau St, Vancouver, BC', 2, 4, 1);\
\
INSERT INTO Tables\
VALUES ('Bridges Restaurant', '1696 Duranleau St, Vancouver, BC', 3, 2, 1);\
\
INSERT INTO Tables\
VALUES ('Bridges Restaurant', '1696 Duranleau St, Vancouver, BC', 4, 2, 0);\
\
INSERT INTO Tables\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 1, 5, 1);\
\
INSERT INTO Tables\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 2, 2, 1);\
\
INSERT INTO Tables\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 3, 3-4, 1);\
\
INSERT INTO Tables\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 4, 6, 1);\
\
INSERT INTO Tables\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 5, 2, 1);\
\
\
INSERT INTO Tables\
VALUES ('BANANA LEAF (FAIRVIEW)', '3005 W Broadway Vancouver, BC', 3, 3-4, 1);\
\
INSERT INTO Tables\
VALUES ('BANANA LEAF (FAIRVIEW)', '3005 W Broadway Vancouver, BC', 4, 6, 1);\
\
INSERT INTO Tables\
VALUES ('BANANA LEAF (FAIRVIEW)', '3005 W Broadway Vancouver, BC', 5, 2, 1);\
\
\
\
\
INSERT INTO Food\
VALUES ('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 'Calamari', 12.85, 'fried to golden brown', 115);\
\
INSERT INTO Food \
VALUES('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 'SOY CHICKEN SKEWERS', 8.50, 'Grilled chicken thighs served with spicy chilli mayo', 4);\
\
INSERT INTO Food  \
VALUES('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 'TIGER PRAWNS', 14.00, 'Prawns with marinated in somethin', 13);\
\
INSERT INTO Food \
VALUES('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 'PEPPER CORN CHICKEN', 7.00, 'Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 50);\
\
INSERT INTO Food  \
VALUES('Provence Mediterranean Grill', '4473 W 10th Ave, Vancouver, BC', 'CHOW MEIN', 8.50, 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old', 25);\
\
\
INSERT INTO Food\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 'Spring rolls', 6.50, 'delicious authentic asian style', 120);\
\
INSERT INTO Food\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 'MANGO AND AVOCADO SALAD', 9.50, 'Mandarin replaces mango during off season.', 30);\
\
INSERT INTO Food\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 'LEMONGRASS CHICKEN', 9.50, 'Grilled chicken thighs marinated in aromatic lemon.', 90);\
\
INSERT INTO Food\
VALUES ('The Boathouse Restaurant',  '1305 Arbutus St, Vancouver, BC', 'RAMEN BOLOGNESE', 7.50, 'Minced beef with a cream cheese sauce, garlic.', 67);\
INSERT INTO Food VALUES('The Sandbar Restaurant', '1535 Johnston St, Vancouver, BC', 'Oceanian', 'CHICKEN FRIED RICE', 8.75, 'The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" ', 16);\
\
INSERT INTO Food VALUES('The Sandbar Restaurant', '1535 Johnston St, Vancouver, BC', 'Oceanian', 'B.B.Q Duck on Rice', 8.50, 'There are many variations of passages of Lorem Ipsum available, but..}