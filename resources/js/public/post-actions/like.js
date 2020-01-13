import to from 'await-to-js'
import toastr from 'toastr'
import auth from '../auth'
import http from '../../shared/http-service'
import asyncEventHandler from '../../shared/async-event-handler'
import postActions from './index'

const onClick = asyncEventHandler(async event => {
    if (!auth.isAuthenticated()) {
        return toastr.info('Login to complete that action.')
    }
    const [err] = await to(http.post(`/posts/${postActions.id}/like`))
    if (err) {
        return toastr.error('Error occured during this action!')
    }

    const $like = $(event.currentTarget).find('i')
    const $likeCount = $like.next()
    const isLiked = $like.hasClass('fa-heart-o')
    const likeCount = parseInt($likeCount.text())

    if (isLiked) {
        $like.removeClass('fa-heart-o').addClass('fa-heart')
        $likeCount.text(likeCount + 1)
    } else {
        $like.removeClass('fa-heart').addClass('fa-heart-o')
        $likeCount.text(likeCount - 1)
    }
})

const like = {
    init() {
        $('[data-user-action="like"]').on('click', onClick)
    }
}

export default like
