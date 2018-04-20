<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [
    'uses' => 'PostController@getBlogIndex',
    'as' => 'blog.index'
]);
Route::get('/blog', [
    'uses' => 'PostController@getBlogIndex',
    'as' => 'blog.index'
]);
Route::get('/blog/{post_id}&{end}', [
    'uses' => 'PostController@getSinglePost',
    'as' => 'blog.single'
]);
Route::get('/blog/{author}/posts', [
    'uses' => 'PostController@getAuthorPosts',
    'as' => 'blog.author.posts'
]);
Route::get('/tags/{tag_id}/posts', [
    'uses' => 'PostController@getTagPosts',
    'as' => 'blog.tag.posts'
]);
Route::get('/category/{category_id}/posts', [
    'uses' => 'PostController@getCategoryPosts',
    'as' => 'blog.category.posts'
]);
Route::get('/blog/search', [
    'uses' => 'PostController@getSearchPosts',
    'as' => 'blog.search.posts'
]);

/* other routes*/
Route::get('/about', [
    'uses' => 'ContactMessageController@getAboutPage',
    'as' => 'about'
]);

Route::get('/contact', [
    'uses' => 'ContactMessageController@getContactIndex',
    'as' => 'contact'
]);

Route::post('/contact/sendmail', [
    'uses' => 'ContactMessageController@postSendMessage',
    'as' => 'contact.send'
]);
Route::get('/admin/login', [
    'uses' => 'AdministController@getLogin',
    'as' => 'login'
]);

Route::post('/admin/login', [
    'uses' => 'AdministController@postLogin',
    'as' => 'login'
]);
Route::post('/blog/post/comment', [
    'uses' => 'PostController@postCommentOnPost',
    'as' => 'blog.post.comment'
]);
Route::post('/post/comment/commentoncomment', [
    'uses' => 'PostController@postCommentOnComment',
    'as' => 'post.comment.commentoncomment'
]);

Route::group([
    'prefix' => '/admin',
    'middleware'=>['auth','guest']
], function () {
    Route::get('/', [
        'uses'=>'AdministController@getIndex',
            'as'=>'admin.index'
        ]
    );
    Route::get('/blog/post/photo/delete/{post_id}&{photo_id}', [
        'uses' => 'PostController@getDeletePostPhoto',
        'as' => 'blog.post.photo.delete'
    ]);
    Route::get('/logout', [
        'uses' => 'AdministController@getLogout',
        'as' => 'logout'
    ]);

    Route::get('/blog/posts', [
            'uses'=>'PostController@getPostIndex',
            'as'=>'admin.blog.index'
        ]
    );
    Route::get('/blog/categories', [
            'uses'=>'CategoryController@getCategoryIndex',
            'as'=>'admin.blog.categories'
        ]
    );
    Route::get('/blog/post/{post_id}&{end}', [
            'uses'=>'PostController@getSinglePost',
            'as'=>'admin.blog.post'
        ]
    );
    Route::get('/blog/posts/create', [
            'uses'=>'PostController@getCreatePost',
            'as'=>'admin.blog.create_post'
        ]
    );
    Route::post('/blog/post/create', [
            'uses'=>'PostController@postCreatePost',
            'as'=>'admin.blog.post.create'
        ]
    );
    Route::post('/blog/category/create', [
            'uses'=>'CategoryController@postCreateCategory',
            'as'=>'admin.blog.category.create'
        ]
    );
    Route::get('/blog/post/{post_id}/edit', [
            'uses'=>'PostController@getUpdatePost',
            'as'=>'admin.blog.post.edit'
        ]
    );

    Route::get('/blog/post/{post_id}/delete', [
            'uses'=>'PostController@getDeletePost',
            'as'=>'admin.blog.post.delete'
        ]
    );
    Route::post('/blog/post/update', [
            'uses'=>'PostController@postUpdatePost',
            'as'=>'admin.blog.post.update'
        ]
    );
    Route::post('/blog/categories/update', [
            'uses'=>'CategoryController@postUpdateCategory',
            'as'=>'admin.blog.category.update'
        ]
    );
    Route::get('/blog/category/{category_id}/delete', [
            'uses'=>'CategoryController@getDeleteCategory',
            'as'=>'admin.blog.category.delete'
        ]
    );
    Route::get('/contact/messages', [
        'uses' => 'ContactMessageController@getContactMessageIndex',
        'as' => 'admin.contact.index'
    ]);
    Route::get('/contact/message/{message_id}/delete', [
        'uses' => 'ContactMessageController@getDeleteMessage',
        'as' => 'admin.contact.delete'
    ]);
});