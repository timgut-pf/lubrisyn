(function () {
  function init(root) {
    var select = root.querySelector('[data-lsh-select]');
    if (!select) return;

    select.addEventListener('change', function () {
      var url = this.value;
      if (url) window.location.href = url;
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.wp-block-acf-lubrisyn-hero').forEach(init);
  });
})();