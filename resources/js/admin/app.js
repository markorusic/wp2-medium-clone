import dataForm from './modules/data-form'
import dataTable from './modules/data-table'
import photoUpload from './modules/photo-upload'

window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('jquery-serializejson')
require('bootstrap')

window.dataTable = dataTable
window.dataForm = dataForm

document.addEventListener('DOMContentLoaded', () => {
    photoUpload.init()
})
