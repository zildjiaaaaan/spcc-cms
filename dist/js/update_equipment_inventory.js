$(function () {

    // Set datetimepicker format
    $('#unavailable_since, #unavailable_until').datetimepicker({
        format: 'L'
    });
    

    // if #status is Available, then #state can be active or non-borrowable
    // if #status is Unavailable, then state can be used, missing, defective, borrowed
    $("#status").change(function() {
        var status = $("#status option:selected").text();
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
        } else if (status === 'Unavailable') {
            $(".unavailable").show();
            $(".borrower").hide();
        } else {
            $(".unavailable").hide();
        }

        if (state === 'Missing') {
            $("#unavailableUntil").prop("disabled", true);
            $("#unavailableUntil").val('');
            $("#unavailableUntil").css("cursor", "not-allowed");
        } else {
            $("#unavailableUntil").prop("disabled", false);
            $("#unavailableUntil").css("cursor", "pointer");
        }
    });





});