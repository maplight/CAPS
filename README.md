#CAL-ACCESS Campaign Power Search

The CAL-ACCESS Power Search is a tool that allows journalists and citizens to easily search the California Secretary of State's bulk data download to find political contributions to all recipient committees from 2001 through the present.

## System Overview:
The CAL-ACCESS Search is a series of php web pages with associated images, css, and javascript files, php scripts and sql files.  The php web pages will display and operate the CAL-ACCESS Campaign Power Search web pages.  The scripts and sql files are used to run a first install of the data and to maintain the data.

## Installation:
Generally on the first install you will run the install script, which can take up to 10-12 hours to run depending on the bandwidth available as it scrapes web data back to 1999.  Once that is done the update_data script will need to be ran each time the data is ready to be updated.  This script takes about an hour and a half to two hours to run depending on server speed.  I suggest running this script right after http://campaignfinance.cdn.sos.ca.gov/dbwebexport.zip file has finished processing and that a cron is set up to run the update script.

This system will use 2 MySQL databases, one called ca_process and the other called ca_search.  The ca_process database is used to store all of the working date files and temporary processing files.  The ca_search database will hold the final, read-only tables that the web pages require.  If a multiple server setup is used just the ca_search database needs to replicate across the servers and the ca_process database can stay on the master server.


* Step 1: Make sure the following configuration files are in your MySQL server configuration file:
Under [mysql]
local-infile = 1
Under [mysqld]
ft_min_word_len = 1
ft_stopword_file = ""

* Step 2: Make sure your SQL server can process LOAD DATA LOCAL files, as this is used to load in the files downloaded via FTP.

* Step 3: You will need to create 2 databases on your master SQL server.  Call one ca_process and the other ca_search
DROP DATABASE IF EXISTS ca_process;
CREATE DATABASE ca_process CHARACTER SET latin1 COLLATE latin1_swedish_ci;
DROP DATABASE IF EXISTS ca_search;
CREATE DATABASE ca_search CHARACTER SET latin1 COLLATE latin1_swedish_ci;

* Step 4: Create two MySQL users for these database, one user will need full read/write access to both databases and will be used for the install and update process.  This user will also need load data local infile access (file access) granted to it. The other user can be granted SELECT only and is used just for the web page side.  The same user can be used for both processes if desired though not recommended.
For the users in our demo system:
GRANT SELECT ON ca_search.* TO 'caps'@'localhost' IDENTIFIED BY '##_caps_password_##';
GRANT ALL ON ca_search.* TO 'caps_script'@'localhost' IDENTIFIED BY '#_caps_script_password_##';
GRANT ALL ON ca_process.* TO 'caps_script'@'localhost';
GRANT FILE ON *.* TO 'caps_script'@'localhost';

* Step 5: Once this is done copy all the CAPS files into the directory you wish to have them run from.  The following directory tree is used in the files:
/ - root folder, holds the files needed for the web database
/css - holds the css files
/css/i - holds the CA-SOS images used in their css files
/img - holds the power search and CA-SOS images
/js - holds javascript code
/js/vender - CA-SOS javascript directory and files
/scripts - holds the update and install scripts

* Step 6: Update the file /connect.php - You will need to put the login / password for both accounts you created, along with an e-mail address where update error reports will be sent to.  During the update process if files are missing from the ftp data none of the ftp files will process and an error message will be e-mailed to this person so they can look into it and reprocess the data if needed.

* Step 7: Secure the file /connect.php - I suggest you make this file read-only to webserver user only.  Since this file holds password information it's important to secure it.

* Step 8: Run the install.php script from within the /scripts directory; cd scripts; php install.php - this script should create all the necessary tables in each of the databases, then it will scrape the California web pages for Propositions, Candidate and Committee information back to 1999.  Finally it will run the update script which will download and process the newest ftp files, and process and create the needed data and tables for the web files.  The install script can take 8 - 12 hours to run depending on your server speed and bandwidth available.

* Step 9: Set the update_data.php to run in a cron job.

* Step 10: Set the maximum number of records to be able to download a csv file in results.php; display_data function.

* Step 11: In the file results.php there is a function called display_data.  The first variable in that function, $max_download_records, needs to be set to whatever the maximum number of records you want to have in a download file.  By default it is set to 150,000 records.


##### Possible Multi-Server Setup Notes:
Setting up a multi-server could be done a couple ways, the simpliest is to just have the web pages and database on multiservers with a load balancer serving up a free server.  This would require only replication of the ca_search database with one master database server that also processes the data updates.

Another setup is to build a database cluster with a central entry point, again, just needing to cluster the ca_search database, then the web pages can be placed among multiple servers as needed.

##### HTML Header and Footer Notes:
If you want to replace the header and footer information for the application, the SOS header and footer html resides in sos.html. The files that would would need to be updated are index.php and advanced.php
