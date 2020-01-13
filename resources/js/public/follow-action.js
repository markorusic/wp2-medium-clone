import to from 'await-to-js'
import toastr from 'toastr'
import auth from './auth'
import http from '../shared/http-service'
import asyncEventHandler from '../shared/async-event-handler'

const onFollowClick = asyncEventHandler(async event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const $follow = $(event.currentTarget)
    const { userId } = $follow.data()
    const [err] = await to(http.post(`/users/${userId}/follow`))
    if (err) {
        return toastr.error('Error occured during this action!')
    }

    const isFollowing = $follow.hasClass('btn-success')

    if (isFollowing) {
        $follow.removeClass('btn-success').addClass('btn-outline-success')
        $follow.text('Follow')
    } else {
        $follow.removeClass('btn-outline-success').addClass('btn-success')
        $follow.text('Following')
    }
})

const followAction = {
    init() {
        $('[data-user-action="follow"]').on('click', onFollowClick)
    }
}

export default followAction
