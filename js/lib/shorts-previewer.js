const domain = window.location.origin;
const hashArray = window.location.hash.endsWith("/")
  ? window.location.hash.slice(1, -1).split("/")
  : window.location.hash.slice(1).split("/");
const slug = hashArray[hashArray.length - 1];

const shortsDialog = document.querySelector("#shorts-dialog");
const backButton = document.querySelector("a.back-button");
const shareButton = document.querySelector("a.share");
const shortsOverview = document.querySelector("#shorts-overview");
const shortsShare = document.querySelector("#shorts-share");

function initPreviewDialog(postUrl, shareUrl, title, byline, date, content) {
  const dialogTitle = shortsDialog.querySelector(".dialog-body .title h2");
  const dialogContent = shortsDialog.querySelector(".dialog-body .post-content p");
  const dialogByline = shortsDialog.querySelector(".dialog-body .post-meta .byline");
  const dialogDate = shortsDialog.querySelector(".dialog-body .post-meta .date");
  const dialogArticleLink = shortsDialog.querySelector(".dialog-footer a.link");

  dialogTitle.textContent = title;
  dialogContent.innerHTML = content;
  dialogByline.textContent = byline;
  dialogDate.textContent = date;
  dialogArticleLink.href = postUrl;

  shortsDialog.showModal();

  shortsShare.innerHTML = shortsShare.innerHTML.replace(/{{title}}/g, title);
  shortsShare.innerHTML = shortsShare.innerHTML.replace(/{{postUrl}}/g, postUrl);
  shortsShare.innerHTML = shortsShare.innerHTML.replace(/{{shareUrl}}/g, shareUrl);

  shortsDialog.addEventListener("show", () => {
    shortsOverview.classList.remove("hidden");
    shortsShare.classList.add("hidden");
    backButton.classList.add("hidden");
  });

  shortsDialog.addEventListener("close", () => {
    shortsOverview.classList.remove("hidden");
    shortsShare.classList.add("hidden");
    backButton.classList.add("hidden");
  });
}

async function shortsPreviewer() {
  if (!slug) {
    return;
  }

  const response = await fetch(
    `${domain}/graphql?query=query{shortArticle(idType: DATABASE_ID, id: ${slug}) {title date content articleLink byline {nodes{name}}}}`,
    {
      method: "GET",
      mode: "cors",
      cache: "no-cache",
      credentials: "same-origin",
      headers: {
        "Content-Type": "application/json",
      },
      redirect: "follow",
      referrerPolicy: "no-referrer",
    },
  );

  const { errors, data } = await response.json();

  const { title, byline, date, content, articleLink } = data.shortArticle;
  const bylineName = byline.nodes[0].name;
  const shareUrl = `${domain}/shorts/#/${slug}/`;

  initPreviewDialog(articleLink, shareUrl, title, bylineName, date, content);
}

shortsPreviewer();
