getHoverTab();

// Function
function getHoverTab() {
  const searchParams = new URLSearchParams(window.location.search);
  if (searchParams.has("page")) {
    let param = searchParams.get("page");
    $(`#sidebar #sidebar-nav #${param}`).removeClass("collapsed");
  }
}
