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

async function getDataCboxAsync(action, fieldId, fieldName, idCbox, param = '') {
  await $.ajax({
    url: `controller/ajax.php?action=${action}&${param}`,
    cache: false,
    contentType: false,
    processData: false,
    method: "GET",
    type: "GET",
    success: function (resp) {
      let data = JSON.parse(resp)?.data;
      let html = '<option value="">Please select ...</option>';
      if (data && data?.length > 0) {
        data.forEach((element) => {
          html += `<option value="${element[fieldId]}">${element[fieldName]}</option>`;
        });
      }
      $(idCbox).html(html);
    },
  });
}

function getCurrentDate() {
  var today = new Date();
  var day = String(today.getDate()).padStart(2, "0");
  var month = String(today.getMonth() + 1).padStart(2, "0");
  var year = today.getFullYear();
  var formattedDate = year + "-" + month + "-" + day;
  return formattedDate;
}

function getCurrentMonth() {
  var today = new Date();
  var month = String(today.getMonth() + 1).padStart(2, "0");
  var year = today.getFullYear();
  var formattedDate = year + "-" + month;
  return formattedDate;
}

function getCurrentTime() {
  var today = new Date();
  var hour = String(today.getHours()).padStart(2, "0");
  var min = String(today.getMinutes()).padStart(2, "0");
  // var second = String(today.getSeconds()).padStart(2, "0");
  var formattedTime = hour + ":" + min;
  return formattedTime;
}

function calcTimeToHour(startTime, endTime) {
  // 14:00 15:45
  // Chuyển đổi thời gian bắt đầu thành giờ và phút
  var [startHour, startMinute] = startTime.split(":");
  var startHourInt = parseInt(startHour);
  var startMinuteInt = parseInt(startMinute);

  // Chuyển đổi thời gian kết thúc thành giờ và phút
  var [endHour, endMinute] = endTime.split(":");
  var endHourInt = parseInt(endHour);
  var endMinuteInt = parseInt(endMinute);

  // Tính toán số giờ
  var totalHours = endHourInt - startHourInt;
  var totalMinutes = (endMinuteInt / 60) - (startMinuteInt / 60);
  if (totalMinutes < 0) {
    totalHours -= 1;
    totalMinutes += 60;
  }
  if(totalMinutes < 1){
    return parseFloat(totalMinutes.toString()).toFixed(2).toString();
  }
  // Định dạng kết quả
  var result = totalHours.toString() + "." + parseFloat(totalMinutes.toString()).toFixed(2).toString();

  return result;
}
