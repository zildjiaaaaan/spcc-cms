var equipmentDetailsArr = [];

$(function() {

    $("#equipment").select2({
        width: 'resolve',
        placeholder: "Select Equipment"
    });

    $("#borrower").select2({
        width: 'resolve',
        placeholder: "Select Borrower"
    });

    // Set the height of the quantity input
    $("#add_to_list").css("height", "52px");

    // Disable '_' in the remarks input
    $(".remarks").keypress(function(e) {
        var key = e.which;
        // return false if the key is '_' or '"' or "'"
        if (key === 95 || key === 34 || key === 39) {
            return false;
        }
    });

    //Set linked datetimepicker format
    $('#unavailable_until').datetimepicker({
        format: 'L',
        minDate: new Date()
    });


    // Handle blur event
    // $("form :input").blur(handleBlurEvent);

    $("#equipment").change(function() {
        var equipmentDetailsId = $(this).val();

        if (equipmentDetailsId != '') {
            $.ajax({
                url: "ajax/get_quantity.php",
                type: 'GET', 
                data: {
                    'equipmentDetailsId': equipmentDetailsId
                },
                cache:false,
                async:false,
                success: function (data) {

                    var data = JSON.parse(data);
                    var d_quantity = data.quantity;
                    var d_remarks = data.remarks;

                    if (equipmentDetailsArr.length > 0) {
                        for (let i = 0; i < equipmentDetailsArr.length; i++) {
                            if (equipmentDetailsArr[i].equipmentDetailsId === equipmentDetailsId) {
                            d_quantity -= equipmentDetailsArr[i].qty;
                            if (d_quantity < 0) {
                                d_quantity = 0;
                            }
                                break;
                            }
                        }
                    }

                    $("#quantity").val(d_quantity);
                    $("#quantity").attr({
                        "max" : d_quantity,
                        "min" : 0
                    });

                    $("#quantity").on("input", function() {
                        var value = $(this).val();
                        var min = parseInt($(this).attr("min"));
                        var max = parseInt($(this).attr("max"));

                        if (value < min) {
                            $(this).val(min);
                        } else if (value > max) {
                            $(this).val(max);
                        }
                    });

                    $("#current_remarks").attr('placeholder', d_remarks);
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    showCustomMessage(errorMessage);
                }
            });
        }
    });

    $('#equipment_list').find('td').addClass("px-2 py-1 align-middle")
    $('#equipment_list').find('th').addClass("p-1 align-middle")

    // Adding to list
    $("#add_to_list").click(function() {

        var borrowerId = $("#borrower").val();
        var borrowerName = $("#borrower option:selected").text();

        var equipmentDetailsId = $("#equipment").val();
        var equipmentName = $("#equipment option:selected").text();
        if (equipmentName != '') {
            equipmentName = equipmentName.split(" (")[0];
        }

        var f_unavailableUntil = '';
        if ($("#unavailableUntil").val() != '') {
            f_unavailableUntil = formatDate($("#unavailableUntil").val());
        }
        
        var quantity = $("#quantity").val().trim();
        if (quantity == '0') {
            quantity = '';
        }

        var current_remarks = $("#current_remarks").val().trim();

        // if remarks has "_" then replace it with "-"
        var remarks = $("#new_remarks").val().trim();      
        remarks = remarks.replace(/_/g, "-");
        var remarksForId = remarks.replace(/ /g, "-");

        // Determiner for adding in array
        var hasNoId = true;
        // Determiner for adding in new cell
        var addCell = true;

        var oldData = $("#current_equipment_list").html();
        // Determiner for clearing form
        var clearForm = true;
        
        if (borrowerId !== '' && equipmentName !== '' && quantity !== '' && f_unavailableUntil !== '') {

            if (equipmentDetailsArr.length > 0) {
                equipmentDetailsArr.forEach((equipment) => {
                    if (equipment.equipmentDetailsId === equipmentDetailsId) {
                        const qtyId = `${equipmentId}`;
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
                        equipmentDetailsId,
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
            $("#equipment").select2("val", "");

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

function handleBlurEvent() {

    var status = $("#status option:selected").text();
    var state = $("#state option:selected").text();

    var borrowerId = '';
    var f_unavailableSince = '';
    var f_unavailableUntil = '';

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
}

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
    var del_uSince = del_arr[4];
    var del_uUntil = del_arr[5];
    var del_borId = del_arr[6];

    var delIndex = equipmentDetailsArr.findIndex((equipment) => {
        return (
        equipment.equipmentId === del_id &&
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