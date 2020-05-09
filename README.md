# bowlingdb
Web App utilizing PHP, SQL and XAMPP for a local build to fetch, display, add, edit and delete database entries. Specfically the database is one that contains information of Bowlers, Competitions, Teams, what Team a Bowler was a member of, which Competitions a Bowler competed in and their Scores. There are a few views built in to show an informational collection of the data.

Basic HTML, CSS, and JavaScript are used. In addition PHP is used to fetch, sort, add, edit, delete and display the information from the database using SQL and a MySQL database.

XAMPP is used for local deployment. Version used is 5.5.19, an older one but one that was configured to run on the machine already.

## Table of Content
- [Motivation](#motivation)
- [Screenshots](#screenshots)
- [Tech Used](#tech-used)
- [Features](#features)
- [Installation](#installation)
- [How to Use](#how-to-use)
- [Credits](#credits)

## Motivation
A group project for a Databases course was the initial motivation for finding a project to do. The motivation for the Bowling Database specifically is a group member had loads of spreadsheets with data that was cumbersome to edit and view. An easier way to see the data, enter new data or take out entries as well was needed.

## Screenshots


## Tech Used
* HTML
* CSS
* JavaScript
* PHP
* SQL
* XAMPP - 
[Download XAMPP Version used](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/5.5.19/)

## Features
The Bowling Database has *Six* Tables:
1. Bowler
2. Team
3. Member Of
4. Competition
5. Competed In
6. Scores

*Four* Views were created in phpMyAdmin and referenced by name:
1. Biggest Teams
  - List of Teams that have had the most members overall. In other words, the sum of bowlers on the team through all the entries.
2. Biggest Teams by Year
  - List of Teams that, in one year, had the most team members. Any year is eligible.
3. Bowlers: Top 10 Most Matches Played
  - List of Top 10 Bowlers that have the most matches played total.
4. Bowlers: Top 10 Average Scores
  - The Top 10 Bowlers with the highest average game score. This does not factor in number of games played. As in one match played, that could be a very high average, versus many matches played.
  
This project was motivated by creating an easier way to access already collected data. Due to quantity requirements, there was more data than readily available needed. That is why there is a `generate.php` file included. **This is not connected to the main pages**. This is run to randomly generate data and entries into the database.

`generate.php` is mainly a product of [faker.php](https://github.com/fzaninotto/Faker), so thank you [fzaninotto](https://github.com/fzaninotto) for making it possible!

## Installation
#### Initial Setup 
- [Download XAMPP](https://sourceforge.net/projects/xampp/files/XAMPP%20Windows/5.5.19/)
  - This is what we use to run a local server and allow the PHP to run and access our database that we also use XAMPP to configure.
  - A standard download and install procedure here. No special configurations were made to allow it to work on my machine. Once installed simply open the *XAMPP Control Panel* and start Apache, MySQL and Tomcat. That's all you need to get going.
  
**(PLACE XAMPP SCREENSHOT HERE)**

- Use your favorite editor for web development. [Brackets](http://brackets.io/) was used along with Chrome Developer Tools to complete this project. I will say Brackets didn't have the best support for PHP, so maybe find a good PHP extenstion for it (and let me know because I couldn't). Or just use your preferred tool that has PHP support. Or don't. I didn't.

## How to Use
#### Setup Database
To setup the database we need to enter the SQL commands through XAMPP's *phpMyAdmin* tool.

1. Database Creation
    - With XAMPP installed, open a web browser and navigate to **localhost/phpmyadmin**.
    - You'll see on the left a list of existing databases. Click **New** to create a new database.
    - The *.php* files are configured for a database named *testbowling* but this name can be anything - it just must also be changed in the code.
2. Table Creation - SQL Statements
<details><summary>Bowler</summary>
  
  ```
  CREATE TABLE Bowler (
    B_id varchar(255) NOT NULL,
    Name varchar(255) NOT NULL,
    Email varchar(255)
  );
  ALTER TABLE Bowler ADD PRIMARY KEY (B_id);
  ```
</details>
<details><summary>Team</summary>
  
  ```
  CREATE TABLE Team (
    Team_name varchar(255) NOT NULL,
    Year YEAR NOT NULL,
    League varchar (255),
    Bowling_center varchar(255),
    Coach varchar(255),
    Sponsor varchar(255),
    INDEX (Team_name),
    INDEX (Year)
  );
  ALTER TABLE Team 
  ADD CONSTRAINT PK_Team PRIMARY KEY (Team_name, Year);
  ```
</details>
<details><summary>Member Of</summary>
  
  ```
  CREATE TABLE Member_of (
    B_id varchar(255) NOT NULL,
    Team_name varchar(255) NOT NULL,
    Year YEAR NOT NULL
  );
  ALTER TABLE Member_of
  ADD CONSTRAINT PK_Member_of PRIMARY KEY (B_id, Team_Name, Year);
  ALTER TABLE Member_of
  ADD FOREIGN KEY (B_id) REFERENCES Bowler(B_id);
  ALTER TABLE Member_of
  ADD FOREIGN KEY (Team_name) REFERENCES Team(Team_name);
  ```
</details>
<details><summary>Competition</summary>
  
  ```
  CREATE TABLE Competition (
    Competition_name varchar(255),
    Date DATE NOT NULL,
    Location varchar(255) NOT NULL,
    Format varchar(255),
    INDEX (Date),
    INDEX (Location)
  );
  ALTER TABLE Competition
  ADD CONSTRAINT PK_Competition PRIMARY KEY(Competition_name, Date, Location);
  ```
</details>
<details><summary>Competed In</summary>
  
  ```
  CREATE TABLE Member_of (
    B_id varchar(255) NOT NULL,
    Team_name varchar(255) NOT NULL,
    Year YEAR NOT NULL
  );
  ALTER TABLE Member_of
  ADD CONSTRAINT PK_Member_of PRIMARY KEY (B_id, Team_Name, Year);
  ALTER TABLE Member_of
  ADD FOREIGN KEY (B_id) REFERENCES Bowler(B_id);
  ALTER TABLE Member_of
  ADD FOREIGN KEY (Team_name) REFERENCES Team(Team_name);
  ```
</details>
<details><summary>Scores</summary>
  
  ```
  CREATE TABLE Scores (
    B_id varchar(255) NOT NULL,
    Date DATE NOT NULL,
    Game_1 int DEFAULT 0,
    Game_2 int DEFAULT 0,
    Game_3 int DEFAULT 0
  );
  ALTER TABLE Scores
  ADD CONSTRAINT PK_Scores PRIMARY KEY(B_id, Date);
  ALTER TABLE Scores
  ADD FOREIGN KEY (B_id) REFERENCES Bowler(B_id);
  ALTER TABLE Scores
  ADD FOREIGN KEY (Date) REFERENCES Competition(Date);
  ```
</details>

**Considering the table dependencies they must be created in this order. Any entries in the database must also follow this order.**

#### Generate Data
With the database created you may enter your own entries into the database in the case of having real data to keep or if you'd like to do some of your own testing. If you'd like to *generate* a large amount of data just to see the database filled and navigate through the web app read on.

Once you've downloaded the files for this project you'll notice `generator.php`, this is the one we'll use.



#### Open the Web App

## Credits

