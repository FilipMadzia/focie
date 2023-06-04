const new_folder_button = document.querySelector("#new-folder-button");
const new_folder = document.querySelector("#new-folder");
const close_button = document.querySelector("#close-button");
const name = document.querySelector("#name");

new_folder_button.addEventListener("click", () => {
    new_folder.style.display = "block";
    name.focus();
});
close_button.addEventListener("click", () => {
    new_folder.style.display = "none";
});

const folders = document.querySelectorAll(".folder");

for(let i = 0; i < folders.length; i++) {
    folders[i].addEventListener("contextmenu", (e) => {
        e.preventDefault();

        if(folders[i].childNodes.length < 4) {
            let options_menu = folders[i].appendChild(document.createElement("div"));
            options_menu.classList.add("options-menu");
            options_menu.innerHTML += "<span>Zmień nazwę</span>";
            options_menu.innerHTML += "<a href='usun_album/?nazwa=" + folders[i].querySelector("a").querySelector("p").innerHTML + "'>Usuń</a>";
            
            // usuwanie poprzednich menu opcji
            for(let j = 0; j < folders.length; j++) {
                if(folders[j].childNodes.length >= 4 && j != i) {
                    folders[j].removeChild(folders[j].querySelector("div"));
                }
            }
        }
        else {
            folders[i].removeChild(folders[i].querySelector("div"));
        }
    });
}

if(window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}