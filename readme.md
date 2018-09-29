# ApiRestfulTrait

A simple Laravel trait with restful crud implementation.

## Installation

### With Composer

```
$ composer require moraesgil/api-restful-trait
```

```json
{
   "require": {
      "moraesgil/api-restful-trait": "~1.0"
   }
}
```
### Usage


create your routes in routes/api.php or your prefer routes file
```php
Route::resource('/samples', 'YourLaravelController', ['except' => ['create', 'edit', 'show']]);

```

make sure you have a model at App\Entities\YourModel same name of controller to Autoload model entity, or you can inject dependencies, see bellow the following instructions in Extras area...
```terminal
//terminal
php artisan make model YourLaravel
```

In your Controller
```php
use Traits\Controllers\ApiRestfulTrait;  //<<<<< add this line

class YourLaravelController extends Controller {
     use ApiRestfulTrait;  //<<<<< add this line  

     // ..others non-crud methods
}

```
Include the [EntityValidatorTrait](https://github.com/MoraesGil/EntityValidatorTrait) it's a dependency. Please check documentation for more info. Also read about Autoload model entity in this extras section.
```php
use Traits\Entities\EntityValidatorTrait;  //<<<<< add this line

class YourLaravel extends Model {
   use EntityValidatorTrait; //<<<<< add this line
}

```


## Extras

### Getting an index page

May  you have to call a page in index() if not ajax request, we have getPage(), can you override method or just to pass data by constructor, also you can redirect to another page passing the route name;

```php
//in or controller you can pass
class YourLaravelController extends Controller {
   public function __construct()
   {  
      //Passing view and data
       $this->indexView = "youCrudBlade"; //default "crud"
       $this->indexData = [
          "pageTitle" => "Page title for exemple"
       ];

       // OR redirect to named route
       $this->indexRedirectRouteName ="myroute.indexpage"
   }
}
```

### Autoload model entity

By default it try access a model with same name of controller, you can to pass your entity by name or object in constructor

```php
class YourLaravelController extends Controller {
   public function __construct(Mymodel $m)
   {
       $this->Model = $m;
       // or
       $this->modelName = "Mymodel";
       // or Just create a model same name of controller, from this controller sample "YourLaravel"
   }
}
```

### Getting data from Entity custom method

By default it return a Laravel paginate getting limit by request, can you override method or call a customMethod from your entitiy passing $modelGetMethod

```php
class YourLaravelController extends Controller {
   public function __construct()
   {
       $this->modelGetMethodName = "myCustomPaginate";
   }
}
```

## Dependencies

* **EntityValidatorTrait** - *My trait to allow model do self validation* - [EntityValidatorTrait](https://github.com/MoraesGil/EntityValidatorTrait)

## Authors

* **Gilberto Prudêncio Vaz de Moraes** - *Initial work* - [MoraesGil](https://github.com/Moraesgil)

## License
This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details
