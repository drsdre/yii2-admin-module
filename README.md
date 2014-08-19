Yii2 admin module
=================

This is under heavy development. Please do not use it in production!

Admin module inspired by [Django](https://www.djangoproject.com/) admin.
Interface using [SB Admin](http://startbootstrap.com/template-overviews/sb-admin/) template.

##Installation


The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist asdf-studio/yii2-admin-module "*"
```

or add

```
"asdf-studio/yii2-admin-module": "*"
```

to the require section of your `composer.json` file.


##Usage

First you need to create a new module in your's project directory (e.g. `/path/to/project/frontent/modules/admin`). Create a new class `Module` extending `asdfstudio\admin\Module`.

```php
use asdfstudio\admin\Module as AdminModule;

class Module extends AdminModule {

}
```

And add this module to yours configuration file to `modules` and `bootstrap` sections

```php
return [
    'bootstrap' => ['admin'],
    'modules' => [
    	...
        'admin' => [
            'class' => 'frontend\modules\admin\Module',
        ],
        ...
    ],
    ...
];
```

It's all. Now you can access to admin page by `/admin` path.


###Registering models 

Now we need to register first model in our admin panel. Create class `UserItem` in your `admin/items` folder.
Class should implement `asdfstudio\admin\AdminItemInterface`. 

```php
use asdfstudio\admin\AdminItemInterface;
use common\models\User;

class UserItem extends User implements AdminItemInterface {
    public static function adminAttributes()
    {
        return [ // this attributes will show in table
            'id',
            'name',
            'email',
        ];
    }

    public static function adminLabels()
    {
        return ['User', 'Users']; // labels used in admin page
    }

    public static function adminSlug()
    {
        return 'user'; // this is a path inside admin module. E.g. /admin/manage/user[/<id>[/edit]]
    }
}
```

Register this item in your Module class:

```php
use asdfstudio\admin\Module as AdminModule;

class Module extends AdminModule {
	...
	public function init() {
		parent::init();

		$this->registerItem(UserItem::className()); // this register item in admin module

		$this->sidebar->addItem(UserItem::className());// and this creates link in sidebar
	}
	...
}

```

Now go to `/admin/manage/user` and you will see table with all users.
