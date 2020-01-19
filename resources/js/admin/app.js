import dataForm from '../shared/data-form'
import router from '../shared/router'
import markdownEditor from '../shared/markdown-editor'
import dataTable from './modules/data-table'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('bootstrap')

document.addEventListener('DOMContentLoaded', () => {
    dataForm.init()
})

router.match('/admin/posts/all', () => {
    dataTable.init({
        resource: 'posts',
        searchBy: 'title',
        allowedActions: [
            dataTable.resourceAction.edit,
            dataTable.resourceAction.delete
        ],
        columns: [
            {
                name: 'title',
                sortable: true
            },
            {
                name: 'main_photo',
                displayName: 'Main photo',
                type: 'photo'
            }
        ]
    })
})

router.match('/admin/posts/:id/edit', () => {
    markdownEditor.init('[name="content"]')
})

router.match('/admin/users/all', () => {
    dataTable.init({
        resource: 'users',
        searchBy: 'name',
        crudActions: [
            dataTable.resourceAction.edit,
            dataTable.resourceAction.delete
        ],
        actions: [
            {
                type: 'success',
                icon: 'user',
                title: 'Activity',
                link: user => `/admin/users/${user.id}/activity`
            }
        ],
        columns: [
            {
                name: 'avatar',
                displayName: 'Avatar',
                type: 'photo'
            },
            {
                name: 'name',
                sortable: true
            },
            {
                name: 'email'
            }
        ]
    })
})
