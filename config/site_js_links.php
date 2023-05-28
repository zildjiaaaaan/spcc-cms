
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->


<script src="dist/js/jquery_confirm/jquery-confirm.js"></script>

<script src="dist/js/common_javascript_functions.js"></script>

<script>

function toggleMode(isLightMode) {
    $("body").toggleClass("dark-mode", !isLightMode);
    $(".cell-link").css("color", isLightMode ? "black" : "white").hover(function () {
        $(this).css("color", "#007bff");
    }, function () {
        $(this).css("color", isLightMode ? "black" : "white");
    });
}

function showLoader() {
    var loaderOverlay = document.createElement('div');
    loaderOverlay.id = 'loader-overlay';
    var loader = document.createElement('div');
    loader.id = 'loader';
    loaderOverlay.appendChild(loader);
    document.body.appendChild(loaderOverlay);
}

function hideLoader() {
    var loaderOverlay = document.getElementById('loader-overlay');
    if (loaderOverlay) {
        loaderOverlay.parentNode.removeChild(loaderOverlay);
    }
}

$(function () {

    showLoader();

    setTimeout(function() {
        hideLoader();

        var isLightMode = $("#customSwitch1").prop("checked");
        toggleMode(isLightMode);
    }, 750);

    var isLightMode = $("#customSwitch1").prop("checked");
    toggleMode(isLightMode);

    $("#customSwitch1").on("change", function () {
        isLightMode = $(this).prop("checked");
        toggleMode(isLightMode);

        $.ajax({
            url: "config/dark-mode.php",
            type: "POST",
            data: {
                isLightMode: isLightMode
            },
            success: function (data) {
                //console.log(data);
            }
        });
    });
});

</script>

