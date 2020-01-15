import toastr from 'toastr'
import auth from './auth'
import http from '../shared/http-service'
import asyncEventHandler from '../shared/async-event-handler'

const onFollowClick = asyncEventHandler(event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const $follow = $(event.currentTarget)
    const $followText = $follow.find('span')
    const { userId } = $follow.data()

    return http.post(`/users/${userId}/follow`).then(() => {
        const isFollowing = $follow.hasClass('btn-success')
        if (isFollowing) {
            $follow.removeClass('btn-success').addClass('btn-outline-success')
            $followText.text('Follow')
        } else {
            $follow.removeClass('btn-outline-success').addClass('btn-success')
            $followText.text('Following')
        }
    })
})

const followAction = {
    init() {
        $('[data-user-action="follow"]').on('click', onFollowClick)
    }
}

export default followAction
