# API for Food and Authentication

Using Laravel Sanctum for token generation


## Routes

Every food routes require token bearer Authorization header

### Info
 - GET: /api

### Users
 - POST: /api/users/authenticate
 
### Food
 - GET: /api/foods/
 - GET: /api/foods/{id}
 - POST: /api/foods/
 - PUT: /api/foods/{id}
 - DELETE: /api/foods/delete/{id}

## Test

### Tests\Feature\AuthenticationTest
  - Invalid login credentials
  - if user exists
  - if user already authenticated
  - everything going normal

### Tests\Feature\FoodTest
  - must authenticated and retrieve data
  - if data invalid 
  - if food is actually exist