var equipmentDetailsArr = [];

$(function() {
    // Set the height of the quantity input
    $("#quantity").css("height", "52px");

    // On load, hide inputs with .unavailable class
    $(".unavailable").hide();

    // Disable '_' in the remarks input
    $("#remarks").keypress(function(e) {
        var key = e.which;
        // return false if the key is '_' or '"' or "'"
        if (key === 95 || key === 34 || key === 39) {
            return false;
        }
    });

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
            // html += '<option value="In Repair">In Repair</option>';
            html += '<option value="Borrowed">Borrowed</option>';
            // html += '<option value="Transferred">Transferred</option>';
        }

        $("#state").html(html);
    });

    // if the #status is Unavailable, then show the elements that has the class of unavailable except .borrower
    var status = '';
    var state = '';
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

    var borrowerId = '';
    var f_unavailableSince = '';
    var f_unavailableUntil = '';

    $("form :input").change(function() {
        var equipmentId = $("#equipment").val();
        var equipmentName = $("#equipment option:selected").text();
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
                console.log(checkForm);
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
                    'f_unavailableUntil': f_unavailableUntil
                },
                cache: false,
                success: function (count) {
                    if(count > 0) {
                        showCustomMessage("This equipment has already been stored previously. Please check inventory or the Trash.");
                        $("#add_to_list").attr("disabled", "disabled");
                    } else {
                        $("#add_to_list").removeAttr("disabled");
                    }
                },
            })
        }
    });

    $('#equipment_list').find('td').addClass("px-2 py-1 align-middle")
    $('#equipment_list').find('th').addClass("p-1 align-middle")

    // Adding to list
    $("#add_to_list").click(function() {

        var equipmentId = $("#equipment").val();
        var equipmentName = $("#equipment option:selected").text();
        
        // var status = $("#status").val();
        // var state = $("#state").val();

        // if remarks has "_" then replace it with "-"
        var remarks = $("#remarks").val().trim();      
        remarks = remarks.replace(/_/g, "-");
        var remarksForId = remarks.replace(/ /g, "-");

        var quantity = $("#quantity").val().trim();
        if (quantity == '0') {
            quantity = '';
        }

        borrowerId = '*';
        f_unavailableSince = '';
        f_unavailableUntil = '';

        if (state === 'Borrowed') {
            if ($("#borrower").val() && $("#unavailableSince").val() && $("#unavailableUntil").val()) {
                borrowerId = $("#borrower").val();
                f_unavailableSince = formatDate($("#unavailableSince").val());
                f_unavailableUntil = formatDate($("#unavailableUntil").val());
            }
        } else if (state === 'Missing' && $("#unavailableSince").val()) {
            f_unavailableSince = formatDate($("#unavailableSince").val());
            borrowerId = '';
        } else if (status === 'Unavailable' && $("#unavailableSince").val() && $("#unavailableUntil").val()) {
            f_unavailableSince = formatDate($("#unavailableSince").val());
            f_unavailableUntil = formatDate($("#unavailableUntil").val());
            borrowerId = '';
        } else {
            if (status === 'Available') {
                borrowerId = '';
            }
        }

        // Determiner for adding in array
        var hasNoId = true;
        // Determiner for adding in new cell
        var addCell = true;

        var oldData = $("#current_equipment_list").html();
        // Determiner for clearing form
        var clearForm = true;
        
        if (equipmentName !== '' && status !== '' && state !== '' && quantity !== '' && borrowerId !== '*') {

            if (equipmentDetailsArr.length > 0) {
                equipmentDetailsArr.forEach((equipment) => {
                    if (
                    equipment.equipmentId === equipmentId &&
                    equipment.status === status &&
                    equipment.state === state &&
                    equipment.remarks === remarks &&
                    equipment.unavailableSince === f_unavailableSince &&
                    equipment.unavailableUntil === f_unavailableUntil &&
                    equipment.borrowerId === borrowerId
                    ) {
                        const qtyId = `${equipmentId}_${status}_${state}_${remarksForId}_${f_unavailableSince}_${f_unavailableUntil}_${borrowerId}`;
                        addQuantity(parseInt(quantity), qtyId);
                        equipment.qty += parseInt(quantity);
                        hasNoId = false;
                        addCell = false;
                        console.log(addCell);
                        return; // Break out of the loop early
                    }
                });
            }

            if (addCell) {
                const qtyId = `${equipmentId}_${status}_${state}_${remarksForId}_${f_unavailableSince}_${f_unavailableUntil}_${borrowerId}`;
                const inputs = [
                    `<input type="hidden" name="equipmentIds[]" value="${equipmentId}" />`,
                    `<input type="hidden" name="statuses[]" value="${status}" />`,
                    `<input type="hidden" name="states[]" value="${state}" />`,
                    `<input type="hidden" name="quantities[]" id="inp-${qtyId}" value="${quantity}" />`,
                    `<input type="hidden" name="remarks[]" value="${remarks}" />`,
                    `<input type="hidden" name="borrowerIds[]" value="${borrowerId}" />`,
                    `<input type="hidden" name="unavailableSinces[]" value="${f_unavailableSince}" />`,
                    `<input type="hidden" name="unavailableUntils[]" value="${f_unavailableUntil}" />`
                ].join('');
                
                const tr = `
                    <tr>
                    <td class="px-2 py-1 align-middle">${serial}</td>
                    <td class="px-2 py-1 align-middle">${equipmentName}</td>
                    <td class="px-2 py-1 align-middle">${status}</td>
                    <td class="px-2 py-1 align-middle">${state}</td>
                    <td class="px-2 py-1 align-middle" id="${qtyId}">${quantity}</td>
                    <td class="px-2 py-1 align-middle">${remarks}${inputs}</td>
                    <td class="px-2 py-1 align-middle text-center">
                        <button type="button" class="btn btn-outline-danger btn-sm rounded-0" onclick="deleteCurrentRow(this);">
                        <i class="fa fa-times"></i>
                        </button>
                    </td>
                    </tr>`;
                
                oldData += tr;
                serial++;
                
                const $currentEquipmentList = $("#current_equipment_list");
                $currentEquipmentList.html(oldData);
                
                if (hasNoId) {
                    equipmentDetailsArr.push({
                    equipmentId,
                    status,
                    state,
                    qty: parseInt(quantity),
                    remarks,
                    borrowerId,
                    unavailableSince: f_unavailableSince,
                    unavailableUntil: f_unavailableUntil
                    });
                }
            } else {
                showCustomMessage("Equipment \""+ equipmentName +"\" already exists. The quantity has been updated.");
            }

        } else {
            showCustomMessage("Please fill out all the fields.");
            clearForm = false;
        }

        // reset the form
        if (clearForm) {
            $("#equipment, #status, #state, #remarks, #quantity").val('');

            if (status === 'Unavailable') {
                $("#unavailable_since, #unavailable_until").val('');
                if (state === 'Borrowed') {
                    $("#borrower").val('');
                }
                $(".unavailable").hide();
            }
        }    
    });
});

