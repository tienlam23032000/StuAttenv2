getHoverTab();

// Function
function getHoverTab() {
  const searchParams = new URLSearchParams(window.location.search);
  if (searchParams.has("page")) {
    let param = searchParams.get("page");
    $(`#sidebar #sidebar-nav #${param}`).removeClass("collapsed");
  }
}
function alert_toast($msg = "TEST", $bg = "success", $delay = 3000) {
  $("#toastMessage").removeClass("alert-success");
  $("#toastMessage").removeClass("alert-danger");
  $("#toastMessage").removeClass("alert-info");
  $("#toastMessage").removeClass("alert-warning");

  if ($bg == "success") $("#toastMessage").addClass("alert-success");
  if ($bg == "danger") $("#toastMessage").addClass("alert-danger");
  if ($bg == "info") $("#toastMessage").addClass("alert-info");
  if ($bg == "warning") $("#toastMessage").addClass("alert-warning");
  $("#toastMessage").html($msg);
  $("#toastAction")
    .toast({
      delay: $delay,
    })
    .toast("show");
}

async function getDataCboxAsync(action, fieldId, fieldName, idCbox) {
  await $.ajax({
      url: `controller/ajax.php?action=${action}`,
      cache: false,
      contentType: false,
      processData: false,
      method: 'GET',
      type: 'GET',
      success: function(resp) {
          let data = JSON.parse(resp)?.data
          let html = '<option value="">Please select ...</option>'
          if (data && data?.length > 0) {
              data.forEach(element => {
                  html += `<option value="${element[fieldId]}">${element[fieldName]}</option>`
              });
          }
          $(idCbox).html(html)
      }
  })
}