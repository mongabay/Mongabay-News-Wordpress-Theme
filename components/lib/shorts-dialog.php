<?php
function get_shorts_dialog($show_more = false)
{ ?>
  <dialog id="shorts-dialog">
    <div class="dialog-content ph--40 pv--80">
      <div class="dialog-header">
        <a class="back back-button icon icon-left hidden"></a>
        <a class="close close-button icon icon-cancel"></a>
      </div>
      <div id="shorts-overview">
        <div class="dialog-body">
          <div class="container in-column gap--20">
            <div class="title headline gap--8">
              <h2></h2>
              <div class="post-meta">
                <span class="byline"></span>
                <span class="date"></span>
              </div>
            </div>
            <div class="post-content">
              <p></p>
            </div>
          </div>
        </div>
        <div class="dialog-footer container in-row gap--20 pv--16">
          <a class="theme--button secondary simple share"><?php _e('Share Short', 'mongabay'); ?></a>
          <a class="theme--button secondary simple link" href=""><?php _e('Read Full Article', 'mongabay'); ?></a>
        </div>
      </div>
      <div id="shorts-share" class="dialog-content ph--40 pv--40 hidden">
        <?php share_icons_grid('shorts'); ?>
      </div>
    </div>
  </dialog>
  <?php if ($show_more) { ?>
    <div id="posts"></div>
    <div class="container centered pv--40">
      <a class="theme--button outlined load-more-button" data-post-type="short-article"><?php _e('Load more', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  <?php } ?>

  <script>
    const sharingGridCopy = document.getElementById('shorts-share').innerHTML;

    function initDialog() {
      const shortsTriggers = document.querySelectorAll('.shorts-trigger');
      const shortsDialog = document.querySelector('#shorts-dialog');
      const closeDialogButton = document.querySelector('a.close-button');
      const backButton = document.querySelector('a.back-button');
      const shareButton = document.querySelector('a.share');
      const shortsOverview = document.querySelector('#shorts-overview');
      const shortsShare = document.querySelector('#shorts-share');

      let title = '';
      let postUrl = '';
      let shareUrl = '';

      shortsTriggers.forEach(trigger => {
        trigger.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          const postTitle = e.target.closest('.shorts-trigger').querySelector('.title').textContent.trim();
          const excerpt = e.target.closest('.shorts-trigger').querySelector('.post-excerpt').innerHTML;
          const byline = e.target.closest('.shorts-trigger').querySelector('.post-meta .byline').textContent;
          const date = e.target.closest('.shorts-trigger').querySelector('.post-meta .date').textContent;
          const url = e.target.closest('.shorts-trigger').dataset.articlelink;
          const sUrl = e.target.closest('.shorts-trigger').dataset.shareurl;

          postUrl = url;
          shareUrl = sUrl;
          title = postTitle;

          const dialogTitle = shortsDialog.querySelector('.dialog-body .title h2');
          const dialogContent = shortsDialog.querySelector('.dialog-body .post-content p');
          const dialogByline = shortsDialog.querySelector('.dialog-body .post-meta .byline');
          const dialogDate = shortsDialog.querySelector('.dialog-body .post-meta .date');
          const dialogArticleLink = shortsDialog.querySelector('.dialog-footer a.link');

          dialogTitle.textContent = postTitle;
          dialogContent.innerHTML = excerpt;
          dialogByline.textContent = byline;
          dialogDate.textContent = date;
          dialogArticleLink.href = postUrl;

          shortsDialog.showModal();

          shortsShare.innerHTML = shortsShare.innerHTML.replace(/{{title}}/g, title);
          shortsShare.innerHTML = shortsShare.innerHTML.replace(/{{postUrl}}/g, postUrl);
          shortsShare.innerHTML = shortsShare.innerHTML.replace(/{{shareUrl}}/g, shareUrl);
        });
      });

      function close() {
        document.getElementById("copy-url").value = '{{shareUrl}}';
        shortsDialog.close();
      };

      function back() {
        shortsOverview.classList.remove('hidden');
        shortsShare.classList.add('hidden');
        backButton.classList.add('hidden');
      }

      shareButton.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        shortsOverview.classList.add('hidden');
        shortsShare.classList.remove('hidden');
        backButton.classList.remove('hidden');

        while (true) {
          const copyText = document.getElementById("copy-url");

          if (copyText) {

            function copyURL() {
              copyText.select();
              copyText.setSelectionRange(0, 99999);
              navigator.clipboard.writeText(copyText.value);
            }

            function emailArticle() {
              window.alert("Email this article?");
              window.location.href = "mailto:?subject=" + encodeURIComponent(document.title) + "&body=" + encodeURIComponent(window.location.href);
            }

            document.querySelector(".icon-share-copy").addEventListener("click", copyURL);
            document.querySelector("a.email").addEventListener("click", emailArticle);

            break;
          } else {
            new Promise((resolve, reject) => {
              setTimeout(() => {
                resolve();
              }, 100);
            });
          }
        }
      });

      shortsDialog.addEventListener('show', () => {
        shortsOverview.classList.remove('hidden');
        shortsShare.classList.add('hidden');
        backButton.classList.add('hidden');
      });

      shortsDialog.addEventListener('close', () => {
        shortsOverview.classList.remove('hidden');
        shortsShare.classList.add('hidden');
        backButton.classList.add('hidden');
        document.getElementById("shorts-share").innerHTML = sharingGridCopy;
      });

      closeDialogButton.addEventListener('click', close);
      backButton.addEventListener('click', back);
    }

    initDialog();
  </script>

<?php } ?>
