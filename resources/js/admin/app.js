import dataForm from './modules/data-form'
import dataTable from './modules/data-table'
import photoUpload from './modules/photo-upload'
import router from '../shared/router'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('jquery-serializejson')
require('bootstrap')

window.dataTable = dataTable
window.dataForm = dataForm

document.addEventListener('DOMContentLoaded', () => {
    photoUpload.init()
})

router.match('/admin/posts/:id/edit', () => {
    require('simplemde/dist/simplemde.min.css')
    const SimpleMDE = require('simplemde')

    new SimpleMDE({
        autoDownloadFontAwesome: false,
        element: document.getElementById('content')
    })
})
