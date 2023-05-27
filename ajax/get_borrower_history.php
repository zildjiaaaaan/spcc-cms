<?php 
	include '../config/connection.php';

  	$borrowerId = $_GET['borrower_id'];

    $data = '';
    /*
    equipments = equipment, brand
    equipment_details = quantity, unavailable_since, unavailable_date, remarks
    borrowed = borrower_id, equipment_details_id

    */
    $query = "SELECT `borrowed`.*, `quantity`, `unavailable_since`, `unavailable_until`,
                `remarks`, `equipment`, `brand`, `equipments`.`id` as `equipment_id`,
                `contact_no`, `equipments`.`is_del` as `is_del`
            FROM `borrowed`, `equipment_details`, `equipments`, `borrowers`
            WHERE `borrowed`.`borrower_id` = '$borrowerId'
                AND `borrowed`.`borrower_id` = `borrowers`.`id`
                AND `equipment_details_id` = `equipment_details`.`id`
                AND `equipment_id` = `equipments`.`id`;";

    try {
      $stmt = $con->prepare($query);
      $stmt->execute();

      $i = 0;
      while($r = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $del = '';
        if ($r['is_del'] == 1) {
          $del = '(item deleted)';
        }

        $i++;
        $data = $data.'<tr>';

        $b_id = $r['borrower_id'];
        $equipment_detail_id = $r['equipment_details_id'];
        $equipment_id = $r['equipment_id'];

        $link = '<a class="cell-link" href="update_equipment_inventory.php?equipment_id='.$equipment_id.'&equipment_detail_id='.$equipment_detail_id.'&b_id='.$b_id.'" target="_blank">';
        
        $data = $data.'<td class="px-2 py-1 align-middle text-center">'.$i.'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle">'.$link.$r['equipment'].' — '.strtoupper($r['brand']).'<i> '.$del.'</i> </a></td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['quantity'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['unavailable_since'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['unavailable_until'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['contact_no'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-left">'.$r['remarks'].'</td>';

        $data = $data.'</tr>';
      }

    } catch(PDOException $ex) {
      echo $ex->getTraceAsString();
      echo $ex->getMessage();
      exit;
    }

  	echo $data;
?>