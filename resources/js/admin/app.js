import dataForm from './modules/data-form'
import dataTable from './modules/data-table'
import router from '../shared/router'
import markdownEditor from '../shared/markdown-editor'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('jquery-serializejson')
require('bootstrap')

router.match('/admin/posts/show-all', () => {
    dataTable.init({
        resource: 'posts',
        searchBy: 'title',
        actions: [
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
    dataForm.init()
})
