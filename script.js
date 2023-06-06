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
const new_name = document.querySelector("#new-name");
const close_button_2 = document.querySelector("#close-button-2");
const new_name_input = document.querySelector("#new-name-input");
const current_name = document.querySelector("#current-name");

for(let i = 0; i < folders.length; i++) {
    folders[i].addEventListener("contextmenu", (e) => {
        e.preventDefault();

        if(folders[i].childNodes.length < 4) {
            let options_menu = folders[i].appendChild(document.createElement("div"));
            options_menu.classList.add("options-menu");
            options_menu.innerHTML += "<span id=menu-" + i + ">Zmień nazwę</span>";
            options_menu.innerHTML += "<a href='usun_album/?nazwa=" + folders[i].querySelector("a").querySelector("p").innerHTML + "'>Usuń</a>";
            
            // usuwanie poprzednich menu opcji
            for(let j = 0; j < folders.length; j++) {
                if(folders[j].childNodes.length >= 4 && j != i) {
                    folders[j].removeChild(folders[j].querySelector("div"));
                }
            }

            options_menu.querySelector("span").addEventListener("click", () => {
                new_name.style.display = "block";
                new_name_input.focus();
                current_name.value = folders[i].querySelector("a").querySelector("p").innerHTML;
            });
        }
        else {
            folders[i].removeChild(folders[i].querySelector("div"));
        }
    });
}

close_button_2.addEventListener("click", () => {
    new_name.style.display = "none";
});

if(window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}