
***
*** Shots
***
*** Gianuca.Moro@unipd.it --- April 2018

A web page which 
- allows to select a date,
- shows all the shots present in that date,
- checks the shots that the user wants to be copied.

The web app is implemented in Php, and uses the Javascript libraries:
- Twig (web/js/Twig) is a templating library for web pages
- Kalendae (web/js/kalenda) is an utility do show a calendar
  to select a date - project available at https://github.com/Twipped/Kalendae
  (original source code in "archive" directory - at the moment NOT USED)


***
*** Directory web
***

Include the Twig templates of the web pages (extension phtml) and
the static files for web (images and javascript libraries)


***
*** Source files
***

The Php source files are in the main directory,
- config.php: keep some configuration variables
- externalCmds.php: code which interface to database and filesystem
- index.php: main code

***
*** archive directory
***

- source of kalendae library

***
*** Information on the infrastructure
***

Server with MDSplus data:
mds-control
mds-data-1
mds-data-2

Trees are stored in:
/w7x/vault/w7x
/w7x/vault/treename

Name format for shot: w7x_yymmddnnn


Status:

<0 - NOT READY   RED
0  - NOT READY   RED
1  -             RED-CHECK - ready to be checked
2  -             ORANGE    - checked
>2 - PROCESSED   GREEN
