<?php

namespace App\Enums;

abstract class UserActivityType {

    // Auth
    const REGISTER_SUCCESS = 'Registered successfully';
    const LOGIN_FAIL = 'Login failed';
    const LOGIN_SUCCESS = 'Logged in successfully';
    const LOGOUT_SUCCESS = 'Logged out successfully';

    // Profile
    const PROFILE_UPDATED = 'Profile updated';

    // User
    const USER_FOLLOW = 'Followed';
    const USER_UNFOLLOW = 'Unfollowed';

    // Post
    const POST_LIKE = 'Liked post';
    const POST_UNLIKE = 'Unliked post';
    const POST_COMMENT = 'Commented post';
    const POST_COMMENT_REMOVE = 'Removed comment from post';
    const POST_CREATE = 'Created post';
    const POST_UPDATE = 'Updated post';
    const POST_DELETE = 'Removed post';

}
