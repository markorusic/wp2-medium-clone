import toastr from 'toastr'
import auth from './auth'
import http from '../shared/http-service'
import asyncEventHandler from '../shared/async-event-handler'

const classType = {
    follow: 'btn-success',
    unfollow: 'btn-outline-success'
}

const onFollowClick = asyncEventHandler(event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const $follow = $(event.currentTarget)
    const $followText = $follow.find('span')
    const { userId } = $follow.data()

    return http.post(`/users/${userId}/follow`).then(() => {
        const isFollowing = $follow.hasClass(classType.follow)
        if (isFollowing) {
            $follow.removeClass(classType.follow).addClass(classType.unfollow)
            $followText.text('Follow')
            toastr.info('Unfollowed!')
        } else {
            $follow.removeClass(classType.unfollow).addClass(classType.follow)
            $followText.text('Following')
            toastr.success('Followed!')
        }
    })
})

const followAction = {
    init() {
        $('[data-user-action="follow"]').on('click', onFollowClick)
    }
}

export default followAction
