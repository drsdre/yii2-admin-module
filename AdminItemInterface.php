<?php


namespace asdfstudio\admin;


interface AdminItemInterface {
    /**
     * List of model's attributes for displaying table and view and edit pages configuration
     *
     * ```php
     *  [ // display attributes. @see [[DetailView]] for configuration syntax
     *      'id',
     *      'username',
     *      [ // support related models
     *          'attribute' => 'posts', // getter name, e.g. getPosts()
     *          'format' => ['model', ['labelAttribute' => 'title']], // @see [[AdminFormatter]]
     *          'multiple' => true, // has multiple models,
     *          'visible' => function($model) { // can be boolean or callable
     *              return $model->id === Yii::$app->user->id; // show field only if is the same usr
     *          },
     *          'create' =>  false, // allows create new related model when editing current
     *          'editable'   => true, // allows edit this field
     *      ],
     *  ],
     * ```
     *
     * @return array
     */
    public static function adminAttributes();

    /**
     * Should return an array with single and plural form of model name, e.g.
     *
     * ```php
     *  return ['User', 'Users'];
     *  // or
     *  return 'User';
     * ```
     *
     * @return array|string
     */
    public static function adminLabels();

    /**
     * Slug for url, e.g.
     * Slug should match regex: [\w\d-_]+
     *
     * ```php
     *  return 'user'; // url will be /admin/manage/user[<id>[/<action]]
     * ```
     *
     * @return string
     */
    public static function adminSlug();

}
