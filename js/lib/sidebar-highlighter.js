const sidebarMenu = document.querySelector("ul.no-bullets");
const targetsArray = [];

sidebarMenu.childNodes.forEach((el) => {
  if (el.nodeName === "LI") {
    const link = el.querySelector("a");
    const target = link.getAttribute("href");
    targetsArray.push(target);
  }
});

window.addEventListener("scroll", function () {
  const scroll = window.scrollY;

  targetsArray.forEach((target, index) => {
    const targetOffset = document.querySelector(target).offsetTop;
    if (
      scroll >= targetOffset - 20 &&
      targetsArray[index + 1] &&
      scroll < document.querySelector(targetsArray[index + 1]).offsetTop - 20
    ) {
      sidebarMenu.childNodes.forEach((el) => {
        if (el.nodeName === "LI") {
          el.classList.remove("active");
        }
      });
      sidebarMenu.childNodes[index].classList.add("active");
    }

    if (scroll >= document.querySelector(targetsArray[targetsArray.length - 1]).offsetTop - 20) {
      sidebarMenu.childNodes.forEach((el) => {
        if (el.nodeName === "LI") {
          el.classList.remove("active");
        }

        if (
          el.nodeName === "LI" &&
          el === sidebarMenu.childNodes[sidebarMenu.childNodes.length - 1]
        ) {
          el.classList.add("active");
        }
      });
    }
  });
});
