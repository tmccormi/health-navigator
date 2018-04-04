<?php
/**
* Script to display results for a given procedure order.
*
* Copyright (C) 2013 Rod Roark <rod@sunsetsystems.com>
*
* LICENSE: This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 2
* of the License, or (at your option) any later version.
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* You should have received a copy of the GNU General Public License
* along with this program.  If not, see <http://opensource.org/licenses/gpl-license.php>.
*
* @package   OpenEMR
* @author    Rod Roark <rod@sunsetsystems.com>
*/

$sanitize_all_escapes = true;
$fake_register_globals = false;

require_once("../globals.php");
require_once("$srcdir/acl.inc");
require_once("$srcdir/formdata.inc.php");
require_once("$srcdir/options.inc.php");
require_once("$srcdir/formatting.inc.php");
require_once("../orders/lab_exchange_tools.php");

// Check authorization.
$thisauth = acl_check('patients', 'med');
if (!$thisauth) die(xl('Not authorized'));

$orderid = intval($_GET['orderid']);

function getListItem($listid, $value) {
  $lrow = sqlQuery("SELECT title FROM list_options " .
    "WHERE list_id = ? AND option_id = ?",
    array($listid, $value));
  $tmp = xl_list_label($lrow['title']);
  if (empty($tmp)) $tmp = "($report_status)";
  return $tmp;
}

function myCellText($s) {
  if ($s === '') return '&nbsp;';
  return text($s);
}

// Check if the given string already exists in the $aNotes array.
// If not, stores it as a new entry.
// Either way, returns the corresponding key which is a small integer.
function storeNote($s) {
  global $aNotes;
  $key = array_search($s, $aNotes);
  if ($key !== FALSE) return $key;
  $key = count($aNotes);
  $aNotes[$key] = $s;
  return $key;
}

if (!empty($_POST['form_sign_list'])) {
  if (!acl_check('patients', 'sign')) {
    die(xl('Not authorized to sign results'));
  }
  // When signing results we are careful to sign only those reports that were
  // in the sending form. While this will usually be all the reports linked to
  // the order it's possible for a new report to come in while viewing these,
  // and it would be very bad to sign results that nobody has seen!
  $arrSign = explode(',', $_POST['form_sign_list']);
  foreach ($arrSign as $id) {
  sqlStatement("UPDATE procedure_report SET " .
    "review_status = 'reviewed' WHERE " .
    "procedure_report_id = ?", array($id));
  }
}

$orow = sqlQuery("SELECT " .
  "po.procedure_order_id, po.date_ordered, po.diagnoses, " .
  "po.order_status, po.specimen_type, " .
  "pd.pubpid, pd.lname, pd.fname, pd.mname, " .
  "pp.name AS labname, " .
  "u.lname AS ulname, u.fname AS ufname, u.mname AS umname " .
  "FROM procedure_order AS po " .
  "LEFT JOIN patient_data AS pd ON pd.pid = po.patient_id " .
  "LEFT JOIN procedure_providers AS pp ON pp.ppid = po.lab_id " .
  "LEFT JOIN users AS u ON u.id = po.provider_id " .
  "WHERE po.procedure_order_id = ?",
  array($orderid));
?>
<html>

<head>
<?php html_header_show(); ?>

<link rel="stylesheet" href='<?php echo $css_header; ?>' type='text/css'>
<title><?php echo xlt('Order Results'); ?></title>

<style>

body {
 margin: 9pt;
 font-family: sans-serif; 
 font-size: 1em;
}

