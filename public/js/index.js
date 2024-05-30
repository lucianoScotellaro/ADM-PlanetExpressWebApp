const rateUserBtn = document.querySelector('#rate-user-btn')
const userProfileMain = document.querySelector('#profile-user-main')
const closeFormBtn = document.querySelector('#close-form-btn')

rateUserBtn.addEventListener('click', function (){
    userProfileMain.classList.add('show-rate-user-form')
})

closeFormBtn.addEventListener('click', function (){
    userProfileMain.classList.remove('show-rate-user-form')
})
