<?php 
	include '../config/connection.php';

  	$patientId = $_GET['patient_id'];

    $data = '';
    /*
    medicines (medicine_name)
    medicine_details (packing)
    patient_visits (visit_date, disease)
    patient_medication_history (quantity, dosage)

    */
    $query = "SELECT `m`.`medicine_name`, `m`.`is_del`, `md`.`packing`, `pv`.`pres_remarks`, `u`.`display_name`,
    `pv`.`visit_date`, `pv`.`disease`, `pmh`.`quantity`, `pmh`.`dosage`, `pv`.`id`, `m`.`medicine_brand`
    from `medicines` as `m`, `medicine_details` as `md`, 
    `patient_visits` as `pv`, `patient_medication_history` as `pmh`, `users` as `u`
    where `m`.`id` = `md`.`medicine_id` and 
    `pv`.`patient_id` = $patientId and 
    `pv`.`id` = `pmh`.`patient_visit_id` and 
    `md`.`id` = `pmh`.`medicine_details_id` and
    `pv`.`user_id` = `u`.`id`
    order by `pv`.`id` asc, `pmh`.`id` asc;";

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
        
        $data = $data.'<td class="px-2 py-1 align-middle text-center">'.$i.'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle">'.date("M d, Y", strtotime($r['visit_date'])).'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle">'.$r['disease'].'</a></td>';
        $data = $data.'<td class="px-2 py-1 align-middle">'.strtoupper($r['medicine_name']).' — '.$r['medicine_brand'].'<i> '.$del.'</i></td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['packing'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['quantity'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-right">'.$r['dosage'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-left">'.$r['pres_remarks'].'</td>';
        $data = $data.'<td class="px-2 py-1 align-middle text-left">'.$r['display_name'].'</td>';

        $data = $data.'</tr>';
      }

    } catch(PDOException $ex) {
      echo $ex->getTraceAsString();
      echo $ex->getMessage();
      exit;
    }

  	echo $data;
?>