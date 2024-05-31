const requestsLink = document.querySelector('#requests-link')
const requestsNavContainer = document.querySelector('#requests-nav-links-container')
const rateUserBtn = document.querySelector('#rate-user-btn')
const userProfileMain = document.querySelector('#profile-user-main')
const closeFormBtn = document.querySelector('#close-form-btn')

requestsLink.addEventListener('mouseenter', function(){
    requestsNavContainer.classList.add('show')
})

requestsLink.addEventListener('mouseleave', function(){
    requestsNavContainer.classList.remove('show')
})

rateUserBtn.addEventListener('click', function (){
    userProfileMain.classList.add('show-rate-user-form')
})

closeFormBtn.addEventListener('click', function (){
    userProfileMain.classList.remove('show-rate-user-form')
})
