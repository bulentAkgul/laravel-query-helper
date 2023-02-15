# Laravel Query Helper

This package aims to add a handy and quite flexible features to Laravel's Eloquent query builder.

## Installation

First, install package.
```
sail composer require bakgul/laravel-query-helper
```

Next, pulish config.
```
sail artisan vendor:publish --tag=query-helper
```

## Usage

### Filtering

First, you need to add `IsFilterable` trait and an array of filters to each model where you want to apply filters. That array can have two keys:

- Self: the list of filters that will be applied directly to that model.
- With: an associative array of related models that can be used to filter the main model. The keys in this array must be the same as the method names of the relations.

Let's say we have the following models and relations.

```php
class User extends ...
{
    use IsFilterable, ...;

    public static $filters = [
        'self' => [
            \Bakgul\LaravelQueryHelper\Filters\Name::class,
            \Bakgul\LaravelQueryHelper\Filters\Email::class,
        ],
        'with' => [
            'roles' => Role::class,
        ]
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

```php
class Role extends Model
{
    use IsFilterable;

    private static array $filters = [
        'self' => [
            \Bakgul\LaravelQueryHelper\Filters\Name::class,
        ],
        'with' => [
            'users' => User::class,
            'abilities' => Ability::class,
        ],
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function abilities()
    {
        return $this->belongsToMany(Ability::class);
    }
}
```

```php
class Ability extends Model
{
    use IsFilterable;

