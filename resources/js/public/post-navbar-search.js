import debounce from 'lodash/debounce'
import http from '../shared/http-service'
import templateRender from '../shared/template-render'

const $search = {
    button: null,
    input: null,
    results: null
}

const onToggleClick = event => {
    event.preventDefault()
    if ($search.input.hasClass('slide-in')) {
        $search.input.removeClass('slide-in').addClass('slide-out')
        $search.results.addClass('d-none')
        $search.input.blur()
    } else {
        $search.input.removeClass('slide-out').addClass('slide-in')
        $search.input.focus()
        if ($search.results.find('[data-content]').children().length > 0) {
            $search.results.removeClass('d-none')
        }
    }
}

const searchItemTemplate = item => `
    <a class="d-flex pointer text-dark" href="${item.url}">
        <img class="avatar-sm mr-3 mb-2" src="${item.img}" alt="${item.title}" />
        <span>${item.title}</span>
    </a>
`

const searchItemsTemplate = (title, items = []) =>
    templateRender.if(
        items.length > 0,
        `<div>
            <div class="d-flex flex-column justify-content-between pb-2 border-bottom">
                <h3 class="m-0">${title}</h3>
            </div>
            <div class="d-flex flex-column py-2" data-content>
                ${templateRender.list(items, searchItemTemplate)}
            </div>
        </div>`
    )

const onSearchPress = event => {
    const term = event.target.value
    const isEsc = event.keyCode === 27
    if (isEsc) {
        return onToggleClick(event)
    }
    if (term === '') {
        return $search.results.html('').addClass('d-none')
    }
    const config = {
        params: {
            term,
            size: 3
        }
    }
    http.get('/content/search', config).then(response => {
        const { posts, users, categories } = response.data
        const noData =
            posts.data.length === 0 &&
            users.data.length === 0 &&
            categories.data.length === 0
        if (noData) {
            return $search.results.addClass('d-none').html('')
        }
        $search.results.removeClass('d-none').html(`
            ${searchItemsTemplate(
                'Posts',
                posts.data.map(({ id, title, main_photo }) => ({
                    title,
                    url: `/posts/${id}`,
                    img: main_photo
                }))
            )}
            ${searchItemsTemplate(
                'Users',
                users.data.map(({ id, name, avatar }) => ({
                    title: name,
                    url: `/users/${id}`,
                    img: avatar
                }))
            )}
            ${searchItemsTemplate(
                'Categories',
                categories.data.map(({ id, name, main_photo }) => ({
                    title: name,
                    url: `/posts/category/${id}`,
                    img: main_photo
                }))
            )}
        `)
    })
}

const navbarPostSearch = {
    init() {
        $search.button = $('#navbar-search-toggle')
        $search.input = $('#navbar-search-input')
        $search.results = $('#navbar-search-results')

        $search.button.on('click', onToggleClick)
        $search.input.on('keyup', debounce(onSearchPress, 400))
    }
}

export default navbarPostSearch