function formatDate(dateString) {
    var parts = dateString.split("/");
    var month = parts[0].padStart(2, '0');
    var day = parts[1].padStart(2, '0');
    var year = parts[2];
    return year + "-" + month + "-" + day;
}

function deleteCurrentRow(obj) {
    var rowIndex = obj.parentNode.parentNode.rowIndex;
    var row = document.getElementById("equipment_list").rows[rowIndex];
    var del_remarks = row.cells[5].textContent;
    var id = row.cells[4].id;

    document.getElementById("equipment_list").deleteRow(rowIndex);

    var del_arr = id.split("_");
    var del_id = del_arr[0];
    var del_status = del_arr[1];
    var del_state = del_arr[2];
    var del_uSince = del_arr[4];
    var del_uUntil = del_arr[5];
    var del_borId = del_arr[6];

    var delIndex = equipmentDetailsArr.findIndex((equipment) => {
        return (
        equipment.equipmentId === del_id &&
        equipment.status === del_status &&
        equipment.state === del_state &&
        equipment.remarks === del_remarks &&
        equipment.unavailableSince === del_uSince &&
        equipment.unavailableUntil === del_uUntil &&
        equipment.borrowerId === del_borId
        );
    });

    if (delIndex !== -1) {
        equipmentDetailsArr.splice(delIndex, 1);
    }
}

function addQuantity(quantity, qtyId) {
    var currentQty = $("#"+qtyId).text();
    currentQty = parseInt(currentQty);
    currentQty += quantity;
    $("#"+qtyId).text(currentQty);
    $("#inp-"+qtyId).val(currentQty);
}