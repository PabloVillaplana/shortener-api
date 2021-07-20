## Shortener URL API

## Installation

1) Run in your terminal:
``` bash
git clone https://github.com/PabloVillaplana/shortener-api.git
```

2) Copy and Set your information in your .env file
``` bash
cp .env.example .env
```

3) Run in your Project folder:
``` bash
composer install
php artisan key:generate
php artisan migrate
npm run dev -> Local
npm run prod -> production
```


## Challenges

**1) Your mission, should you choose to accept it, is to make a URL shortener API.**
   
I created a table in the database to handle the short url called url_short, where the original URL and the short url can be saved, also if the site is NSFW

Create a Model to be able to verify if the URLs created already exist with the randon string and if they exist, generate others.

I created an endpoint where it will return the URLs according to the order of how they have been visited, from the most visited to the least visited

**2) Make a web client for the URL shortener.**

I created a component in VUE where the TOP-100 created in challenge 1 is queried to obtain the list of visited URLs to display them on a ranking board on the home page.

I created a small form where the User has the possibility to add URLs and put the verification of whether they are NSFW with a check box

If the user clicks on a URL where it is not NSFW it will open a modal where it will show him that the site is not NSFW and he will be redirected to it in 10 seconds, or it also has a button where he can cancel the action.

**3) Decisions**
``` bash
1. Create a trait called ApiResponder to handle the endpoint responses for the future to avoid duplicating code elsewhere. 
```



## Future Improvements

``` bash
1. Protect the API with a token using the Laravel Passport library to have better security to access the endpoints 
2. Create an administrative panel where there are administrative users with the possibility of managing the sites entered in the application 
```
