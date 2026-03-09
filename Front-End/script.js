const likeBtn = document.querySelector(".like-btn");
const likeCount = document.querySelector(".like-count");

let liked = false;
let count = 1234;

likeBtn.addEventListener("click", () => {

    liked = !liked;

    if(liked){
        count++;
        likeBtn.innerHTML = '<i class="fa-solid fa-heart"></i>';
        likeBtn.style.color="red";
    }else{
        count--;
        likeBtn.innerHTML = '<i class="fa-regular fa-heart"></i>';
        likeBtn.style.color="white";
    }

    likeCount.textContent = count;

});