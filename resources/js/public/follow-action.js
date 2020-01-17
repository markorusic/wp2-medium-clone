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

    return http.post(`/users/${userId}/follow`).then(({ data }) => {
        const $followText = $follow.find('span')
        $('[data-followers-count]').text(data.followers_count)
        if (data.followed) {
            $follow.removeClass(classType.unfollow).addClass(classType.follow)
            $followText.text('Following')
            toastr.success('Followed!')
        } else {
            $follow.removeClass(classType.follow).addClass(classType.unfollow)
            $followText.text('Follow')
            toastr.info('Unfollowed!')
        }
    })
})

const followAction = {
    init() {
        $('[data-follow-user]').on('click', onFollowClick)
    }
}

export default followAction
