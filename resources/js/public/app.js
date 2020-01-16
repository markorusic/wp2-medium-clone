import router from '../shared/router'
import markdownEditor from '../shared/markdown-editor'
import dataForm from '../shared/data-form'
import postActions from './post-actions'
import auth from './auth'
import followAction from './follow-action'
import navbarSearch from './navbar-search'
import userProfile from './user-profile'
import { fetchUserList } from './user-list'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('bootstrap')
window.auth = auth

document.addEventListener('DOMContentLoaded', () => {
    navbarSearch.init()
    followAction.init()
})

router.match('/posts/:id', ({ id }) => {
    const mde = markdownEditor.init('#content-ta')
    const $content = document.querySelector('#content')
    $content.innerHTML = mde.toHTML(mde.value())
    mde.remove()

    postActions.init(id)
})

router.match('/posts/create/_', () => {
    markdownEditor.init('[name="content"]')
    dataForm.init({
        onCreateSuccess: ({ response }) => {
            router.redirect(`/posts/${response.data.id}`)
        }
    })
})

router.match('/posts/:id/edit', () => {
    markdownEditor.init('[name="content"]')
    dataForm.init({
        photoUploadProps: {
            landscapeImg: true
        }
    })
})

router.match('/users/:id', ({ id }) => {
    $('#followers-modal').on(
        'show.bs.modal',
        fetchUserList(`/users/${id}/followers`)
    )
    $('#following-modal').on(
        'show.bs.modal',
        fetchUserList(`/users/${id}/following`)
    )
})

router.match('/users/:id/edit', () => {
    dataForm.init()
})
