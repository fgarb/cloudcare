# Laravel Example App For CloudCare

 [![GitHub license](https://img.shields.io/github/license/gothinkster/laravel-realworld-example-app.svg)](https://raw.githubusercontent.com/gothinkster/laravel-realworld-example-app/master/LICENSE)

> ### Example of a Proxy API from [PunkAPI](https://punkapi.com).

This is a small application using Docker and Laravel to get data from Punk API and return to client.
Authorization is made using Sanctum (Passport is a bit too much for personal access tokens, but I have added a small part to issue refresh tokens with Sanctum, using "Token Abilities" feature).

The Proxy Controller has been extended to support multiple public APIs with few steps just by extending ProxyController

Frontend part is a "horrible" Vue template I have written very quickly and lacks some features, like:
- Refresh Token when authorization expires
- Don't store user token in local storage
- Better layout to be more responsive

Surely, I will improve the frontend, but for now... time is over :)

----------

Francesco Garbin
