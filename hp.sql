CREATE TABLE `restaurants` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(256) default NULL,
  `address` varchar(256) default NULL,
  `city` varchar(128) default NULL,
  `state` varchar(128) default NULL,
  `zipcode` varchar(10) default NULL,
  `country` varchar(128) default NULL,
  `phone` varchar(128) default NULL,
  `genre` varchar(128) NOT NULL,
  `area` varchar(128) default NULL, 
  `adminRating` float default 0.0,
  `userRating` float default 0.0,
  `noOfRatings` int(10) default 0,
  PRIMARY KEY  (`id`)
);


create table users(
	id int(10) unsigned NOT NULL auto_increment,
	username varchar(100) NOT NULL,
	email varchar(128) NOT NULL,
	firstname varchar(100) default NULL,
	lastname varchar(100) default NULL,
	noOfRatings int(10) default 0,
	privilege tinyint(4) unsigned NULL,
	created datetime default NULL,
	primary key(id)
); 



create table reviews(
	id int(10) unsigned NOT NULL auto_increment,
	user_id int(10) unsigned NOT NULL,
	restaurant_id int(4) unsigned NOT NULL,
	created datetime default NULL,
	rating tinyint(4) unsigned NULL,
	title varchar(200) default NULL,
	`comment` varchar(2000) default NULL,
	primary key(id),
	foreign key(restaurant_id) references restaurants(id),
	foreign key(user_id) references users(id)
); 

create table dishReviews(
	id int(10) unsigned NOT NULL auto_increment,
	user_id int(10) unsigned NOT NULL,
	dish_id int(4) unsigned NOT NULL,
	restaurant_id int(4) unsigned NOT NULL,
	created datetime default NULL,
	rating tinyint(4) unsigned NULL,
	deleted tinyint(4) unsigned NULL, 
	`comment` varchar(1000) default NULL,
	primary key(id),
	foreign key(dish_id) references dishess(id),
	foreign key(user_id) references users(id),
	foreign key(restaurant_id) references restaurants(id)
); 

CREATE TABLE `dishes` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `created` datetime NOT NULL,
  `name` varchar(128) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `rating` float default 0.0,
  `noOfRatings` int(10) default 0,
  `description` varchar(2048) NOT NULL,
  PRIMARY KEY  (`id`),
  foreign key(restaurant_id) references restaurants(id)
);

CREATE TABLE `images` (
  `id` int(11) unsigned NOT NULL auto_increment,
  `imagePath` varchar(256) NOT NULL,
  `caption` varchar(128) NOT NULL,
  `restaurant_id` int(11) NOT NULL,
  `dish_id` int(11) default NULL,
  `user_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  foreign key(restaurant_id) references restaurants(id),
  foreign key(user_id) references users(id),
  foreign key(dish_id) references dishes(id)
); 

CREATE TABLE categories(
	id int(11) unsigned NOT NULL auto_increment,
	name varchar(256) NOT NULL,
	catType varchar(256) NOT NULL,
	subcat varchar(128),
	PRIMARY KEY (id)
);

create table restCategories(
	restaurant_id int(11) unsigned not null,
	category_id int(11) unsigned not null,
	primary key (restaurant_id, category_id),
	foreign key(restaurant_id) references restaurants(id),
	foreign key(category_id) references categories(id) 
);

create table features(
	id int(11) unsigned not null auto_increment,
	name varchar(256) not null,
	fType tinyint(4) not null default 0,
	primary key(id)
); 

create table restFeatures(
	restaurant_id int(11) unsigned not null,
	feature_id int(11) unsigned not null,
	val varchar(128), 
	primary key(restaurant_id, feature_id),
	foreign key(restaurant_id) references restaurants(id),
	foreign key(feature_id) references features(id)
);

create table dishFeatures(
	dish_id int(11) unsigned not null,
	feature_id int(11) unsigned not null,
	val varchar(128), 
	primary key(dish_id, feature_id),
	foreign key(dish_id) references dishes(id),
	foreign key(feature_id) references features(id)
);

create table gDishes(
	id int(11) unsigned not null auto_increment,
	name varchar(128), 
	description varchar(2048),
	cuisine_id int(11) unsigned not null,
	dishType varchar(128),
	familyName varchar(128),
	primary key(id),
	foreign key(cuisine_id) references categories(id)
);