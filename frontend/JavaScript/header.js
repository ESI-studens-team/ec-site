const searchBox = document.getElementById("searchBox");
const searchResult = document.getElementById("searchResult");

searchBox.addEventListener("click", (e) => {
  e.stopPropagation();

  if (searchResult.style.display === "block") {
    searchResult.style.display = "none";
  } else {
    searchResult.style.display = "block";
  }
});

// 外をクリックしたら閉じる
document.addEventListener("click", () => {
  searchResult.style.display = "none";
});