const sidebarHeader = document.querySelector(".sidebar-header");
const gameList = document.querySelector(".game-list");
const arrow = document.querySelector(".arrow");

sidebarHeader.addEventListener("click", () => {

  gameList.classList.toggle("hidden");

  if (gameList.classList.contains("hidden")) {
    arrow.textContent = "▶";
  } else {
    arrow.textContent = "▼";
  }

});