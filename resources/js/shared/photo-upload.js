import toastr from 'toastr'
import http from '../shared/http-service'

let props = {
    portraitImg: false,
    landscapeImg: false
}

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
        if (props.landscapeImg && img.height >= img.width) {
            return toastr.error('Photo has to be landscape.')
        } else if (props.portraitImg && img.width >= img.height) {
            return toastr.error('Photo has to be portrait.')
        }
        state.file = file
        $upload.input.val(img.src)
        renderPreview()
    }
    img.src = src
}

const photoUpload = {
    init(_props = {}) {
        props = { ...props, ..._props }
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
        const url = location.pathname.startsWith('/admin')
            ? '/admin/upload/photo'
            : '/upload/photo'

        data.append('photo', state.file, state.file.name)

        return http
            .post(url, data, config)
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
