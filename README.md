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
Configuration format is similar to [DetailView](http://www.yiiframework.com/doc-2.0/guide-data-widgets.html#detailview).

```php
use asdfstudio\admin\AdminItemInterface;
use common\models\User;

class UserItem extends User implements AdminItemInterface {
    public static function adminAttributes()
    {
        return [ // this attributes will show in table
            'id',
            'name',
            'email:email',
            [
                'attribute' => 'role',
                'format' => ['list', [User::ROLE_USER => 'User']],
            ],
            [
                'attribute' => 'status',
                'format' => ['list', [User::STATUS_ACTIVE => 'Active', User::STATUS_DELETED => 'Deleted']],
            ],
            'created_at:datetime',
            'updated_at:datetime',
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


##License BSD

Copyright (c) 2014, asdf-studio All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

    Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.
    Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.
    Neither the name of the asdf-studio nor the names of its contributors may be used to endorse or promote products derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