    private static array $filters = [
        'self' => [
            \Bakgul\LaravelQueryHelper\Filters\Name::class
        ],
        'with' => [
            'roles' => Role::class,
        ]
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
```

```php
$users = User::filter($request->filters)->get();
```

When you call `filter` method, it will generate a filtering array throughout the `filters` array on models starting from `User` recursively.

To prevent infinite loop in recursion, we stop each branch when they go back to main class after adding main class to the tree. That means You can filter by the following logic:
- Users that have cetain roles that belongs to some users.
- 
This example might not be seen very usefull, but it explains the capability.

Since it's an expensive operation to construct the filtering array, we will cache it when it's created first time.

`$request->filters` should be in a structure like the example down below. The important things here:
- `self` filters will be passed directly.
- `with` filters will be collected under `with` key.

```php
[
    // *** will be replaced by % by the Text filter.
    // ***x means the string that ends with 'x'
    // x*** means the string that start with 'x',
    // ***x*** means the string that contains 'x',
    // x means the string that is 'x'
    'name' => ['***x***', '***y'],
    'with' => [
        'roles' => [
            'name' => ['editor***'],
            'with' => [
                'abilities' => [
                    'name' => ['delete']
                ]
            ]
        ]
    ]
]
```

The example up above will filter the users based on the following list:
- its name contains 'x' or ends with 'y'
- the name of one of its roles starts with 'editor'
- the role can delete something.

#### *Polymorphic Relationship Filter*

Unlike other relationships, polymoprphic ones should be listed under the `self` key of `$filters` array.

```php
class Post extends Model
{
    use IsFilterable;

    private static array $filters = [
        'self' => [
            \Bakgul\LaravelQueryHelper\Filters\MorphMany::class
        ],
        'with' => [
            'user' => User::class,
        ],
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->morphToMany(Comment::class, 'commentable');
    }
}
```

The filter in request should be like this:

```php
[
    'morph_many' => [
        // method name: 'to' if the relation method is 'morphToMany'
        //              'by' if the relation method is 'morphedByMany'
        'to',
        // relationsip name
        'comments',
        // prefix:
        //     if 'to' then this will be 'comment_id'
        //     if 'by' then this will be 'commentable_id' and 'commentable_type' 
        'comment',
        // the N number of ids that will be cheched in the column up above
        2, 3, 5
    ]
]
```

### Groupping

Groupping operates on PHP level. If you want to group your data in the database level, you can't use this part. Otherwise, this is how to use it:

- Add `IsGrouppable` trait to the model that you want to group.
- If you want to group a model with some columns all the time, add `protected static $groupKeys = ['name', 'year']` property to the model.
- Then use it like so:

```php
/**
 * users table has these columns:
 *     first_name, 
 *     last_name, 
 *     email, 
 *     ... some irrelevant columns
 *     created_at
 */

$users = User::group(['first_name']);
```

`group` method can be found in `IsGrouppable` trait as `scopeGroup` and it accepts the following arguments:
- **keys**: the list of group keys
- **take**: the number of items that will be in each group. Default is zero and means 'all'
- **isLast**: make it true when you want to get latest records. Default is false.
- **select**: the array of columns that will be selected. Default is ['*']
 means 'all'
- **column**: the name of the column when you needed. I use it for the time modifiers such as year and month. Its default value is 'created_at' 

But what if you want to group users with a column that doesn't exist. You can do that thanks to modifiers that are shipped in the package and can be be found on `src/Modifiers`.

The modifiers will change the sql query to add new field on the fly. For example:

```php
$users = User::group(
    keys: ['year', 'email_provider'],
    take: 5,
    isLast: true,
    select: ['first_name', 'last_name', 'email']
    column: 'updated_at'
);
```

The method up above will add `year` and `email_provider` fields to the each user. `year` will be extracted from `updated_at` while `email_provider` from `email`. Each user will contain selected 3 columns and these 2.

### Modifiying

This is used by groupping functionality, and it's already explained, but just as a remainder, you can use it out of grouping too.

- Add `IsModifyable` trait to model.
- call `modify` method as a part of query builder.

```php
$users = User::modify(
    keys: ['year', 'month'],
    select: ['name', 'email'],
    column: 'updated_at'
)->get();
```

### Sorting

It's a quite simple method that allowes you to pass all sorting columns in one method.

```php
User::sort(['name'], ['email', 'desc']);
```

## Extending Functionalities

### Filtering

In order to extend available filters, all you need to do is to create your own filter classes and use them in models. Let's say you have a table that contains `city` and need to apply a filter to it.

First create a class. The class name must be the pascal case version of the key that you will pass in request.

```php
namespace App\Filters;

class City extends Text
{
    public $column = 'city';
}
```

Or you can create your own filtering logic instead of using `Text` filter.

```php
namespace App\Filters;

class City extends Filter
{
    protected function filter(Builder $query, mixed $filter): Builder
    {
        // if you want to accept multiple values, call filters method
        return $this->filters($query, $filter, $this->callback());

        // otherwise...
        return $query->where('city', $filter);
    }

    protected function callback(): callable
    {
        return fn ($query, $filter) => $query->where('city', $filter);
    }
}
```

After you create your new filter class, add it to model which can use it.

```php
class Address extends Model
{
    private static array $filters = [
        'self' => [
            \Bakgul\LaravelQueryHelper\Filters\Name::class,
            \App\Filters\City::class,
        ],
        'with' => [
            'user' => User::class,
            'country' => Country::class,
        ],
    ];
}
```

Now, you can filter addresses based on city, or users who is in that city/cities.

```php
Adress::filter(['city' => ['ankara', 'london']])->get();

User::filter(['with' => ['city' => ['ankara', 'london']]])->get();
```

### Groupping

If you want to use one of your current columns as they are, you don't need to take any action. Simply pass the column name in the array of group keys.

But let's say you have `domain` column where you store the web sites' adresses and you want to group them beased on top-level domain (.com, .net, etc.)

First you need a modifier to extract that part and store it in a new field in query.

```php
namespace App\Modifiers;

class TopLevelDomain extends Modify
{
    public function modifyQuery(Builder $query, array $keys, string $column): Builder
    {
        $raw = $this->rawQuery();

        return $query->when(
            in_array('top_level_domain', $keys),
            fn ($q) => $q->addSelect($raw)
        );
    }

    private function rawQuery(): Expression
    {
        return DB::raw("REPLACE(domain, SUBSTRING_INDEX(domain, '.', 1) , '') as top_level_domain");
    }
}
```

Then you need to add it to `modifiers` array in `config/query-helper.php`

```php
    'modifiers' => [
        // default ones,
        \App\Modifiers\TopLevelDomain::class,
    ]
```

Now you can use it like so:

```php
$customers = Customer::group(['top_level_domain']);
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
