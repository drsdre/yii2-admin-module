<?php


namespace asdfstudio\admin;


interface AdminItemInterface
{
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
     *          'visible' => true, // visible item in list, view, create and update
     *          'editable' => false, // edit item in update and create
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
