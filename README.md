# Laravel Example App For CloudCare

 [![GitHub license](https://img.shields.io/github/license/gothinkster/laravel-realworld-example-app.svg)](https://raw.githubusercontent.com/gothinkster/laravel-realworld-example-app/master/LICENSE)

> ### Example of a Proxy API from [PunkAPI](https://punkapi.com).

This is a small application using Docker and Laravel to get data from Punk API and return to client.
Authorization is made using Sanctum (I have added a small part to issue refresh tokens with Sanctum, using "Token Abilities" feature instead of using Passport).

The Proxy Controller has been extended to support multiple public APIs with few steps just by extending ProxyController

Frontend part lacks some features, like:
- Refresh Token when authorization expires
- Don't store user token in local storage
- Better layout to be more responsive

### Testing locally

----

You can just clone the repo and build the image with:
```
docker-compose build
```

Then run
```
docker-compose up 
```

and you can access the application on http://localhost with user: root and password: password.
There is a startup script waiting for mysql to be up and running before migrating and seeding the DB.
