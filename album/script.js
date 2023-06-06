const upload_image_button = document.querySelector("#upload-image-button");
const upload_image = document.querySelector("#upload-image");
const close_button = document.querySelector("#close-button");

upload_image_button.addEventListener("click", () => {
    upload_image.style.display = "block";
});
close_button.addEventListener("click", () => {
    upload_image.style.display = "none";
});

if(window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

const images = document.querySelectorAll(".image");

for(let i = 0; i < images.length; i++) {
    images[i].addEventListener("click", () => {
        let image_src = images[i].querySelector("img").src;

        let image = document.body.appendChild(document.createElement("div"));
        image.classList.add("image-viewer");
        image.innerHTML = "<a href='" + image_src + "' download><img src='" + image_src + "'></a>" + "<img id='close-image' src='../ikony/zamknij.svg' alt='Zamknij'>";

        const close_button = document.body.querySelector(".image-viewer").querySelector("#close-image");
        close_button.addEventListener("click", () => {
            document.body.removeChild(document.body.querySelector(".image-viewer"));
        });
    });
}