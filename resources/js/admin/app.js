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
        title: 'Posts',
        baseUrl: '/admin/posts',
        searchBy: 'title',
        crudActions: [dataTable.crudAction.edit, dataTable.crudAction.delete],
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
        title: 'Users',
        baseUrl: '/admin/users',
        searchBy: 'name',
        crudActions: [dataTable.crudAction.edit, dataTable.crudAction.delete],
        actions: [
            {
                type: 'success',
                icon: 'user',
                title: 'Activity',
                link: user => `/admin/users/${user.id}/activity`
            },
            {
                type: 'success',
                icon: 'user',
                title: 'Comments',
                link: user => `/admin/users/${user.id}/comments/all`
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

router.match('/admin/users/:id/comments/all', ({ id }) => {
    dataTable.init({
        title: 'Comments',
        baseUrl: `/admin/users/${id}/comments`,
        crudActions: [dataTable.crudAction.edit, dataTable.crudAction.delete],
        columns: [
            {
                name: 'content',
                displayName: 'Comment'
            }
        ]
    })
})

router.match('/admin/categories/all', () => {
    dataTable.init({
        title: 'Categories',
        baseUrl: '/admin/categories',
        searchBy: 'name',
        crudActions: [
            dataTable.crudAction.create,
            dataTable.crudAction.edit,
            dataTable.crudAction.delete
        ],
        columns: [
            {
                name: 'name'
            },
            {
                name: 'description'
            },
            {
                name: 'main_photo',
                displayName: 'Main photo',
                type: 'photo'
            }
        ]
    })
})
