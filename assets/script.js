
document.addEventListener("DOMContentLoaded", () => {

    const avatarInput =
        document.getElementById("avatar");

    const avatarPreview =
        document.querySelector(".edit-avatar-preview");

    if(avatarInput && avatarPreview){

        avatarInput.addEventListener("change", function(){

            const file = this.files[0];

            if(file){

                avatarPreview.src =
                    URL.createObjectURL(file);

            }

        });

    }

});


const avatar =
document.getElementById("profileAvatar");

const modal =
document.getElementById("avatarModal");

const modalImg =
document.getElementById("avatarModalImg");

const closeBtn =
document.querySelector(".close-avatar");

if(
    avatar &&
    modal &&
    modalImg
){

    avatar.addEventListener("click", () => {

        modal.style.display = "flex";

        modalImg.src = avatar.src;

    });

}

if(closeBtn){

    closeBtn.addEventListener("click", () => {

        modal.style.display = "none";

    });

}

if(modal){

    modal.addEventListener("click", (e) => {

        if(e.target === modal){

            modal.style.display = "none";

        }

    });

}

document.addEventListener("DOMContentLoaded", () => {

    const slider =
        document.querySelector(".games-slider");

    const cards =
        document.querySelectorAll(".games-slider .game-card");

    let current = 0;

    function activateCard(){

        cards.forEach(card => {
            card.classList.remove("active");
        });

        cards[current].classList.add("active");

slider.scrollTo({
    left:
        cards[current].offsetLeft -
        (slider.clientWidth / 2) +
        (cards[current].clientWidth / 2),

    behavior: "smooth"
});

        current++;

        if(current >= cards.length){
            current = 0;
        }
    }

    activateCard();

    setInterval(activateCard, 3000);

});