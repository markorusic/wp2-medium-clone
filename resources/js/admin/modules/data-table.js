import debounce from 'lodash/debounce'
import http from '../../shared/http-service'

let props = {
    resource: null,
    searchBy: null,
    columns: []
}

let state = {
    sort: ''
}

const $dom = {
    root: null,
    search: null,
    table: null,
    pagination: null
}

const view = {
    renderContainer() {
        const html = `
    <div id="${props.resource}-data-table" class="container py-2">
        <div class="card-header">
        <div class="flex-sp-between">
            <h4 class="bold uc-first">${props.resource}</h4>
            <span>
            <a class="btn btn-success btn-sm" href="/admin/${
                props.resource
            }/create">
                <i class="fa fa-plus" aria-hidden="true"></i> 
                Create
            </a>
            </span>
        </div>
        ${(() => {
            if (props.searchBy) {
                return `
                <div class="mt-1">
                <input
                    type="text"
                    class="resource-table-search form-control"
                    placeholder="Search"
                    data-resource-search
                >
                </div>
            `
            }
            return ''
        })()}
        </div>
    
    <table class="table resource-table" data-resource-table>
    </table>
    <div data-resource-pagination></div>
    `
        $('body main').html(html)
        return $(`#${props.resource}-data-table`)
    },
    renderLoader() {
        $dom.table.html(`
    <div style="display: flex; height: 620px; justify-content: center; align-items: center; background-color: #428bca12;">
    <div class="spinner-border text-primary" role="status">
        <span class="sr-only">Loading...</span>
    </div>
    </div>
    `)
    },
    renderTable({ content = [], pagination = {} }) {
        const tableHtml = `
    <thead>
        <tr>
        <th>#</th>
        ${(() =>
            props.columns
                .map(
                    column => `
            <th class="uc-first clickable" data-sort="${column}">
            <span>${column.split('_').join(' ')}<span>
            <span><i class="fa fa-sort" aria-hidden="true"></i></span>
            </th>
        `
                )
                .join(''))()}
        <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        ${(() =>
            content.map(
                (item, index) => `
        <tr data-id="${item.id}">
            <td>${(pagination.page - 1) * pagination.size + index + 1}</td>
            ${(() =>
                props.columns
                    .map(column => {
                        switch (column) {
                            case 'main_photo':
                                return `
                    <td data-name="main_photo">
                    <img src="${item[column]}" alt="Photo not found" class="table-img">
                    </td>`
                            default:
                                return `<td data-name="${column}">${item[column]}</td>`
                        }
                    })
                    .join(''))()}
            
            <td class="flex resource-actions">
                <a class="btn btn-primary white-txt mr-2 btn-sm"
                href="/admin/${props.resource}/edit?id=${item.id}" 
                >
                <i class="fa fa-pencil" aria-hidden="true"></i>
                </a>
                <a class="btn btn-danger white-txt btn-sm"
                data-delete="/admin/${props.resource}/delete?id=${item.id}"
                >
                <i class="fa fa-trash-o" aria-hidden="true"></i>
                </a>
            </td>
        </tr>
        `
            ))()}
    </tbody>
    `
        const pages = [
            ...Array(
                Math.ceil(pagination.totalElements / pagination.size)
            ).keys()
        ].map(page => page + 1)

        const paginationHtml = `
    <ul class="pagination">
        <li class="page-item${
            pagination.page > 1 ? '' : ' disabled'
        }" data-page="${pagination.page - 1}">
        <a class="page-link" href="#">
            <span aria-hidden="true">&laquo;</span>
        </a>
        </li>
        ${pages
            .map(
                page =>
                    `<li class="page-item${
                        pagination.page === page ? ' active' : ''
                    }" data-page="${page}"><a class="page-link" href="#">${page}</a></li>`
            )
            .join('')}
        <li class="page-item${
            pagination.page < pages.length - 1 ? '' : ' disabled'
        }" data-page="${pagination.page + 1}">
            <a class="page-link" href="#">
            <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>`
        $dom.table.html(tableHtml)
        $dom.pagination.html(paginationHtml)
    },
    renderErrorTable() {
        $dom.pagination.html('')
        $dom.table.html(`
    <div class="alert alert-warning mt-3 text-center" role="alert">
    Error happend while loading ${props.resource}.
    </div>
    `)
    }
}

const cacheDom = () => {
    $dom.root = view.renderContainer()
    $dom.search = $dom.root.find('[data-resource-search]')
    $dom.table = $dom.root.find('[data-resource-table]')
    $dom.pagination = $dom.root.find('[data-resource-pagination]')
}

const bindSearchEvent = () => {
    $dom.search.on('keyup', debounce(handleSearch, 400))
}

const bindTableEvents = () => {
    $dom.table.find('[data-sort]').on('click', handleSort)
    $dom.table.find('[data-delete]').on('click', handleRecordDelete)
    $dom.pagination.find('[data-page]').on('click', handlePageChange)
}

// Fetch logic
const fetchContent = ({ page = 0, size = 10, ...rest } = {}) => {
    const restString = Object.entries(rest)
        .filter(([, value]) => !!value)
        .map(item => item.join('='))
        .join('&')
    const url = `/admin/${props.resource}?page=${page}&size=${size}${
        restString ? '&' + restString : ''
    }`
    return http.get(url).then(res => res.data)
}

// Load data procedure
const loadData = options => {
    view.renderLoader()
    return fetchContent(options)
        .then(({ data, current_page, per_page, total }) => {
            view.renderTable({
                content: data,
                pagination: {
                    page: current_page,
                    size: per_page,
                    totalElements: total
                }
            })
            bindTableEvents()
        })
        .catch(err => {
            view.renderErrorTable(err)
        })
}

// Event handlers
const handleSearch = event => {
    loadData({
        sort: state.sort,
        [props.searchBy]: event.target.value
    })
}

const handleSort = event => {
    const { sort } = $(event.currentTarget).data()
    const [sortParam, sortOrder] = state.sort.split(',')
    const order =
        sortParam !== sort ? 'desc' : sortOrder === 'desc' ? 'asc' : 'desc'

    state.sort = `${sort},${order}`

    loadData({
        sort: state.sort,
        page: $dom.pagination.find('.active').data().page,
        [props.searchBy]: $dom.search.val()
    })
}

const handleRecordDelete = event => {
    const $el = $(event.currentTarget)
    const $resourceEl = $el.parent().parent()
    const endpoint = $el.data().delete
    if (!endpoint || !confirm('Are you sure?')) {
        return null
    }
    http.delete(endpoint)
        .then(() => {
            $resourceEl.fadeOut()
        })
        .catch(error => {
            alert('error')
            console.log(error)
        })
}

const handlePageChange = event => {
    event.preventDefault()
    const { page } = $(event.currentTarget).data()
    loadData({
        page,
        sort: state.sort,
        [props.searchBy]: $dom.search.val()
    })
}

const dataTable = {
    init(configProps) {
        props = { ...props, ...configProps }
        $(() => {
            cacheDom()
            loadData().then(bindSearchEvent)
        })
    }
}

export default dataTable
