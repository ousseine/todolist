// enable and disable list-tasks-end
let toggle = true
const tasksEnd = document.getElementById('list-tasks-end')

document.getElementById('task-end').addEventListener('click', () => {
    const bi = document.querySelector('#task-end .bi')

    if(toggle) {
        bi.classList.remove('bi-chevron-down')
        bi.classList.add('bi-chevron-right')
        tasksEnd.style.display = 'none'
        toggle = false
    } else {
        bi.classList.remove('bi-chevron-right')
        bi.classList.add('bi-chevron-down')
        tasksEnd.style.display = 'block'
        toggle = true
    }
})