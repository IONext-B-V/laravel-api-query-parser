# Laravel API query parser

## Description
This is a simple request query parameter parser for REST-APIs based on Laravel's framework.

## Requirements
- PHP >=7.2
- Laravel framework >= 6.2
- Mockery >= 1.3 (dev)
- PHPUnit >= 8.4 (dev)

## Installation
- Add ionext/laravel-api-query-parser to your composer.json and make composer update, or composer require ionext/laravel-api-query-parser
- Setup the service provider:
    in config/app.php add the following line:
    ```php
    'providers' => [
        ...
        ApiQueryParser\Provider\RequestQueryParserProvider::class,
        ...  
    ];
    ```
    
## Usage
```php
    // app/API/V1/Models/UserController.php
    namespace App\Api\V1\Http\Controllers;
    
    use App\Models\User;
    use App\Api\V1\Resources\UserResource;
    use App\Api\V1\Resources\UserResourceCollection;
    use ApiQueryParser\ResourceQueryParserTrait;
    use ApiQueryParser\BuilderParamsApplierTrait;
    
    class UserController extends Controller
    {
        use ResourceQueryParserTrait;
        use BuilderParamsApplierTrait;
                
        public function index(Request $request)
        {
            $params = $this->parseQueryParams($request);
            $query = User::query();
            $paginator = $this->applyParams($query, $params);
    
            return UserResourceCollection::make(
                $paginator
            );
        }
    }
```

## Query syntax

### Eager loading
Q: /users?connection[]=profile  
R: will return the collection of the users with their profiles included

### Filtering
Q: /users?filter[]=name:ct:admin    
R: will return the collection of the users whose names contains the admin string

__Available filter options__    

| Operator      | Description           | Example |
| ------------- | --------------------- | ------- |
| ct            | String contains       | name:ct:Peter |
| nct           | String NOT contains   | name:nct:Peter |
| sw	        | String starts with    | username:sw:admin |
| ew	        | String ends with      | email:ew:gmail.com |
| eq	        | Equals                | level:eq:3 |
| ne	        | Not equals            | level:ne:4 |
| gt	        | Greater than          | level:gt:2 |
| ge	        | Greater than or equal | level:ge:3 |
| lt	        | Lesser than           | level:lt:4 |
| le	        | Lesser than or equal  | level:le:3 |
| in	        | In array              | level:in:1&#124;2&#124;3 |
| nin	        | Not in array          | level:nin:1&#124;2&#124;3 |

#### Note on filtering based on relational values
It is now possible to filter based on relational values using the table.column notation. eg. comments.author:eq:mike

### Locating
Q: /users?location=longitude_field:latitude_field:0.000000:0.000000:10
R: This requires the user to have longitude and latitude fields. It will return the collection of the users within a given (10km) radius from the provided latitude and longitude. The distance to each entity will be passed via a custom distance column.
When using location, a simple paginator will be used due to a bug in Laravel. This means the last page will be null. 

#### Advanced location, joining tables
Q: /users?location=longitude_field:latitude_field:0.000000:0.000000:10:offices|office_id
R: Notice the last parameter separated by a pipe(|). This allows joining another table on containing the latitude and longitude information, 
adding location_id, latitude and longitude fields to the final result.

### Sorting
Q: /users?sort[]=name:ASC   
R: will return the collection of the users sort by their names ascending

### Pagination
Q: /users?limit=10&page=3   
R: will return a part of the collection of the users (from the 21st to 30th)

### Credits
Package was originally developed by Gábor Németh (github: https://github.com/ngabor84) for Laravel Lumen(https://github.com/ngabor84/lumen-api-query-parser). The realisation of this package
required some refactoring to be used with Laravel.


