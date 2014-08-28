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

Now we need to register first model in our admin panel. Create class `UserEntity` in your `admin/entities` folder.
Class should inherit `asdfstudio\admin\base\Entity`.
Attributes configuration format is similar to [DetailView](http://www.yiiframework.com/doc-2.0/guide-data-widgets.html#detailview).

```php
use asdfstudio\admin\base\Entity;
use common\models\User;

class UserEntity extends Entity
{
    public static function attributes()
    {
        return [ // this attributes will show in table and detail view
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

    public static function labels()
    {
        return ['User', 'Users']; // labels used in admin page
    }

    public static function slug()
    {
        return 'user'; // this is a path inside admin module. E.g. /admin/manage/user[/<id>[/edit]]
    }

    public static function model()
    {
        return User::className(); // class of User model
    }

    public function form($scenario = Model::SCENARIO_DEFAULT)
    {
        return [ // form configuration
            'class' => Form::className(), // form class name
            'renderSaveButton' => true, // render save button or not
            'fields' => [ // fields configuration
                [
                    'wrapper' => '<div class="col-md-8">{items}</div>', // wrapper of items
                    'items' => [
                        [
                            'class' => Input::className(),
                            'attribute' => 'username',
                        ],
                        [
                            'class' => Select::className(),
                            'attribute' => 'role',
                            'items' => [User::ROLE_USER => 'User', User::ROLE_ADMIN => 'Admin'],
                        ],
                        [ // list of all user posts
                            'class' => Select::className(),
                            'attribute' => 'posts', // attribute name, for saving should implement setter for `posts` attribute
                            'labelAttribute' => 'title', // shows in select box
                            'query' => Post::find()->indexBy('id'), // all posts, should be indexed
                        ],
                    ]
                ],
                [
                    'wrapper' => '<div class="col-md-4">{items}</div>',
                    'items' => [
                        [ // example button
                            'id' => 'ban',
                            'class' => Button::className(),
                            'label' => 'Ban user',
                            'options' => [
                                'class' => 'btn btn-danger'
                            ],
                            'action' => function(User $model) {
                                $model->setAttribute('active', false);
                            },
                        ],
                    ],
                ],
            ],
        ];
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

		$this->registerEntity(UserEntity::className()); // this register entity in admin module

		$this->sidebar->addItem(UserEntity::className());// and this creates link in sidebar
	}
	...
}

```

Now go to `/admin/manage/user` and you will see table with all users.

For example view [asdf-studio/yii2-blog-module](https://github.com/asdf-studio/yii2-blog-module).
