import range from 'lodash/range'
import http from '../shared/http-service'
import templateRender from '../shared/template-render'

const $profile = {
    followers: null,
    following: null
}

const userTemplate = user => `
    <a class="d-flex align-items-center p-3 border-bottom" href="/users/${user.id}">
        <img class="avatar mr-3" src="${user.avatar}" alt="${user.name}">
        <span class="text-dark">${user.name}</span>
    </a>
`

const userListTempalte = users => `
    <div class="d-flex flex-column">
        ${templateRender.list(users, userTemplate)}
    </div>
`

const userListLoadingTempalte = (numbeOfItems = 5) => `
    <div class="d-flex flex-column">
        ${templateRender.list(
            range(0, numbeOfItems),
            () =>
                `<div class="border-bottom border-white" style="height: 82px; background-color: #ebe6e6"></div>`
        )}
    </div>
`

const userListAlertTemplate = message => `
    <div class="d-flex flex-column">
        <div class="alert alert-danger" role="alert">
            ${message}
        </div>
    </div>
`

const onModalShow = ({ id, param }) => event => {
    const $container = $(event.currentTarget).find('.modal-body')
    $container.html(userListLoadingTempalte())
    return http
        .get(`/users/${id}/${param}`)
        .then(response => {
            const users = response.data.data
            $container.html(userListTempalte(users))
        })
        .catch(() => {
            const message = `Error occured while fetching ${param} data.`
            $container.html(userListAlertTemplate(message))
        })
}

const userProfile = {
    init(id) {
        $profile.followers = $('#followers-modal')
        $profile.following = $('#following-modal')

        $profile.followers.on(
            'show.bs.modal',
            onModalShow({ id, param: 'followers' })
        )

        $profile.following.on(
            'show.bs.modal',
            onModalShow({ id, param: 'following' })
        )
    }
}

export default userProfile
