jQuery(document).ready(function ($) {
  if ($("body").hasClass("post-type-short-article")) {
    // Create WordCounter instance
    const counter = new wp.utils.WordCounter();

    $("#post").on("submit", function (event) {
      let content = "";

      // Get TinyMCE content if available
      if (typeof tinymce !== "undefined" && tinymce.activeEditor !== null) {
        content = tinymce.activeEditor.getContent({ format: "raw" }); 
      } else {
        // Fallback to plain textarea
        content = $("#content").val();
      }

      // Use WordPress WordCounter (defaults to words)
      const wordCount = counter.count(content, "words");

      if (wordCount > 500) {
        event.preventDefault();
        alert("Content exceeds 500 words. Please shorten your content.");
        return false;
      }
    });
  }
});