tr.head   { font-size:10pt; background-color:#cccccc; text-align:center; }
tr.detail { font-size:10pt; }
a, a:visited, a:hover { color:#0000cc; }

table {
 border-style: solid;
 border-width: 1px 0px 0px 1px;
 border-color: black;
}

td, th {
 border-style: solid;
 border-width: 0px 1px 1px 0px;
 border-color: black;
}

</style>

<script type="text/javascript" src="../../library/dialog.js"></script>
<script type="text/javascript" src="../../library/textformat.js"></script>

<script language="JavaScript">

var mypcc = '<?php echo $GLOBALS['phone_country_code'] ?>';

</script>

</head>

<body>
<form method='post' action='single_order_results.php?orderid=<?php echo $orderid; ?>'>

<table width='100%' cellpadding='2' cellspacing='0'>
 <tr bgcolor='#cccccc'>
  <td width='5%' nowrap><?php echo xlt('Patient ID'); ?></td>
  <td width='45%'><?php echo myCellText($orow['pubpid']); ?></td>
  <td width='5%' nowrap><?php echo xlt('Order ID'); ?></td>
  <td width='45%'><?php echo myCellText($orow['procedure_order_id']); ?></td>
 </tr>
 <tr bgcolor='#cccccc'>
  <td nowrap><?php echo xlt('Patient Name'); ?></td>
  <td><?php echo myCellText($orow['lname'] . ', ' . $orow['fname'] . ' ' . $orow['mname']); ?></td>
  <td nowrap><?php echo xlt('Ordered By'); ?></td>
  <td><?php echo myCellText($orow['ulname'] . ', ' . $orow['ufname'] . ' ' . $orow['umname']); ?></td>
 </tr>
 <tr bgcolor='#cccccc'>
  <td nowrap><?php echo xlt('Order Date'); ?></td>
  <td><?php echo myCellText(oeFormatShortDate($orow['date_ordered'])); ?></td>
  <td nowrap><?php echo xlt('Print Date'); ?></td>
  <td><?php echo oeFormatShortDate(date('Y-m-d')); ?></td>
 </tr>
 <tr bgcolor='#cccccc'>
  <td nowrap><?php echo xlt('Order Status'); ?></td>
  <td><?php echo myCellText($orow['order_status']); ?></td>
  <td nowrap><?php echo xlt('Diagnoses'); ?></td>
  <td><?php echo myCellText($orow['diagnoses']); ?></td>
 </tr>
 <tr bgcolor='#cccccc'>
  <td nowrap><?php echo xlt('Lab'); ?></td>
  <td><?php echo myCellText($orow['labname']); ?></td>
  <td nowrap><?php echo xlt('Specimen Type'); ?></td>
  <td><?php echo myCellText($orow['specimen_type']); ?></td>
 </tr>
</table>

&nbsp;<br />

<table width='100%' cellpadding='2' cellspacing='0'>

 <tr class='head'>
  <td rowspan='2' valign='middle'><?php echo xlt('Ordered Procedure'); ?></td>
  <td colspan='4'><?php echo xlt('Report'); ?></td>
  <td colspan='7'><?php echo xlt('Results'); ?></td>
 </tr>

 <tr class='head'>
  <td><?php echo xlt('Reported'); ?></td>
  <td><?php echo xlt('Specimen'); ?></td>
  <td><?php echo xlt('Status'); ?></td>
  <td><?php echo xlt('Note'); ?></td>
  <td><?php echo xlt('Code'); ?></td>
  <td><?php echo xlt('Name'); ?></td>
  <td><?php echo xlt('Abn'); ?></td>
  <td><?php echo xlt('Value'); ?></td>
  <td><?php echo xlt('Range'); ?></td>
  <td><?php echo xlt('Units'); ?></td>
  <td><?php echo xlt('Note'); ?></td>
 </tr>

<?php 
$query = "SELECT " .
  "po.date_ordered, pc.procedure_order_seq, pc.procedure_code, " .
  "pc.procedure_name, " .
  "pr.procedure_report_id, pr.date_report, pr.date_collected, pr.specimen_num, " .
  "pr.report_status, pr.review_status, pr.report_notes " .
  "FROM procedure_order AS po " .
  "JOIN procedure_order_code AS pc ON pc.procedure_order_id = po.procedure_order_id " .
  "LEFT JOIN procedure_report AS pr ON pr.procedure_order_id = po.procedure_order_id AND " .
  "pr.procedure_order_seq = pc.procedure_order_seq " .
  "WHERE po.procedure_order_id = ? " .
  "ORDER BY pc.procedure_order_seq, pr.procedure_report_id";

$res = sqlStatement($query, array($orderid));

$lastpoid = -1;
$lastpcid = -1;
$lastprid = -1;
$encount = 0;
$lino = 0;
$extra_html = '';
$aNotes = array();
$sign_list = '';

while ($row = sqlFetchArray($res)) {
  $order_type_id  = empty($row['order_type_id'      ]) ? 0 : ($row['order_type_id' ] + 0);
  $order_seq      = empty($row['procedure_order_seq']) ? 0 : ($row['procedure_order_seq'] + 0);
  $report_id      = empty($row['procedure_report_id']) ? 0 : ($row['procedure_report_id'] + 0);
  $procedure_code = empty($row['procedure_code'  ]) ? '' : $row['procedure_code'];
  $procedure_name = empty($row['procedure_name'  ]) ? '' : $row['procedure_name'];
  $date_report    = empty($row['date_report'     ]) ? '' : $row['date_report'];
  $date_collected = empty($row['date_collected'  ]) ? '' : substr($row['date_collected'], 0, 16);
  $specimen_num   = empty($row['specimen_num'    ]) ? '' : $row['specimen_num'];
  $report_status  = empty($row['report_status'   ]) ? '' : $row['report_status']; 
  $review_status  = empty($row['review_status'   ]) ? 'received' : $row['review_status'];

  if ($review_status != 'reviewed') {
    if ($sign_list) $sign_list .= ',';
    $sign_list .= $report_id;
  }

  $report_noteid ='';
  if (!empty($row['report_notes'])) {
    $report_noteid = 1 + storeNote($row['report_notes']);
  }

  $query = "SELECT " .
    "ps.result_code, ps.result_text, ps.abnormal, ps.result, " .
    "ps.range, ps.result_status, ps.facility, ps.units, ps.comments " .
    "FROM procedure_result AS ps " .
    "WHERE ps.procedure_report_id = ? " .
    "ORDER BY ps.result_code, ps.procedure_result_id";

  $rres = sqlStatement($query, array($report_id));
  $rrows = array();
  while ($rrow = sqlFetchArray($rres)) {
    $rrows[] = $rrow;
  }
  if (empty($rrows)) {
    $rrows[0] = array('result_code' => '');
  }

  foreach ($rrows as $rrow) {
    $result_code      = empty($rrow['result_code'     ]) ? '' : $rrow['result_code'];
    $result_text      = empty($rrow['result_text'     ]) ? '' : $rrow['result_text'];
    $result_abnormal  = empty($rrow['abnormal'        ]) ? '' : $rrow['abnormal'];
    $result_result    = empty($rrow['result'          ]) ? '' : $rrow['result'];
    $result_units     = empty($rrow['units'           ]) ? '' : $rrow['units'];
    $result_facility  = empty($rrow['facility'        ]) ? '' : $rrow['facility'];
    $result_comments  = empty($rrow['comments'        ]) ? '' : $rrow['comments'];
    $result_range     = empty($rrow['range'           ]) ? '' : $rrow['range'];
    $result_status    = empty($rrow['result_status'   ]) ? '' : $rrow['result_status'];

    $result_comments = trim($result_comments);
    $result_noteid = '';
    if (!empty($result_comments)) {
      $result_noteid = 1 + storeNote($result_comments);
    }

    if ($lastpoid != $order_id || $lastpcid != $order_seq) {
      ++$encount;
    }
    $bgcolor = "#" . (($encount & 1) ? "ddddff" : "ffdddd");

    echo " <tr class='detail' bgcolor='$bgcolor'>\n";

    if ($lastpcid != $order_seq) {
      $lastprid = -1; // force report fields on first line of each procedure
      echo "  <td>" . text("$procedure_code: $procedure_name") . "</td>\n";
    }
    else {
      echo "  <td style='background-color:transparent'>&nbsp;</td>";
    }

    // If this starts a new report or a new order, generate the report fields.
    if ($report_id != $lastprid) {
      echo "  <td>";
      echo myCellText(oeFormatShortDate($date_report));
      echo "</td>\n";

      echo "  <td>";
      echo myCellText($specimen_num);
      echo "</td>\n";

      echo "  <td title='" . xla('Check mark indicates reviewed') . "'>";
      echo myCellText(getListItem('proc_rep_status', $report_status));
      if ($row['review_status'] == 'reviewed') {
        echo " &#x2713;"; // unicode check mark character
      }
      echo "</td>\n";

      echo "  <td align='center'>";
      echo myCellText($report_noteid);
      echo "</td>\n";
    }
    else {
      echo "  <td colspan='4' style='background-color:transparent'>&nbsp;</td>\n";
    }

    if ($result_code !== '') {
      echo "  <td>";
      echo myCellText($result_code);
      echo "</td>\n";
      echo "  <td>";
      echo myCellText($result_text);
      echo "</td>\n";
      echo "  <td>";
      echo myCellText(getListItem('proc_res_abnormal', $result_abnormal));
      echo "</td>\n";
      echo "  <td>";
      echo myCellText($result_result);
      echo "</td>\n";
      echo "  <td>";
      echo myCellText($result_range);
      echo "</td>\n";
      echo "  <td>";
      echo myCellText($result_units);
      echo "</td>\n";
      echo "  <td align='center'>";
      echo myCellText($result_noteid);
      echo "</td>\n";
    }
    else {
      echo "  <td colspan='7' style='background-color:transparent'>&nbsp;</td>\n";
    }

    echo " </tr>\n";

    $lastpoid = $order_id;
    $lastpcid = $order_seq;
    $lastprid = $report_id;
    ++$lino;
  }
}
?>

</table>

&nbsp;<br />
<table width='100%' style='border-width:0px;'>
 <tr>
  <td style='border-width:0px;'>
<?php
if (!empty($aNotes)) {
  echo "<table cellpadding='3' cellspacing='0'>\n";
  echo " <tr bgcolor='#cccccc'>\n";
  echo "  <th align='center' colspan='2'>" . xlt('Notes') . "</th>\n";
  echo " </tr>\n";
  foreach ($aNotes as $key => $value) {
    echo " <tr>\n";
    echo "  <td valign='top'>" . ($key + 1) . "</td>\n";
    echo "  <td>" . nl2br(text($value)) . "</td>\n";
    echo " </tr>\n";
  }
  echo "</table>\n";
}
?>
  </td>
  <td style='border-width:0px;' align='right' valign='top'>
<?php if ($sign_list) { ?>
   <input type='hidden' name='form_sign_list' value='<?php echo attr($sign_list); ?>' />
   <input type='submit' name='form_sign' value='<?php echo xla('Sign Results'); ?>'
    title='<?php echo xla('Mark these reports as reviewed'); ?>' />
<?php } ?>
  </td>
 </tr>
</table>

</form>
</body>
</html>
