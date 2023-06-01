
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

    $(".select2-selection").css("background-color", isLightMode ? "white" : "#343a40");
    $(".select2-selection__rendered").css("color", isLightMode ? "black" : "white");
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

function updateLiveTime() {
    var now = new Date();
    var options = { month: 'long', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit', hour12: true };
    var time = now.toLocaleString('en-US', options);
    $('#live-time').text("Today is " + time);
}

// Update the live time every second
setInterval(updateLiveTime, 1000);

$(function () {

    showLoader();

    setTimeout(function() {
        hideLoader();

        var isLightMode = $("#customSwitch1").prop("checked");
        toggleMode(isLightMode);
    }, 1500);

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


    // Force blur to select2 elements
    var selectClicked = false;

    $('.select-select2').on('click', function() {
        selectClicked = true;
    });

    // Event listener for clicks outside the <select> element
    $(document).on('click', function(event) {
        var target = $(event.target);

        // Check if the click occurred outside the <select> element just after clicking inside it
        if (!target.is('.select-select2') && !target.parents().is('.select-select2') && selectClicked) {
        // Force blur on Select2 element
        setTimeout(function() {
            $('.select2-container-active').removeClass('select2-container-active');
            $(':focus').blur();
            //handleBlurEvent();
            handleBlurEvent(function(isRecorded) {
                //console.log(isRecorded);
            });
            // console.log("Is Select2 element blurred?", !$(':focus').is('.select2-container-active'));
        }, 1);

        // Reset selectClicked flag
        selectClicked = false;
        }
    });
});

</script>
<script src="plugins/select2/js/select2.min.js"></script>

