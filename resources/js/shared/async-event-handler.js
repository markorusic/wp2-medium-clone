const asyncEventHandler = fn => {
    let isLoading = false
    return event => {
        event.preventDefault()
        if (!isLoading) {
            isLoading = true
            return fn(event).finally(() => {
                isLoading = false
            })
        }
    }
}

export default asyncEventHandler
