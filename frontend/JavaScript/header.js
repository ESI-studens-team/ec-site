const searchBox = document.getElementById("searchBox");
const searchResult = document.getElementById("searchResult");

const categoryBtn = document.getElementById("categoryBtn");
const categoryMenu = document.getElementById("categoryMenu");
const categoryArrow = document.getElementById("categoryArrow");

// 表示・非表示を切り替える関数
function toggleMenu(button, menu, arrow = null, isLink = false) {
  button.addEventListener("click", (e) => {
    if (isLink) e.preventDefault();
    e.stopPropagation();

    const isOpen = menu.style.display === "block";

    menu.style.display = isOpen ? "none" : "block";

    if (arrow) {
      arrow.classList.toggle("open", !isOpen);
    }
  });
}

// 検索メニュー
toggleMenu(searchBox, searchResult);

// カテゴリーメニュー
toggleMenu(categoryBtn, categoryMenu, categoryArrow, true);

// 外をクリックしたら閉じる
document.addEventListener("click", () => {
  searchResult.style.display = "none";

  categoryMenu.style.display = "none";
  categoryArrow.classList.remove("open");
});