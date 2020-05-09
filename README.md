# bowlingdb
Web App utilizing PHP, SQL and XAMPP for a local build to fetch, display, add, edit and delete database entries. Specfically the database is one that contains information of Bowlers, Competitions, Teams, what Team a Bowler was a member of, which Competitions a Bowler competed in and their Scores. There are a few views built in to show an informational collection of the data.

Basic HTML, CSS, and JavaScript are used. In addition PHP is used to fetch, sort, add, edit, delete and display the information from the database using SQL and a MySQL database.

XAMPP is used for local deployment. Version used is 5.5.19, an older one but one that was configured to run on the machine already.
**Only tested on a Windows 10 Machine**

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
  
![XAMPP Control Panel](

- Use your favorite editor for web development. [Brackets](http://brackets.io/) was used along with Chrome Developer Tools to complete this project. I will say Brackets didn't have the best support for PHP, so maybe find a good PHP extenstion for it (and let me know because I couldn't). Or just use your preferred tool that has PHP support. Or don't. I didn't.

## How to Use
#### Setup Database
To setup the database we need to enter the SQL commands through XAMPP's *phpMyAdmin* tool.

**1. Database Creation**
    - With XAMPP installed, open a web browser and navigate to **localhost/phpmyadmin**.
    - You'll see on the left a list of existing databases. Click **New** to create a new database.
    - The *.php* files are configured for a database named *testbowling* but this name can be anything - it just must also be changed in the code.
    
---

To perform the following SQL statements navigate to **localhost/phpmyadmin** just as when creating the database. Once that inital page is open click your database *then* click the **SQL** tab at the top of the page. This is where you'll perform all the statements. To ensure you're targeting the correct database it will say *"Run SQL query/queries on database testbowling:"* - "testbowling" is the name in my database, it should be whichever database you want to target.

---
    
**2. Table Creation - SQL Statements**
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

**3. View Creation - SQL Statements**

<details><summary>Biggest Teams</summary>
  
  ```
  CREATE VIEW bigteams AS
  SELECT Team_name, count(B_id) as 'Number of Members'
  FROM member_of
  GROUP BY Team_name
  ORDER BY count(B_id) DESC
  ```
</details>
<details><summary>Biggest Teams in One Year</summary>
  
  ```
  CREATE VIEW bigteamsyear AS
  SELECT Team_name, Year, count(B_id) as 'Number of Members'
  FROM member_of
  GROUP BY Team_name, Year
  ORDER BY count(B_id) DESC
  ```
</details>
<details><summary>Bowlers: Top 10 Most Matches Played</summary>
  
  ```
  CREATE VIEW bowlerview1 AS
  SELECT b.Name, count(c.Date) as 'Number of Matches Played'
  FROM competed_in as c, bowler as b
  WHERE b.B_id = c.B_id
  GROUP BY c.B_id
  ORDER BY count(c.Date) DESC
  LIMIT 10
  ```
</details>
<details><summary>Bowlers: Top 10 Average Score</summary>
  
  ```
  CREATE VIEW bowlerview2 AS
  SELECT b.Name, count(s.Date) as 'Games Played', (s.Game_1 + s.Game_2 + s.Game_3) / 3 AS 'Average Game Score'
  FROM scores as s, bowler as b
  WHERE b.B_id = s.B_id
  GROUP BY s.B_id
  ORDER BY (s.Game_1 + s.Game_2 + s.Game_3) / 3 DESC
  LIMIT 10
  ```
</details>

#### Generate Data
**You MUST have [Faker](https://github.com/fzaninotto/Faker) to generate with `generate.php`. Click the link to go to the page and read how to include it in your project. That is not my work and as such I didn't include it in my repository!**

With the database created you may enter your own entries into the database in the case of having real data to keep or if you'd like to do some of your own testing. If you'd like to *generate* a large amount of data just to see the database filled and navigate through the web app read on.

Once you've downloaded the files for this project you'll notice `generator.php`, this is the one we'll use.

**Now for XAMPP to work you must place the project folder in a folder called `/htdocs` in XAMPP**. This is the server root and where it looks for all documents when using the path `localhost/(project folder here)`. So that is exactly what you must do.

  - Move your project folder with all the files into `C:\xampp\htdocs`
  - Now you should be able to go to `C:\xampp\htdocs\(project folder here)` and see all the files
  - In your web browser, navigate to `localhost/(project folder here)/generate.php` 
    + If it's successful you'll see a very simple text/html output on the screen. If there's an error you will also see a very basic output before redirecting to the `error.php` page - just a prettier error display.

This is the only use of `generator.php` - It must be accessed directly and is not connected to the rest of the files included. In other words, you cannot navigate to `generator.php` from any other file nor to any other file from the generator.

#### Open the Web App
With your data either entered or generated, or if you have no data and want to enter it on the web app itself, you're ready to view the pages.

  - Navigate to `localhost/(project folder here)/index.php` or simply `localhost/(project folder here)` since by default the browser will look for a file named *index* in the folder specified and display it.
  
From here you're able to view all Six Tables and navigate easily between them in `index.php`, different parameters are used to designate which table to display and how to sort. The Four Views are built into a separate pages and are accessed by the buttons below the title. These are, as shown above, simply created in the database then referenced by name only from a specific .php file for each view.

## Credits
- Nick Ray -> Coding the Web App and helping in designing the database
- Allen Hartig -> The idea, motivation and doing a lot of the heavy lifting on designing the database
