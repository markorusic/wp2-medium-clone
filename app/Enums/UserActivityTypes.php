<?php

namespace App\Enums;

abstract class UserActivityType {

    // Auth
    const REGISTER_SUCCESS = 'REGISTER_SUCCESS';
    const LOGIN_FAIL = 'LOGIN_FAIL';
    const LOGIN_SUCCESS = 'LOGIN_SUCCESS';
    const LOGOUT_SUCCESS = 'LOGOUT_SUCCESS';

    // Profile
    const PROFILE_UPDATED = 'PROFILE_UPDATED';

    // User
    const USER_FOLLOW = 'USER_FOLLOW';
    const USER_UNFOLLOW = 'USER_UNFOLLOW';

    // Post
    const POST_LIKE = 'POST_LIKE';
    const POST_UNLIKE = 'POST_UNLIKE';
    const POST_COMMENT = 'POST_COMMENT';
    const POST_COMMENT_REMOVE = 'POST_COMMENT_REMOVE';
    const POST_CREATE = 'POST_CREATE';
    const POST_UPDATE = 'POST_UPDATE';
    const POST_DELETE = 'POST_DELETE';

}
