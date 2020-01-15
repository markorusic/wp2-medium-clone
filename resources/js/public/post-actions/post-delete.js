import toastr from 'toastr'
import asyncEventHandler from '../../shared/async-event-handler'
import router from '../../shared/router'
import http from '../../shared/http-service'

const onDeleteClick = asyncEventHandler(event => {
    if (confirm('Are you sure that you want to delete this post?')) {
        const url = $(event.currentTarget).attr('href')
        return http.delete(url).then(() => {
            toastr.success('Successfully deleted!')
            router.redirect('')
        })
    }
    return Promise.resolve()
})

const postDelete = {
    init() {
        $('#delete-post').on('click', onDeleteClick)
    }
}

export default postDelete
