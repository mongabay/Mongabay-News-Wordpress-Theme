const domain = window.location.origin;
const url = new URL(window.location.href);
const hashArray = url.hash.replace(/^#\/?/, "").split("/").filter(Boolean);
const pathArray = url.pathname.split("/").filter(Boolean);
const shortIdentifier = url.searchParams.get("short")
  || (pathArray.length > 1 ? pathArray[pathArray.length - 1] : hashArray[hashArray.length - 1]);

const shortsDialog = document.querySelector("#shorts-dialog");
const backButton = document.querySelector("a.back-button");
const shareButton = document.querySelector("a.share");
const shortsOverview = document.querySelector("#shorts-overview");
const shortsShare = document.querySelector("#shorts-share");

function initPreviewDialog(postUrl, shareUrl, title, byline, date, content) {
  const dialogTitle = shortsDialog.querySelector(".dialog-body .title h2");
  const dialogContent = shortsDialog.querySelector(".dialog-body .post-content .post-content-body");
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
  if (!/^[\da-z-]+$/i.test(shortIdentifier || "") || !shortsDialog) {
    return;
  }

  let shortData = window.shortsPreviewData;
  if (!shortData) {
    const isNumericId = /^\d+$/.test(shortIdentifier);
    const queryId = isNumericId ? shortIdentifier : JSON.stringify(shortIdentifier);
    const queryIdType = isNumericId ? "DATABASE_ID" : "SLUG";
    const query = `query{shortArticle(idType:${queryIdType},id:${queryId}){title slug date content articleLink byline{nodes{name}}}}`;
    const response = await fetch(`${domain}/graphql?query=${encodeURIComponent(query)}`, {
      method: "GET",
      mode: "cors",
      cache: "default",
      credentials: "omit",
      headers: {
        "Content-Type": "application/json",
      },
      redirect: "follow",
      referrerPolicy: "no-referrer",
    });

    if (!response.ok) {
      throw new Error(`Short preview request failed with status ${response.status}`);
    }

    const { errors, data } = await response.json();
    if (errors?.length || !data?.shortArticle) {
      throw new Error(errors?.map((error) => error.message).join(", ") || "Short not found");
    }

    const { title, slug, byline, date, content, articleLink } = data.shortArticle;
    shortData = {
      title,
      slug,
      date,
      content,
      articleLink,
      byline: byline?.nodes?.[0]?.name || "",
    };
  }

  const { title, slug, byline, date, content, articleLink } = shortData;
  const shareUrlObject = new URL(window.location.href);
  const sharePath = pathArray.length > 1 ? pathArray.slice(0, -1) : pathArray;
  shareUrlObject.pathname = `/${sharePath.join("/")}/${slug}/`;
  shareUrlObject.searchParams.delete("short");
  shareUrlObject.hash = "";

  window.history.replaceState(null, "", shareUrlObject);
  const bylineName = typeof byline === "string" ? byline : byline?.nodes?.[0]?.name || "";
  initPreviewDialog(articleLink, shareUrlObject.toString(), title, bylineName, date, content);
}

shortsPreviewer().catch((error) => {
  console.error("Unable to open the shared short.", error);
});
