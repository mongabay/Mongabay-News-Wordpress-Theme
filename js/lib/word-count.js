jQuery(document).ready(function ($) {
  if ($("body").hasClass("post-type-short-article")) {
    $("#post").on("submit", function (event) {
      let content = "";

      // Check if TinyMCE editor is active and get content
      if (typeof tinymce !== "undefined" && tinymce.activeEditor !== null) {
        content = tinymce.activeEditor.getContent({ format: "text" });
      } else {
        // Fallback to plain text editor
        content = $("#content").val();
      }

      // Count words and validate
      let wordCount = content.trim().split(/\s+/).length;
      if (wordCount > 500) {
        event.preventDefault();
        alert("Content exceeds 500 words. Please shorten your content.");
        return false;
      }
    });
  }
});
