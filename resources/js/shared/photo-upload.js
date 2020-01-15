import toastr from 'toastr'
import http from '../shared/http-service'

const state = {
    file: null
}

const $upload = {
    fileInput: null,
    input: null,
    container: null
}

const renderPreview = () => {
    const src = $upload.input.val()
    if (src) {
        $upload.container.html(`<img class="img-fluid" src="${src}">`)
    }
}

const onContainerClick = event => {
    event.preventDefault()
    $upload.fileInput.trigger('click')
}

const onFileChange = event => {
    const [file] = event.target.files
    if (!file) {
        return null
    }
    if (!file.type.includes('image/')) {
        return toastr.error('Invalid file type.')
    }
    const src = URL.createObjectURL(file)
    const img = new Image()
    img.onload = () => {
        if (img.height >= img.width) {
            return toastr.error('Photo has to be landscape.')
        }
        state.file = file
        $upload.input.val(img.src)
        renderPreview()
    }
    img.src = src
}

const photoUpload = {
    init() {
        $upload.input = $('[data-photo-input]')
        $upload.fileInput = $('[data-photo-file-input]')
        $upload.container = $('.photo-upload-control')

        $upload.container.on('click', onContainerClick)
        $upload.fileInput.on('change', onFileChange)
    },
    upload() {
        if (!state.file) {
            return Promise.resolve()
        }
        const data = new FormData()
        const config = { headers: { 'content-type': 'multipart/form-data' } }
        data.append('file', state.file, state.file.name)
        return http
            .post('/upload/photo', data, config)
            .then(({ data }) => {
                $upload.input.val(data.url)
                state.file = null
                return data.url
            })
            .catch(err => {
                toastr.error('Upload error.')
                return Promise.reject(err)
            })
    }
}

export default photoUpload
