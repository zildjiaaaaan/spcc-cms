$(function () {

    $("#equipment").select2({
        width: 'resolve',
        placeholder: "Select Equipment"
    });

    $("#borrower").select2({
        width: 'resolve',
        placeholder: "Select Borrower"
    });    

    // Set the height of the quantity input
    $("#quantity").css("height", "52px");

    // Disable '_' in the remarks input
    $("#remarks").keypress(function(e) {
        var key = e.which;
        // return false if the key is '_' or '"' or "'"
        if (key === 95 || key === 34 || key === 39) {
            return false;
        }
    });

    //Set linked datetimepicker format
    $('#unavailable_since').datetimepicker({
        format: 'L',
    });
    $('#unavailable_until').datetimepicker({
        format: 'L',
        useCurrent: false
    });
    $("#unavailable_since").on("change.datetimepicker", function (e) {
        $('#unavailable_until').datetimepicker('minDate', e.date);
    });
    $("#unavailable_until").on("change.datetimepicker", function (e) {
        $('#unavailable_since').datetimepicker('maxDate', e.date);
    });
    
    
    // if #status is Available, then #state can be active or non-borrowable
    // if #status is Unavailable, then state can be used, missing, defective, borrowed

    var status = '';
    var state = '';

    $("#status").change(function() {
        status = $("#status option:selected").text();
        var html = '';

        if (status === 'Available') {
            html = '<option value="Active">Active</option>';
            html += '<option value="Non-Borrowable">Non-Borrowable</option>';
        } else if (status === 'Unavailable') {
            html = '<option value="Used">Used</option>';
            html += '<option value="Missing">Missing</option>';
            html += '<option value="Defective">Defective</option>';
            html += '<option value="Borrowed">Borrowed</option>';
        }

        $("#state").html(html);
    });

    $("#status, #state").change(function() {
        status = $("#status option:selected").text();
        state = $("#state option:selected").text();

        if (status === 'Unavailable' && state === 'Borrowed') {
            $(".unavailable").show();
            $("#unavailableSince").prop("required", true);
            $("#borrower").prop("required", true);
        } else if (status === 'Unavailable') {
            $(".unavailable").show();
            $(".borrower").hide();
            $("#unavailableSince").prop("required", true);
            $("#borrower").prop("required", false);
        } else {
            $(".unavailable").hide();
            $("#unavailableSince").prop("required", false);
            $("#borrower").prop("required", false);
        }

        if (state === 'Missing') {
            $("#unavailableUntil").prop("disabled", true);
            $("#unavailableUntil").val('');
            $("#unavailableUntil").css("cursor", "not-allowed");
            $("#unavailableUntil").prop("required", false);
            $("#borrower").prop("required", false);  
        } else {
            if (state !== 'Borrowed') {
                $("#borrower").prop("required", false);
            }
            $("#unavailableUntil").prop("disabled", false);
            $("#unavailableUntil").css("cursor", "pointer");
            $("#unavailableUntil").prop("required", true);
        }
    });

    $("form :input").blur(handleBlurEvent);

});

function handleBlurEvent() {

    var status = $("#status option:selected").text();
    var state = $("#state option:selected").text();

    var borrowerId = '';
    var f_unavailableSince = '';
    var f_unavailableUntil = '';

    var update_id = $("#update_id").val();
    var equipmentId = $("#equipment").val();
    var remarks = $("#remarks").val().trim();
    var checkForm = true;

    if (state === 'Borrowed') {
        if ($("#borrower").val() && $("#unavailableSince").val() && $("#unavailableUntil").val()) {
            borrowerId = $("#borrower").val();
            f_unavailableSince = formatDate($("#unavailableSince").val());
            f_unavailableUntil = formatDate($("#unavailableUntil").val());
        } else {
            checkForm = false;
        }
    } else if (state === 'Missing' && $("#unavailableSince").val()) {
        f_unavailableSince = formatDate($("#unavailableSince").val());
    } else if (status === 'Unavailable' && $("#unavailableSince").val() && $("#unavailableUntil").val()) {
        f_unavailableSince = formatDate($("#unavailableSince").val());
        f_unavailableUntil = formatDate($("#unavailableUntil").val());
    } else {
        if (status === 'Unavailable') {
            checkForm = false;
        }
    }

    if (checkForm) {
        $.ajax({
            url: "ajax/check_equipment_status.php",
            type: 'GET',
            data: {
                'equipmentId': equipmentId,
                'status': status,
                'state': state,
                'remarks': remarks,
                'borrowerId': borrowerId,
                'f_unavailableSince': f_unavailableSince,
                'f_unavailableUntil': f_unavailableUntil,
                'update_id': update_id
            },
            cache: false,
            success: function (count) {
                if(count > 0) {
                    showCustomMessage("This equipment has already been stored previously. Please check inventory or the Trash.");
                    $("#submit").attr("disabled", "disabled");
                    console.log(count);
                } else {
                    $("#submit").removeAttr("disabled");
                    console.log(count);
                }
            },
        })
    }
}

function formatDate(dateString) {
    var parts = dateString.split("/");
    var month = parts[0].padStart(2, '0');
    var day = parts[1].padStart(2, '0');
    var year = parts[2];
    return year + "-" + month + "-" + day;
}