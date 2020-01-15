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
    const userId = $follow.data().followUser

    return http.post(`/users/${userId}/follow`).then(() => {
        const $followText = $follow.find('span')
        const $followersCount = $('[data-followers-count]')

        const followersCount = parseInt($followersCount.text())
        const isFollowing = $follow.hasClass(classType.follow)

        if (isFollowing) {
            $follow.removeClass(classType.follow).addClass(classType.unfollow)
            $followText.text('Follow')
            $followersCount.text(followersCount - 1)
            toastr.info('Unfollowed!')
        } else {
            $follow.removeClass(classType.unfollow).addClass(classType.follow)
            $followText.text('Following')
            $followersCount.text(followersCount + 1)
            toastr.success('Followed!')
        }
    })
})

const followAction = {
    init() {
        $('[data-follow-user]').on('click', onFollowClick)
    }
}

export default followAction
