About this project
===

This project is an [OpenClassRooms's project](https://openclassrooms.com/projects/projet-contribuez-a-votre-ecosysteme) whose goal is to contribute back to the web community by building a website which has a profound social impact.

Using all the skills learnt during the course, as a Project Manager and as a Web developer, I had to find an idea which has a social meaning to me and make it alive.

My project, entitled "*YouJobs*", aims to provide a social job platform where users are the driving force of it's ecosystem.
Indeed, everyone after creating an account, can contribute and post a job opportunity or a tip related to the job market.
Users can thank other users, send messages, post comments, report an advert or a tip which might be inappropriate... The accent is put on the user itself and his ability to share.

Technical aspects
---

* Entirely made with the [Symfony framework](http://www.symfony.com) (v3.3)
* User authentification system  
   - Custom made using [Symfony's Guard component](https://symfony.com/doc/current/security/guard_authentication.html)
   - OAuth2 implementation
* Libraries used : 
    * Bootstrap (v3)
    * jQuery (3.2.1)
    * DataTables
    * League (OAuth2 authentification)
    
Install instructions
---

Clone the repository : `git clone https://github.com/lakchote/youjobs.git`

Install the dependencies with composer : `composer install`

If you want to start with a virgin project (without fixtures providing adverts, tips, users, messages already) : 

`php bin/console doctrine:database:create`

`php bin/console doctrine:migrations:diff`

`php bin/console doctrine:migrations:migrate`

`php bin/console doctrine:fixtures:load` (it will create the default advert categories, tips categories and job types categories)

Else, I have created a SQL file for the fixtures "youjobs.sql", you need to : 
* import it
* create a directory named "/uploads/user" inside the /web directory of the project with 4 different photos : "woman_a.jpeg", "woman_b.jpeg", "profil.jpeg", "man.jpeg".

Here are the login/password for the users, **log in with "l.akchote@gmail.com" to gain access to the backoffice** and view an example of the messages feature :

| User | Password |
| ---- | -------- |
| l.akchote@gmail.com | Y@ujobs! |
| eric.berger@gmail.com | s0lid@1337 |
| catherine.frost@gmail.com | !funnyKitten |
| dionna.stare@gmail.com | wond3rWoman_ |

Then, run the project : `php bin/console server:run`

Improvements to bring
---

* Report comments / messages
* Provide a reason for reporting adverts / comments / messages
* Block other users
* Messages history with other users
* Search feature for tips, sorting job opportunies by city and/or type of contract (CDI, CDD, internship, interim)
