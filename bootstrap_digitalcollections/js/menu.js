window.onload = function() {
  var showSearchBtn = document.getElementById("show-search"),
      cancelSearchBtn = document.getElementById("cancel-search"),
      searchForm = document.getElementById("islandora-solr-simple-search-form"),
      menuElements = [showSearchBtn, cancelSearchBtn, searchForm];

  showSearchBtn.addEventListener("click", toggleSearch);
  cancelSearchBtn.addEventListener("click", toggleSearch);
  document.addEventListener("keydown", removeSearchOnEscape);

  /*
  #######################################
  #######################################
  ###                                 ###
  ###   Search toggle functionality   ###
  ###                                 ###
  #######################################
  #######################################
  */

  function toggleSearch() {
    for ( i = 0; i < menuElements.length; i++ ) {
      var menuElement = menuElements[i];

      if ( menuElement.id == "show-search" || menuElement.id == "cancel-search" ) {
        toggleSearchButtons(menuElement);
      }
      else {
        toggleSearchForm(menuElement);
      }
    }
  }

  function toggleSearchButtons(el) {
    el.classList.toggle("hidden");
    
    if ( !el.classList.contains("hidden") ) {
      el.classList.add("animated", "fadeIn");
    }
    else {
      el.classList.remove("animated", "fadeIn");
    }
  }

  function toggleSearchForm(el) {
    el.classList.toggle("visible-xs");
    
    if ( !el.classList.contains("visible-xs") ) {
      el.classList.add("animated", "slideInDown");
      document.getElementById("edit-islandora-simple-search-query").focus();
    }
    else {
      el.classList.remove("animated", "slideInDown");
    }
  }

  function removeSearchOnEscape(evt) {
    evt = evt || window.event;
    var isEscape = false;

    if ("key" in evt) {
      isEscape = (evt.key == "Escape" || evt.key == "Esc");
    } else {
      isEscape = (evt.keyCode == 27);
    }

    if (isEscape && !cancelSearchBtn.classList.contains("hidden")) {
      toggleSearch();
    }
  }
}
