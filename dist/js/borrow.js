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
        minDate: new Date(),
        defaultDate: new Date()
    });

    $("#equipment").change(function() {
        var equipmentDetailsId = $(this).val().split(" ")[0];

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
                        var filteredEquipmentDetails = equipmentDetailsArr.filter(function(details) {
                            return details.equipmentDetailsId === equipmentDetailsId;
                        });
                
                        if (filteredEquipmentDetails.length > 0) {
                            d_quantity -= filteredEquipmentDetails.reduce(function(total, details) {
                                return total + details.qty;
                            }, 0);
                
                            if (d_quantity < 0) {
                                d_quantity = 0;
                            }
                        }
                    }
              
                      $("#quantity").val(d_quantity);
                      $("#quantity").attr({
                        "max": d_quantity,
                        "min": 0
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

    // Handle blur event
    var isWarning = false;
    $("form :input").blur(function() {
        handleBlurEvent(function(isRecorded) {
            // Use the isRecorded value here
            isWarning = isRecorded;
        });
    });
    

    $('#equipment_list').find('td').addClass("px-2 py-1 align-middle")
    $('#equipment_list').find('th').addClass("p-1 align-middle")

    // Adding to list
    $("#add_to_list").click(function() {

        var borrowerId = $("#borrower").val();
        var borrowerName = $("#borrower option:selected").text();

        var equipmentDetailsId = $("#equipment").val().split(" ")[0];
        var equipmentId = $("#equipment").val().split(" ")[1];
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

        var current_remarks = $("#current_remarks").attr("placeholder");

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
        
        if (borrowerId !== '' && equipmentDetailsId !== '' && quantity !== '' && f_unavailableUntil !== '') {

            if (equipmentDetailsArr.length > 0) {
                equipmentDetailsArr.forEach((equipment) => {
                    if (
                    equipment.equipmentDetailsId === equipmentDetailsId &&
                    equipment.borrowerId === borrowerId &&
                    equipment.unavailableUntil === f_unavailableUntil &&
                    equipment.remarks === remarks
                    ) {
                        const qtyId = `${equipmentDetailsId}_${borrowerId}_${f_unavailableUntil}_${remarksForId}`;
                        addQuantity(parseInt(quantity), qtyId);
                        equipment.qty += parseInt(quantity);
                        hasNoId = false;
                        addCell = false;
                        return; // Break out of the loop early
                    }
                });
            }

            if (addCell) {
                var hasRecord = (isWarning) ? '1' : '';
                const qtyId = `${equipmentDetailsId}_${borrowerId}_${f_unavailableUntil}_${remarksForId}`;
                const inputs = [
                    `<input type="hidden" name="equipmentDetailsIds[]" value="${equipmentDetailsId}" />`,
                    `<input type="hidden" name="equipmentIds[]" value="${equipmentId}" />`,
                    `<input type="hidden" name="borrowerIds[]" value="${borrowerId}" />`,
                    `<input type="hidden" name="unavailableUntils[]" value="${f_unavailableUntil}" />`,
                    `<input type="hidden" name="quantities[]" id="inp-${qtyId}" value="${quantity}" />`,
                    `<input type="hidden" name="current_remarks[]" value="${current_remarks}" />`,
                    `<input type="hidden" name="remarks[]" value="${remarks}" />`,
                    `<input type="hidden" name="hasRecords[]" value="${hasRecord}" />`,
                ].join('');

                var tr_style = (isWarning) ? 'class="bg-warning"' : '';
                
                const tr = `
                    <tr ${tr_style}>
                    <td class="px-2 py-1 align-middle">${serial}</td>
                    <td class="px-2 py-1 align-middle">${equipmentName}</td>
                    <td class="px-2 py-1 align-middle" id="${qtyId}">${quantity}</td>
                    <td class="px-2 py-1 align-middle">${f_unavailableUntil}</td>
                    <td class="px-2 py-1 align-middle">${borrowerName}</td>
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
                        equipmentId,
                        borrowerId,
                        unavailableUntil: f_unavailableUntil,
                        qty: parseInt(quantity),
                        current_remarks,
                        remarks,
                        hasRecord                    
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
            $("#equipment, #borrower").val('').trigger('change');
            $("#new_remarks, #quantity").val('');
            $("#current_remarks").attr('placeholder', "Some remarks");
            $('#unavailable_until').datetimepicker('date', new Date());
            if (isWarning) {
                showCustomMessage("This unit has been borrowed with identical details. Please read the 'Note' below.");
            }
        }
    });
});

function handleBlurEvent(callback) {
    var isRecorded = false;
  
    var borrowerId = $("#borrower").val();
    var equipmentDetailsId = $("#equipment").val().split(" ")[0];
    var f_unavailableUntil = '';
    
    if ($("#unavailableUntil").val() != '') {
        f_unavailableUntil = formatDate($("#unavailableUntil").val());
    }
    
    var quantity = $("#quantity").val().trim();
    if (quantity == '0') {
        quantity = '';
    }
    
    var remarks = $("#new_remarks").val().trim();
  
    if (borrowerId != '' && equipmentDetailsId != '' && f_unavailableUntil != '' && quantity != '') {
        $.ajax({
            url: "ajax/check_equipment_status.php",
            type: 'GET',
            data: {
            'borrowerId': borrowerId,
            'f_unavailableUntil': f_unavailableUntil,
            'remarks': remarks,
            'page': "borrow.php"
            },
            cache: false,
            success: function(count) {
                if (count > 0) {
                        isRecorded = true;
                }
                callback(isRecorded); // Pass the result to the callback function
            }
        });
    } else {
        callback(isRecorded); // Pass the result to the callback function
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
    var id = row.cells[2].id;

    document.getElementById("equipment_list").deleteRow(rowIndex);

    var del_arr = id.split("_");
    var del_id = del_arr[0];
    var del_uUntil = del_arr[2];
    var del_borId = del_arr[1];

    var delIndex = equipmentDetailsArr.findIndex((equipment) => {
        return (
        equipment.equipmentDetailsId === del_id &&
        equipment.remarks === del_remarks &&
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