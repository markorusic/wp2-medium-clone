import utils from './utils'
import formModule from './modules/formModule'
import photoUploadModule from './modules/photoUploadModule'
import dataTable from './modules/data-table'

window.dataTable = dataTable
window.Popper = require('popper.js').default
window.$ = window.jQuery = require('jquery')
require('jquery-ui-bundle')
require('jquery-ui-bundle/jquery-ui.css')
require('jquery-serializejson')
require('bootstrap')

document.addEventListener('DOMContentLoaded', () => {
    utils.init()
    formModule.init()
    photoUploadModule.init()
})
