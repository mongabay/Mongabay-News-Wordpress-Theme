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
              <div class="post-content-body"></div>
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
  <dialog id="shorts-video-dialog">
    <a class="close close-video-button icon icon-cancel"></a>
    <div class="shorts-video-modal-body"></div>
  </dialog>
  <?php if ($show_more) { ?>
    <div id="posts"></div>
    <div class="container centered pv--40">
      <a class="theme--button outlined load-more-button" data-post-type="short-article"><?php _e('Load more', 'mongabay'); ?><span class="icon icon-right"></span></a>
    </div>
  <?php } ?>

  <script>
    const sharingGridCopy = document.getElementById('shorts-share').innerHTML;

    let activeAudioCard = null;
    let activeAudioElement = null;

    function setAutoplayOnIframe(iframe, format) {
      if (!iframe || !iframe.src) {
        return;
      }

      const src = iframe.src;
      const hasQuery = src.includes('?');
      const separator = hasQuery ? '&' : '?';
      let autoplayParam = 'autoplay=1';

      if (format === 'audio') {
        autoplayParam = 'auto_play=true';
      }

      if (!src.includes('autoplay=1') && !src.includes('auto_play=true')) {
        iframe.src = src + separator + autoplayParam;
      }

      iframe.setAttribute('allow', 'autoplay; encrypted-media; fullscreen');
    }

    function stopDialogMediaPlayback(shortsDialog) {
      const mediaFrames = shortsDialog.querySelectorAll('.post-content iframe');

      mediaFrames.forEach((iframe) => {
        iframe.src = '';
      });
    }

    function stopVideoDialogPlayback(shortsVideoDialog) {
      const dialogBody = shortsVideoDialog.querySelector('.shorts-video-modal-body');
      dialogBody.innerHTML = '';
    }

    function getCardAudioElement(card) {
      if (!card) {
        return null;
      }

      return card.querySelector('.shorts-audio-player-host audio');
    }

    function getCardAudioPlayerHost(card) {
      if (!card) {
        return null;
      }

      return card.querySelector('.shorts-audio-player-host');
    }

    function getCardStickyPlayerSlot(card) {
      if (!card) {
        return null;
      }

      return card.querySelector('.shorts-audio-sticky-player-slot');
    }

    function restoreAudioPlayerToCard(card) {
      const playerHost = getCardAudioPlayerHost(card);
      const playerContainer = card ? card.querySelector('.shorts-audio-embed') : null;

      if (!playerHost || !playerContainer || playerHost.parentElement === playerContainer) {
        return;
      }

      playerContainer.appendChild(playerHost);
    }

    function moveAudioPlayerToStickyShell(card) {
      const playerHost = getCardAudioPlayerHost(card);
      const stickyPlayerSlot = getCardStickyPlayerSlot(card);

      if (!playerHost || !stickyPlayerSlot || playerHost.parentElement === stickyPlayerSlot) {
        return;
      }

      stickyPlayerSlot.appendChild(playerHost);
    }

    function clearActiveAudioCard() {
      if (activeAudioCard) {
        restoreAudioPlayerToCard(activeAudioCard);
        activeAudioCard.classList.remove('audio-player-sticky');
        activeAudioCard.classList.remove('audio-player-active');
      }

      activeAudioCard = null;
      activeAudioElement = null;
    }

    function stopActiveAudio() {
      if (activeAudioElement) {
        activeAudioElement.pause();
        activeAudioElement.currentTime = 0;
      }

      clearActiveAudioCard();
    }

    function updateStickyAudioPlayer() {
      if (!activeAudioCard || !activeAudioElement) {
        return;
      }

      const cardRect = activeAudioCard.getBoundingClientRect();
      const isCardOutOfView = cardRect.bottom < 0 || cardRect.top > window.innerHeight;

      if (isCardOutOfView) {
        moveAudioPlayerToStickyShell(activeAudioCard);
      } else {
        restoreAudioPlayerToCard(activeAudioCard);
      }

      activeAudioCard.classList.toggle('audio-player-sticky', isCardOutOfView);
    }

    function activateAudioCard(card, audioElement) {
      if (activeAudioElement && activeAudioElement !== audioElement) {
        activeAudioElement.pause();
        activeAudioElement.currentTime = 0;
      }

      if (activeAudioCard && activeAudioCard !== card) {
        restoreAudioPlayerToCard(activeAudioCard);
        activeAudioCard.classList.remove('audio-player-sticky');
        activeAudioCard.classList.remove('audio-player-active');
      }

      activeAudioCard = card;
      activeAudioElement = audioElement;
      activeAudioCard.classList.add('audio-player-active');
      updateStickyAudioPlayer();
    }

    function playAudioFromCard(card) {
      const audioElement = getCardAudioElement(card);

      if (!audioElement) {
        return;
      }

      activateAudioCard(card, audioElement);

      const playPromise = activeAudioElement.play();
      if (playPromise && typeof playPromise.catch === 'function') {
        playPromise.catch(() => {});
      }
    }

    function initDialog() {
      const shortsTriggers = document.querySelectorAll('.shorts-trigger');
      const shortsAudioCards = document.querySelectorAll('.shorts-audio-card');
      const shortsDialog = document.querySelector('#shorts-dialog');
      const shortsVideoDialog = document.querySelector('#shorts-video-dialog');
      const shortsVideoDialogBody = document.querySelector('.shorts-video-modal-body');
      const closeDialogButton = document.querySelector('a.close-button');
      const closeVideoDialogButton = document.querySelector('a.close-video-button');
      const backButton = document.querySelector('a.back-button');
      const shareButton = document.querySelector('a.share');
      const shortsOverview = document.querySelector('#shorts-overview');
      const shortsShare = document.querySelector('#shorts-share');

      let title = '';
      let postUrl = '';
      let shareUrl = '';

      shortsAudioCards.forEach(card => {
        if (card.dataset.audioBound === '1') {
          return;
        }

        card.dataset.audioBound = '1';

        const audioElement = getCardAudioElement(card);

        if (audioElement && audioElement.dataset.audioEventsBound !== '1') {
          audioElement.dataset.audioEventsBound = '1';

          audioElement.addEventListener('play', () => {
            activateAudioCard(card, audioElement);
          });

          audioElement.addEventListener('ended', () => {
            if (activeAudioElement === audioElement) {
              clearActiveAudioCard();
            }
          });
        }

        card.addEventListener('click', (e) => {
          const isCloseButtonClick = e.target.closest('.shorts-audio-sticky-close');
          const isControlClick = e.target.closest('.shorts-audio-player-host') || e.target.closest('.shorts-audio-sticky-player-slot');

          if (isCloseButtonClick) {
            e.preventDefault();
            e.stopPropagation();
            stopActiveAudio();
            return;
          }

          if (!isControlClick) {
            e.preventDefault();
          } else {
            return;
          }

          playAudioFromCard(card);
        });
      });

      shortsTriggers.forEach(trigger => {
        if (trigger.dataset.dialogBound === '1') {
          return;
        }

        trigger.dataset.dialogBound = '1';

        trigger.addEventListener('click', (e) => {
          e.preventDefault();
          e.stopPropagation();
          const card = e.target.closest('.shorts-trigger');
          const postTitle = card.querySelector('.title').textContent.trim();
          const excerpt = card.querySelector('.post-excerpt').innerHTML;
          const byline = card.querySelector('.post-meta .byline').textContent;
          const date = card.querySelector('.post-meta .date').textContent;
          const url = card.dataset.articlelink;
          const sUrl = card.dataset.shareurl;
          const mediaFormat = card.dataset.mediaFormat || '';
          const mediaEmbed = card.querySelector('.shorts-media-embed');

          if (mediaFormat === 'video' && mediaEmbed) {
            stopActiveAudio();
            shortsVideoDialogBody.innerHTML = mediaEmbed.innerHTML;
            shortsVideoDialog.showModal();

            const videoIframe = shortsVideoDialogBody.querySelector('iframe');
            setAutoplayOnIframe(videoIframe, mediaFormat);
            return;
          }

          postUrl = url;
          shareUrl = sUrl;
          title = postTitle;

          const dialogTitle = shortsDialog.querySelector('.dialog-body .title h2');
          const dialogContent = shortsDialog.querySelector('.dialog-body .post-content .post-content-body');
          const dialogByline = shortsDialog.querySelector('.dialog-body .post-meta .byline');
          const dialogDate = shortsDialog.querySelector('.dialog-body .post-meta .date');
          const dialogArticleLink = shortsDialog.querySelector('.dialog-footer a.link');

          dialogTitle.textContent = postTitle;
          dialogContent.innerHTML = excerpt;

          if (mediaEmbed) {
            dialogContent.innerHTML = mediaEmbed.innerHTML;
          }

          dialogByline.textContent = byline;
          dialogDate.textContent = date;
          dialogArticleLink.href = postUrl;

          shortsDialog.showModal();

          if (mediaEmbed) {
            const iframe = dialogContent.querySelector('iframe');
            setAutoplayOnIframe(iframe, mediaFormat);
          }

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
        stopDialogMediaPlayback(shortsDialog);

        shortsOverview.classList.remove('hidden');
        shortsShare.classList.add('hidden');
        backButton.classList.add('hidden');
        document.getElementById("shorts-share").innerHTML = sharingGridCopy;
      });

      shortsVideoDialog.addEventListener('close', () => {
        stopVideoDialogPlayback(shortsVideoDialog);
      });

      window.addEventListener('scroll', updateStickyAudioPlayer, {
        passive: true
      });
      window.addEventListener('resize', updateStickyAudioPlayer);

      closeDialogButton.addEventListener('click', close);
      closeVideoDialogButton.addEventListener('click', () => {
        shortsVideoDialog.close();
      });
      backButton.addEventListener('click', back);
    }

    initDialog();
  </script>

<?php } ?>